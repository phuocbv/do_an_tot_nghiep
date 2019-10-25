<?php
/**
 * Created by PhpStorm.
 * User: da
 * Date: 19/09/2017
 * Time: 01:07
 */

namespace App\Repositories\Contracts;


interface InternShipGroupRepositoryInterface
{
    public function getGroupAssigned($courseID);
}
