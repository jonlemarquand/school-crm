<?php

namespace App\Repositories;

use App\Interfaces\FormInterface;
use App\Models\Form;

class FormRepository extends BaseRepository implements FormInterface
{
    public function __construct(Form $form)
    {
        parent::__construct($form);
    }
}
