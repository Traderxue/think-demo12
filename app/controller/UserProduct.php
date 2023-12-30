<?php
namespace app\controller;

use app\BaseController;
use think\Request;
use app\model\UserProduct as UserProductModel;
use app\util\Res;

class UserProduct extends BaseController{

    protected $result;

    function __construct(\think\App $app){
        $this->result = new Res();
    }

    function buy(Request $request){
        $post = $request->post();

        $user_producted = new UserProductModel([
            "user_id"=>$post["user_id"],
            "product_id"=>$post["producted_id"],
            "num"=>$post["num"]
        ]);

        $res = $user_producted->save();
        if($res){
            return $this->result->success("买入成功",$res);
        }
        return $this->result->error("买入失败");
    }

    function page(Request $request){
        $page = $request->param("page");
        $pageSize = $request->param("pageSize");
        
        $list = UserProductModel::paginate([
            "page"=>$page,
            "list_rows"=>$pageSize
        ]);
        return $this->result->success("获取数据成功",$list);
    }
}