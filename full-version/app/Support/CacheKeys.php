<?php

namespace App\Support;

/**
 * Centralized cache key registry.
 * Every cache key in the app MUST be defined here to prevent key mismatches.
 */
final class CacheKeys
{
    public const HOME_DATA      = 'home.data';
    public const SECTIONS_HOME  = 'sections_home';
    public const SECTIONS_ABOUT = 'sections_about';
    public const SECTIONS_DAWAH = 'sections_dawah';
    public const SECTIONS_LIVE  = 'sections_live';

    public static function sections(string $page): string
    {
        return "sections_{$page}";
    }
}
