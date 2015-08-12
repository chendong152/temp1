<?php
/**
 * Created by Dong.
 * User: Dong(mailto:techdong@hotmail.com)
 * Date: 2015-08-01
 * Time: 23:46
 * desc: 微信授权回调
 */
require_once __DIR__ . '/../config/config.php';

$redirect = $config['redirect'];// $_SERVER['HTTP_REFERER'];
session_start();
$code = $_REQUEST['code'];

$go = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$config['appId']}&secret={$config['secret']}&code=$code&grant_type=authorization_code";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $go);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
$res = curl_exec($ch);
$json_obj = json_decode($res, true);
$_SESSION['token'] = $json_obj['access_token'];
$_SESSION['openid'] = $json_obj['openid'];
setcookie('token', $_SESSION['token']);
setcookie('openid', $_SESSION['openid']);

//拉取用户信息
$go = "https://api.weixin.qq.com/sns/userinfo?access_token={$json_obj['access_token']}&openid={$json_obj['openid']}&lang=zh_CN";
curl_setopt($ch, CURLOPT_URL, $go);
$user = curl_exec($ch);
curl_close($ch);
$_SESSION['user'] = $user;

if($_SESSION['user'])
    header('Location:' . $_COOKIE['backurl']);
?>
<!DOCTYPE html>
<html>
<head>
</head>
<body>
<? echo $_SESSION['user'] ?>
<script type="text/javascript" src="../js/jquery.min.js"></script>
<script type="text/javascript" src="../js/util.js"></script>
<script type="text/javascript" src="../js/wxsns.js"></script>
<script>

</script>
</body>
</html>
