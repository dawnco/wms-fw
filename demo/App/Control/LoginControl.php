<?php
/**
 * @author Dawnc
 * @date   2020-05-28
 */

namespace App\Control;

use App\Dict\Dict;
use App\Exception\AppException;
use Wms\Fw\Db;

class LoginControl
{
    public function __construct()
    {
        $this->db = Db::instance();
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

            $token = Session::new($admin);

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
}
