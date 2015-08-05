<?php

/**
 * Created by Dong.
 * User: Dong(mailto:techdong@hotmail.com)
 * Date: 2015-08-02
 * Time: 2:22
 */

require_once 'dish.php';

echo json_encode(!empty($_GET['action']) ? $_GET['action']() : json_encode(array('code' => 404, 'msg' => '当前action不存在')));
exit;

function check() {
    global $allDishes;
    $ds = $_REQUEST['dishes'];
    $dishes = array($allDishes[$ds[0]], $allDishes[$ds[1]], $allDishes[$ds[2]]);
    $merge = Dish::merge($dishes);

    //记录结果到库
    //todo:

    //鉴定
    return array('code' => 0, 'score' => 1, 'msg' =>getText($merge) );
}