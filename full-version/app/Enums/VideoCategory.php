<?php

namespace App\Enums;

enum VideoCategory: string
{
    case Debate = 'debate';
    case Critique = 'critique';
    case DirectDialog = 'direct_dialogue';
    case Lecture = 'lecture';

    public function label(): string
    {
        return match($this) {
            self::Debate => 'مناظرة',
            self::Critique => 'نقد كتابي',
            self::DirectDialog => 'حوار مباشر',
            self::Lecture => 'محاضرة',
        };
    }
}
