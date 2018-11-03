<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    //声明可被接收的变量
    protected $fillable = ['user_id','goods_id','amount'];
}
