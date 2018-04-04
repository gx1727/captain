<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width,height=device-height, user-scalable=no,initial-scale=1, minimum-scale=1, maximum-scale=1,target-densitydpi=device-dpi ">
    <title>cv_title</title>
    <link href="/static/acase/css/bootstrap.min.css" rel="stylesheet">
    <link href="/static/acase/css/swiper.min.css" rel="stylesheet">
    <link href="/static/acase/css/style.css?v=1.0.2" rel="stylesheet">
    <link href="/static/acase/css/iconfont.css" rel="stylesheet">
</head>
<body>
<div class="content">
    <div class="page-block  swiper-container home-swiper" style="margin-bottom:0;">
        <div class="swiper-wrapper">
            <div class="swiper-slide">
                <a href="#">
                    <img src="/static/acase/img/ic_1.jpg" style="height: 200px">
                </a>
            </div>
            <div class="swiper-slide">
                <a href="#">
                    <img src="/static/acase/img/ic_2.jpg" style="height: 200px">
                </a>
            </div>
            <div class="swiper-slide">
                <a href="#">
                    <img src="/static/acase/img/ic_3.jpg" style="height: 200px">
                </a>
            </div>
        </div>
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
    </div>
    <div class="sys-notice page-user">
        <div class="user-menu" style="color: #B5BEC4;">
            <div>
                <p style="font-size: 5vw;font-weight: bolder;color: #777;">29</p>
                参与数
            </div>
            <div class="">
                <p style="font-size: 5vw;font-weight: bolder;color: #777;">2439</p>
                投票数
            </div>
            <div class="">
                <p style="font-size: 5vw;font-weight: bolder;color: #777;">243339</p>
                访问量
            </div>
        </div>
    </div>

    <div class="sys-notice" style="margin: 3vw;box-shadow: 0 0 20px rgba(0,0,0,0.1); border-radius: 15px;padding: 7px 20px;">
        <span class="iconfont icon-talk" style="margin-right: 10px;"></span>
        投票结束倒计时00天23时05分15秒
    </div>
    <div class="sys-notice" style="margin-bottom: 5px;">
        说明：<p/>
        磊在在 <p/>
        在在<p/>
    </div>
    <div class="sys-notice" style="margin-bottom: 5px;">
        这里是详细说明
    </div>
</div>
<div class="foot foot1">
    <div class="menu menu1">
        <a href="/case" class="iconfont icon-home">投票</a>
        <a href="/case/rank" class="iconfont icon-hot">排名</a>
        <a href="/case/rule" class="iconfont icon-user active">规则</a>
    </div>
</div>

<div class="follow-modal" style="display:none;">
    <div class="po-a center">
        <img src="/static/acase/img/qrcode.jpg">
        <p style="margin: 2vw 10vw;font-size: 5vw;">长按识别二维码<br/>关注公众号，为她投票</p>
        <a class="follow-modal-close">X</a>
    </div>
</div>
<script type="text/javascript" src="/static/acase/js/jquery-3.2.0.min.js"></script>
<script type="text/javascript" src="/static/acase/js/swiper.min.js"></script>
<script>
    var homeSwiper = new Swiper('.home-swiper', {
        loop: true,
        nextButton: '.swiper-button-next',
        prevButton: '.swiper-button-prev',
        autoplay: 2500,//可选选项，自动滑动
        speed: 1000,
        autoplayDisableOnInteraction: false,
    })
    $(function () {
        $(".follow-modal-close").click(function () {
            $(".follow-modal").hide();
        });
    });
</script>
</body>
</html>