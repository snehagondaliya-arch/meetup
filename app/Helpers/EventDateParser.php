<?php

namespace App\Helpers;

use Carbon\Carbon;

class EventDateParser
{
    public static function parse($text)
    {
        $text = self::normalize($text);

        if (!$text) {
            throw new \Exception('Invalid or empty datetime text');
        }

        $tzAbbr = self::extractTimezone($text);
        $timezone = self::mapTimezone($tzAbbr);

        // Remove timezone from string
        $text = preg_replace('/\b' . preg_quote($tzAbbr, '/') . '\b$/', '', $text);

        if (stripos($text, ' to ') !== false) {
            return self::parseRange($text, $timezone);
        }

        return self::parseSingle($text, $timezone);
    }

    // ----------------------------------------

    private static function normalize($text)
    {
        if (!$text || trim($text) === 'N/A') {
            return null;
        }

        // Replace dot separator
        $text = str_replace('·', ' ', $text);

        // Fix timezone sticking (PMCEST → PM CEST)
        $text = preg_replace('/([AP]M)([A-Z]{2,5})/', '$1 $2', $text);

        // Add AM/PM if missing (assume PM)
        $text = preg_replace('/(\d{1,2}:\d{2})(?!\s?[AP]M)/', '$1 PM', $text);

        // Add current year if missing
        if (!preg_match('/\b\d{4}\b/', $text)) {
            $text .= ' ' . now()->year;
        }

        // Normalize commas
        $text = preg_replace('/,\s*/', ', ', $text);

        // Remove extra spaces
        $text = preg_replace('/\s+/', ' ', $text);

        return trim($text);
    }

    // ----------------------------------------

    private static function extractTimezone($text)
    {
        preg_match('/\b([A-Z]{2,5})$/', trim($text), $match);
        return $match[1] ?? 'UTC';
    }

    // ----------------------------------------

    private static function mapTimezone($tz)
    {
        return [
            'IST' => 'Asia/Kolkata',

            // Latin America
            'ART' => 'America/Argentina/Buenos_Aires',
            'BRT' => 'America/Sao_Paulo',
            'COT' => 'America/Bogota',

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
        ][$tz] ?? 'UTC';
    }

    // ----------------------------------------

    private static function safeParse($text, $timezone)
    {
        try {
            return Carbon::parse(trim($text), $timezone);
        } catch (\Exception $e) {
            throw new \Exception("Failed to parse: " . $text);
        }
    }

    // ----------------------------------------

    private static function parseRange($text, $timezone)
    {
        [$startPart, $endPart] = explode(' to ', $text);

        $start = self::safeParse($startPart, $timezone);

        // If end contains full date → cross-day
        if (preg_match('/[A-Za-z]+,\s*[A-Za-z]+\s+\d+/', $endPart)) {

            $end = self::safeParse($endPart, $timezone);

        } else {

            // Same day → attach date
            $startDate = self::extractDate($startPart);

            if (!$startDate) {
                throw new \Exception('Failed to extract date from: ' . $startPart);
            }

            $end = self::safeParse($startDate . ' ' . trim($endPart), $timezone);
        }

        // Fix overnight case (e.g., 10 PM → 2 AM)
        if ($end->lt($start)) {
            $end->addDay();
        }

        return [
            'start' => $start->copy()->utc(),
            'end' => $end->copy()->utc(),
            'timezone' => $timezone,
        ];
    }

    // ----------------------------------------

    private static function parseSingle($text, $timezone)
    {
        $date = self::safeParse($text, $timezone);

        return [
            'start' => $date->copy()->utc(),
            'end' => null,
            'timezone' => $timezone,
        ];
    }

    // ----------------------------------------

    private static function extractDate($text)
    {
        preg_match('/([A-Za-z]+,\s*[A-Za-z]+\s+\d+)/', $text, $match);
        return $match[1] ?? null;
    }
}