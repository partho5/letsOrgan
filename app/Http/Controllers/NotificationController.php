<?php

namespace App\Http\Controllers;

use App\Library\Library;
use App\Module\Notification\OneSignal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{

    private $Lib, $pushNotification;

    function __construct()
    {
        $this->middleware('auth');
        $this->Lib = new Library();
        $this->pushNotification = new OneSignal();
    }

    public function markAsSeen(Request $request){
        $notificationId = $request->input('notificationId');
        DB::table('notification_seen_records')->insert([
            'user_id'           => Auth::id(),
            'community_id'      => $this->Lib->getCurrentCommunityId(),
            'notification_id'   => $notificationId,
            'seen_from'         => $request->input('seenFrom'),
            'created_at'        => Carbon::now(),
            'updated_at'        => Carbon::now()
        ]);
        echo "$notificationId marked as seen";
    }


    public function saveOnesignalDeviceId(Request $request){
        $this->pushNotification->saveDeviceId($request);
    }
}
