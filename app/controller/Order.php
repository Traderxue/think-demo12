<?php

namespace app\controller;

use think\Request;
use app\BaseController;
use app\model\order as OrderModel;
use app\model\User as UserModel;
use app\util\Res;

//id u_id operate amount verify

class Order extends BaseController{
    private $result;

    public function __construct(\think\App $app){
        $this->result = new Res();
    }

    public function add(Request $request){
        $post = $request->post();

        $order = new OrderModel([
            "u_id"=>$post["u_id"],
            "operate"=>$post["operate"],
            "amount"=>$post["amount"],
            "verify"=>0
        ]);
        $res = $order->save();

        if($res){
            return $this->result->success("添加数据成功",$res);
        }
        return $this->result->error("添加数据失败");
    }

    public function edit(Request $request){
        $post = $request->post();
        $order = OrderModel::where("id",$post["id"])->find();

        $res = $order->save([
            "operate"=>$post["operate"],
            "money"=>$post["money"],
            "verify"=>$post["verify"]
        ]);

        if($res){
            return $this->result->success("修改数据成功",$order);
        }
        return $this->result->error("修改数据失败");
    }

    public function deleteById($id){
        $res = OrderModel::where("id",$id)->delete();
        if($res){
            return $this->result->error("删除数据失败");
        }
        return $this->result->success("删除数据成功",$res);
    }

    public function page(Request $request){
        $page = $request->param('page');
        $pageSize = $request->param("pageSize");
        $u_id = $request->param("u_id");
        
        $list = OrderModel::where("u_id",$u_id)->paginate([
            "page"=>$page,
            "list_rows"=>$pageSize
        ]);
        return $this->result->success("获取数据成功",$list);
    }

    public function getByUid($u_id){
        $list = OrderModel::where("u_id",$u_id)->select();
        return $this->result->success("获取数据成功",$list);
    }

}