<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuCategory extends Model
{
    //声明可被接收的变量
    protected $fillable = ['name','type_accumulation','shop_id','description','is_selected'];
}
