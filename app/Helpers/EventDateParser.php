<?php
namespace App\Helpers;
use Carbon\Carbon;

class EventDateParser
{
    public static function parse($text)
    {
        $text = self::normalize($text);

        $tzAbbr = self::extractTimezone($text);
        $timezone = self::mapTimezone($tzAbbr);

        // Remove timezone from string for clean parsing
        $text = preg_replace('/\b' . $tzAbbr . '\b$/', '', $text);

        // Detect if range exists
        if (stripos($text, ' to ') !== false) {
            return self::parseRange($text, $timezone);
        }

        return self::parseSingle($text, $timezone);
    }

    // ----------------------------------------

    private static function normalize($text)
    {
        $text = str_replace('·', ' ', $text);

        // Fix missing space before timezone
        $text = preg_replace('/([AP]M)([A-Z]{2,5})/', '$1 $2', $text);

        // Clean multiple spaces
        $text = preg_replace('/\s+/', ' ', $text);

        return trim($text);
    }

    // ----------------------------------------

    private static function extractTimezone($text)
    {
        preg_match('/\b([A-Z]{2,5})$/', $text, $match);
        return $match[1] ?? 'UTC';
    }

    // ----------------------------------------

    private static function mapTimezone($tz)
    {
        return [
            'IST' => 'Asia/Kolkata',
            'UTC' => 'UTC',
            'GMT' => 'Europe/London',

            'EST' => 'America/New_York',
            'EDT' => 'America/New_York',
            'CST' => 'America/Chicago',
            'CDT' => 'America/Chicago',
            'PST' => 'America/Los_Angeles',
            'PDT' => 'America/Los_Angeles',

            'CET' => 'Europe/Paris',
            'CEST' => 'Europe/Paris',
            'BST' => 'Europe/London',

            'JST' => 'Asia/Tokyo',
            'SGT' => 'Asia/Singapore',
            'HKT' => 'Asia/Hong_Kong',

            'PYT' => 'America/Asuncion',
        ][$tz] ?? 'UTC';
    }

    // ----------------------------------------

    private static function parseRange($text, $timezone)
    {
        [$startPart, $endPart] = explode(' to ', $text);

        // Check if end part contains full date
        if (preg_match('/[A-Za-z]+,\s+[A-Za-z]+\s+\d+/', $endPart)) {
            // Cross-day format
            $start = Carbon::parse($startPart, $timezone);
            $end = Carbon::parse($endPart, $timezone);
        } else {
            // Same-day format
            $startDate = self::extractDate($startPart);

            $start = Carbon::parse($startPart, $timezone);

            // Attach same date to end time
            $end = Carbon::parse($startDate . ' ' . trim($endPart), $timezone);

            // Fix midnight crossover (11 PM → 1 AM)
            if ($end->lt($start)) {
                $end->addDay();
            }
        }

        return [
            'start' => $start->copy(),
            'end' => $end->copy(),
            'timezone' => $timezone,
        ];
    }

    // ----------------------------------------

    private static function parseSingle($text, $timezone)
    {
        $date = Carbon::parse($text, $timezone);

        return [
            'start' => $date->copy(),
            'end' => null,
            'timezone' => $timezone,
        ];
    }

    // ----------------------------------------

    private static function extractDate($text)
    {
        preg_match('/([A-Za-z]+,\s+[A-Za-z]+\s+\d+)/', $text, $match);
        return $match[1] ?? '';
    }
}