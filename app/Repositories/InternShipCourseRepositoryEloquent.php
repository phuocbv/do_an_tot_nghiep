<?php
/**
 * Created by PhpStorm.
 * User: da
 * Date: 19/09/2017
 * Time: 01:25
 */

namespace App\Repositories;


use App\InternShipCourse;
use App\Repositories\Contracts\InternShipCourseRepositoryInterface;
use App\StudentInternShipCourse;

class InternShipCourseRepositoryEloquent extends BaseRepository implements InternShipCourseRepositoryInterface
{
    public function __construct(InternShipCourse $model)
    {
        parent::__construct($model);
    }

    public function showCourseDetail($courseId)
    {
        $internShipCourse = $this->find($courseId);

        if (!$internShipCourse) return null;

        $internShipCourse['listGroupAssigned'] = $internShipCourse->internShipGroups()
            ->where('lecture_id', '<>', '')->get();

        $internShipCourse['listGroupAssigned']->each(function ($item, $key) {
            $item['studentInCourse'] = StudentInternShipCourse::where([
                'student_id' => $item->student_id,
                'internship_course_id' => $item->internship_course_id
            ])->first();
        });
        $internShipCourse['listGroupAssigned'] = $internShipCourse['listGroupAssigned']->groupBy('company_id');

        return $internShipCourse;
    }
}
