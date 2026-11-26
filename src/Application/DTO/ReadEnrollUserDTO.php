<?php

namespace App\Application\DTO;

class ReadEnrollUserDTO
{
    public function __construct(
        public string $user_id,
        public string $course_id,
        public string $status,
        public string $created_at
    ) {
    }
}