<?php

namespace App\Enums;

enum ArticleCategory: string
{
    case Critique = 'critique';
    case Comparative = 'comparative';
    case QuickReply = 'quick_reply';
    case History = 'history';

    public function label(): string
    {
        return match($this) {
            self::Critique => 'نقد كتابي',
            self::Comparative => 'مقارنة أديان',
            self::QuickReply => 'رد سريع',
            self::History => 'تاريخ الكنيسة',
        };
    }
}
