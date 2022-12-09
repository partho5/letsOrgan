<?php
/**
 * Created by PhpStorm.
 * User: partho
 * Date: 1/10/18
 * Time: 3:32 PM
 */

namespace App\Module\Notification;


use App\OnesignalDeviceInformation;
use Illuminate\Support\Facades\Auth;

class OneSignal
{

    function __construct()
    {
    }

    public function saveDeviceId($request){
        $onesignalDevice = OnesignalDeviceInformation::firstOrNew([
            'device_id'     => $request->deviceId ?: 'unknown',
            'user_id'       => Auth::id()
        ]);
        $onesignalDevice->device_type = $request->deviceType ?: 'unknown';
        $onesignalDevice->device_name = $request->deviceName ?: 'unknown';
        $onesignalDevice->browser = $request->browser ?: 'unknown';

        $onesignalDevice->save();

        echo 'onesignal id saved';
    }

    /**
     * @param collection $userIds
     * @return array $deviceIdsOfTargetUsers
     */
    public function getDeviceIdsForUserIds($userIds){
        $deviceIdsOfTargetUsers = [];
        //single user may have more than 1 device ids
        foreach ($userIds as $userId){
            $deviceIdsOfSingleUser = OnesignalDeviceInformation::where('user_id', $userId)
                ->where('device_id', '!=', 'unknown')
                ->pluck('device_id');
            foreach ($deviceIdsOfSingleUser as $deviceId){
                array_push($deviceIdsOfTargetUsers, $deviceId);
            }
        }
        return $deviceIdsOfTargetUsers;
    }
}