<?php
/**
 * Created by PhpStorm.
 * User: partho
 * Date: 12/13/17
 * Time: 3:03 PM
 */

namespace App\HelperClasses;

class ListingPointBadges{

    public $pointList = [
        'join_community'                => 200,
        'add_member'                    => 10,
        'add_course'                    => 5,
        'add_teacher'                   => 2,
        'profile_30_percent'            => 10,
        'profile_60_percent'            => 8,
        'profile_100_percent'           => 7,
        'add_book'                      => 10,
        'add_question'                  => 4,
        'add_sheet'                     => 2,
        'add_slide'                     => 2,
        'add_other_files'               => 2,
        'add_youtube_link'              => 2,
        'daily_quiz_solve'              => 2,
        'daily_iq_test'                 => 2,
        'add_exam_post'                 => 2,
        'add_assignment_post'           => 2,
        'add_general_post'              => 2,
        'add_class_routine'             => 5,
        'get_vote_on_youtube_link'      => 2,
    ];


    public function getPointFor($event){
        return $this->pointList[$event];
    }



    /*
     * e.g. get 'Novice' badge if users earns 205 reputation points
     *
     * These badges are earned depending on earned points
     * */
    public $reputationDependentBadgeList = [
        'Novice'            => 205,
        'Inquisitive'       => 215,
        'Trainee'           => 250,
        'Learner'           => 300,
        'Helpful'           => 350,
        'Curious'           => 400,
        'Interested'        => 450,
        'Contributor'       => 500,
        'Scholar'           => 1000,
        'Geek'              => 1200,
        'Master'            => 1500,
    ];

    /*
     * These badges doesn't depend on reputation points, but depends on different activities
     * */
    public $activityDependentBadgeList = [

    ];
}