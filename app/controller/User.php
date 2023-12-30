<?php

namespace app\controller;

use think\Request;
use app\BaseController;
use app\model\User as UserModel;
use app\controller\Upload;
use app\util\Res;

class User extends BaseController
{
    protected $result;

    function __construct(\think\App $app)
    {
        $this->result = new Res();
    }

    function register(Request $request)
    {

        $post = $request->post();

        $userByUsername = UserModel::where("username", $post["username"])->find();

        if ($userByUsername) {
            return $this->result->error("用户已存在");
        }


        if ($post["invite_code"]) {
            $u = UserModel::where("invite_code", $post["invite_code"])->find();
            if (!$u) {
                return $this->result->error("邀请码不存在");
            }
            $u->save(["invite_num" => $u->invite_num + 1]);
        }
        $user = new UserModel([
            "username" => $post["username"],
            "password" => password_hash($post["password"], PASSWORD_DEFAULT),
            "add_time" => date("Y-m-d H:i:s")
        ]);
        $res = $user->save();
        if ($res) {
            return $this->result->success("注册成功", $user);
        }
        return $this->result->error("注册失败");
    }

    function login(Request $request)
    {
        $post = $request->post();
        $user = UserModel::where("username",$post["username"])->find();
        if(!$user){
            return $this->result->error("用户不存在");
        }

        if(!password_verify($post["password"],$user->password)){
            return $this->result->error("用户名或密码错误");
        }

        // jwt
        return $this->result->success("登录成功",$user);

    }


    function edit(Request $request){
        $post = $request->post();

        $user = UserModel::where("id",$post["id"])->find();

        $upload = new Upload();

        $url = $upload->index();

        if(!$url){
            return $this->result->error("图片上传失败");
        }

        $res = $user->save([
            "nickname"=>$post["nickname"],
            "invite_code"=>$post["invite_code"],
            "avator"=>$url
        ]);

        if($res){
            return $this->result->success("修改资料成功",$user);
        }
        return $this->result->error("修改用户资料失败");
    }
}
