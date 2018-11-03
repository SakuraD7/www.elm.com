<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\MenuCategory;
use App\Models\Shop;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ShopController extends Controller
{
    //商家列表的信息
    public function index()
    {
        if(isset($_GET['keyword'])){
            $keyword=$_GET['keyword'];
        }
        @$keyword!=''?$shops = Shop::where('shop_name','like',"%{$keyword}%")->get():$shops=Shop::all();
        $datas = [];
        foreach ($shops as $shop) {
            $data = [
                "id" => $shop->id,
                "shop_name" => $shop->shop_name,
                "shop_img" => $shop->shop_img,
                "shop_rating" => $shop->shop_rating,
                "brand" => $shop->brand,
                "on_time" => $shop->on_time,
                "fengniao" => $shop->fengniao,
                "bao" => $shop->bao,
                "piao" => $shop->piao,
                "zhun" => $shop->zhun,
                "start_send" => $shop->start_send,
                "send_cost" => $shop->send_cost,
                "distance" => 800,
                "estimate_time" => 10,
                "notice" => $shop->notice,
                "discount" => $shop->discount
            ];
            $datas[] = $data;
        }
        return $datas;
    }

    //获取指定的商家信息
    public function seller()
    {
        //获取指定商家ID
        $shop_id = $_GET['id'];
        //根据ID查询商家信息
        $shop = Shop::where('id', $shop_id)->first();
        $datas = [];
        //查询该商家的菜品分类
        $menucategories = MenuCategory::where('shop_id', $shop_id)->get();
        foreach ($menucategories as $menucategory) {
            $menus = Menu::where('category_id', $menucategory->id)->get();
            $goods = [];
            foreach ($menus as $menu) {
                    $menusdata = [
                        "goods_id" => $menu->id,
                        "goods_name" => $menu->goods_name,
                        "rating" => $menu->rating,
                        "goods_price" => $menu->goods_price,
                        "description" => $menu->description,
                        "month_sales" => $menu->month_sales,
                        "rating_count" => $menu->rating_count,
                        "tips" => $menu->tips,
                        "satisfy_count" => $menu->satisfy_count,
                        "satisfy_rate" => $menu->satisfy_rate,
                        "goods_img" => $menu->goods_img
                    ];
                $goods[] = $menusdata;
            }
            $categorydata = [
                "description" => $menucategory->description,
                "is_selected" => $menucategory->is_selected == 1 ? true : false,
                "name" => $menucategory->name,
                "type_accumulation" => $menucategory->type_accumulation,
                "goods_list" => $goods
            ];
            $commodity[] = $categorydata;
        }
        $shopdata = [
            "id" => $shop->id,
            "shop_name" => $shop->shop_name,
            "shop_img" => $shop->shop_img,
            "shop_rating" => $shop->shop_rating,
            "service_code" => 4.4,
            "foods_code" => 4.5,
            "high_or_low" => true,
            "h_l_percent" => 30,
            "brand" => $shop->brand == 1 ? true : false,
            "on_time" => $shop->brand == 1 ? true : false,
            "fengniao" => $shop->brand == 1 ? true : false,
            "bao" => $shop->brand == 1 ? true : false,
            "piao" => $shop->brand == 1 ? true : false,
            "zhun" => $shop->brand == 1 ? true : false,
            "start_send" => $shop->start_send,
            "send_cost" => $shop->send_cost,
            "distance" => 637,
            "estimate_time" => 31,
            "notice" => $shop->notice,
            "discount" => $shop->discount,
            "evaluate" => [[
                "user_id" => 12344,
                "username" => "w******k",
                "user_img" => "/images/slider-pic4.jpeg",
                "time" => "2017-2-22",
                "evaluate_code" => 1,
                "send_time" => 30,
                "evaluate_details" => "不怎么好吃"
            ], [
                "user_id" => 12344,
                "username" => "w******k",
                "user_img" => "/images/slider-pic4.jpeg",
                "time" => "2017-2-22",
                "evaluate_code" => 5,
                "send_time" => 30,
                "evaluate_details" => "很好吃"
            ]
            ],
            "commodity"=>$commodity,
        ];
        return $shopdata;
    }
}
