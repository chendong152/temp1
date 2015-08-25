/**
 * Created by Dong on 2015-08-10.
 */

function App() {
    this.imgCount = 0, this.loadedCount = 0, this.__inited = false;
}
App.prototype = {
    _animates: {
        p1: function () {
            $(".page1 .img5").css({x: -100}).transition({
                x: 0, opacity: 1, delay: 100, duration: 500,
                complete: function () {
                    $(".page1 .img3").css({scale: 0}).transition({
                        scale: 1, opacity: 1, delay: 300,
                        complete: function () {
                            $(".page1 .img4").css({scale: 0, x: -50}).transition({
                                scale: 1, x: 0, opacity: 1, delay: 300,
                                complete: function () {
                                    $(".page1 .img2-2").css({x: -100}).transition({
                                        x: 0, opacity: 1, delay: 100,
                                        complete: function () {
                                            $(this).css({animation: 'infinite tada 5s ease'});
                                        }
                                    });
                                    $('.page1 .img2').css({x: -100}).transition({
                                        x: 0, opacity: 1, delay: 100,
                                        complete: function () {
                                            $(this).css({animation: 'infinite tada 5s ease'});
                                            setTimeout(function () {
                                                $('.page1 .img6-1').css({
                                                    opacity: 1,
                                                    animation: 'bounceInDown 800ms ease-in'
                                                });
                                                $('.page1 .img8').transition({
                                                    x: 0.5 * $('.page').width(), opacity: 1, delay: 800, duration: 500,
                                                    complete: function () {
                                                        $(".page1 .img6-2").css({opacity: 1}).show();
                                                    }
                                                });
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
            $(".page2 .img21").css({scale: 0, opacity: 0}).transition({
                scale: 1, opacity: 1,
                complete: function () {
                    this.d1 = true;
                    $('.page2 .img21').css({animation: 'infinite tada2 5s ease'});
                }
            });
        },
        p3: function () {
            $('.page3 .my-dishes').css({}).transition({
                opacity: 1, delay: 500, complete: function () {
                    $('.page3 .txt32').css({scale: 0}).transition({
                        scale: 1, opacity: 1, delay: 500, complete: function () {
                            $('.page3 .img37').css({}).transition({opacity: 1});
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
        $('.page1 .img6,.page1 .img7,.page1 .img2,.page1 .img2-2').css({animation: '1s'}),
            $(".page2 .dishes .dish.selected").removeClass('selected'), $(".page2 .count").hide(), mySwiper.slideTo(0);
        return this.goTo(parseInt(i) || 0);
    },
    init: function () {
        var self = this;
        if (!self.__inited) {
            this.imgCount = $("img[loadsrc]").load(function () {
                var p = self.imgCount == 0 ? 0 : ++self.loadedCount / self.imgCount;
                $(".loader .progress").text('努力加载中...' + parseInt(p * 100) + '%');
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
        return self;
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
                    var li = '<li class="{thumb}"><img src="{headimgurl}"/><dl><dt><em>{nickname}</em>与{bench}{comp}是同款吃货</dt><dd>Ta是<em>{result_kind}</em></dd></dl></li>';
                    li = '<li class="{thumb}"><table><tr><td><img class="head_img" src="{headimgurl}"/></td><td><em class="other_alias">{nickname}</em>与<span class="bench">{bench}</span><span class="verb">{comp}</span>是同款吃货</br><span style="{disp}">他是<em>{result_kind}</em></span></td></tr></table></li>'
                    $('.page4 .items').append($(replace(li, item)));
                }
                $('.page4 .txt44 .count').text(count);
                $(".page4 .txt42 .nickname").text((wx.owner.nickname || wx.user.nickname).substr(0, 4) + "是"),
                    $(".page4 .txt42 .kind").text(wx.owner.result_kind || wx.user.kind),
                    $(".page4 .txt42 .detail").text(wx.owner.result_detail || wx.user.detail);
            });
            break;
        case 4:
            $.getJSON('biz/ajax.php?action=pk', {from: getParam('from_id')}, function (data) {
                var li = '<li class="item {thumb}"><label class="index">{index}</label><img class="head_img" src="{headimgurl}"><dl><dt>{nickname}</dt><dd>与{master}的相似度{similar}%</dd></dl></li>';
                var p = $(".page5 .items.current").empty();
                for (var i in data.current || []) {
                    var item = data.current[i];
                    item.master = !wx.owner.openid || wx.user.openid == wx.owner.openid ? '你' : 'Ta';
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
    app.init().renew = !getParam("from_id");
});
