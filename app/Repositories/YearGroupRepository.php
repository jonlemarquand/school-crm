<?php

namespace App\Repositories;

use App\Interfaces\YearGroupInterface;
use App\Models\YearGroup;

class YearGroupRepository extends BaseRepository implements YearGroupInterface
{
    public function __construct(YearGroup $yeargroup)
    {
        parent::__construct($yeargroup);
    }
}
