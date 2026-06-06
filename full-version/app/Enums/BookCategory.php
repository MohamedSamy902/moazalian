<?php

namespace App\Enums;

enum BookCategory: string
{
    case Critique = 'critique';
    case Authenticity = 'authenticity';
    case Doctrine = 'doctrine';
    case Prophecy = 'prophecy';
    case Other = 'other';

    public function label(): string
    {
        return match($this) {
            self::Critique => 'نقد كتابي',
            self::Authenticity => 'توثيق المخطوطات',
            self::Doctrine => 'العقيدة',
            self::Prophecy => 'البشارات',
            self::Other => 'أخرى',
        };
    }
}
