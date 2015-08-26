<?php
/**
 * Created by PhpStorm.
 * User: Dong(mailto:techdong@hotmail.com)
 * Date: 2015-08-01
 * Time: 1:01
 */
session_start();
require_once __DIR__ . '/biz/mysql.class.php';
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/biz/dishConfig.php';
require_once __DIR__ . '/wx/h.php';

header('Content-Type: text/html;charset=utf-8');

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

$user = !$isWx ? json_decode('{"openid":"openid5","nickname": "Nick5","sex":"1", "city":"CITY","country":"COUNTRY","headimgurl":"http:\/\/cc.om"}') : null;
if (isset($_SESSION['user']))
    $user = json_decode($_SESSION['user']);
else
    $_SESSION['user'] = json_encode($user);//为了在非微信下测试

if (!$user)
    exit('no user');

$from_id = isset($_REQUEST['from_id']) ? $_REQUEST['from_id'] : null;
$bench = $db->exec("select * from savor_user_record r,savor_user u where r.openid=u.openid and r.id=$from_id");
if ($bench) $bench = $bench[0];
$myRec = $db->exec("select * from savor_user_record where openid='{$user->openid}' and from_id=$from_id");
if ($myRec) $myRec = $myRec[0];

$userOnWx = wx_get_user($user->openid);
if ($userOnWx) $user->subscribe = true;
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate"/>
    <meta http-equiv="Pragma" content="no-cache"/>
    <meta http-equiv="Expires" content="0"/>

    <meta name="imagemode" content="force">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <meta http-equiv="charset" content="utf8"/>
    <title>谁是你的菜</title>
    <link rel="stylesheet" href="css/base.css" charset="utf-8"/>
    <link rel="stylesheet" href="css/swiper3.1.0.min.css"/>
    <script src="js/jquery.min.js"></script>
    <script src="js/util.js"></script>
    <script src="js/wxsns.js"></script>
    <script src="js/jquery.transit.min.js"></script>
    <script src="js/swiper3.1.0.jquery.min.js"></script>
    <script src="js/jquery.touchSwipe.min.js"></script>
    <script src="js/mg.js"></script>
    <script src="wx/sdk.js"></script>
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
        wx.owner =<?echo json_encode($bench?$bench:array())?>;
        wx.myRec =<?echo json_encode($myRec?$myRec:array())?>;
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
        window.dev && (document.title = $(document.body).width() + "," + $(document.body).height());
        $('.con').width($('.page').width($(".wrapper").width()).width() * $('.con>.page').length + 100);
    });
</script>
<div class="loader">
    <div class="progress">努力加载中...</div>
    <canvas class="progress"></canvas>
</div>
<div class="wrapper swiper-container2">
    <div class="con swiper-wrapper2">
        <div class="page swiper-slide2 active page1">
            <img class="img2 animate" loadsrc="img/1/shuishinidecai.png"/>
            <img class="img2-2 animate" loadsrc="img/1/xuntongkuanchihuo.png"/>
            <img class="img3 animate" loadsrc="img/1/cai.png"/>
            <img class="img4 animate" loadsrc="img/1/nan.png"/>
            <img class="img5 animate" loadsrc="img/1/nv.png"/>
            <img class="img6 img6-1 animate" loadsrc="img/1/start.png"/>
            <img class="img6 img6-2 animate" loadsrc="img/1/starttxt.png"/>
            <img class="img8 animate" loadsrc="img/1/fdj.png"/>
            <script type="text/javascript">
                $(".page1 .img6").click(function () {
                    app.nextPage(), $(".swing").removeClass("swing");
                })
            </script>
        </div>

        <div class="page swiper-slide2 page2">
            <div class="tip">
                <img class="bg" loadsrc="img/2/layer.png">
                <img class="btn" loadsrc="img/2/b1.png">
                <script
                    type="text/javascript">$(".page2>.tip>.btn").click(function () {$('.page>.tip').hide()})</script>
            </div>
            <script>
                var dishList = <?echo json_encode($allDishes,1)?>;
            </script>
            <img class="img21 animate" loadsrc="img/2/s1.png"/>

            <div class="dishes swiper-container">
                <div class="swiper-wrapper origi">
                    <div class="swiper-slide">
                        <?php
                        foreach ($allDishes as $index => $dish) {
                            ?>
                            <div class="dish " data-id="<? echo $index ?>">
                                <div><img loadsrc="<? echo preg_replace('/\.png$/', '0.png', $dish->img) ?>"/></div>
                                <div><img loadsrc="<? echo $dish->img ?>"/></div>
                            </div>
                        <? } ?>
                    </div>
                </div>
            </div>

            <script>
                var mySwiper = new Swiper('.page2 .swiper-container', {
                    slidesPerView: 1, spaceBetween: 10,//freeMode:true,
                    direction: 'horizontal', loop: false,
                });
                $(".swiper-button-prev-m").click(function () {
                    mySwiper.slidePrev()
                });
                $(".swiper-button-next-m").click(function () {
                    mySwiper.slideNext()
                });
            </script>
            <img class="count" loadsrc="img/2/1.png"/>
            <img class="img26 " loadsrc="img/2/nook.png"/>
            <img class="img26 animate ok" loadsrc="img/2/ok.png" id="btnCheck"/>

            <script type="text/javascript">
                $("#btnCheck").hide(), $(".page2 .count").hide();
                function visibleQuestion() {
                    var c = $('.page2 .dishes .dish.selected').length;
                    $(".page2 .count").attr('src', 'img/2/' + c + '.png')[c < 1 ? 'hide' : 'show']() , $('.page2 .img26.ok')[(c != 3 ? 'hide' : 'show')]().css({opacity: 0}).transition({opacity: 1});
                }
                $('.page2 .dishes').delegate('.dish', 'click', function () {
                    if ($('.page2 .dishes .dish.selected').length >= 3 && !$(this).hasClass('selected')) return;
                    $(this).toggleClass('selected'), visibleQuestion();
                }).swipe({
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
                                })).css({animation: 'tada-soon 1s ease'}).appendTo($('.page2 .my-dishes'));
                                visibleQuestion();
                            }
                        });
                    }
                });
                $("#btnCheck").click(function () {
                    var self = this;
                    wx.user.dishes = [];
                    $('.page2 .dishes .dish.selected').each(function () {
                        wx.user.dishes.push($(this).data('id'));
                    });
                    if (wx.user.dishes.length != 3) return;
                    showMyDishes();
                    $.ajax({
                        url: 'biz/ajax.php?action=check', dataType: "json", type: "POST", data: $.extend({
                            //dishes: [1, Math.floor(Math.random() * 12), Math.floor(Math.random() * 12)],
                            from: app.renew ? null : getParam('from_id')
                        }, wx.user),
                        success: function (data) {
                            $("#lblMsg").text(wx.user.kind = data.msg[0]), $("#lblMsg2").text(wx.user.detail = data.msg[1]);
                            app.done = true, app.recId = app.renew ? data.id : null, app.share();
                            app.goTo(getParam('from_id') && !app.renew ? 3 : 2);
                        },
                    });
                });
            </script>
        </div>

        <div class="page swiper-slide2 page3">
            <div class="txt32 animate">
                <div style="" onclick="$('.con').css('transform','translateX(0) ')">
                    经鉴定，你是<em id="lblMsg">美食家</em>
                </div>
                <div id="lblMsg2" style=""></div>
            </div>

            <div class="my-dishes animate">
                <script>
                    function showMyDishes() {
                        var s = '<div class="dish">'
                            + '<div><img src="{img}"/></div>'
                                //+ '<div class="label">{name}</div>'
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
            <img class="img37 animate" loadsrc="img/3/start.png" id="btnGoResult"/>
            <script type="text/javascript">
                $("#btnGoResult").click(function () {
                    app.willGo4 = true, $(".shares").show();
                    //app.nextPage();
                });
            </script>
        </div>

        <div class="page swiper-slide2 page4">
            <img class="img43 my-head" load="img/h.png"/>

            <div class="txt42">
                <div><label class="nickname1"></label><label>我是</label><em class="kind"></em></div>
                <div class="detail"></div>
            </div>

            <div class="txt44 ">已找到<span class="count">0</span>个同款</div>
            <style>
                table { width: 100%; height: 100%; border-collapse: collapse; padding: 0; border: none; }
                td:first-child { width: 35%; text-align: center; }
                td:last-child { padding-right: 1em; }
            </style>
            <ul class="items">
                <li>
                    <table>
                        <tr>
                            <td><img class="head_img" loadsrc="img/h.png"/></td>
                            <td>
                                <em class="other_alias">卷</em>与<span class="bench">你</span><span class="verb">不是</span>同款吃货
                                </br>
                                他是<em>文艺级吃货</em></td>
                        </tr>
                    </table>
                </li>
            </ul>
            <img class="img44 " loadsrc="img/4/chongxinfaqi.png" id="btnRestart1">
            <img class="img45 " loadsrc="img/4/chakanpaihang.png" id="btnPk">
            <script type="text/javascript">
                $("#btnRestart1").click(function () {
                    app.start((app.renew = $('.page4 .img44[src*=metoo]').length == 0) ? 0 : 1);
                });
                $("#btnPk").click(function () {//alert(JSON.stringify('<?echo json_encode($userOnWx)?>'))
                    wx.user.subscribe ? app.nextPage() : location.replace('http://mp.weixin.qq.com/s?__biz=MzA3NDg3NDI4OQ==&mid=208958565&idx=1&sn=64dae1a2f4a184a533a686053f34e4bd#rd');
                });
            </script>
        </div>

        <div class="page swiper-slide2 page5">
            <div class=" img51 active s" data-index="0"></div>
            <div class=" img52 s" data-index="1"></div>
            <div class="wrapper">
                <ul class="items current" style="">
                    <!--li class="item thumb">
                        <label class="index">1</label>
                        <img class="head_img" loadsrc="img/h.png">
                        <dl>
                            <dt>则卷</dt>
                            <dd>与你的相似度100%</dd>
                        </dl>
                    </li-->
                </ul>
                <ul class="items history">
                    <li class="item">
                        <div>
                            <label class="l">2015-1-1</label>
                            <label>共找到12个同款</label>
                        </div>
                    </li>
                    <li class="item">
                        <div>
                            <label class="l">2015-1-1</label>
                            <label>共找到12个同款</label>
                        </div>
                    </li>
                </ul>
                <ul class="items his-cur" style="">
                    <!--li class="item thumb">
                        <label class="index">1</label>
                        <img class="head_img" loadsrc="img/h.png">
                        <dl>
                            <dt>则卷</dt>
                            <dd>与你的相似度100%</dd>
                        </dl>
                    </li-->
                </ul>
            </div>

            <img class="img53 " loadsrc="img/5/chongxinfaqi.png" id="btnRestart"/>
            <script type="text/javascript">
                var p = $('.page5 .wrapper');
                p.width(p.children().width($(window).width()).length * $(window).width());
                $(".page5 .s").click(function () {
                    $('.page5 .s').removeClass('active'), $('.page5 .wrapper').transition({
                        x: -parseInt($(this).addClass('active').data("index")) * $(window).width()
                    });
                });
                $(".page5 .history").delegate('li', 'click', function () {
                    $.getJSON("biz/ajax.php?action=pkById", {id: $(this).data("id")}, function (data) {
                        $('.page5 .wrapper').transition({x: -2 * $(window).width()});
                        var li = '<li class="item {thumb}"><label class="index">{index}</label><img class="head_img" src="{headimgurl}"><dl><dt>{nickname}</dt><dd>与{master}的相似度{similar}%</dd></dl></li>';
                        var p = $(".page5 .items.his-cur").empty();
                        for (var i in data || []) {
                            var item = data[i];
                            item.master = !wx.owner.openid || wx.user.openid == wx.owner.openid ? '你' : 'Ta';
                            item.index = parseInt(i) + 1, item.thumb = i < 3 ? 'thumb' : '', item.similar = parseFloat(item.similar).toFixed(0);
                            p.append($(replace(li, item)));
                        }
                    });
                });
                $("#btnRestart").click(function () {
                    app.start().renew = true;
                })
            </script>
        </div>
    </div>
</div>
<script type="text/javascript">
    <?php
        $schema=$_SERVER['REQUEST_SCHEME']?$_SERVER['REQUEST_SCHEME']:'http';
        $jsWx=array(
            'jsapi_ticket'=>wx_get_jsapi_ticket(),
            'timestamp'=>time(),
            'url'=>"$schema://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}",//
            'nonceStr'=>'fkuwx'.time(),
        );
        ksort($jsWx);
        $input=implode('&',array_map(function($k,$v){return strtolower($k)."=$v";},array_keys($jsWx),array_values($jsWx)));
        //echo "var input='$input'";
        $jsWx['signature']=strtolower(sha1($input));
    ?>
    wx = window.wx || {}, wx.config = wx.config || {}, host = 'http://' + location.host + '/savor_wx';//todo:分享地址
    wx.url = '<?echo $jsWx['url']?>';
    wx.config(c = {
        debug: false,
        appId: "<?echo $config['appId']?>",
        timestamp: <?echo $jsWx['timestamp']?>,
        nonceStr: '<?echo $jsWx['nonceStr']?>',
        signature: '<?echo $jsWx['signature']?>',
        jsApiList: ['onMenuShareTimeline', 'onMenuShareAppMessage']
    });
    function r() {
        return app.recId ? $.extend(wx.user, {id: app.recId}) : (wx.user.openid == wx.owner.openid ? wx.owner : null);
    }
    var t = {
        icon: function () {
            return r() ? r().headimgurl : host + '/img/fenxiang.png';
        }, title: function () {
            var t = r();
            return t ? t.nickname + '是' + (t.result_kind || t.kind) + '，快看看你是否与我是同款吃货' : '快快寻找身边的同款吃货 ，一起行走在去吃的路上吧'
        }, url: function () {
            var t = r();
            return host + "?from_openid=" + (t ? t.openid : '') + (t ? "&p=3&from_id=" + t.id : '');
        }, success: function (ret) {
            app.willGo4 && app.goTo(3);
            $(".shares").hide();
        }
    };
    App.prototype.share = function () {
        var gc = function () {
            return {
                title: t.title(), // 分享标题
                link: t.url(),
                imgUrl: t.icon(), // 分享图标
                success: t.success,
            };
        };
        // 获取“分享到朋友圈”按钮点击状态及自定义分享内容接口
        wx.onMenuShareTimeline(gc());
        // 获取“分享给朋友”按钮点击状态及自定义分享内容接口{
        /*desc: "", // 分享描述
         type: 'link', // 分享类型,music、video或link，不填默认为link
         }*/
        wx.onMenuShareAppMessage(gc());
    }
    wx.ready(function () {
        app.share();
    });
</script>
<div class="shares"><img loadsrc="img/shares.png"/>
    <style>.shares {
            position: absolute;
            left: 0;
            width: 100%;
            top: 0;
            height: 100%;
            z-index: 300;
            background: rgba(0, 0, 0, 0.7);
            display: none;
        }
        .shares > img { width: 100%; }
    </style>
    <script type="text/javascript">
        $(".shares").click(function () {
            $(this).toggle();
        });
    </script>
</div>
<div id="music">
    <div class="music"></div>
    <span>开启</span>
    <audio src="js/m.mp3" id="mp3" loop autoplay="autoplay"></audio>
    <style>
        #music {
            position: absolute;
            width: 24px;
            height: 24px;
            right: 8px;
            top: 8px;
            z-index: 10;
            opacity: 0.5;
        }
        #music > span {
            color: #fff;
            position: absolute;
            left: -56px;
            top: 0;
            line-height: 36px;
            font-size: 14px;
            opacity: 0;
            -webkit-transition: all 0.3s linear;
            transition: all 0.3s linear;
        }
        .music {
            width: 100%;
            height: 100%;
            background: url(img/music.png) no-repeat center center;
            background-size: 100%;
            -webkit-animation: rotate 1s linear infinite;
        }
    </style>
    <script type="text/javascript">if (location.host.match(/192\./)) $("audio")[0].pause()</script>
</div>
</body>
</html>