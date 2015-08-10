/**
 * Created by Dong on 2015-08-10.
 */

function App() {

}
App.prototype = {
    _animates: {
        p1: function () {
            $(".page1 .img5").css({x: -80}).transition({
                x: 0,
                complete: function () {
                    $(".page1 .img3").css({scale: 0}).transition({
                        scale: 1,
                        complete: function () {
                            $(".page1 .img4,.page1 .img").css({scale: 0, x: -50}).transition({
                                scale: 1, x: 0,
                                complete: function () {
                                    $('.page1 .img2,.page1 .img2-2').css({x: 100}).transition({
                                        x: 0,
                                        complete: function () {
                                            $(this).addClass('swing');
                                        }
                                    });
                                    setTimeout(function () {
                                        $('.page1 .img6,.page1 .img7').css({y: -$('.page1').height()}).transition({y: 0});
                                    }, 1000);
                                }
                            });
                        }
                    });
                }
            });
        }
    },
    nextPage: function () {
        this.goTo($('.page.active').index() + 1);
    }
    ,
    goTo: function (i) {
        var self = $('.page.active'), fn = this._animates['p' + (i + 1)];
        fn && fn(), self.removeClass('active')
            .parent().css({transform: 'translateX(' + -$(".con>.page").width() * i + "px)"})
            .find(".page:eq(" + i + ")").addClass('active');
        //return $(".con").transition({x: -$(".con>.page").width() * (self.index() + 1)});
        return self;
    }
    ,
    start: function () {
        $('.page.active').parent().css({transform: 'translate3d(0,0,0)'}) && this.goTo(0);
        $('.page2 .img1,.page1 .img2').removeClass('swing');
        $(".page2 .my-dishes").empty(), $('.page2 .dishes .dish').show(), mySwiper.slideTo(0);
    }
}
;
var app = new App();

var imgTotal = $('img'), c = 0;
$(document).delegate('img', 'load', function () {console.log(++c)});
$(function () {
    $('.loader').remove();
    app.start();
});

