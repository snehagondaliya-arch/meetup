<?php

namespace App\Helpers;

use Carbon\Carbon;

class Helpers
{
    /**
     * Parse datetime_text formats like:
     * - "Saturday, Apr 18 · 2:00 PM to 5:00 PM IST"
     * - "Saturday, Apr 25, 9:00 AM to Sunday, Apr 26, 11:00 AM IST"
     * - "Sun, Apr 5 · 5:03 PM"
     * Returns parsed components or null.
     */
    public static function parseDatetimeText($datetimeText)
    {
        if (!$datetimeText) {
            return null;
        }

        $text = preg_replace('/\s+/', ' ', trim($datetimeText));

        // Full range with explicit start and end dates
        $patternRange = '/^(?P<start_day>\w+),\s+(?P<start_month>\w+)\s+(?P<start_date>\d{1,2}),\s*(?P<start_time>\d{1,2}:\d{2}\s*(?:AM|PM))\s+to\s+(?P<end_day>\w+),\s+(?P<end_month>\w+)\s+(?P<end_date>\d{1,2}),\s*(?P<end_time>\d{1,2}:\d{2}\s*(?:AM|PM))\s*(?P<timezone>\w+)?$/i';
        if (preg_match($patternRange, $text, $matches)) {
            return [
                'start_day_of_week' => $matches['start_day'],
                'start_month' => $matches['start_month'],
                'start_date' => $matches['start_date'],
                'start_time' => strtoupper($matches['start_time']),
                'end_day_of_week' => $matches['end_day'],
                'end_month' => $matches['end_month'],
                'end_date' => $matches['end_date'],
                'end_time' => strtoupper($matches['end_time']),
                'timezone' => self::normalizeTimezone($matches['timezone'] ?? null),
            ];
        }

        // Single date range using '·'
        $patternSingleDateRange = '/^(?P<day>\w+),\s+(?P<month>\w+)\s+(?P<date>\d{1,2})\s+·\s+(?P<start_time>\d{1,2}:\d{2}\s*(?:AM|PM))\s+to\s+(?P<end_time>\d{1,2}:\d{2}\s*(?:AM|PM))\s*(?P<timezone>\w+)?$/i';
        if (preg_match($patternSingleDateRange, $text, $matches)) {
            return [
                'start_day_of_week' => $matches['day'],
                'start_month' => $matches['month'],
                'start_date' => $matches['date'],
                'start_time' => strtoupper($matches['start_time']),
                'end_day_of_week' => $matches['day'],
                'end_month' => $matches['month'],
                'end_date' => $matches['date'],
                'end_time' => strtoupper($matches['end_time']),
                'timezone' => self::normalizeTimezone($matches['timezone'] ?? null),
            ];
        }

        // Single datetime entry with no explicit end time
        $patternSingle = '/^(?P<day>\w+),\s+(?P<month>\w+)\s+(?P<date>\d{1,2})\s*·\s*(?P<time>\d{1,2}:\d{2}\s*(?:AM|PM))\s*(?P<timezone>\w+)?$/i';
        if (preg_match($patternSingle, $text, $matches)) {
            return [
                'start_day_of_week' => $matches['day'],
                'start_month' => $matches['month'],
                'start_date' => $matches['date'],
                'start_time' => strtoupper($matches['time']),
                'end_day_of_week' => $matches['day'],
                'end_month' => $matches['month'],
                'end_date' => $matches['date'],
                'end_time' => strtoupper($matches['time']),
                'timezone' => self::normalizeTimezone($matches['timezone'] ?? null),
            ];
        }

        return null;
    }

    protected static function normalizeTimezone($timezone)
    {
        if (!$timezone) {
            return config('app.timezone', 'UTC');
        }

        $timezone = strtoupper(trim($timezone));

        $map = [
            'IST' => 'Asia/Kolkata',
            'UTC' => 'UTC',
            'GMT' => 'GMT',
            'PST' => 'America/Los_Angeles',
            'PDT' => 'America/Los_Angeles',
            'EST' => 'America/New_York',
            'EDT' => 'America/New_York',
            'CST' => 'America/Chicago',
            'CDT' => 'America/Chicago',
        ];

        return $map[$timezone] ?? $timezone;
    }

    /**
     * Convert parsed datetime to Carbon instance
     */
    public static function getStartDateTime($datetimeText, $year = null)
    {
        $parsed = self::parseDatetimeText($datetimeText);

        if (!$parsed) {
            return null;
        }

        if (!$year) {
            $year = now()->year;
        }

        try {
            $dateString = sprintf('%s %s %s %s', $parsed['start_month'], $parsed['start_date'], $year, $parsed['start_time']);
            return Carbon::createFromFormat('M d Y h:i A', $dateString, $parsed['timezone']);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Convert parsed datetime to Carbon instance (end time)
     */
    public static function getEndDateTime($datetimeText, $year = null)
    {
        $parsed = self::parseDatetimeText($datetimeText);

        if (!$parsed) {
            return null;
        }

        if (!$year) {
            $year = now()->year;
        }

        try {
            $dateString = sprintf('%s %s %s %s', $parsed['end_month'], $parsed['end_date'], $year, $parsed['end_time']);
            return Carbon::createFromFormat('M d Y h:i A', $dateString, $parsed['timezone']);
        } catch (\Exception $e) {
            return null;
        }
    }
}

// Global helper functions
if (!function_exists('parseDatetimeText')) {
    function parseDatetimeText($datetimeText)
    {
        return Helpers::parseDatetimeText($datetimeText);
    }
}

if (!function_exists('getStartDateTime')) {
    function getStartDateTime($datetimeText, $year = null)
    {
        return Helpers::getStartDateTime($datetimeText, $year);
    }
}

if (!function_exists('getEndDateTime')) {
    function getEndDateTime($datetimeText, $year = null)
    {
        return Helpers::getEndDateTime($datetimeText, $year);
    }
}
