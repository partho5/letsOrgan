<?php

/**
 * Created by PhpStorm.
 * User: partho
 * Date: 1/10/18
 * Time: 3:29 PM
 */

namespace App\Module\Notification;

use App\AllCommunitiesOfUsers;
use App\Library\Library;
use App\User;

class NotificationProcessor
{
    private $Lib, $oneSignal;

    function __construct()
    {
        $this->Lib = new Library();
        $this->oneSignal = new OneSignal();
    }

    public function test(){
        return "success";
    }

    public function deviceToReceivePushNotification(){
        $communityIdThatWillGetPushNotification = $this->Lib->getCurrentCommunityId();
        $userIds = AllCommunitiesOfUsers::where('community_id', $communityIdThatWillGetPushNotification)
            ->where('academic_session', $this->Lib->getAcademicSession($communityIdThatWillGetPushNotification))
            ->pluck('user_id');
        return $this->oneSignal->getDeviceIdsForUserIds($userIds);
    }


    public function getMailListToSendMail($communityId){
        $session = $this->Lib->getAcademicSession($communityId);
        $selectedUserId = AllCommunitiesOfUsers::where('community_id', $communityId)
            ->where('academic_session', $session)->select('user_id')->get();
        return User::whereIn('id', $selectedUserId)->pluck('email');
    }
}