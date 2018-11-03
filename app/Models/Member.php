<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User;

class Member extends User{
    use Notifiable;
    //声明可被接收的变量
    protected $fillable = ['username','password','tel','remember_token'];
}
