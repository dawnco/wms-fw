<?php
/**
 * @author Dawnc
 * @date   2020-05-28
 */

namespace App\Control;

use App\Dict\Dict;
use App\Exception\AppException;
use App\Lib\Token;
use Wms\Fw\Db;
use Wms\Lib\Redis;

class LoginControl
{
    protected $token;

    public function __construct()
    {
        $this->db    = Db::instance();
        $token       = $_SERVER['HTTP_X_TOKEN'] ?? '';
        $this->token = new Token(Redis::getInstance(), $token);
    }

    public function index()
    {

        $username = input("username");
        $password = input("password");

        $admin = $this->db->getLine("SELECT * FROM admin WHERE `username`= ?s", [$username]);

        //判断用户是否存在
        if (!$admin) {
            throw new AppException("帐号不存在");
        }

        if ($admin['enabled'] == Dict::YES) {
            throw new AppException('账号被禁用');
        }

        if (password_verify($password, $admin['password'])) {

            $token = $this->token->new($admin)->getTokenKey();

            return [
                "token"    => $token,
                "id"       => $admin['id'],
                "username" => $admin['username'],
                "name"     => $admin['name'],
            ];
        } else {
            throw new AppException("密码错误");
        }
    }

    public function logout()
    {
        $this->token->destroy();
    }
}
