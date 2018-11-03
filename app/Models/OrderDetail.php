<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    //声明可被接收的变量
    protected $fillable = [
        'order_id','goods_id','amount','goods_name','goods_img','goods_price'
    ];
}
