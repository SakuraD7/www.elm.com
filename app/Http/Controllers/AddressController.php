<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AddressController extends Controller{
    //添加地址
    public function create(Request $request){
       $user_id = Auth::user()->id;
       Address::create([
           'user_id'=>$user_id,
           'name'=>$request->name,
           'tel'=>$request->tel,
           'province'=>$request->provence,
           'city'=>$request->city,
           'county'=>$request->area,
           'address'=>$request->detail_address,
           'is_default'=>0
       ]);
        return [
            "status"=> "true",
            "message"=> "添加成功"
        ];
    }
    //地址列表
    public function index(){
        $user_id = Auth::user()->id;
        //dd($user_id);
        $addresses = Address::where('user_id',$user_id)->get();
        //dd($addresses);
        $datas = [];
        foreach ($addresses as $address){
            $data = [
                'id' =>$address->id,
                'name' => $address->name,
                'tel' => $address->tel,
                'provence' => $address->province,
                'city' => $address->city,
                'area' => $address->county,
                'detail_address' => $address->address
            ];
            $datas[] = $data;
        }
        return $datas;
    }
    //修改回显
    public function edit(){
        $id=$_GET['id'];
        $addresses = Address::where('id',$id)->first();
        $data= [
            'id'=>$addresses->id,
            'provence'=>$addresses->province,
            'city'=>$addresses->city,
            'area'=>$addresses->county,
            'detail_address'=>$addresses->address,
            'name'=>$addresses->name,
            'tel'=>$addresses->tel,
        ];
        return $data;
    }
    //保存修改地址
    public function update(Request $request){
        //接收修改的地址id
        $id=$request->id;
        DB::table('addresses')
            ->where('id',$id)
            ->update([
            'name'=>$request->name,
            'tel'=>$request->tel,
            'province'=>$request->provence,
            'city'=>$request->city,
            'county'=>$request->area,
            'address'=>$request->detail_address,
        ]);
        return [
            "status"=> "true",
            "message"=> "修改地址成功"
        ];
    }
}
