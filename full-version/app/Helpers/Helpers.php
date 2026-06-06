<?php

namespace App\Helpers;

class Helpers
{
    public static function appClasses()
    {
        // default data array
        $defaultData = [
            'theme' => 'dark',
            'layout' => 'vertical',
            'menuCollapsed' => false,
            'hasCustomizer' => false,
            'showDropdownOnHover' => true,
            'displayCustomizer' => false,
            'contentLayout' => 'compact',
            'headerType' => 'fixed',
            'navbarType' => 'fixed',
            'menuFixed' => true,
            'menuFlipped' => false,
            'menuOffcanvas' => false,
            'footerFixed' => false,
            'customizerControls' => [],
            'themeOpt' => 'dark',
            'layoutClasses' => 'layout-menu-expanded',
            'isMenu' => true,
            'isNavbar' => true,
            'isFooter' => true,
            'menuAttributes' => [],
            'bodyCustomClass' => '',
            'navbarClass' => '',
            'footerClass' => '',
            'textDirection' => 'ltr',
            'skinName' => 'default',
            'semiDark' => false,
            'color' => '',
        ];

        return $defaultData;
    }

    public static function updatePageConfig($pageConfigs)
    {
        // Do nothing for now
    }

    public static function generatePrimaryColorCSS($color)
    {
        return '';
    }
}
