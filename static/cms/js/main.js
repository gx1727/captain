(function($) {

	"use strict";
    $('.navigation').singlePageNav({
        currentClass : 'active'
    });
    $('.toggle-menu').click(function(){
        $('.responsive-menu').stop(true,true).slideToggle();
        return false;
    });

    $("img.lazyload").lazyload();

    $('.tools').hide();
    $('.tools').first().show();

    $(".projects-holder").find('.project-item').click(function() {
        var tool = $(this).attr('tool');
        if(tool) {
            $('.tools').hide();
            $('.' + tool).show();
        }
    });

    // 时间
    $("#btn_get_time").click(function() {
        $.post('/tools/time', {
                op: 'get_time'
            }, function(ret) {
                if(ret.code == 0) {
                    $('#display_time').val(ret.data);
                }
            });
    });
    $("#btn_format_time").click(function() {
        var time = $('#display_time').val();
        $.post('/tools/time', {
                op: 'format_time',
                time: time
            }, function(ret) {
                if(ret.code == 0) {
                    $('#display_format_time').html(ret.data);
                }
            });
    });
    $("#btn_format_date").click(function(){
        var year = $('.date').find('input.year').val();
        var mouth = $('.date').find('input.mouth').val();
        var day = $('.date').find('input.day').val();
        var hour = $('.date').find('input.hour').val();
        var minute = $('.date').find('input.minute').val();
        var second = $('.date').find('input.second').val();
        $.post('/tools/time', {
            op: 'create_time',
            year: year,
            mouth: mouth,
            day: day,
            hour: hour,
            minute: minute,
            second: second
        }, function(ret) {
            if(ret.code == 0) {
                $('#display_format_date').html(ret.data);
            }
        });
    })

    // 拼音
    $("#btn_pinyin").click(function(){
        $("#displayAllPinyin").html(__pinyin.getPinyin( $("#pinyinRes").val() ));
        $("#displayHeadPinyin").html(__pinyin.getFirstLetter(  $("#pinyinRes").val() ));
        $("#displayHeadPinyinU").html(__pinyin.getFirstLetterU(  $("#pinyinRes").val() ));
    });

})(jQuery);






