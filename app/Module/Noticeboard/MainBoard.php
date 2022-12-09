<?php

namespace App\Module\Noticeboard;

use App\Library\Library;
use App\NoticeboardAdmin;
use Illuminate\Support\Facades\Auth;

class MainBoard{

    private $userId;
    private $userType;
    private $currentCommunityId;
    private $Lib;

    function __construct($userId)
    {
        $this->Lib = new Library();
        $this->userId = $userId;
        $this->currentCommunityId = $this->Lib->getCurrentCommunityId();
    }

    public function getUserType(){
        $user = NoticeboardAdmin::where('user_id', $this->userId)->where('possessed_by_community', $this->currentCommunityId)->get();
        return $this->userType = count($user) > 0 ? 'admin' : 'normal';
    }

    public function getUserInfo(){
        return [
            'userType'      => $this->getUserType(),
        ];
    }
}

?>