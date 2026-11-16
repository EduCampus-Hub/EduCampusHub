<?php
namespace App\Infrastructure;

use App\Domain\Enum\CourseStatusEnum;
use App\Domain\Repository\CourseRepositoryInterface;
use App\Domain\Model\Course;
use App\Domain\Enum\TargetAudienceEnum;
use RuntimeException;
class JsonCourseRepository implements CourseRepositoryInterface{
    public function __construct(private string $filePath){}
    public function save(Course $course): bool
    {
        $allItems = $this-> findAll();
        $content = array_map(function (Course $courseT){
            return [
                'id'=> $courseT->id,
                'title'=> $courseT->title,
                'description' => $courseT->description,
                'target' => $courseT->target,
                'status' => $courseT->status,
                'isMandatory'=> $courseT->isMandatory,
                'durationInHours' => $courseT->durationInHours,
                'maxCapacity' => $courseT->maxCapacity
            ];
        },$allItems);
        $content[] =[
            'id'=> $course->id,
            'title'=> $course->title,
            'description'=> $course->description,
            'target' => $course->target,
            'status' => $course->status,
            'isMandatory'=> $course->isMandatory,
            'durationInHours' => $course->durationInHours,
            'maxCapacity' => $course->maxCapacity
        ];
        file_put_contents($this->filePath, json_encode($content, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
    public function findById(string $id): ?Course
    {
        $this -> initializeSearch();
        //prima getto tutto il file, non  uso il find All pk dopo dovrei anche trasformarli tutti e sarebbe un peso in piu
        $jsonContent = file_get_contents($this -> filePath);//ritorna il content del file in quella directory.
        //ora la devo formattare in stile json
        $formattedContent = json_decode($jsonContent,true); //restituisce anche i tipi, int, string bool (non gli enum peróco)
        if($formattedContent === null){
            return null;
        }
        $foundCourse =null;
        foreach($formattedContent as $item){
            if($item['id']===$id){
                $foundCourse= Course::restore(
                    id: $item['id'],
                    title: $item['title'],
                    description: $item['description'],
                    target: TargetAudienceEnum::from ($item['target']),
                    status: CourseStatusEnum::from($item['status']),
                    isMandatory: $item['isMandatory'],
                    durationInHours: $item['durationInHours'],
                    maxCapacity: $item['maxCapacity'],
                );
                break;
                //amo usare i break <3 xoxoxo
            }
        }
        return $foundCourse;

    }
    public function findActiveByTarget(TargetAudienceEnum $target): array
    {
        $courses = [];
        $content = $this -> findAll();
        foreach ($content as $course) {
            if ($course->status !== CourseStatusEnum::ACTIVE) {
                continue;
            }

            if (
                $target === TargetAudienceEnum::ALL ||
                $course->target === $target ||
                $course->target === TargetAudienceEnum::ALL
            ) {
                $courses[] = $course;
            }
        }

        return $courses;
    }

    public function findAll(): array
    {
        $this -> initializeSearch();
        $jsonContent = file_get_contents($this -> filePath);//ritorna il content del file in quella directory.
        //ora la devo formattare in stile json
        $formattedContent = json_decode($jsonContent,true); //restituisce anche i tipi, int, string bool (non gli enum peróco)
        if($formattedContent === null){
            return [];
        }
        $courses = []; //array di Course
        foreach($formattedContent as $item){
            $courses[] = Course::restore(
                id: $item['id'],
                title: $item['title'],
                description: $item['description'],
                target: TargetAudienceEnum::from ($item['target']),
                status: CourseStatusEnum::from($item['status']),
                isMandatory: $item['isMandatory'],
                durationInHours: $item['durationInHours'],
                maxCapacity: $item['maxCapacity'],

            );
        }
        return $courses;

    }
    //metodo da chiamare all'inizio di ogni metodo della repo, serve a verificare se la filepath esiste
    private function initializeSearch(){
            if (!file_exists($this->filePath)){
            throw new RuntimeException("File not found");
        }
    }
    
}
