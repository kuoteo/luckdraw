<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AwardLogModel extends Model
{
    protected $fillable = ['uid'];

    protected $table = 'lty_award_log';

    public $timestamps = false;
}
