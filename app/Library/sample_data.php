<?php


$years = [
    '1st Year',
    '2nd Year',
    '3rd Year',
    '4th Year',
    'Masters'
];

$semesters = [
    '1st Year 1st Semester',
    '1st Year 2nd Semester',
    '2nd Year 1st Semester',
    '2nd Year 2nd Semester',
    '3rd Year 1st Semester',
    '3rd Year 2nd Semester',
    '4th Year 1st Semester',
    '4th Year 2nd Semester',
    'Masters 1st Semester',
    'Masters 2nd Semester',
];


$resourceType = [
    'Books',
    'Slides',
    'Questions',
    'Others'
];


$row = [
    [
        'user_id'               => Auth::id(),
        'name'                  => '',
        'parent_dir'            => '',
        'is_root'               => 0,
        'full_dir_url'          => '',
        'file_ext'              => 'null',
        'access_code'           => 2, // shared with 'particular community'
        'possessed_by_community'=> $communityId,
        'file_size'             => 0,
        'soft_delete'           => 0,
        'permanent_delete'      => 0,
        'created_at'            => Carbon::now(),
        'updated_at'            => Carbon::now()
    ]
];



foreach ($resourceType as $resourceItem){
    array_push($subDirectories, [
        'user_id'               => Auth::id(),
        'name'                  => $resourceItem,
        'parent_dir'            => $yearOrSemester,
        'is_root'               => 0,
        'full_dir_url'          => '/'.$yearOrSemester,
        'file_ext'              => 'null',
        'access_code'           => 2, // shared with 'particular community'
        'possessed_by_community'=> $communityId,
        'file_size'             => 0,
        'soft_delete'           => 0,
        'permanent_delete'      => 0,
        'created_at'            => Carbon::now()->toDateTimeString(),
        'updated_at'            => Carbon::now()->toDateTimeString()
    ]);
    foreach ($sessions as $session){
        array_push($subDirectories, [
            'user_id'               => Auth::id(),
            'name'                  => $session,
            'parent_dir'            => "$resourceItem",
            'is_root'               => 0,
            'full_dir_url'          => "/$yearOrSemester/$resourceItem",
            'file_ext'              => 'null',
            'access_code'           => 2, // shared with 'particular community'
            'possessed_by_community'=> $communityId,
            'file_size'             => 0,
            'soft_delete'           => 0,
            'permanent_delete'      => 0,
            'created_at'            => Carbon::now()->toDateTimeString(),
            'updated_at'            => Carbon::now()->toDateTimeString()
        ]);
    }
}







?>