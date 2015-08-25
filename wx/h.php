<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015-08-13
 * Time: 10:25
 */
require_once __DIR__ . '/../config/config.php';
function wx_get_token() {
    global $config;
    $token = S('access_token');
    if (!$token) {
        $res = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' . $config['appId'] . '&secret=' . $config['secret'];
        $res = get_from_url($res);
        $res = json_decode($res, true);
        $token = $res['access_token'];
        // 注意：这里需要将获取到的token缓存起来（或写到数据库中）
        // 不能频繁的访问https://api.weixin.qq.com/cgi-bin/token，每日有次数限制
        // 通过此接口返回的token的有效期目前为2小时。令牌失效后，JS-SDK也就不能用了。
        // 因此，这里将token值缓存1小时，比2小时小。缓存失效后，再从接口获取新的token，这样
        // 就可以避免token失效。
        S('access_token', $token, 3600);
    }
    return $token;
}

function wx_get_jsapi_ticket() {
    $ticket = "";
    do {
        $ticket = S('wx_ticket');
        if (!empty($ticket)) {
            break;
        }
        $token = S('access_token');
        if (empty($token)) {
            wx_get_token();
        }
        $token = S('access_token');
        if (empty($token)) {
            logErr("get access token error.");
            break;
        }
        $url2 = sprintf("https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=%s&type=jsapi", $token);
        $res = get_from_url($url2);
        $res = json_decode($res, true);
        $ticket = $res['ticket'];
        // 注意：这里需要将获取到的ticket缓存起来（或写到数据库中）
        // ticket和token一样，不能频繁的访问接口来获取，在每次获取后，我们把它保存起来。
        S('wx_ticket', $ticket, 3600);
    } while (0);
    return $ticket;
}

function wx_get_user($openid) {
    $token = wx_get_token();
    $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$token&openid=$openid&lang=zh_CN";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $rec = curl_exec($ch);
    $ret = json_decode($rec);
    return $ret->subscribe == 1 ? $ret : null;
}