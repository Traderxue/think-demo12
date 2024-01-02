<?php
namespace app\controller;

use think\Request;
use app\BaseController;
use app\model\Miner as MinerModel;
use app\util\Res;

class Miner extends BaseController{


    
    private $result;

    function __construct(\think\App $app){
        $this->result = new Res();
    }

    public function add(Request $request){
        $post = $request->post();
        $miner = new MinerModel([
            "price"=>$post["price"],
            "model"=>$post["model"],
            "descriptioon"=>$post["description"]
        ]);
        $res = $miner->save();
        if($res){
            return $this->result->success("添加数据成功",$miner);
        }
        return $this->result->error("添加数据失败");
    }

    public function edit(Request $request){
        $post = $request->post();

        $miner = MinerModel::where("id",$post["id"])->find();

        $res = $miner->save([
            "price"=>$post["price"],
            "model"=>$post["model"],
            "description"=>$post["description"]
        ]);

        if($res){
            return $this->result->success("修改数据成功",$miner);
        }
        return $this->result->error("修改数据失败");
    }

    public function page(Request $request){
        $page = $request->param("page");
        $pageSize = $request->param("pageSize");
        $miner = $request->param('miner');

        $list = MinerModel::where("miner","like","%{$miner}%")->paginate([
            "page"=>$page,
            "list_rows"=>$pageSize
        ]);

        return $this->result->success("获取数据成功",$list);

    }

    public function getById($id){
        $miner = MinerModel::where("id",$id)->find();

        if($miner==null){
            return $this->result->error("数据不存在");
        }
        return $this->result->success("获取数据成功",$miner);
    }

    public function delete($id){
        $res = MinerModel::where('id',$id)->delete();

        if($res){
            return $this->result->success("删除数据成功",$res);
        }
        return $this->result->error("删除数据失败");
    }
    

}