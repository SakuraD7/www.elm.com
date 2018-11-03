<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller{
    //保存购物车
    public function create(Request $request){
        //获取数据
        $user_id = Auth::user()->id;
        $goodsList = $request->goodsList;
        $goodsCount = $request->goodsCount;
        //Cart::where('user_id',$user_id)->delete();
        foreach ($goodsList as $k => $goods_id){
            $amount = $goodsCount[$k];
            //检测购物车中是否有该商品，有的话累加，没有添加
            $cart = Cart::where('user_id',$user_id)->where('goods_id',$goods_id)->first();
            if($cart){
                $cart->update(['amount'=>$cart->amount+$amount]);
            }else{
                Cart::create([
                    'user_id'=>$user_id,
                    'goods_id'=>$goods_id,
                    'amount'=>$amount,
                ]);
            }
        }
        return [
            "status"=> "true",
            "message"=> "添加成功"
        ];
    }
    //显示购物车数据
    public function show(){
        $id = Auth::user()->id;
        $carts = Cart::where('user_id',$id)->get();
        $dates=[];
        $totalCost = 0;
        foreach ($carts as $cart){
            $menu = Menu::where('id',$cart->goods_id)->first();
                        $data= [
                        "goods_id"=>$cart->goods_id,
                        "goods_name"=> $menu->goods_name,
                        "goods_img"=>$menu->goods_img,
                        "amount"=>$cart->amount,
                        "goods_price"=>$menu->goods_price,
            ];
            $totalCost += $menu->goods_price*$cart->amount;
            $dates[]=$data;
        }
        return ['goods_list'=>$dates,'totalCost'=>$totalCost];
    }
//    public function show(){
//        $id = Auth::user()->id;
//        $cart = Cart::where('user_id',$id)->latest()->first();
//        $menus = Menu::where('id',$cart->goods_id)->get();
//        //dd($menus);
//        foreach ($menus as $menu){
//            return [
//                "goods_list"=>[
//                    [
//                        "goods_id"=>"$cart->goods_id",
//                        "goods_name"=> "$menu->goods_name",
//                        "goods_img"=>"$menu->goods_img",
//                        "amount"=>"$cart->amount",
//                        "goods_price"=>"$menu->goods_price",
//                    ]
//                ],
//                "totalCost"=>$menu->goods_price*$cart->amount
//            ];
//        }
//    }
}
