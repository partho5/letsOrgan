<?php
/**
 * Created by PhpStorm.
 * User: partho
 * Date: 12/13/17
 * Time: 11:27 AM
 */

namespace App\HelperClasses;

use App\ReputationPoints;
use Carbon\Carbon;

class PointBadges{

    private $userId, $communityId, $pointList;

    function __construct($userId, $communityId)
    {
        $this->userId = $userId;
        $this->communityId = $communityId;
        $this->pointList = new ListingPointBadges();
    }


    public function increaseReputationFor($event){
        ReputationPoints::insert([
            'user_id'       => $this->userId,
            'community_id'  => $this->communityId,
            'point'         => $this->pointList->getPointFor($event),
            'event'         => $event,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        //return $this->pointList->getPointFor($event);
    }

    public function getCurrentReputationPoint(){
        $point = ReputationPoints::where('user_id', $this->userId)->where('community_id', $this->communityId)
            ->select('point')->sum('point');
        return  $point;
    }
}