<?php
namespace App\DTOs\Frontend;

use Illuminate\Http\Request;

class CommentDTO
{
    public function __construct(
        public readonly int $lessonId,
        public readonly ?int $parentId,
        public readonly string $userName,
        public readonly string $body,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            lessonId: $request->integer('lesson_id'),
            parentId: $request->integer('parent_id') ?: null,
            userName: $request->string('user_name')->trim()->value(),
            body: $request->string('body')->trim()->value(),
        );
    }
}
