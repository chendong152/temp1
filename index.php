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
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
</head>
<body>

<style>
    html, body {
        margin: 0;
        padding: 0;
        width: 100%;
        height: 100%;
        overflow: hidden;
    }
    .con {
        width: 100%;
        height: 100%;
        overflow: hidden;
        transition: 0.5s;
        -webkit-transition: 0.5s;
        transform: translate(0%, 0);
    }

    .con .page {
        display: inline-block;
        width: 100%;
        height: 100%;
        margin: 0;
        padding: 0;
        border: none;
        float: left;
    }

    .con .active {
        display: inline-block ;
    }

    .con .page > img {
        width: 100%;
        height: 100%;
        margin: 0;
        border: none;
    }

</style>
<script src="js/jquery.min.js"></script>
<script src="js/util.js"></script>
<script src="js/wxsns.js"></script>
<script type="text/javascript">
    $.fn.extend({
        showNext: function () {
            var self = this;
            while (self == null || !self.hasClass('page')) self = self.parent();
            self && self.removeClass('active').next(".page").addClass('active');
            console.log(self, self.index())
            $(".con").css('transform', 'translateX(' + -$(".con>.page").width() * (self.index() + 1) + "px)");
        },
    })
    ;
    $(function () {
        title=$(window).width()+","+$(window).height();
        $('.con').width($('.page').width($(window).width()).width() * $('.con>.page').length);
        //if (<?echo isset($_SESSION['openid'])?'false':'true'?>) wx.goCode('<?echo $config['appId']?>', 'http://192.168.2.2:8002/wx/cb.php');
    });
</script>
<div class="con">
    <div class="page ready active">
        <img id="img1" src="img/5-0.png" usemap="map">
        <map id="map" name="map">
            <area id="btnGo" shape="rect" coords="178,820,479,914"/>
        </map>
        <script type="text/javascript">
            $("#img1").click(function () {
                $(this).showNext();
            })
            ;
        </script>
    </div>

    <div class="page choose">
        <img src="img/5-1.png" id="btnCheck">

        <script type="text/javascript">
            $("#btnCheck").click(function () {
                var self = this;
                $.ajax({
                    url: 'biz/ajax.php?action=check', dataType: "json", type: "POST", data: $.extend({
                        dishes: [1, Math.floor(Math.random() * 12), Math.floor(Math.random() * 12)]
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

    <div class="page result" style="position: relative;">
        <img src="img/5-2.png">

        <style>
            #lblMsg {
                position: absolute;
                top: 1em;
                right: 0em;
                font-size: 2em;
                background-color: #FCA900;
            }
        </style>
        <div id="lblMsg" style="" onclick="$('.con').css('transform','translateX(0) ')"></div>
    </div>

    <div class="page pk">

    </div>
</div>
</body>
</html>