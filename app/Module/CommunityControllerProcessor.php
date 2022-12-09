<?php

/**
 * Created by PhpStorm.
 * User: partho
 * Date: 2/17/18
 * Time: 11:30 PM
 */

namespace App\Module;

use App\CoursesTakenByStudents;
use App\DeptCourses;
use App\Library\Library;
use App\StudentRolls;
use Illuminate\Support\Facades\Auth;

class CommunityControllerProcessor
{
    private $Lib;

    function __construct()
    {
        $this->Lib = new Library();
    }


    public function getCourses(){
        $userId = Auth::id();
        $communityId = $this->Lib->getCurrentCommunityId();
        $isAdmin = $this->Lib->isAdmin($userId, $communityId);
        if($isAdmin){
            $courses = DeptCourses::where('possessed_by_community', $communityId)
                ->where('data_added_by', $userId)
                ->get();
        }else{
            $courses = DeptCourses::where('possessed_by_community', $communityId)
                ->get();
            foreach ($courses as $course){
                $course['data_added_by_name'] = $this->Lib->getUserName($course->data_added_by);
                $course['already_joined'] = $this->userHasJoinedThisCourse($course->id);
            }
        }
        return $courses;
    }

    public function getRollNo(){
        $communityId = $this->Lib->getCurrentCommunityId();
        $roll = StudentRolls::where('user_id', Auth::id())
            ->where('community_id', $communityId)
            ->where('academic_session', $this->Lib->getAcademicSession($communityId))
            ->select('roll_numeric', 'roll_full_form')->get();
        if(count($roll) > 0){
            return $roll[0];
        }
        return null;
    }

    public function userHasJoinedThisCourse($courseId){
        $takenCourse = CoursesTakenByStudents::where('course_id', $courseId)
            ->where('user_id_of_student', Auth::id())
            ->get();
        return count($takenCourse) > 0 ;
    }


}

