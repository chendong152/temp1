<?php
/**
 * Created by PhpStorm.
 * User: Dong(mailto:techdong@hotmail.com)
 * Date: 2015-08-01
 * Time: 1:01
 */
session_start();
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/biz/dishConfig.php';

header('Content-Type: text/html;charset=utf-8');
header('Cache-Control: no-cache');

if (!isset($_SESSION['openid']))
    $_SESSION['openid'] = isset($_COOKIE['openid']) ? $_COOKIE['openid'] : null;

//echo  $_SERVER['HTTP_USER_AGENT'];
$isWx = preg_match('/micromessenger/i', $_SERVER['HTTP_USER_AGENT']);
if (!isset($_SESSION['user']) || empty($_SESSION['user'])) {
    setcookie('backurl', $_SERVER['REQUEST_URI']);
    if ($isWx) {
        header("Location: https://open.weixin.qq.com/connect/oauth2/authorize?appid={$config['appId']}&redirect_uri={$config['redirect']}&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect");
        exit;
    }
}

$user = !$isWx ? json_decode('{"openid":"openid6","nickname": "NICKNAME","sex":"1", "city":"CITY","country":"COUNTRY","headimgurl":"http:\/\/cc.om"}') : null;
if (isset($_SESSION['user']))
    $user = json_decode($_SESSION['user']);
else
    $_SESSION['user'] = json_encode($user);//为了在非微信下测试

if (!$user)
    exit('no user');
?>
<!DOCTYPE html>
<html>
<head>
    <meta name="imagemode" content="force">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <meta http-equiv="charset" content="utf8"/>
    <link rel="stylesheet" href="css/base.css" charset="utf-8"/>
    <link rel="stylesheet" href="css/swiper3.1.0.min.css"/>
    <script src="js/jquery.min.js"></script>
    <script src="js/util.js"></script>
    <script src="js/wxsns.js"></script>
    <script src="js/jquery.transit.min.js"></script>
    <script src="js/swiper3.1.0.jquery.min.js"></script>
    <script src="js/jquery.touchSwipe.min.js"></script>
    <script src="js/mg.js"></script>
</head>
<body>
<script type="text/javascript">
    onerror = function (m) {
        alert(m);
    }
</script>
<script type="text/javascript">
    wx = window.wx || {};
    try {
        wx.user =<?echo json_encode($user)?>;
    } catch (ex) {
        alert(ex)
    }
    //$("script").each(function(){alert($(this).text())})
    wx.config = $.extend(wx.config, {
        appid: '<?echo $config['appId'];?>',
        secret: '<?echo $config['secret']?>',
        openid: '<?echo $_SESSION['openid']?>'
    });
    $(function () {
        document.title = $(document.body).width() + "," + $(document.body).height();
        $('.my-head').attr('src', wx.user.headimgurl);
        $('.con').width($('.page').width($(".wrapper").width()).width() * $('.con>.page').length + 100);
    });
</script>
<div class="loader">
    <div class="progress">拼命中</div>
    <canvas class="progress"></canvas>
</div>
<div class="wrapper swiper-container2">
    <div class="con swiper-wrapper2">
        <div class="page swiper-slide2 active page1">
            <img class="img1 " loadsrc="img/1/yun1.png"/>
            <img class="img2 animate" loadsrc="img/1/shuishinidecai.png"/>
            <img class="img2-2 animate" loadsrc="img/1/xuntongkuanchihuo.png"/>
            <img class="img3 animate" loadsrc="img/1/cai.png"/>
            <img class="img4 animate" loadsrc="img/1/nan.png"/>
            <img class="img5 animate" loadsrc="img/1/nv.png"/>
            <img class="img6 animate" loadsrc="img/1/start.png"/>
            <img class="img7 animate" loadsrc="img/1/youxishuoming.png"/>
            <script type="text/javascript">
                $(".page1 .img6").click(function () {
                    app.nextPage(), $(".swing").removeClass("swing");
                })
            </script>
        </div>

        <div class="page swiper-slide2 page2">
            <script>
                var dishList = <?echo json_encode($allDishes,1)?>;
            </script>
            <img class="img21 animate" loadsrc="img/2/meishinameduo.png"/>

            <div class="dishes animate swiper-container">
                <div class="swiper-wrapper origi">
                    <?php
                    foreach ($allDishes as $index => $dish) {
                        ?>
                        <div class="dish swiper-slide" data-id="<? echo $index ?>">
                            <div><img loadsrc="<? echo $dish->img ?>"/></div>
                            <div><? echo $dish->name ?></div>
                        </div>
                    <? } ?>
                </div>
            </div>

            <!-- 如果需要导航按钮 -->
            <div class="swiper-button-prev-m"></div>
            <div class="swiper-button-next-m"></div>

            <script>
                var mySwiper = new Swiper('.page2 .swiper-container', {
                    slidesPerView: 3, spaceBetween: 10,//freeMode:true,
                    direction: 'horizontal', loop: false,
                });
                $(".swiper-button-prev-m").click(function () {
                    mySwiper.slidePrev()
                });
                $(".swiper-button-next-m").click(function () {
                    mySwiper.slideNext()
                });
            </script>
            <img class="img23 animate" loadsrc="img/2/yun2.png"/>
            <img class="img24 animate" loadsrc="img/2/wenhao.png"/>

            <div class="txt24 animate">从上面拖三个最想吃的菜</div>

            <div class="my-dishes">
            </div>
            <img class="img25 animate" loadsrc="img/2/nv_2.png"/>
            <img class="img26 animate" loadsrc="img/2/ok.png" id="btnCheck"/>

            <script type="text/javascript">
                $("#btnCheck").hide();
                function visibleQuestion() {
                    $(".page2 .txt24")[($('.page2 .my-dishes .dish').length > 0 ? 'hide' : 'show')]();
                    $('.page2 .img26')[($('.page2 .my-dishes .dish').length != 3 ? 'hide' : 'show')]().css({opacity: 0}).transition({opacity: 1});
                }
                $('.page2 .dishes').swipe({
                    swipe: function (e, direction, distance, duration, fingerCount, fingerData) {
                        if ($('.page2 .my-dishes .dish').length >= 3 || direction != 'down') return;
                        var t = e.target;
                        while (t != null && !$(t).hasClass('dish'))t = $(t).parent();
                        $(t).css({rotateY: '-360deg'}).transition({
                            rotateY: '0deg', duration: 500, complete: function () {
                                $(t).clone().removeClass('swiper-slide').attr('style', '').data('ori', $(t).transition({
                                    scale: 0,
                                    complete: function () {
                                        $(t).css({scale: 1}).hide()
                                    }
                                }))
                                    .addClass('only swing').appendTo($('.page2 .my-dishes'));
                                visibleQuestion();
                            }
                        });
                    }
                });
                $('.page2 .my-dishes').swipe({
                    swipe: function (e, direction, distance, duration, fingerCount, fingerData) {
                        if (direction != 'up') return;
                        var t = e.target;
                        while (t != null && !$(t).hasClass('dish'))t = $(t).parent();
                        $(t).css({rotateY: '-360deg'}).transition({
                            rotateY: '0deg', duration: 500, complete: function () {
                                $(t).data('ori').show();
                                $(t).remove();
                                visibleQuestion();
                            }
                        });
                    }
                });
                $("#btnCheck").click(function () {
                    var self = this;
                    wx.user.dishes = [];
                    $('.page2 .my-dishes .dish').each(function () {
                        wx.user.dishes.push($(this).data('id'));
                    });
                    if (wx.user.dishes.length != 3) return;
                    showMyDishes();
                    app.nextPage();
                    $.ajax({
                        url: 'biz/ajax.php?action=check', dataType: "json", type: "POST", data: $.extend({
                            //dishes: [1, Math.floor(Math.random() * 12), Math.floor(Math.random() * 12)],
                            from: getParam('from')
                        }, wx.user),
                        success: function (data) {
                            $("#lblMsg").text(data.msg[0]);
                            $("#lblMsg2").text(data.msg[1]);
                            //app.nextPage();
                        },
                    });
                })
                ;
            </script>
        </div>

        <div class="page swiper-slide2 page3">
            <img class="img31 " loadsrc="img/3/jiangpai.png">

            <div class="txt32 animate">
                <div style="" onclick="$('.con').css('transform','translateX(0) ')">
                    经鉴定，你是<em id="lblMsg">美食家</em>
                </div>
                <div id="lblMsg2" style=""></div>
            </div>
            <img class="img33 animate" loadsrc="img/3/nan_1.png"/>
            <img class="img34" loadsrc="img/3/yun3_1.png"/>

            <div class="my-dishes animate">
                <script>
                    function showMyDishes() {
                        var s = '<div class="dish">'
                            + '<div><img src="{img}"/></div>'
                            + '<div class="label">{name}</div>'
                            + '</div>';
                        $('.page3 .my-dishes').empty();
                        for (var i in wx.user.dishes) {
                            var dish = dishList[wx.user.dishes[i]], ds = s;
                            for (var key in dish)
                                ds = ds.replace(new RegExp('{' + key + '}', 'ig'), dish[key]);
                            $(ds).appendTo($('.page3 .my-dishes'));
                        }
                    }
                </script>
                <!--
                <div class="dish">
                    <div><img loadsrc="img/2/cai_simple.png"/></div>
                    <div class="label">小栽4在工在枯葳基本原理</div>
                </div>
                <div class="dish">
                    <div><img loadsrc="img/2/cai_simple.png"/></div>
                    <div class="label">小栽4在工在枯葳基本原理</div>
                </div> -->
            </div>
            <div class="img35 animate">
                吃货的嘴是丰满的，但内心是骨感的，快快寻找身边的同款吃货，一起行走在去吃的路上吧
            </div>
            <img class="img36 animate" loadsrc="img/3/nv_3.png"/>
            <img class="img37 animate" loadsrc="img/3/quzhaotongkuan.png" id="btnGoResult"/>
            <script type="text/javascript">
                $("#btnGoResult").click(function () {
                    app.nextPage();
                });
            </script>
        </div>

        <div class="page swiper-slide2 page4">
            <img class="img43 my-head" load="img/h.png"/>

            <div class="txt44 ">已找到<span class="count">1</span>个同款</div>
            <ul class="items">
                <!--li class="thumb">
                    <img class="head_img" loadsrc="img/h.png"/>
                    <dl>
                        <dt><em class="other_alias">卷</em>与<span class="bench">你</span><span class="verb">不是</span>同款吃货
                        </dt>
                        <dd>他是<em>文艺级吃货</em></dd>
                    </dl>
                </li>
                <li>
                    <img loadsrc="img/h.png"/>
                    <dl>
                        <dt><em>则卷</em>与你不是同款吃货</dt>
                        <dd>他是<em>文艺级吃货</em></dd>
                    </dl>
                </li>
                <li>
                    <img loadsrc="img/h.png"/>
                    <dl>
                        <dt><em>则卷</em>与你不是同款吃货</dt>
                        <dd>他是<em>文艺级吃货</em></dd>
                    </dl>
                </li-->
            </ul>
            <img class="img44 " loadsrc="img/4/chongxinfaqi.png" id="btnRestart1">
            <img class="img45 " loadsrc="/img/4/chakanpaihang.png" id="btnPk">
            <script type="text/javascript">
                $("#btnRestart1").click(function () {
                    app.start();
                });
                $("#btnPk").click(function () {
                    $.getJSON('biz/ajax.php?action=pk', {from: getParam('from')}, function (data) {
                        var li = '<li class="item {thumb}"><label class="index">{index}</label><img class="head_img" src="{headimgurl}"><dl><dt>{nickname}</dt><dd>与你的相似度{similar}%</dd></dl></li>';
                        var p = $(".page5 .items").empty();
                        for (var i in data.current || []) {
                            var item = data.current[i];
                            item.index = parseInt(i) + 1, item.thumb = i < 3 ? 'thumb' : '', item.similar = parseFloat(item.similar).toFixed(0);
                            p.append($(replace(li, item)));
                        }
                        app.nextPage();
                    });
                });
            </script>
        </div>

        <div class="page swiper-slide2 page5">
            <div class=" img51 active"></div>
            <div class=" img52"></div>
            <ul class="items ">
                <li class="item thumb">
                    <label class="index">1</label>
                    <img class="head_img" loadsrc="img/h.png">
                    <dl>
                        <dt>则卷</dt>
                        <dd>与你的相似度100%</dd>
                    </dl>
                </li>
                <li class="item thumb">
                    <label class="index">1</label>
                    <img class="head_img" loadsrc="img/h.png">
                    <dl>
                        <dt>则卷</dt>
                        <dd>与你的相似度100%</dd>
                    </dl>
                </li>
                <li class="item thumb">
                    <label class="index">1</label>
                    <img class="head_img" loadsrc="img/h.png">
                    <dl>
                        <dt>则卷</dt>
                        <dd>与你的相似度100%</dd>
                    </dl>
                </li>
                <li class="item ">
                    <label class="index">1</label>
                    <img class="head_img" loadsrc="img/h.png">
                    <dl>
                        <dt>则卷</dt>
                        <dd>与你的相似度100%</dd>
                    </dl>
                </li>
                <li class="item ">
                    <label class="index">1</label>
                    <img class="head_img" loadsrc="img/h.png">
                    <dl>
                        <dt>则卷</dt>
                        <dd>与你的相似度100%</dd>
                    </dl>
                </li>
            </ul>
            <img class="img53 " loadsrc="img/5/chongxinfaqi.png" id="btnRestart"/>
            <script type="text/javascript">
                $("#btnRestart").click(function () {
                    app.start();
                })
            </script>
        </div>
    </div>
</div>
</body>
</html>