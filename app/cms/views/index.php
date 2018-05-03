<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>第一房车_房车改装、旅行、营地</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <script src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
    <script src="/static/js/bootstrap.min.js"
            integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
            crossorigin="anonymous"></script>
    <link rel="stylesheet" href="/static/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="/static/css/01rv.css" crossorigin="anonymous">
</head>
<body>
<header>
    <nav class="navbar navbar-default" style="margin-bottom: 0px">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                        data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" style="padding:0 10px 0 4vw;" href="http://www.01rv.com"><img alt="Brand"
                                                                                                      style="max-width:100px;"
                                                                                                      src="/static/img/sitelogo.png"></a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <?php lanmu(); ?>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container-fluid -->
    </nav>
    <div class="clearfix"></div>
</header>
<div class="container-fluid">
    <div class="row">
        <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
            <!-- Indicators -->
            <ol class="carousel-indicators">
                <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
                <li data-target="#carousel-example-generic" data-slide-to="1"></li>
                <li data-target="#carousel-example-generic" data-slide-to="2"></li>
            </ol>

            <!-- Wrapper for slides -->
            <div class="carousel-inner listbox" role="listbox">
                <div class="item active">
                    <a href="http://www.01rv.com/90370613"><img
                            src="http://p7bo76bgm.bkt.clouddn.com/upload/20180428/1524924680YT9TfTFw.png?imageView2/1/w/1920/h/420/format/jpg/q/90"
                            alt="..."></a>

                    <div class="carousel-caption">
                        <h3><a target="_blank" href="http://www.01rv.com/90370613">时下正热的微型拖挂房车，谈谈历史，聊聊发展前景</a></h3>
                    </div>
                </div>
                <div class="item">
                    <a href="http://www.01rv.com/50160194"><img
                            src="http://p7bo76bgm.bkt.clouddn.com/upload/20180428/1524924675IEQZRTXY.jpg?imageView2/1/w/1920/h/420/format/jpg/q/90"
                            alt="..."></a>

                    <div class="carousel-caption">
                        <h3><a target="_blank" href="http://www.01rv.com/50160194">2018南京国际度假休闲及房车展览会（6.1-6.3）</a></h3>
                    </div>
                </div>
                <div class="item">
                    <a href="http://www.01rv.com/57369306"><img
                            src="http://p7bo76bgm.bkt.clouddn.com/upload/20180428/1524924678RqHacGFO.jpg?imageView2/1/w/1920/h/420/format/jpg/q/90"
                            alt="..."></a>

                    <div class="carousel-caption">
                        <h3><a target="_blank" href="http://www.01rv.com/57369306">第三届上海国际民宿文化产业博览</a></h3>
                    </div>
                </div>
            </div>

            <!-- Controls -->
            <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    </div>

    <div class="clearfix"></div>

</div>


<div class="container">
    <div class="row">
        <h2 class="title-text"><span>房车资讯<i>NEW RV</i></span></h2>

        <div class="col-sm-12 col-md-6">
            <div class="thumbnail zt">
                <?php $recommend_article = get_articles('1', 'news', '', 'cover', '2'); ?>
                <a href="/<?php echo $recommend_article['code']; ?>"><img class="img-responsive center-block"
                                                                          src="<?php echo $recommend_article['img']; ?>?imageView2/1/w/525/h/316/format/jpg/q/90"
                                                                          alt="<?php echo $recommend_article['title']; ?>"></a>

                <div class="caption">
                    <h4>
                        <a href="/<?php echo $recommend_article['code']; ?>"><?php echo mb_substr($recommend_article['title'], 0, 30); ?></a>
                    </h4>

                    <p><?php echo mb_substr($recommend_article['abstract'], 0, 60) . '...'; ?></p>

                    <?php if ($recommend_article['tags']) {
                        foreach ($recommend_article['tags'] as $tag_name => $tag_title) {
                            ?>
                            <a href="/<?php echo $tag_name; ?>"><span
                                    class="label label-info"><?php echo $tag_title; ?></span></a>
                        <?php }
                    } ?>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-6">
            <?php $travels_articles = get_articles(4, 'news', '', 'recommend'); ?>
            <?php if ($travels_articles) {
                foreach ($travels_articles as $article) {
                    ?>
                    <div class="col-sm-12 col-md-6">
                        <div class="thumbnail zt"><a href="/<?php echo $article['code']; ?>">
                                <img class="img-responsive center-block"
                                     src="<?php echo $article['img']; ?>?imageView2/1/w/330/h/183/format/jpg/q/90"
                                     alt="<?php echo $article['title']; ?>">

                                <div class="caption">
                                    <p><?php echo mb_substr($article['title'], 0, 30); ?></p>
                                </div>
                            </a>
                        </div>
                    </div>
                <?php }
            } ?>
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <h2 class="title-text"><span>房车视频<i>RV movie</i></span></h2>

        <div class="container">
            <?php $video_articles = get_articles(8, 'video', '', 'recommend'); ?>
            <?php if ($video_articles) {
                foreach ($video_articles as $article) { ?>
                    <div class="col-xs-12 col-md-3">
                        <div class="thumbnail zt"><a href="/<?php echo $article['code']; ?>">
                                <img src="<?php echo $article['img']; ?>?imageView2/1/w/255/h/160/format/jpg/q/90"
                                     alt="<?php echo $article['title']; ?>">

                                <div class="caption"><?php echo $article['title']; ?></div>
                            </a>
                        </div>
                    </div>
                <?php }
            } ?>
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <h2 class="title-text"><span>房车展会<i>RV Exhibition</i></span></h2>
        <?php $refit_articles = get_articles(6, 'chezhan', '', 'recommend'); ?>
        <?php $sub_articles = sub_articles($refit_articles, 1, 2); ?>
        <div class="col-sm-12 col-md-5">
            <?php if ($sub_articles) {
                foreach ($sub_articles as $article) {
                    ?>
                    <div class="thumbnail zt"><a href="/<?php echo $article['code']; ?>">
                            <img src="<?php echo $article['img']; ?>?imageView2/1/w/427/h/200/format/jpg/q/90"
                                 alt="<?php echo $article['title']; ?>">

                            <div class="caption">
                                <h4><?php echo mb_substr($article['title'], 0, 30); ?></h4>

                                <p><?php echo mb_substr($article['abstract'], 0, 54) . '...'; ?></p>

                            </div>
                        </a>
                    </div>
                <?php }
            } ?>
        </div>


        <div class="col-sm-12 col-md-3">

            <?php $rts_articles = get_articles(2, 'chezhan', '', 'special'); ?>
            <?php if ($rts_articles) {
                foreach ($rts_articles as $article) {
                    ?>
                    <div class="thumbnail zt">
                        <a href="/<?php echo $article['code']; ?>">
                            <div class="caption">
                                <?php echo mb_substr($article['title'], 0, 30); ?>
                            </div>
                            <img src="<?php echo $article['img']; ?>?imageView2/1/w/232/h/268/format/jpg/q/90"
                                 alt="<?php echo $article['title']; ?>">
                        </a>
                    </div>
                <?php }
            } ?>
        </div>


        <div class="col-sm-12 col-md-4">
            <div class="thumbnail zt">
                <?php $sub_articles = sub_articles($refit_articles, 3, 3); ?>
                <?php if ($sub_articles) {
                foreach ($sub_articles as $article){
                ?>
                <a href="/<?php echo $article['code'];?>">
                    <a href="/<?php echo $article['code'];?>">
                        <img src="<?php echo $article['img'];?>?imageView2/1/w/336/h/180/format/jpg/q/90"
                             alt="<?php echo $article['title'];?>">
                        <div class="caption"><?php echo mb_substr($article['title'], 0, 26) . '...';?></div>
                    </a>
                    <?php }
                    } ?>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">

        <h2 class="title-text"><span>专题推荐<i>Special Recommend</i></span></h2>

        <div class="col-sm-12 col-md-6">
            <div class="thumbnail zt"><a href="/njfcz">
                    <img
                        src="http://p7bo76bgm.bkt.clouddn.com/upload/20180426/1524757513lyCSn1gB.jpg?imageView2/1/w/525/h/316/format/jpg/q/90"
                        alt="Thumbnail label">

                    <div class="caption">
                        <h3><a href="/njfcz" target="_blank">2018南京（国际）房车旅游文化博览会</h3>

                        <p>2018南京（国际）房车旅游文化博览会暨首届桠溪国际慢城房车露营大会，将于4月29日-5月1日在南京高淳国际慢城小镇驿站片区举办。</p>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-sm-12 col-md-6">
            <div class="thumbnail zt"><a href="/hffcz">
                    <img
                        src="http://p7bo76bgm.bkt.clouddn.com/upload/20180426/1524758058Yw80RvRD.png?imageView2/1/w/525/h/316/format/jpg/q/90"
                        alt="Thumbnail label">

                    <div class="caption">
                        <h3><a href="/hffcz" target="_blank">第三届中国（合肥）房车露营大会</h3>

                        <p>第三届中国（合肥）房车露营大会暨房车装备与旅游休闲博览会，于4月28日-5月1日在合肥万达茂及酒吧街隆重举行，精彩不容错过！</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <h2 class="title-text"><span>房车游记<i>Caravan Tourism</i></span></h2>

        <div class="col-sm-12 col-md-6">
            <div class="thumbnail zt">
                <?php $recommend_article = get_article('', 'travels', '', 'cover'); ?>
                <a href="/<?php echo $recommend_article['code']; ?>"><img class="img-responsive center-block"
                                                                          src="<?php echo $recommend_article['img']; ?>?imageView2/1/w/525/h/316/format/jpg/q/90"
                                                                          alt="<?php echo $recommend_travels_article['title']; ?>"></a>

                <div class="caption">
                    <h4>
                        <a href="/<?php echo $recommend_article['code']; ?>"><?php echo mb_substr($recommend_article['title'], 0, 30); ?></a>
                    </h4>

                    <p><?php echo mb_substr($recommend_article['abstract'], 0, 60) . '...'; ?></p>

                    <?php if ($recommend_article['tags']) {
                        foreach ($recommend_article['tags'] as $tag_name => $tag_title) {
                            ?>
                            <a href="/<?php echo $tag_name; ?>"><span
                                    class="label label-info"><?php echo $tag_title; ?></span></a>
                        <?php }
                    } ?>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-6">
            <div class="row">
                <?php $travels_articles = get_articles(4, 'travels', '', 'recommend'); ?>
                <?php if ($travels_articles) {
                    foreach ($travels_articles as $article) {
                        ?>
                        <div class="col-sm-12 col-md-6">
                            <div class="thumbnail zt"><a href="/<?php echo $article['code']; ?>">
                                    <img class="img-responsive center-block"
                                         src="<?php echo $article['img']; ?>?imageView2/1/w/330/h/183/format/jpg/q/90"
                                         alt="<?php echo $article['title']; ?>">

                                    <div class="caption">
                                        <p><?php echo mb_substr($article['title'], 0, 30); ?></p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    <?php }
                } ?>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <h2 class="title-text"><span>营地大全<i>Caravan camp</i></span></h2>
        <?php $camp_articles = get_articles(3, 'camp', '', 0, 'new'); ?>
        <?php if ($camp_articles) {
            foreach ($camp_articles as $article) {
                ?>
                <div class="col-sm-12 col-md-4">
                    <div class="thumbnail zt"><a href="/<?php echo $article['code']; ?>">
                            <img src="<?php echo $article['img']; ?>?imageView2/1/w/330/h/200/format/jpg/q/90"
                                 alt="<?php echo $article['title']; ?>">

                            <div class="caption">
                                <p class="index_title"><?php echo $article['title']; ?></p>

                                <p><?php echo mb_substr($article['abstract'], 0, 54) . '...'; ?></p>
                            </div>
                        </a>
                    </div>
                </div>
            <?php }
        } ?>
    </div>
</div>
<div class="container">
    <div class="row">
        <h2 class="title-text"><span>房车入门<i>RV Study</i></span></h2>
        <?php
        $yczn_recommends_articles = get_articles(4, 'yczn', '', 'recommend');
        $yczn_articles = get_articles(7, 'yczn');
        ?>
        <div class="col-sm-12 col-md-4">
            <div class="thumbnail zt">
                <?php if ($yczn_recommends_articles) {
                    foreach ($yczn_recommends_articles as $index => $article) {
                        if ($index >= 2) {
                            continue;
                        } ?>
                        <a href="/<?php echo $article['code']; ?>">
                            <img src="<?php echo $article['img']; ?>?imageView2/1/w/330/h/180/format/jpg/q/90"
                                 alt="<?php echo $article['title']; ?>">

                            <div class="caption">
                                <p><?php echo $article['title']; ?></p>
                            </div>
                        </a>
                    <?php }
                } ?>
            </div>

        </div>
        <div class="col-sm-12 col-md-4">
            <?php if ($yczn_articles) {
                foreach ($yczn_articles as $article) {
                    ?>
                    <div class="media">
                        <div class="media-left">
                            <a href="/<?php echo $article['code']; ?>">
                                <img class="media-object img-circle"
                                     src="<?php echo $article['img']; ?>?imageView2/1/w/60/h/60/format/jpg/q/90"
                                     alt="<?php echo $article['title']; ?>">
                            </a>
                        </div>
                        <div class="media-body">
                            <p class="media-heading P6"><a
                                    href="/<?php echo $article['code']; ?>"><?php echo $article['title']; ?></a></p>
                        </div>
                    </div>
                <?php }
            } ?>
        </div>
        <div class="col-sm-12 col-md-4">
            <div class="thumbnail zt">
                <?php if ($yczn_recommends_articles) {
                    foreach ($yczn_recommends_articles as $index => $article) {
                        if ($index < 2) {
                            continue;
                        } ?>
                        <a href="/<?php echo $article['code']; ?>">
                            <img src="<?php echo $article['img']; ?>?imageView2/1/w/330/h/180/format/jpg/q/90"
                                 alt="<?php echo $article['title']; ?>">

                            <div class="caption">
                                <p><?php echo $article['title']; ?></p>
                            </div>
                        </a>
                    <?php }
                } ?>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <h2 class="title-text"><span>改装案例<i>Refit case</i></span></h2>
        <?php $refit_articles = get_articles(7, 'refit', '', 0, 'new'); ?>
        <div class="col-sm-12 col-md-5">
            <?php if ($refit_articles) {
                foreach ($refit_articles as $index => $article) {
                    if ($index >= 2) {
                        break;
                    }
                    ?>
                    <div class="thumbnail zt"><a href="/<?php echo $article['code']; ?>">
                            <img src="<?php echo $article['img']; ?>?imageView2/1/w/427/h/200/format/jpg/q/90"
                                 alt="<?php echo $article['title']; ?>">

                            <div class="caption">
                                <h4><?php echo mb_substr($article['title'], 0, 30); ?></h4>

                                <p><?php echo mb_substr($article['abstract'], 0, 54) . '...'; ?></p>

                            </div>
                        </a>
                    </div>
                <?php }
            } ?>
        </div>
        <div class="col-sm-12 col-md-3">
            <?php if ($refit_articles) {
                foreach ($refit_articles as $index => $article) {
                    if ($index < 2) {
                        continue;
                    } else if ($index >= 4) {
                        break;;
                    }
                    ?>
                    <div class="thumbnail zt">
                        <a href="/<?php echo $article['code']; ?>">
                            <div class="caption">
                                <?php echo $article['title']; ?>
                            </div>
                            <img src="<?php echo $article['img']; ?>?imageView2/1/w/232/h/268/format/jpg/q/90"
                                 alt="<?php echo $article['title']; ?>">
                        </a>
                    </div>
                <?php }
            } ?>
        </div>

        <div class="col-sm-12 col-md-4">
            <div class="thumbnail zt">
                <?php if ($refit_articles) {
                    foreach ($refit_articles as $index => $article) {
                        if ($index < 4) {
                            continue;
                        }
                        ?>
                        <a href="/<?php echo $article['code']; ?>">
                            <img src="<?php echo $article['img']; ?>?imageView2/1/w/336/h/180/format/jpg/q/90"
                                 alt="<?php echo $article['title']; ?>">

                            <div class="caption"><?php echo $article['title']; ?></div>
                        </a>
                    <?php }
                } ?>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <h2 class="title-text"><span>房车装备<i>RV Equipment</i></span></h2>
        <?php $zhuangbei_articles = get_articles(3, 'zhuangbei', '', 0, 'new'); ?>
        <?php if ($zhuangbei_articles) {
            foreach ($zhuangbei_articles as $article) { ?>
                <div class="col-sm-12 col-md-4">
                    <div class="thumbnail zt"><a href="/<?php echo $article['code']; ?>">
                            <img style="height: 200px;width: 100%;display: block;" src="<?php echo $article['img']; ?>"
                                 alt="<?php echo $article['title']; ?>">

                            <div class="caption">
                                <p class="index_title"><?php echo $article['title']; ?></p>

                                <p><?php echo mb_substr($article['abstract'], 0, 54) . '...'; ?></p>
                            </div>
                        </a>
                    </div>
                </div>
            <?php }
        } ?>
    </div>
</div>
<!-- footer -->
<?php footer(); ?>
<!-- footer end -->
</body>
</html>