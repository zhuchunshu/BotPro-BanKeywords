<?php

namespace App\Plugins\Bankeywords\src\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;

use Illuminate\Database\Eloquent\Model;

class BankeywordsCi extends Model
{
	use HasDateTimeFormatter;
    protected $table = 'bankeywords_ci';
    public $timestamps = true;

}
