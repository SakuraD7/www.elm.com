<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    //声明可被接收的变量
    protected $fillable = ['user_id','province','city','county','address','tel','name','is_default'];
}
