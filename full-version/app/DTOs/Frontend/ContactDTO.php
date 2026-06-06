<?php
namespace App\DTOs\Frontend;

use Illuminate\Http\Request;

class ContactDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $subject,
        public readonly string $message,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            name: $request->string('name')->trim()->value(),
            email: $request->string('email')->trim()->value(),
            subject: $request->string('subject')->trim()->value(),
            message: $request->string('message')->trim()->value(),
        );
    }
}
