<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AwardModel extends Model
{
    protected $fillable = ['uid','awid','award_name','award_num','award_num'];

    protected $table = 'lty_award';

    public $timestamps = false;
}
