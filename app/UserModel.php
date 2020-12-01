<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserModel extends Model
{
    protected $fillable = ['uid','user_name','password','chance'];

    protected $table = 'lty_user';

    public $timestamps = false;
}
