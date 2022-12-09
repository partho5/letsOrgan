<?php

/**
 * Created by PhpStorm.
 * User: partho
 * Date: 1/9/18
 * Time: 7:11 PM
 */

namespace App\Module\Traffic;

use App\VisitorTracker;
use Khill\Lavacharts\Laravel\LavachartsFacade as Lava;

class Visitor{


    function __construct()
    {

    }


    public function processMonthlyTrafficLog(){
        $data = VisitorTracker::where('id', '>', '7000')->take(20)->get();
        //$data = $data->toArray();

        $data2 = [];
        $i=0;
        foreach ($data as $datum){
            //echo "$datum->id <br>";
            $data2[$i++] = [$datum->visit_time, $datum->user_id];
        }



        $visitor = Lava::DataTable();
        $visitor->addStringcolumn('Hour')
            ->addNumberColumn('Number of visitors: ')
            ->addRows($data2);

        Lava::ColumnChart('monthly_log', $visitor, [
            'title' => 'Visitors per hour',
            'legend' => [
                'position' => 'top'
            ]
        ]);
    }
}