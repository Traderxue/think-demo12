<?php

namespace app\controller;

use app\BaseController;
use think\Request;
use app\model\Admin as AdminModel;
use app\controller\Upload;
use app\util\Res;

class Admin extends BaseController
{
    protected $result;

    function __construct(\think\App $app)
    {
        $this->result = new Res();
    }

    function add(Request $request)
    {
        $post = $request->post();
        $admin = new AdminModel([
            "username" => $post["username"],
            "password" => password_hash($post["password"], PASSWORD_DEFAULT)
        ]);
        $res = $admin->save();
        if ($res) {
            return $this->result->success("添加用户成功", $admin);
        }
        return $this->result->error("添加用户失败");
    }

    function login(Request $request)
    {
        $post = $request->post();

        $admin = AdminModel::where("username", $post["username"])->find();

        if (!$admin) {
            return $this->result->error("用户不存在");
        }
        if (password_verify($post["password"], $admin->password)) {
            return $this->result->success("登录成功", $admin);
        }
        return $this->result->error("登录失败");
    }

    function delete($id)
    {
        $res = AdminModel::destroy($id);
        if ($res) {
            return $this->result->success("删除用户成功", $res);
        }
        return $this->result->error("删除用户失败");
    }

    function edit(Request $request)
    {
        $post = $request->post();

        $upload = new Upload();

        $url = $upload->index();

        if (!$url) {
            return $this->result->error("图片上传失败");
        }

        $admin = AdminModel::where("id", $post["id"])->find();

        $res = $admin->save([
            "nickname" => $post["nickname"],
            "avatar" => $url
        ]);

        if ($res) {
            return $this->result->success("修改资料成功", $admin);
        }
        return $this->result->error("修改资料失败");
    }

    function resetPwd(Request $request)
    {
        $post = $request->post();

        $admin = AdminModel::where("username", $post["username"])->find();

        if (!$admin) {
            return $this->result->error("用户不存在");
        }

        if (password_verify($post["password"], $admin->password)) {
            $res = $admin->save([
                "password" => password_hash($post["new_password"], PASSWORD_DEFAULT)
            ]);
            if ($res) {
                return $this->result->success("修改密码成功", $admin);
            }
        }
        return $this->result->error("旧密码错误");
    }

    function page(Request $reqeust)
    {
        $page = $reqeust->param("page");
        $pageSize = $reqeust->param("pageSize");
        $username = $reqeust->param("username");
        $nickname = $reqeust->param("nickname");

        $list = AdminModel::where("username", "like", "%{$username}%")
            ->where("nickname", "like", "%{$nickname}%")->paginate([
                "page" => $page,
                "list_rows" => $pageSize
            ]);

        return $this->result->success('获取数据成功',$list);
    }
}
