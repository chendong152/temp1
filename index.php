<?php
/**
 * Created by PhpStorm.
 * User: Dong(mailto:techdong@hotmail.com)
 * Date: 2015-08-01
 * Time: 1:01
 */
session_start();
require_once 'config/config.php';

?>
<style>
    .page {
        display: none;
        width: 100%;
        height: 100%;
        margin: 0;
        padding: 0;
        border: none;
    }

    .active {
        display: block;
    }
</style>
<script src="js/jquery.min.js"></script>
<script src="js/util.js"></script>
<script src="js/wxsns.js"></script>
<script type="text/javascript">
    document.write(navigator.userAgent);
    $.fn.extend({
        showNext: function () {
            var self = this;
            while (self == null || !self.hasClass('page')) self = self.parent();
            self && self.removeClass('active').next(".page").addClass('active');
        },
    });
    $(function () {
        if (<?echo isset($_SESSION['openid'])?'false':'true'?>) wx.goCode('<?echo $config['appId']?>', 'http://192.168.2.2:8002/wx/cb.php');
    });
</script>
<div class="page ready active">
    <h2>吃货寻味记</h2>
    <button onclick="$(this).showNext()">开始</button>
</div>

<div class="page choose">
    <comment>选择3种菜来鉴定</comment>
    微信头像
    <button id="btnCheck">鉴定</button>
    <script type="text/javascript">
        $("#btnCheck").click(function () {
            var self = this;
            $.ajax({
                url: 'biz/ajax.php?action=check', dataType: "json", type: "POST", data: $.extend({
                    foot: '1,2,3'
                }, wx.user),
                success: function (data) {
                    $("#lblMsg").text(data.msg);
                    $(self).showNext();
                }
            });
        })
        ;
    </script>
</div>

<div class="page result">
    <h2 id="lblMsg">经鉴定，你是个吃货，重口味的</h2>
    <details open="open">
        <summary>看看</summary>
        好叼哦
    </details>
    <div class="tip">快来看看你是否与大白是周款吃货吧</div>
    <button>寻同款(分享)</button>
</div>

<div class="page pk">

</div>