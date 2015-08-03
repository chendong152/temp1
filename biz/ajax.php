<?php

/**
 * Created by Dong.
 * User: Dong(mailto:techdong@hotmail.com)
 * Date: 2015-08-02
 * Time: 2:22
 */
class Dish
{
    public $p1;
    public $p2;
    public $p3;
}

echo json_encode(!empty($_GET['action']) ? $_GET['action']() : json_encode(array('code' => 404, 'msg' => '当前action不存在')));
exit;

function check()
{
    //鉴定
    return array('code' => 0, 'score' => 1, 'msg' => '你是一个吃货');
}