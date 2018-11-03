<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Cart;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller{
    //添加订单
    public function create(Request $request){
        //地址id
        $address_id = $request->address_id;
        //根据地址id查询地址信息
        $address = Address::find($address_id);
        //用户id
        $user_id = Auth::user()->id;
        //根据用户id查询carts表中该用户的订单菜品id
        $goods_id = Cart::where('user_id',$user_id)->orderBy('id','desc')->first()->goods_id;
        //根据菜品id查出menus表中该菜品的所属商家id
        $shop_id = Menu::find($goods_id)->shop_id;
        //根据商家id查出商家信息
        $shops = Shop::where('id',$shop_id)->first();
        //订单编号
        $order_code = mt_rand(1000,9999).date("mdHi",time());
        //交易号
        $out_trade_no = mt_rand(1000,9999).date("YmdHis",time());
        //查询该用户的购物车
        $carts = Cart::where('user_id',$user_id)->get();
        $order_price = 0;//订单总价
        foreach ($carts as $cart){
            $food_id = $cart->goods_id;
            $food = Menu::find($food_id);
            $order_price += $cart->amount*$food->goods_price;
        }
        DB::transaction(function () use($user_id,$shop_id,$shops,$order_code,$address,$order_price,$out_trade_no,$carts){
            //订单表新增数据
            $order = Order::create([
                'user_id'=>$user_id,
                'shop_id'=>$shop_id,
                'sn'=>$order_code,
                'province'=>$address->province,
                'city'=>$address->city,
                'county'=>$address->county,
                'address'=>$address->address,
                'tel'=>$address->tel,
                'name'=>$address->name,
                'total'=>$order_price,
                'status'=>0,
                'out_trade_no'=>$out_trade_no,
            ]);
            //订单详情表新增数据
            foreach ($carts as $cart){
                $goods = Menu::find($cart->goods_id);
                OrderDetail::create([
                    'order_id'=>$order->id,
                    'goods_id'=>$cart->goods_id,
                    'amount'=>$cart->amount,
                    'goods_name'=>$goods->goods_name,
                    'goods_img'=>$goods->goods_img,
                    'goods_price'=>$goods->goods_price,
                ]);
                //购物车清空 删除购物车表里当前用户的数据
                $cart->where('user_id',$user_id)->delete();
            }
        });
        $order=Order::where('user_id',$user_id)->latest()->first()->id;//得到最后一次加入的id
        //dd($order_id);
        return [
            "status"=>"true",
            "message"=> "添加成功",
            "order_id"=>$order,
        ];
    }
    //获得指定订单
    public function order(){
        $id = $_GET['id'];
        $order = Order::where('id',$id)->first();
            $order_detail = OrderDetail::where('order_id',$order->id)->get();
            $goodslist = [];
            foreach ($order_detail as $detail){
                $goods=[
                    'goods_id'=>$detail->goods_id,
                    'goods_name'=>$detail->goods_name,
                    'goods_img'=>$detail->goods_img,
                    'amount'=>$detail->amount,
                    'goods_price'=>$detail->goods_price
                ];
                $goodslist[]=$goods;
            }
        return [
            'id'=>$order->id,
            'order_code'=>$order->sn,
            'order_birth_time'=>$order->created_at->format('Y-m-d H:i:s'),
            'order_status'=>"代付款",
            'shop_id'=>$order->shop_id,
            'shop_name'=>$order->shop_name,
            'shop_img'=>$order->shop_img,
            'goods_list'=>$goodslist,
            'order_price'=>$order->total,
            'order_address'=>$order->province.$order->city.$order->county.' '.$order->address,
            ];
    }
    //订单列表
    public function orderList(){
        //查询用户id
        $user_id = Auth::user()->id;
        //查询该用户的所有订单
        $orders = Order::where('user_id',$user_id)->get();
        foreach ($orders as $order){
            //查询商家信息
            $shop = Shop::where('id',$order->shop_id)->first();
            //查询订单商品
            $order_details = OrderDetail::where('order_id',$order->id)->get();
            foreach ($order_details as $order_detail){
                return [
                    [
                        "id"=>$order->id,
                        "order_code"=> $order->sn,
                        "order_birth_time"=> $order->created_at->format('Y-m-d H:i:s'),
                        "order_status"=> $order->status,
                        "shop_id"=> $shop->id,
                        "shop_name"=> $shop->shop_name,
                        "shop_img"=> $shop->shop_img,
                        "goods_list"=> [
                            [
                                "goods_id"=> $order_detail->goods_id,
                                "goods_name"=> $order_detail->goods_name,
                                "goods_img"=> $order_detail->goods_img,
                                "amount"=> $order_detail->amount,
                                "goods_price"=> $order_detail->goods_price,
                            ],
                        ],
                        "order_price"=>$order_detail->amount*$order_detail->goods_price,
                        "order_address"=>$order->province.$order->city.$order->county.' '.$order->address,
                    ]
                ];
            }
        }
    }
}
