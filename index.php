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

    <link rel="stylesheet" href="css/base.css"/>
    <script src="js/jquery.min.js"></script>
    <script src="js/util.js"></script>
    <script src="js/wxsns.js"></script>
    <script src="js/jquery.transit.min.js"></script>

</head>
<body>
<script type="text/javascript">
    //onload = function () {alert("loaded")}
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
        document.title = $(document.body).width() + "," + $(document.body).height();
        $('.con').width($('.page').width($(".wrapper").width()).width() * $('.con>.page').length);
        //if (<?echo isset($_SESSION['openid'])?'false':'true'?>) wx.goCode('<?echo $config['appId']?>', 'http://192.168.2.2:8002/wx/cb.php');
    });
</script>
<div class="wrapper">
    <div class="con">
        <div class="page active page1">
            <img class="img1 animate" src="img/1/yun1.png"/>
            <img class="img2 animate" src="img/1/shuishinidecai.png"/>
            <img class="img2-2 animate" src="img/1/xuntongkuanchihuo.png"/>
            <img class="img3 animate" src="img/1/cai.png"/>
            <img class="img4 animate" src="img/1/nan.png"/>
            <img class="img5 animate" src="img/1/nv.png"/>
            <img class="img6 animate" src="img/1/start.png"/>
            <img class="img7 animate" src="img/1/youxishuoming.png"/>
            <script type="text/javascript">
                $(".page1 .img6").click(function () {
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
                            $("#lblMsg").text(data.msg[0]);
                            $("#lblMsg2").text(data.msg[1]);
                            $(self).showNext();
                        },
                    });
                })
                ;
            </script>
        </div>

        <div class="page result" style="position: relative;">
            <img src="img/5-2.png">

            <style>
                #lblMsg, #lblMsg2 {
                    position: absolute;
                    top: 1em;
                    right: 0em;
                    font-size: 2em;
                    background-color: #FCA900;
                }

                #lblMsg2 {
                    top: 2.5em;
                }
            </style>
            <div id="lblMsg" style="" onclick="$('.con').css('transform','translateX(0) ')"></div>
            <div id="lblMsg2" style="" onclick="$('.con').css('transform','translateX(0) ')"></div>
        </div>

        <div class="page pk">

        </div>
    </div>
    <div>
</body>
</html>