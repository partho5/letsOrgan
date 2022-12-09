<?php

/**
 * Created by PhpStorm.
 * User: partho
 * Date: 2/10/18
 * Time: 7:59 PM
 */

namespace App\Module\API\Attendance;

use App\Communities;
use App\DeptCourses;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AttendanceProcessor
{

    private $institute, $dept;

    function __construct()
    {
    }

    public function loginResponse($email, $password){
        $user = User::where('email', $email)->first();
        if($user && Hash::check($password, $user->getAuthPassword())){
            return response($user->id, 200);
        }
        return response("Access Denied", 403);
    }

    public function coursesRunBy($userId){
        //run by teacher
        $courses = DeptCourses::where('data_added_by', '=', $userId)
            ->select('id', 'course_code', 'course_name', 'possessed_by_community')
            ->get();
        foreach ($courses as $course){
            $course->course_id = $course->id;
            unset($course['id']);

            $community = $this->community($course->possessed_by_community);
            $course->institute = $community->getInstituteName();
            $course->dept = $community->getDepartmentName();

            unset($course['possessed_by_community']);
        }
        return $courses;
    }

    public function courseTakerStudents($userIdOfTeacher){
        $studentsInfo = DB::table('courses_taken_by_students as takenBy')
            ->join('student_rolls as rolls', 'rolls.user_id', '=', 'takenBy.user_id_of_student')
            ->join('users as u', 'u.id', '=', 'rolls.user_id')
            ->select('u.id as user_id', 'takenBy.course_id', 'rolls.roll_numeric', 'rolls.roll_full_form', 'u.name')
            ->where('takenBy.user_id_of_course_teacher', $userIdOfTeacher)
            ->get();
        return $studentsInfo;
    }

    public function community($communityId){
        $community = Communities::find($communityId);
        $this->institute = $community->institute_name;
        $this->dept = $community->dept_name;
        return $this;
    }

    public function getInstituteName(){
        return $this->institute;
    }

    public function getDepartmentName(){
        return $this->dept;
    }


}