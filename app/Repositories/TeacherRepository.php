<?php

namespace App\Repositories;

use App\Interfaces\TeacherInterface;
use App\Models\Teacher;

class TeacherRepository extends BaseRepository implements TeacherInterface
{
    public function __construct(Teacher $teacher)
    {
        parent::__construct($teacher);
    }
}
