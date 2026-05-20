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
        if ($tzAbbr && preg_match('/\b' . preg_quote($tzAbbr, '/') . '\b$/', $text)) {
            $text = preg_replace('/\b' . preg_quote($tzAbbr, '/') . '\b$/', '', $text);
        }

        // Detect if range exists
        if (stripos($text, ' to ') !== false) {
            return self::parseRange($text, $timezone);
        }

        return self::parseSingle($text, $timezone);
    }


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
        preg_match('/(GMT|UTC)?[+-]\d{1,2}:?\d{0,2}$|[A-Z]{2,5}$/i', trim($text), $match);

        return strtoupper($match[0] ?? 'UTC');
    }
    private static function mapTimezone($tz)
    {

        if (preg_match('/^(GMT|UTC)([+-]\d{1,2})$/', $tz, $m)) {
            return sprintf('%+03d:00', $m[2]);
        }
        $map = [

            // Universal
            'UTC' => 'UTC',
            'GMT' => 'UTC',
            'Z' => 'UTC',

            // India
            'IST' => 'Asia/Kolkata',

            // US
            'EST' => 'America/New_York',
            'EDT' => 'America/New_York',
            'CST' => 'America/Chicago',
            'CDT' => 'America/Chicago',
            'MST' => 'America/Denver',
            'MDT' => 'America/Denver',
            'PST' => 'America/Los_Angeles',
            'PDT' => 'America/Los_Angeles',
            'AKST' => 'America/Anchorage',
            'AKDT' => 'America/Anchorage',
            'HST' => 'Pacific/Honolulu',

            // Europe
            'CET' => 'Europe/Paris',
            'CEST' => 'Europe/Paris',
            'BST' => 'Europe/London',
            'EET' => 'Europe/Athens',
            'EEST' => 'Europe/Athens',
            'WET' => 'Europe/Lisbon',
            'MSK' => 'Europe/Moscow', // Moscow Time
            'WEST' => 'Europe/Lisbon',

            // Asia
            'JST' => 'Asia/Tokyo',
            'KST' => 'Asia/Seoul',
            'SGT' => 'Asia/Singapore',
            'HKT' => 'Asia/Hong_Kong',
            'PKT' => 'Asia/Karachi',
            'BDT' => 'Asia/Dhaka',
            'NPT' => 'Asia/Kathmandu',
            'ICT' => 'Asia/Bangkok',
            'WIB' => 'Asia/Jakarta',
            'WITA' => 'Asia/Makassar',
            'MYT' => 'Asia/Kuala_Lumpur',

            // Middle East
            'GET' => 'Asia/Tbilisi',
            'GST' => 'Asia/Dubai',
            'AST' => 'Asia/Riyadh',
            'IRST' => 'Asia/Tehran',
            'IDT' => 'Asia/Jerusalem',
            'PHT' => 'Asia/Manila', // Philippines Time

            // Africa
            'SAST' => 'Africa/Johannesburg',
            'EAT' => 'Africa/Nairobi',
            'WAT' => 'Africa/Lagos',
            'WAST' => 'Africa/Windhoek',
            'MUT' => 'Indian/Mauritius', // Mauritius Time

            // South America
            'BRT' => 'America/Sao_Paulo',
            'ART' => 'America/Argentina/Buenos_Aires',
            'CLT' => 'America/Santiago',
            'PYT' => 'America/Asuncion',
            'COT' => 'America/Bogota',

            'AEST' => 'Australia/Sydney',   // Australian Eastern Standard Time
            'AEDT' => 'Australia/Sydney',   // Daylight version
            'ACST' => 'Australia/Adelaide', // Central Standard
            'ACDT' => 'Australia/Adelaide', // Central Daylight
            // Pacific
            'SST' => 'Pacific/Apia', // ambiguous
            'NZST' => 'Pacific/Auckland', // New Zealand Standard Time

            // Offsets
            'GMT+3' => '+03:00',
            '+0000' => 'UTC',
            '+0530' => 'Asia/Kolkata',
            '+05:30' => 'Asia/Kolkata',
            '+0800' => 'Asia/Singapore',
            '+0900' => 'Asia/Tokyo',
            '-0500' => 'America/New_York',
            '-0600' => 'America/Chicago',
            '-0700' => 'America/Denver',
            '-0800' => 'America/Los_Angeles',
        ];
        return $map[strtoupper($tz)] ?? 'UTC';
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

    private static function extractDate($text)
    {
        preg_match('/([A-Za-z]+,\s+[A-Za-z]+\s+\d+)/', $text, $match);
        return $match[1] ?? '';
    }
}
