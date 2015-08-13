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
        var self = $('.page.active'), fn = this._animates['p' + (i + 1)];
        self.removeClass('active')
            .parent().css({transform: 'translateX(' + -$(".con>.page").width() * i + "px)"})
            .find(".page:eq(" + i + ")").addClass('active');
        fn && fn();
        return this.onshow ? this.onshow.call(this, i) && this : this;
    },
    start: function () {
        $('.page > .animate').css({opacity: 0});
        $('.page2 .img1,.page1 .img6,.page1 .img7,.page1 .img2').css({animation: '1s'}),
            $(".page2 .my-dishes").empty(), $('.page2 .dishes .dish').show(), mySwiper.slideTo(0), $('.page2 .txt24').hide();
        return this.goTo(0);
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
                    self.start();
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
    if (i != 3) return this;
    $.getJSON('/biz/ajax.php?action=similar', {from: getParam('from_id')}, function (data) {
        var count = 0;
        $('.page4 .items').empty();
        for (var i in data) {
            var item = data[i];
            item['thumb'] = item.similar == 100 ? ++count && 'thumb' : '';
            item['comp'] = item.similar == 100 ? '' : '不';
            item['bench'] = item.nickname == wx.user.nickname ? '你' : item.nickname;
            var li = '<li class="{thumb}"><img src="{headimgurl}"/><dl><dt><em>{nickname}</em>与{bench}{comp}是同款吃货</dt><dd>他是<em>{result_kind}</em></dd></dl></li>';
            $('.page4 .items').append($(replace(li, item)));
        }
        $('.page4 .txt44 .count').text(count);
    });
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