<?php

namespace app\admin\controller;

use app\model\Admin;
use WebmanTech\Auth\Auth;
use support\Request;
use support\Response;

class AuthController
{
    // 登录
    public function login(Request $request): Response
    {
        // 验证提交字段
        $data = $request->post();
        // 查询用户
        $user = Admin::query()->where('username', $data['username'])->first();
        if (!$user || $user->validatePassword($data['password'])) {
            throw new \InvalidArgumentException('帐号或密码错误');
        }
        // 登录
        Auth::guard()->login($user);

        return json($user);
    }

    // 退出登录
    public function logout(): Response
    {
        if (Auth::guard()->isGuest()) {
            return json('guest');
        }

        Auth::guard()->logout();

        return json('logout');
    }

    // 获取用户信息
    public function info(): Response
    {
        return json(Auth::guard()->getUser());
    }
}
