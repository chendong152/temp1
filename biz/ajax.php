<?php

/**
 * Created by Dong.
 * User: Dong(mailto:techdong@hotmail.com)
 * Date: 2015-08-02
 * Time: 2:22
 */

require_once 'dish.php';
require_once 'dishConfig.php';
require_once 'user.php';
require_once __DIR__ . '/mysql.class.php';

echo json_encode(!empty($_GET['action']) ? $_GET['action']() : json_encode(array('code' => 404, 'msg' => '当前action不存在')));
exit;

function check() {
    global $allDishes, $config, $db;
    $ds = $_REQUEST['dishes'];
    $dishes = array($allDishes[$ds[0]], $allDishes[$ds[1]], $allDishes[$ds[2]]);
    $merge = Dish::merge($dishes);

    //记录结果到库:用户基本信息、选择的3个菜品、最高分和最低分键值对、结果鉴定语、时间 (重玩时覆盖)
    //todo:
    $data = array(
        'nickname'   => "dong",
        'openid'     => "openid" . floor(rand(1, 1000)),
        'header_img' => '',
        'dishes'     => implode(',', $ds),
    );
    $db->insert("user")->data($data)->done();

    //鉴定
    return array('code' => 0, 'score' => 1, 'msg' => getText($merge));
}

function t() {
    //todo:从库中获取用户
    global $db;
    $data = $db->select('user')->where('1=1')->limit(1)->done();
    if ($data) $data = $data[0];
    print_r($data);
    $u = User::from($data);
    print_r($u);
    $u->dishes = array(
        new Dish(3, 1, 5, 1, 1, '大鱼'),
        new Dish(3, 1, 5, 1, 1, '中鱼'),
        new Dish(3, 1, 5, 5, 3, '小鱼'),
    );

    $f = clone $u;
    $f->dishes = array_slice($f->dishes, 0, 2, true);
    return $u->match($f);
}