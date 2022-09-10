<?php

namespace App\Repositories;

use App\Interfaces\StudentInterface;
use App\Models\Student;

class StudentRepository extends BaseRepository implements StudentInterface
{
    public function __construct(Student $student)
    {
        parent::__construct($student);
    }
}
