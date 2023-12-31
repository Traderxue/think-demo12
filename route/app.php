<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\facade\Route;

Route::get('think', function () {
    return 'hello,ThinkPHP6!';
});

Route::get('hello/:name', 'index/hello');


Route::group("/user", function () {

    Route::post("/register", "user/register");

    Route::post("/login", "user/login");

    Route::post("/edit", "user/edit");      //需要上传图片要用formFata

    Route::get("/get/:id", "user/getUseryId");
});

Route::group("/bill", function () {

    Route::post("/putup", "bill/putup");            //需要上传图片要用formFata

    Route::post("/withdraw", "bill/withdraw");

    Route::put("/verify/:id", "bill/verify");

    Route::get("/get/:u_id", "bill/getByUid");

    Route::get("/page", "bill/page");
});

Route::group("/product", function () {

    Route::post("/add", "product/add");

    Route::remove("/remove/:id", "product/remove");      //下架

    Route::get("/page", "product/page");
});

Route::group("/userproduct", function () {

    Route::post("/buy", "userproduct/buy");

    Route::get("/page", "userproduct/page");
});

Route::group("/admin", function () {

    Route::post("/add", "admin/add");

    Route::post("/login", "admin/login");

    Route::delete("/delete", "admin/delete");

    Route::post("/edit", "admin/edit");

    Route::post("/resetpwd", "admin/resetPwd");

    Route::get("/page", "admin/page");
});
