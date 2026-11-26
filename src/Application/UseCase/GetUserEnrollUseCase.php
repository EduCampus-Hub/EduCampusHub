<?php

namespace App\Application\UseCase;

use App\Application\DTO\CreateEnrollUserDTO;
use App\Domain\Enum\Status;
use App\Domain\Model\Enrollment;
use App\Domain\Repository\EnrollmentRepositoryInterface;
use DateTime;

class GetUserEnrollUseCase
{
    public function __construct(
        private EnrollmentRepositoryInterface $enrollmentRepository
    ){}


    //buona norma dare il nome execute a tutti i metodi che eseguono un'azione
    // in questo caso recuperare la lista delle richieste
    public function execute($user_id):array
    {
        //sort --> sort crescente
        //usort -->sort decrescente
        return $this->enrollmentRepository->findByUserId($user_id);
    }
}