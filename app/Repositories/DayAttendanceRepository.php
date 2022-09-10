<?php

namespace App\Repositories;

use App\Interfaces\DayAttendanceInterface;
use App\Models\DayAttendance;

class DayAttendanceRepository extends BaseRepository implements DayAttendanceInterface
{
    public function __construct(DayAttendance $dayattendance)
    {
        parent::__construct($dayattendance);
    }
}
