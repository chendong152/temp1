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
include_once __DIR__ . '/../wx/h.php';

session_start();
echo json_encode(!empty($_GET['action']) ? $_GET['action']() : json_encode(array('code' => 404, 'msg' => '当前action不存在')));
exit;

function check()
{
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
        'nickname' => $user->nickname,
        'openid' => $user->openid,
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
        'openid' => $data['openid'],
        'dishes' => implode(',', $ds),
        'style' => implode(',', array_values($merge->toArray())),
        'score_high' => key($scores['high']) . ':' . current($scores['high']),
        'score_low' => key($scores['low']) . ':' . current($scores['low']),
        'result_kind' => $texts[0],
        'result_detail' => $texts[1],
        'from_id' => $from,
        'from_openid' => isset($_REQUEST['from_openid']) ? $_REQUEST['from_openid'] : null,
    );
    //相似度
    if (!empty($data['from_id'])) {
        $rec = $db->select("user_record")->limit(1)->where("id=" . $data['from_id'])->done();//获取推荐人的游戏信息
        if ($rec) {
            $rec = $rec[0];
            $data['similar'] = User::match(get_dish($rec['dishes']), get_dish($data['dishes']));
            $data['from_openid'] = $rec['openid'];
        }
    }
    $db->insert("user_record")->data($data)->done();//保存
    $id = $db->exec("SELECT LAST_INSERT_ID() as id");
    if ($id) $id = $id[0];
    return array('code' => 0, 'score' => 1, 'msg' => $texts, 'id' => $id['id']);
}

function t()
{
    print_r($_SERVER);
}

/**
 * 获取与当前玩家相似度最高的3人
 * @return array
 *
 */
function similar()
{
    global $db;
    $tn = 'user_record';
    $current = json_decode($_SESSION['user']);//当前玩家
    $from_id = $_REQUEST['from'];
    if ($from_id) {
        //return array_slice(pkById($from_id), 0, 3);

        //主玩家（本期的发起者）记录
        $bench = $db->exec("select * from savor_user_record WHERE  id=$from_id");
        if ($bench) $bench = $bench[0];

        //查找本期(除当前玩家外。基于：一个玩家一期只能玩一次)所有参与记录
        $sql = "select * from savor_user_record r,savor_user u where r.openid=u.openid and r.from_id={$from_id} and r.openid<>'{$current->openid}' order by similar DESC ";
        $rs = $db->exec($sql);//select($tn)->where("from_id=$from and openid<>'{$current->openid}'")->done();

        //计算所有参与记录与当前玩家的相似度
        // $me = $db->select($tn)->where("from_id=$from_id and openid='{$current->openid}'")->done();//当前玩家数据
        //if (is_array($me)) $me = $me[0];//当前玩家的本次游戏记录
        // if (!$me) return array();

        $similars = array_map(function ($r) use (/*$me,*/ $bench, $current) {
            //$r['similar'] = User::match(get_dish($me['dishes']), get_dish($r['dishes']));
            $r['bench'] = $bench['openid'] == $current->openid ? '你' : $current->nickname;
            return $r;
        }, $rs);
        //获取相似度最高的前3人
        usort($similars, function ($item1, $item2) {
            return $item2['similar'] - $item1['similar'];
        });

        //返回
        return array_slice($similars, 0, 3);
    }
    return array();
    //如果好友从分享的链接点出来，前端显示“我是同味吃货吗”，让好友游戏
}

function pk()
{
    global $db;
    $user = json_decode($_SESSION['user']);
    $from = $_REQUEST['from'];
    $rec = $db->select('user_record')->where("id=$from")->done();
    if ($rec) $rec = $rec[0];

    //当前登陆用户的最后一次记录
    $myLast = "select * from savor_user_record where openid='{$user->openid}' limit 1";
    $myLast = $db->exec($myLast);
    if ($myLast) $myLast = $myLast[0];

    $ret = array();

    //if (!$from) $from = $myLast['id'];//如果不是从分享进去，则是发起一期游戏，获取刚刚发起的这期（实际上一般还没有别人玩，是没有数据的）
    $from = $myLast['id'];//始终查看登陆用户最后一期排行
    $ret['current'] = $db->exec("select * from savor_user_record r,savor_user u where r.openid=u.openid and r.from_id=$from");
    usort($ret['current'], function ($i1, $i2) {
        return $i2['similar'] - $i1['similar'];
    });

    //返回（我的，不管本次是不是别人发起的）历史排行
    //$ret['history'] = $db->select("user_record")->where("where openid='{$user->openid}'")->done();
    $sql = "select * from (select id,create_time,count from savor_user_record r,
(SELECT from_id,count(1) count FROM `savor_user_record` where from_openid='{$user->openid}' group by from_id) g
where r.id=g.from_id
union
SELECT  id,create_time,0 from savor_user_record where openid='{$user->openid}' and from_id is NULL)t order by create_time desc";
    $ret['history'] = $db->exec($sql);

    return $ret;
}

//根据记录id，获取对应期的排行
function pkById($id = null)
{
    global $db;
    $id = $id ? $id : $_REQUEST['id'];
    $rs = $db->exec("select * from savor_user_record r,savor_user u where r.openid=u.openid and r.from_id=$id");
    usort($rs, function ($i1, $i2) {
        return $i2['similar'] - $i1['similar'];
    });
    return $rs;
}