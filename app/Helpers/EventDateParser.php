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

        // space before timezone
        $text = preg_replace('/([AP]M)([A-Z]{2,5})/', '$1 $2', $text);

        // spaces
        $text = preg_replace('/\s+/', ' ', $text);

        return trim($text);
    }


    private static function extractTimezone($text)
    {
        preg_match('/\b([A-Z]{2,5})([+-]\d{1,2}(:\d{2})?)?$/i', $text, $match);
        return $match[1] ?? 'UTC';
    }


    private static function mapTimezone($tz)
    {
        return [
        // Universal
        'UTC' => 'UTC',
        'GMT' => 'Europe/London',

        // India
        'IST' => 'Asia/Kolkata',

        // US Timezones
        'EST' => 'America/New_York',
        'EDT' => 'America/New_York',
        'CST' => 'America/Chicago',
        'CDT' => 'America/Chicago',
        'MST' => 'America/Denver',
        'MDT' => 'America/Denver',
        'PST' => 'America/Los_Angeles',
        'PDT' => 'America/Los_Angeles',

        // Europe
        'CET' => 'Europe/Paris',
        'CEST' => 'Europe/Paris',
        'BST' => 'Europe/London',
        'EET' => 'Europe/Athens',
        'EEST' => 'Europe/Athens',

        // Asia
        'JST' => 'Asia/Tokyo',
        'KST' => 'Asia/Seoul',
        'SGT' => 'Asia/Singapore',
        'HKT' => 'Asia/Hong_Kong',
        'CST-CHINA' => 'Asia/Shanghai', 

        // Australia
        'AEST' => 'Australia/Sydney',
        'AEDT' => 'Australia/Sydney',
        'ACST' => 'Australia/Adelaide',
        'ACDT' => 'Australia/Adelaide',

        // Middle East
        'GST' => 'Asia/Dubai',

        // South America
        'BRT' => 'America/Sao_Paulo',
        'ART' => 'America/Argentina/Buenos_Aires',
        'PYT' => 'America/Asuncion',

        // Africa
        'SAST' => 'Africa/Johannesburg',

    ][strtoupper($tz)] ?? 'UTC';
    }


    private static function parseRange($text, $timezone)
    {
        [$startPart, $endPart] = explode(' to ', $text);

        // Check if end part contains full date
        if (preg_match('/[A-Za-z]+,\s+[A-Za-z]+\s+\d+/', $endPart)) {
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
            'start' => $start->copy()->utc(),
            'end' => $end->copy()->utc(),
            'timezone' => $timezone,
        ];
    }

    private static function parseSingle($text, $timezone)
    {
        $date = Carbon::parse($text, $timezone);

        return [
            'start' => $date->copy()->utc(),
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