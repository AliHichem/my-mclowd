<?php
namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;


class TaskSearch
{
    public $query, $categories, $type, $currency, $timePeriod, $budget;
}