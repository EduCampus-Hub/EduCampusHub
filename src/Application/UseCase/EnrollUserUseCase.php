<?php

namespace App\Application\UseCase;

use App\Application\DTO\CreateEnrollUserDTO;
use App\Domain\Enum\Status;
use App\Domain\Model\Enrollment;
use App\Domain\Repository\EnrollmentRepositoryInterface;
use DateTime;

class EnrollUserUseCase
{
    public function __construct(
        private EnrollmentRepositoryInterface $repository
    ) {
    }
    private $incrementalId = 0;

    public function execute(CreateEnrollUserDTO $dto): void
    {  
        $existingEnrollment = $this->repository->findByUserAndCourse($dto->user_id, $dto->course_id);
        if ($existingEnrollment) {
            return;
        }
        
        $this->incrementalId= $this->incrementalId + 1;

        $enrollment = Enrollment::create(
            $this->incrementalId,
            $dto->user_id,
            $dto->course_id,
            new DateTime(),
            Status::Pending
        );
        $this->repository->save($enrollment);
    }
}

//utilizziamo findByUserAndCourse() per controllare se l'iscrizione esiste gia per non iscriversi due volte allo stesso scoro