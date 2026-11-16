<?php
namespace App\Domain\Model;

use DateTime;
use App\Domain\Enum\Status;

readonly class Enrollment
{
    private function __construct
    (
    public string $id,
    public string $user_id,
    public string $course_id,
    public DateTime $enrolled_at,
    public Status $status

    ){}
    public static function create(string $id,string $user_id,string $course_id,DateTime $enrolled_at, Status $status):self
    {
        return new self(
            id: $id,
            user_id :$user_id,
            course_id : $course_id,
            enrolled_at : $enrolled_at,
            status : $status
        );
    }
    public static function restore(string $id,string $user_id,string $course_id,DateTime $enrolled_at, Status $status):self
    {
          return new self(
            id: $id,
            user_id :$user_id,
            course_id : $course_id,
            enrolled_at : $enrolled_at,
            status : $status
        );
    }
}