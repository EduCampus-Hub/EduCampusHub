<?php

namespace App\Application\DTO;

use App\Domain\Enum\CourseStatusEnum;
use App\Domain\Enum\TargetAudienceEnum;
use InvalidArgumentException;

readonly class CourseInput
{
    public const MISSING_TEXT = 'Title and description are required.';
    public const INVALID_NUMBERS = 'Max capacity and duration must be greater than zero.';
    public const INVALID_TARGET = 'Invalid target audience.';
    public const INVALID_STATUS = 'Invalid course status.';

    public function __construct(
        public int $maxCapacity,
        public string $targetAudience,
        public string $status,
        public string $title,
        public string $description,
        public bool $isMandatory,
        public int $durationInHours
    ) {}

    public function validate(): void
    {
        if (trim($this->title) === '' || trim($this->description) === '') {
            throw new InvalidArgumentException(self::MISSING_TEXT);
        }

        if ($this->maxCapacity <= 0 || $this->durationInHours <= 0) {
            throw new InvalidArgumentException(self::INVALID_NUMBERS);
        }
    }

    public function targetEnum(): TargetAudienceEnum
    {
        return TargetAudienceEnum::tryFrom(strtolower($this->targetAudience))
            ?? throw new InvalidArgumentException(self::INVALID_TARGET);
    }

    public function statusEnum(): CourseStatusEnum
    {
        return CourseStatusEnum::tryFrom(strtolower($this->status))
            ?? throw new InvalidArgumentException(self::INVALID_STATUS);
    }
}
