<?php
//nomi classi test devono finire con TEST !!!!!
namespace tests\unit\application;
use PHPUnit\Framework\TestCase;
use App\Application\DTO\CreateEnrollUserDTO;
use App\Application\UseCase\EnrollUserUseCase;
use App\Domain\Enum\Status;
use App\Domain\Model\Enrollment;
use App\Domain\Repository\EnrollmentRepositoryInterface;
 //test da completare perché non ho ancora la classe, ho iniziato la struttura
class Team3_EnrollmentUseCaseTest extends TestCase{
   public function testSavesNewEnrollment(): void
    {
        $repo = $this->createMock(EnrollmentRepositoryInterface::class);
        $repo->method('findByUserAndCourse')->willReturn(null);
        $repo->expects($this->once())
             ->method('save')
             ->with($this->isInstanceOf(Enrollment::class));

        (new EnrollUserUseCase($repo))->execute(
            new CreateEnrollUserDTO('user-1', 'course-1')
        );
    }

    public function testDoesNotSaveIfAlreadyEnrolled(): void
    {
        $repo = $this->createMock(EnrollmentRepositoryInterface::class);
        $repo->method('findByUserAndCourse')
             ->willReturn($this->createMock(Enrollment::class));
        $repo->expects($this->never())->method('save');

        (new EnrollUserUseCase($repo))->execute(
            new CreateEnrollUserDTO('user-1', 'course-1')
        );
    }

    public function testCreatesEnrollmentWithCorrectData(): void
    {
        $repo = $this->createMock(EnrollmentRepositoryInterface::class);
        $repo->method('findByUserAndCourse')->willReturn(null);

        $repo->expects($this->once())
            ->method('save')
            ->with($this->callback(function (Enrollment $enrollment): bool {
                return $enrollment->id === '1'
                    && $enrollment->user_id === 'user-1'
                    && $enrollment->course_id === 'course-1'
                    && $enrollment->status === Status::Pending;
            }));

        (new EnrollUserUseCase($repo))->execute(
            new CreateEnrollUserDTO('user-1', 'course-1')
        );
    }

}