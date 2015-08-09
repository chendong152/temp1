/**
 * Created by Dong on 2015-08-10.
 */

var imgTotal = $('img'), c = 0;
$(document).delegate('img', 'load', function () {console.log(++c)});
$(function () {
    $('.loader').remove();
    p1();
});
function p1() {
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