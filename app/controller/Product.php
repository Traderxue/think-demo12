<?php
namespace app\controller;

use think\Request;
use app\model\Product as ProductModel;
use app\util\Res;
use app\BaseController;

class Product extends BaseController{
    private $result;

    function __construct(\think\App $app){
        $this->result = new Res();
    }

    function add(Request $request){
        $post = $request->post();
        $product = new ProductModel([
            "name"=>$post["name"],
            "cycle"=>$post["cycle"],
            "rate"=>$post["rate"],
            "price"=>$post["price"]
        ]);
        $res = $product->save();
        if($res){
            return $this->result->success("添加品种成功",$product);
        }
        return $this->result->error("添加品种失败");
    }

    function remove($id){
        $product = ProductModel::where("id",$id)->find();
        $res = $product->save([
            "status"=>0
        ]);
        if($res){
            return $this->result->success("下架成功",$product);
        }
        return $this->result->error("下架失败");
    }

    function page(Request $request){
        $page = $request->param("page");
        $pageSize =$request->param("pageSize");
        $name = $request->param("name");

        $list = ProductModel::where("name","like","%{$name}%")->paginate([
            "page"=>$page,
            "list_rows"=>$pageSize
        ]);
        return  $this->result->success("获取数据成功",$list);
    }

}