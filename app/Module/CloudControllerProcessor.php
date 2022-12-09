<?php
/**
 * Created by PhpStorm.
 * User: partho
 * Date: 2/19/18
 * Time: 1:21 PM
 */

namespace App\Module;


use App\Library\Library;
use function GuzzleHttp\Psr7\mimetype_from_extension;

class CloudControllerProcessor
{
    private $Lib;

    function __construct()
    {
        $this->Lib = new Library();
    }


    public function getFileExtFromUrl($fileUrl){
        return pathinfo($fileUrl)['extension'];
    }

}