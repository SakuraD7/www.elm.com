<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class MemberController extends Controller{
    //用户注册
    public function create(Request $request){
        //验证数据
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ], [
            'username.required'=>'用户名不能为空',
            'password.required'=>'密码不能为空',
        ]);
        //验证失败
        if ($validator->fails()) {
            return [
                "status" => "false",
                "massage" => $validator->errors()
            ];
        }
        $code = Redis::get('code'.$request->tel);
        if($request->sms!=$code){
            return [
                "status" => "false",
                "massage" => "请输入正确的验证码"
            ];
        }
        Member::create([
            'username'=>$request->username,
            'password'=>bcrypt($request->password),
            'tel'=>$request->tel,
        ]);
        return [
            "status"=> "true",
            "message"=> "注册成功"
        ];
    }

    //用户登录
    public function login(Request $request){
        //验证数据
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'password' => 'required',
        ], [
            'username.required'=>'用户名不能为空',
            'password.required'=>'密码不能为空',
        ]);
        //验证失败
        if ($validator->fails()) {
            return [
                "status" => "false",
                "message" => '用户名或密码不能为空',
            ];
        }
        //登录验证
        if(Auth::attempt(['username'=>$request->name,'password'=>$request->password])){
            return [
                "status"=> "true",
                "message" => "登陆成功",
                "user_id"=>Auth::user()->id,
                "username"=>$request->name,
            ];
        }else{
            return [
                "status"=> "false",
                "message" => "请输入正确的登录信息",
                "user_id"=>Auth::user()->id,
                "username"=>$request->name
            ];
        }
    }

    public function changePassword(Request $request){
        if (Hash::check($request->oldPassword, auth()->user()->password)){
            auth()->user()->update([
                'password' => bcrypt($request->newPassword),
            ]);
            return [
                "status"=>"true",
                "message"=> "修改成功"
            ];
        }else{
            return [
                "status"=>"false",
                "message"=> "旧密码错误"
            ];
        }
    }

    //忘记密码
    public function forgetPassword(Request $request){
        $tel = Member::where('tel',$request->tel)->first()->tel;
        if($tel){
            $code= Redis::get('code'.$request->tel);
            if($request->sms!=$code){
                return [
                    "status" => "false",
                    "massage" => "请输入正确的验证码"
                ];
            }else{
                DB::table('members')
                    ->where('tel',$tel)
                    ->update([
                        'password'=>bcrypt($request->password)
                    ]);
                return
                [
                    "status"=>"true",
                    "message"=> "重置密码成功"
                ];
            }
        }else{
            return [
                "status"=>"false",
                "message"=> "请输入正确的手机号码"
            ];
        }
    }
}
