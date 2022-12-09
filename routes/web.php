<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Route::group(['prefix'=> '/', 'middleware' => 'VisitorTrackerMiddleware'], function (){

});


Route::get('/', 'HomepageController@index');
Route::get('/login', function (){
    return redirect('/');
});
Route::get('/logout', function (){
    return "Click Logout from <a href='/'>LetsOrgan homepage</a>";
});

Route::get('/users/profile/id/{id}', 'ProfileController@showSingleProfile');
Route::get('/users/profile/fetch_what_iam', 'ProfileController@getPortFolioWhatIam');



Route::get('/users/profile/update', 'ProfileController@showUpdateProfile');
Route::post('/users/profile/update', 'ProfileController@doUpdateProfile');



Route::get('/community/all', 'CommunityController@showAllCommunities');
Route::get('/community/view/all_members', 'CommunityController@showAllMembers');
Route::get('/community/view/{community}', 'CommunityController@showSingleCommunity');

Route::get('/community/search/', function (){
    return redirect('/');
});
Route::post('/community/search/', 'CommunityController@searchCommunity');

Route::get('/community/create/', 'CommunityController@showCreateCommunity');
Route::post('/community/create/', 'CommunityController@doCreateCommunity');

Route::get('/community/edit/{communityId}', 'CommunityController@showEditCommunity');
Route::patch('/community/edit/{communityId}', 'CommunityController@doEditCommunity');

Route::get('/community/join/{token}/{invited_by?}', 'CommunityController@joinRequestToCommunity');

Route::get('/community/courses', 'CommunityController@showAddCourses');
Route::post('/community/courses/save_roll_no', 'CommunityController@saveRollNo');
Route::post('/community/courses/join_course', 'CommunityController@joinCourse');
Route::post('/community/courses/leave_course', 'CommunityController@leaveCourse');

Route::get('/community/teachers', 'CommunityController@showAddTeachers');


Route::post('/community/action/change_root_community', 'CommunityController@changeRootCommunity');
Route::post('/community/action/fetch_join_token', 'CommunityController@fetchJoinToken');

// /community/action/... are mostly (NOT ALL) ajax calls
Route::post('/community/action/save_general_posts', 'CommunityController@saveGeneralPosts');
Route::post('/community/action/save_exam', 'CommunityController@saveExams');
Route::post('/community/action/save_assignment', 'CommunityController@saveAssignments');
Route::post('/community/action/save_poll', 'CommunityController@savePolls');
Route::post('/community/action/save_notice', 'CommunityController@saveNotices');
Route::post('/community/action/cast_vote', 'CommunityController@castVote'); //poll voting

Route::get('/community/action/delete_post/{postType}/{postId}', 'CommunityController@deletePost');

Route::post('/community/action/update_post', 'CommunityController@updatePost');

Route::post('/community/action/save_courses', 'CommunityController@saveCourses');
Route::post('/community/action/delete_course', 'CommunityController@deleteCourse');
Route::post('/community/action/save_teachers', 'CommunityController@saveTeachers');


Route::post('/community/action/update_cls_routine', 'CommunityController@updateClassRoutine');


Route::get('/users/exam/details/{examId}', 'CommunityController@showExamDetails');
Route::get('/users/assignment/details/{assignmentId}', 'CommunityController@showAssignmentDetails');





Route::get('/upload/request', 'UploadController@showUploadRequest');
Route::post('/upload/request', 'UploadController@doUploadRequest');

Route::get('/upload/request/all', 'UploadController@showAllUploadRequest');
Route::get('/upload/request/respond/{requestId}', 'UploadController@showUploadRequestResponse');
Route::post('/upload/request/respond/{requestId}', 'UploadController@doUploadRequestResponse');
Route::post('/upload/request/delete/{requestId}', 'UploadController@deleteUploadRequest');
Route::post('/upload/save_youtube_link', 'UploadController@saveYoutubeLink');

//Route::get('/upload', 'UploadController@index');
//Route::post('/upload', 'UploadController@doUpload');

Route::get('/users/cloud/bin/{communityId}', 'CloudController@showRecycleBin');
Route::post('/users/cloud/bin/restore', 'CloudController@restoreFromRecycleBin');
Route::get('/users/cloud/{communityId}', 'CloudController@index');
Route::post('/users/cloud', 'CloudController@cloudAction');
Route::post('/users/cloud/save_current_cloud', 'CloudController@saveCurrentCloud');
Route::post('/users/cloud/execute_contributor_action', 'CloudController@executeContributorAction');


Route::get('/users/community/settings', 'SettingsController@showSettings');
Route::post('/users/community/settings', 'SettingsController@saveAcademicSession');
Route::post('/users/community/settings/update_selected_dir', 'SettingsController@updateSyncableDirId');

Route::get('/users/cloud/view/{cloudId}', 'CloudController@showSingleDirFile');
Route::get('/users/cloud/view/{cloudId}/open', 'CloudController@openDirLocation');
Route::get('/users/cloud/preview/{cloudId}', 'CloudController@previewFile');
Route::get('/users/cloud/download/{cloudId}', 'CloudController@downloadFile');

Route::post('/users/cloud/cut_copy_dir_file', 'CloudController@cutCopyDirFile');
Route::post('/users/cloud/delete_dir_file', 'CloudController@deleteDirFile');
Route::post('/users/cloud/rename_dir', 'CloudController@renameDir');
Route::post('/users/cloud/{cloudId}', 'CloudController@cloudUpload');




Route::get('/users/ask', 'QuestionController@index');
Route::post('/users/ask', 'QuestionController@saveQuestion');

Route::get('/users/question/all', 'QuestionController@showAllQuestions');
Route::get('/users/question/{id}', 'QuestionController@showSingleQuestion');
Route::post('/users/question/vote', 'QuestionController@castVote');
Route::post('/users/question/comment_for_q', 'QuestionController@doCommentForQuestion');
Route::post('/users/question/edit_comment', 'QuestionController@editComment');

Route::post('/users/answer/save', 'QuestionController@saveAnswer');
Route::post('/users/answer/delete', 'QuestionController@deleteAnswer');
Route::post('/users/answer/update', 'QuestionController@updateAnswer');



Route::post('/notification/mark_as_seen', 'NotificationController@markAsSeen');
Route::post('/push_notification/save_onesignal_device_id', 'NotificationController@saveOnesignalDeviceId');



Route::get('/website/{website_name}', 'userWebsiteController@index');



Route::get('/tutorial/video', 'TutorialController@showVideoTutPage');



Route::get('/api/desktop/{token}', 'DesktopClientController@index');
Route::get('/api/mobile/{token}', 'MobileClientController@index');



Route::get('/api/mobile/attendance/get_courses', 'ApiController@ATTENDANCE_getCourses');
Route::get('/api/mobile/attendance/send_attendance_report', 'ApiController@ATTENDANCE_sendAttendanceReport');


Route::get('/api/words_reminder/simple_request_to_server', 'ApiController@WORDS_REMINDER_respondToSimpleRequest');



Route::get('/teachers/id/{id}', 'TeachersController@index');



Route::get('/privacy-policy', 'HomepageController@showPrivacyPolicy');
Route::get('/features', 'HomepageController@showChoriyedaoFeatures');
Route::get('/sponsor/benefit', 'HomepageController@showSponsorBenefit');
Route::post('/sponsor/save_sponsor_data', 'HomepageController@saveSponsorData');


Route::get('/notice_board', 'Noticeboard\BoardController@index');



Route::get('/visitor', 'VisitorController@index');
Route::get('/visitor_graph', 'VisitorController@representGraphically');
Route::post('visitor/increment_visit_time', 'VisitorController@increaseVisitTime');


Route::get('/api/books', 'ApiController@index');


Route::resource('/contest', 'ContestController');
Route::post('/contest/register', 'ContestController@registerForContest');
Route::post('/contest/register_cancel', 'ContestController@cancelRegistration');
Route::get('contest_fetch_total_registered', 'ContestController@fetchTotalRegistered');
Route::post('/contest/start/', 'ContestController@startContest');


Auth::routes();


Route::get('/home', 'HomeController@index');


Route::get('/test', 'HomeController@test');


Route::get('/sse', 'HomeController@sse');

