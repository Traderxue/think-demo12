<?php
namespace app\controller;

use think\Request;
use app\BaseController;
use app\model\Bill as BillModel;
use app\util\Res;
use app\controller\Upload;

class Bill extends BaseController{

    protected $result;

    function __construct(\think\App $app){
        $this->result = new Res();
    }

    function putup(Request $request){
        $post = $request->post();

        $upload = new Upload();

        $url = $upload->index();

        $bill = new BillModel([
            "u_id"=>$post["u_id"],
            "operate"=>1,
            "prove"=>$url,
            "num"=>$post["num"],
        ]);
        $res = $bill->save();
        if($res){
            return $this->result->success("提交成功,等待审核",$bill);
        }
        return $this->result->error("提交失败");
    }

    function withdraw(Request $request){
        $post = $request->post();
        
        $bill = new BillModel([
            "u_id",$post["u_id"],
            "operate"=>0,
            "num"=>$post["num"]
        ]);

        $res = $bill->save();
        if($res){
            return $this->result->success("提交成功,等待审核",$bill);
        }
        return $this->result->error("提交失败");
    }

    function verify($id){
        $bill = BillModel::where("id",$id)->find();
        if($bill->verify==1){
            return $this->result->error("订单已审核,请勿重复请求");
        }
        if($bill->operate==1){
            $bill->save([
                "balance"=>(float) $bill->balance + (float) $bill->num,
                "verify"=>1
            ]);
            return $this->result->success("订单已审核",$bill);
        }else{
            $bill->save([
                "balance"=>(float) $bill->balance - (float) $bill->num,
                "verify"=>1
            ]);
            return $this->result->success("订单已审核",$bill);
        }
    }

    function getByUid($u_id){
        $list = BillModel::where("u_id",$u_id)->select();
        return $this->result->success("获取数据成功",$list);
    }

    function page(Request $request){
        $page =  $request->param("page");
        $pageSzie = $request->param("pageSize");

        $list = BillModel::paginate([
            "page"=>$page,
            "list_rows"=>$pageSzie
        ]);
        return $this->result->success("获取数据成功",$list);
    }
}