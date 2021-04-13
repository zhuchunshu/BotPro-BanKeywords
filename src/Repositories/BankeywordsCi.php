<?php

namespace App\Plugins\Bankeywords\src\Repositories;

use App\Plugins\Bankeywords\src\Models\BankeywordsCi as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class BankeywordsCi extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
