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
//$_SESSION['openid'] = 'ready';//$_GET['openid'];
?>
<script type="text/javascript" src="../js/util.js"></script>
<script type="text/javascript" src="../js/wxsns.js.js"></script>
<script type="text/javascript">
    document.write("loading...");
    wx.getToken('<?echo $config.appId?>', '<?echo $config.secret?>', function (ret) {
        alert('token:' + ret.access_token);
        location.replace('<?echo $redirect?>');
    });
</script>
