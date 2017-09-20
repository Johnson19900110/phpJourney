<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Auth验证登录
    public function authenticate(Request $request)
    {
        $email = $request->input('email');
        $pass = $request->input('pass');
        $remember = $request->input('remember', true);

        if(Auth::attempt(['email'=>$email, 'password'=>$pass], $remember)) {
            $user = Auth::user();
            $data = array(
                'status' => 0,
                'message' => '登录成功',
                'user' => array(
                    'name' => $user['name'],
                )
            );
        }else {
            $data = array(
                'status' => 1,
                'message' => '用户名或密码不正确',
            );
        }

        return response()->json($data);
    }

    // Auth验证当前登录用户
    public function checkUser()
    {
        return Auth::check() ? 0 : 1;
    }

    // 退出登录
    public function logout()
    {
        Auth::logout();
        return response()->json(array('status' => 0, 'message' => '退出成功'));
    }
}
