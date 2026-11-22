<?php

namespace App\Application\DTO;

class CreateEnrollUserDTO
{
    public function __construct(
        public string $user_id,
        public string $course_id
    ) {
    }
}