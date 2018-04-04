<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width,height=device-height, user-scalable=no,initial-scale=1, minimum-scale=1, maximum-scale=1,target-densitydpi=device-dpi ">
    <title>cv_title</title>
    <link href="/static/acase/css/bootstrap.min.css" rel="stylesheet">
    <link href="/static/acase/css/swiper.min.css" rel="stylesheet">
    <link href="/static/acase/css/style.css?v=1.0.2" rel="stylesheet">
    <link href="/static/acase/css/iconfont.css" rel="stylesheet">
    <link href="/static/acase/css/swipebox.css" rel="stylesheet">
</head>
<body class="project-page">
    <div class="content">
        <div class="project-info">
            <div>
                <img style="width: 100%;" src="/static/acase/img/ic_3.jpg"/>
                <div class="publisher">
                    暮云春树工 </span><img style="height: 40px;width:40px;border-radius: 50%;margin-top: -25px;" src="/static/acase/img/ic_3.jpg"/>
                </div>
            </div>
        </div>
        <div class="page-block product-bottom">
            <div class="participator-list tablist">
                <div class="participator-list-content tablist-content">
                    <div class="active introduction content">
                        <ul>
                            <li class="con-detail">
                                <div class="extend-text">
                                    fsafdaf
                                </div>
                                <div class="extend-img">
                                    <a class="extend-img-item swipebox" href="/static/acase/img/ic_3.jpg" style="background:url('/static/acase/img/ic_3.jpg') center center;background-size:cover" rel="img"></a>
                                    <a class="extend-img-item swipebox" href="/static/acase/img/ic_3.jpg" style="background:url('/static/acase/img/ic_3.jpg') center center;background-size:cover" rel="img"></a>
                                    <a class="extend-img-item swipebox" href="/static/acase/img/ic_3.jpg" style="background:url('/static/acase/img/ic_3.jpg') center center;background-size:cover" rel="img"></a>
                                    <a class="extend-img-item swipebox" href="/static/acase/img/ic_3.jpg" style="background:url('/static/acase/img/ic_3.jpg') center center;background-size:cover" rel="img"></a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="sys-notice" style="margin-top: 5px;">
        说明：<p/>
 dsdd
    </div>

    <div class="share-area">
        <div class="share-button"><span class="glyphicon glyphicon-share" aria-hidden="true"></span> 分享给好友</div>
        <div class="back-button"><span class="iconfont icon-home" aria-hidden="true"></span> 返回首页</div>
    </div>

    <div class="foot foot2">
        <div class="menu menu2">
            <a class="sign-now participate">立即投票</a>
        </div>
    </div>
    <div class="follow-modal" style="display:none;">
        <div class="po-a center">
            <img src="/static/acase/img/qrcode.jpg">
            <p style="margin: 2vw 10vw;font-size: 5vw;">长按识别二维码<br/>关注公众号，为她投票</p>
            <a class="follow-modal-close">X</a>
        </div>
    </div>


    <div class="modal" id="share" tabindex="-1" role="dialog" style="background:url('/static/acase/img/weixin-share.png') top center;background-size:100% auto;">


    <script type="text/javascript" src="/static/acase/js/jquery-2.1.0.min.js"></script>
    <script type="text/javascript" src="/static/acase/js/swiper.min.js"></script>
    <script type="text/javascript" src="/static/acase/js/jquery.swipebox.js"></script>
    <script>

        $(function () {
           $('.swipebox').swipebox({loopAtEnd:true});

            $(".share-button").click(function () {
                $("#share").show();
            });
            $("#share").click(function () {
                $(this).hide();
            });
            $(".back-button").click(function () {
                location.href = '/case';
            });

            $(".follow-modal-close").click(function () {
                $(".follow-modal").hide();
            });
            $(".participate").click(function () {
                $(".follow-modal").show();
                return false;
            })
        });
    </script>
</body>
</html>