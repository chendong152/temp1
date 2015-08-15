/**
 * Created by Dong on 2015-08-10.
 */

function App() {
    this.imgCount = 0, this.loadedCount = 0, this.__inited = false;
}
App.prototype = {
    _animates: {
        p1: function () {
            $(".page1 .img5").css({x: -180}).transition({
                x: 0, opacity: 1, delay: 100, duration: 500,
                complete: function () {
                    $(".page1 .img3").css({scale: 0}).transition({
                        scale: 1, opacity: 1, delay: 300,
                        complete: function () {
                            $(".page1 .img4").css({scale: 0, x: -50}).transition({
                                scale: 1, x: 0, opacity: 1, delay: 300,
                                complete: function () {
                                    $(".page1 .img2-2").css({x: 100}).transition({
                                        x: 0, opacity: 1, delay: 100,
                                        complete: function () {
                                            $(this).css({animation: 'infinite tada 5s ease'});
                                        }
                                    });
                                    $('.page1 .img2').css({x: 100}).transition({
                                        x: 0, opacity: 1, delay: 100,
                                        complete: function () {
                                            $(this).css({animation: 'infinite tada 5s ease'});
                                            setTimeout(function () {
                                                $('.page1 .img6,.page1 .img7').css({
                                                    opacity: 1,
                                                    animation: 'bounceInDown 800ms ease-in'
                                                })
                                            }, 1000);
                                        }
                                    });
                                }
                            });
                        }
                    });
                }
            });
        },
        p2: function () {
            $('.page2 .dishes').css({scale: 0}).transition({
                scale: 1, opacity: 1,
                complete: function () {
                    $(".page2 .img25").css({x: $(".page2 .img25").width()}).transition({
                        x: 0, duration: 500, opacity: 1,
                        complete: function () {
                            $(".page2 .img21,.page2 .img23,.page2 .img24").css({scale: 0, opacity: 0}).transition({
                                scale: 1, opacity: 1,
                                complete: function () {
                                    if (!this.d1) {
                                        this.d1 = true;
                                        $('.page2 .img21').css({animation: 'infinite tada2 5s ease'});
                                        $(".page2 .img24").hide(), $(".page2 .txt24").show().css({opacity: 0}).transition({opacity: 1});
                                    }
                                }
                            });
                        }
                    });
                }
            });
        },
        p3: function () {
            $('.page3 .img33').css({x: -100, opacity: 1}).transition({x: 0});
            $('.page3 .img36').css({x: 200, opacity: 1}).transition({
                x: 0, complete: function () {
                    $('.page3 .my-dishes').css({}).transition({
                        opacity: 1, delay: 500, complete: function () {
                            $('.page3 .txt32').css({scale: 0}).transition({
                                scale: 1, opacity: 1, delay: 500, complete: function () {
                                    $('.page3 .img35').css({scale: 0}).transition({
                                        scale: 1, opacity: 1, delay: 500, complete: function () {
                                            $('.page3 .img37').css({}).transition({opacity: 1});
                                        }
                                    });
                                }
                            });
                        }
                    });
                }
            });
        }
    },
    nextPage: function () {
        return this.goTo($('.page.active').index() + 1);
    },
    goTo: function (i) {
        if (i + 1 > $(".page").length) return;
        var self = $('.page.active'), fn = this._animates['p' + (i + 1)];
        self.removeClass('active')
            .parent().css({transform: 'translateX(' + -$(".con>.page").width() * i + "px)"})
            .find(".page:eq(" + i + ")").addClass('active');
        fn && fn();
        return this.onshow && this.onshow.call(this, i) , this;
    },
    start: function (i) {
        $('.page > .animate').css({opacity: 0});
        $('.page2 .img1,.page1 .img6,.page1 .img7,.page1 .img2').css({animation: '1s'}),
            $(".page2 .my-dishes").empty(), $('.page2 .dishes .dish').show(), mySwiper.slideTo(0), $('.page2 .txt24').hide();
        return this.goTo(parseInt(i) || 0);
    },
    init: function () {
        var self = this;
        if (!self.__inited) {
            this.imgCount = $("img[loadsrc]").load(function () {
                var p = self.imgCount == 0 ? 0 : ++self.loadedCount / self.imgCount;
                $(".loader .progress").text(parseInt(p * 100) + '%');
                if (p == 1) {
                    $('.loader').transit({
                        x: -500, complete: function () {
                            this.remove();
                        }
                    });
                    self.start(getParam("p") || 0);
                }
            }).length;
            $("img[loadsrc]").each(function () {
                $(this).attr('src', $(this).attr('loadsrc'))
            });
            self.__inited = true;
        }
    }
}
;
var app = new App();
app.onshow = function (i) {
    switch (i) {
        case  3:
            $('.page4 .my-head').attr('src', app.recId || !wx.owner.openid ? wx.user.headimgurl : wx.owner.headimgurl);
            $(".page4 .img44").attr("src", 'img/4/' + ( !wx.owner.openid || wx.owner.openid == wx.user.openid ? 'chongxinfaqi.png' : (app.done || (wx.myRec && wx.myRec.id > 0) ? '我也要玩.png' : 'metoo.png')));
            $.getJSON('/biz/ajax.php?action=similar', {
                from: app.recId ? app.recId : getParam('from_id'),
                bench: app.done ? 'me' : null
            }, function (data) {
                var count = 0;
                $('.page4 .items').empty();
                for (var i in data) {
                    var item = data[i];
                    item['thumb'] = item.result_kind == wx.owner.result_kind ? ++count && 'thumb' : '';
                    item['comp'] = item.result_kind == wx.owner.result_kind ? '' : '不';
                    item['disp'] = item.result_kind == wx.owner.result_kind ? 'display:none' : '';
                    var li = '<li class="{thumb}"><img src="{headimgurl}"/><dl><dt><em>{nickname}</em>与{bench}{comp}是同款吃货</dt><dd>他是<em>{result_kind}</em></dd></dl></li>';
                    li = '<li class="{thumb}"><table><tr><td><img class="head_img" src="{headimgurl}"/></td><td><em class="other_alias">{nickname}</em>与<span class="bench">{bench}</span><span class="verb">{comp}</span>是同款吃货</br><span style="{disp}">他是<em>{result_kind}</em></span></td></tr></table></li>'
                    $('.page4 .items').append($(replace(li, item)));
                }
                $('.page4 .txt44 .count').text(count);
                $(".page4 .txt42 .nickname").text(wx.owner.nickname), $(".page4 .txt42 .kind").text(wx.owner.result_kind),
                    $(".page4 .txt42 .detail").text(wx.owner.result_detail);
            });
            break;
        case 4:
            $.getJSON('biz/ajax.php?action=pk', {from: getParam('from_id')}, function (data) {
                var li = '<li class="item {thumb}"><label class="index">{index}</label><img class="head_img" src="{headimgurl}"><dl><dt>{nickname}</dt><dd>与你的相似度{similar}%</dd></dl></li>';
                var p = $(".page5 .items.current").empty();
                for (var i in data.current || []) {
                    var item = data.current[i];
                    item.index = parseInt(i) + 1, item.thumb = i < 3 ? 'thumb' : '', item.similar = parseFloat(item.similar).toFixed(0);
                    p.append($(replace(li, item)));
                }
                p = $(".page5 .items.history").empty();
                li = '<li class="item" data-id={id}><div><label class="l">{date}</label><label>共找到{count}个同款</label></div></li>'
                for (var i in data.history || []) {
                    var item = data.history[i];
                    item.date = item.create_time.substr(0, 10);
                    p.append($(replace(li, item)));
                }
            });
    }
    return this;
}
$(function () {
    app.init();
});

function drawProcess(p) {
    $('canvas.progress').each(function () {
        var text = 'ing';// $(this).text();
        var process = p;
        text.substring(0, text.length - 1);
        var canvas = this;
        var context = canvas.getContext('2d'), width = $(this).width();

        context.clearRect(0, 0, width, width);
        context.beginPath();
        context.moveTo(width / 2, width / 2);
        context.arc(width / 2, width / 2, width / 2, 0, Math.PI * 2, false);
        context.closePath();
        context.fillStyle = '#ddd';
        context.fill();
        context.beginPath();
        context.moveTo(width / 2, width / 2);
        context.arc(width / 2, width / 2, width / 2, 0, Math.PI * 2 * process / 100, false);
        context.closePath();
        context.fillStyle = '#2a2';
        context.fill();
        context.beginPath();
        context.moveTo(width / 2, width / 2);
        context.arc(width / 2, width / 2, width / 2 - 3, 0, Math.PI * 2, true);
        context.closePath();
        context.fillStyle = 'rgba(255,255,255,1)';
        context.fill();
        context.beginPath();
        context.arc(width / 2, width / 2, width / 2 * 18.5 / 24, 0, Math.PI * 2, true);
        context.closePath();
        context.strokeStyle = '#ddd';
        context.stroke();
        context.font = "bold 9pt Arial";
        context.fillStyle = '#2a2';
        context.textAlign = 'center';
        context.textBaseline = 'middle';
        context.moveTo(width / 2, width / 2);
        context.fillText(text, width / 2, width / 2);
    });
}