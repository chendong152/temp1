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

session_start();
echo json_encode(!empty($_GET['action']) ? $_GET['action']() : json_encode(array('code' => 404, 'msg' => '当前action不存在')));
exit;

function check() {
    global $allDishes, $config, $db;
    $user = json_decode($_SESSION['user']);
    if (!$user) return array('msg' => 'no user');

    //游戏记录
    $from = isset($_REQUEST['from']) ? $_REQUEST['from'] : null;
    $exists = $from == null ? false : $db->select('user_record')->where("id=$from")->done();
    if ($exists) $exists = $exists[0];
    if ($exists['openid'] == $user->openid) return array('msg' => array('不能玩自己分享的哦'));

    $ds = $_REQUEST['dishes'];
    $dishes = array($allDishes[$ds[0]], $allDishes[$ds[1]], $allDishes[$ds[2]]);
    $merge = Dish::merge($dishes);

    //记录结果到库:用户基本信息、选择的3个菜品、最高分和最低分键值对、结果鉴定语、时间 (重玩时更新用户信息，新增游戏数据)
    $data = array(
        'nickname'   => $user->nickname,
        'openid'     => $user->openid,
        'headimgurl' => $user->headimgurl,
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

    $exists = $from == null ? false : $db->select('user_record')->where("openid='{$user->openid}' and from_id=$from")->done();
    if ($exists)
        return array('msg' => array('本期已玩过了'));//一期只能玩一次
    $data = array(
        'openid'        => $data['openid'],
        'dishes'        => implode(',', $ds),
        'style'         => implode(',', array_values($merge->toArray())),
        'score_high'    => key($scores['high']) . ':' . current($scores['high']),
        'score_low'     => key($scores['low']) . ':' . current($scores['low']),
        'result_kind'   => $texts[0],
        'result_detail' => $texts[1],
        'from_id'       => $from,
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
    global $db;
    $sql = "select * from savor_user_record r,savor_user u where r.openid=u.openid";
    print_r($db->exec($sql));
    return '';
}

/**
 * 获取与当前玩家相似度最高的3人
 * @return array
 *
 */
function similar() {
    global $db;
    $tn = 'user_record';
    $current = json_decode($_SESSION['user']);//当前玩家
    $from = $_REQUEST['from'];
    if ($from) {
        //查找本期(除当前玩家外。基于：一个玩家一期只能玩一次)所有参与记录
        $sql = "select * from savor_user_record r,savor_user u where r.openid=u.openid and r.from_id={$from} and r.openid<>'{$current->openid}'";
        $rs = $db->exec($sql);//select($tn)->where("from_id=$from and openid<>'{$current->openid}'")->done();

        //计算所有参与记录与当前玩家的相似度
        $base = $db->select($tn)->where("from_id=$from and openid='{$current->openid}'")->done();
        if (is_array($base)) $base = $base[0];//当前玩家的本次游戏记录

        if (!$base) return array();

        $sililars = array_map(function ($r) use ($base) {
            $r['similar'] = User::match(get_dish($base['dishes']), get_dish($r['dishes']));
            return $r;
        }, $rs);
        //获取相似度最高的前3人
        usort($sililars, function ($item1, $item2) {
            return $item2['similar'] - $item1['similar'];
        });

        //返回
        return array_slice($sililars, 0, 3);
    }
    return array();
    //否则，前端显示“我是同味吃货吗”，点击就提示分享
}

function pk() {
    global $db;
    $user = json_decode($_SESSION['user']);
    $from = $_REQUEST['from'];
    $rec = $db->select('user_record')->where("id=$from")->done();
    if ($rec) $rec = $rec[0];
    $isMyself = $rec['openid'] == $user->openid;

    $ret = array();
    //如果是我自己，则返回当期排行
    if ($isMyself) {
        $ret['current'] = $db->exec("select * from savor_user_record r,savor_user u where r.openid=u.openid and r.from_id=$from");
        usort($ret['current'], function ($i1, $i2) {
            return $i2['similar'] - $i1['similar'];
        });
    }
    //返回历史排行
    $ret['history'] = $db->select("user_record")->where("where openid='{$user->openid}'")->done();
    return $ret;
}