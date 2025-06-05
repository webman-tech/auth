<?php

namespace app\api\controller;

use app\model\User;
use support\Request;
use support\Response;
use WebmanTech\Auth\Auth;

class AuthController
{
    // 登录
    public function login(Request $request): Response
    {
        // 验证提交字段
        $data = $request->post();
        // 查询用户
        $user = User::query()->where('username', $data['username'])->first();
        if (!$user || $user->validatePassword($data['password'])) {
            throw new \InvalidArgumentException('帐号或密码错误');
        }
        // 登录
        Auth::guard()->login($user);
        $user->refreshToken();

        return json($user);
    }

    // 退出登录
    public function logout(): Response
    {
        if (Auth::guard()->isGuest()) {
            return json('guest');
        }

        /** @var User $user */
        $user = Auth::guard()->getUser();
        $user->refreshToken(null);
        Auth::guard()->logout();

        return json('logout');
    }

    // 获取用户信息
    public function info(): Response
    {
        return json(Auth::guard()->getUser());
    }
}
