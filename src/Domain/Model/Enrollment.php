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
    public static function create(){}
    public static function restore(){}
}