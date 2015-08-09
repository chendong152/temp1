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

    //记录结果到库:用户基本信息、选择的3个菜品、最高分和最低分键值对、结果鉴定语、时间 (重玩时更新用户信息，新增游戏数据)
    //todo:
    $data = array(
        'nickname'   => "dong",
        'openid'     => "openid" . floor(rand(1, 10)),
        'header_img' => '',
    );
    $exists = $db->select("user")->where("openid='{$data['openid']}'")->limit(1)->done();
    if ($exists) {
        $data['update_time'] = time();
        $db->update('user')->where("openid='{$data['openid']}'");
    } else {
        $db->insert('user');
    }
    $db->data($data)->done();

    //鉴定
    $texts = getText($merge);
    $scores = getScore($merge);

    //游戏记录
    $data = array(
        'openid'        => $data['openid'],
        'dishes'        => implode(',', $ds),
        'style'         => implode(',', array_values($merge->toArray())),
        'score_high'    => key($scores['high']) . ':' . current($scores['high']),
        'score_low'     => key($scores['low']) . ':' . current($scores['low']),
        'result_kind'   => $texts[0],
        'result_detail' => $texts[1],
        'from_id'       => isset($_REQUEST['from']) ? $_REQUEST['from'] : null,
    );
    //相似度
    if (!empty($data['from_id'])) {
        $rec = $db->select("user_record")->limit(1)->where("id=" . $data['from_id'])->done();//获取推荐人的游戏信息
        $rec = $rec[0];
        $data['similar'] = User::match(get_dish($rec['dishes']), get_dish($data['dishes']));
        $data['from_openid'] = $rec['openid'];
    }
    $db->insert("user_record")->data($data)->done();//保存

    return array('code' => 0, 'score' => 1, 'msg' => $texts);
}

function t() {
    $ddd = new Dish();
    print_r(get_dish($ddd) === $ddd);

    //todo:从库中获取用户
    global $db;
    $data = $db->select('user')->where('1=1')->limit(1)->done();
    if ($data) $data = $data[0];
    print_r($data);
    $u = User::from($data);
    print_r(new DateTime($data['create_time']));
    print_r($u);
    $u->dishes = array(
        new Dish(3, 1, 5, 1, 1, '大鱼'),
        new Dish(3, 1, 5, 1, 1, '中鱼'),
        new Dish(3, 1, 5, 5, 3, '小鱼'),
    );

    $f = clone $u;
    $f->dishes = array_slice($f->dishes, 0, 2, true);
    return $u->matchWith($f);
}