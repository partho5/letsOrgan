<?php

//$Lib = new \App\Library\Library();
//$userId = 3;
//$allCommunitiesOfUser = \App\AllCommunitiesOfUsers::all();
//foreach ($allCommunitiesOfUser as $community){
//    $assignPointToUser = \App\ReputationPoints::firstOrNew(['user_id'=> $community->user_id, 'community_id'=> $community->community_id]);
//    $assignPointToUser->point = 200;
//    $assignPointToUser->event = 'join_community';
//    $assignPointToUser->save();
//}
//echo 'join community point inserted successfully';




//$user = \App\User::pluck('email');
// \Illuminate\Support\Facades\Mail::bcc($user)->queue(new \App\Mail\SendMailToAllQueue());

echo "hello";



$user = [
    'name'  => 'Partho',
    'email' => 'souravpk.sp@gmail.com'
];


//	\Illuminate\Support\Facades\Mail::send('pages.qa_password_reset', ['user' => $user], function ($m) use ($user) {
//	    $m->from('choriyedao@gmail.com', 'ChoriyeDao');
//
//	    $m->to("partho8181bd@gmail.com")->subject('Power Exam date changed');
//	});

//
//for($i=0; $i<200; $i++){
//	\Illuminate\Support\Facades\Mail::send('pages.qa_password_reset', ['user' => $user], function ($m) use ($user) {
//	    $m->from('choriyedao@gmail.com', 'ChoriyeDao');
//
//	    $m->to("partho8181bd@gmail.com", $user['name'])->subject('Power Exam date changed');
//	});
//    echo "$i <br>";
//}



//$x= DB::table('qa_users')->where('userid', 3)->update(['passcheck'=> UNHEX(SHA1(CONCAT(LEFT(passsalt, 8), '', RIGHT(passsalt, 8)))) ]);
//$x= DB::table('qa_users')->where('userid', 3)->select('passcheck');
//$x = DB::statement("UPDATE qa_users SET passcheck = UNHEX(SHA1(CONCAT(LEFT(passsalt, 8), '111111', RIGHT(passsalt, 8)))) WHERE userid = 3");
//$x = DB::select( DB::raw("select * from qa_users") );

//dd( $x[0]->passcheck);
