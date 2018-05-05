<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>gx1727</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="/static/cms/css/normalize.css">
    <link rel="stylesheet" href="/static/cms/css/font-awesome.css">
    <link rel="stylesheet" href="/static/cms/css/bootstrap.min.css">
    <link rel="stylesheet" href="/static/cms/css/templatemo-style.css">
    <script src="/static/cms/js/vendor/modernizr-2.6.2.min.js"></script>
</head>
<body>
<!--[if lt IE 7]>
<p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
<![endif]-->

<div class="responsive-header visible-xs visible-sm">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="top-section">
                    <div class="profile-image">
                        <img src="/static/cms/img/gx1727.jpg" alt="Volton">
                    </div>
                    <div class="profile-content">
                        <h3 class="profile-title">GX1727</h3>
                        <p class="profile-description">一个怀揣着梦想的老码农</p>
                    </div>
                </div>
            </div>
            <div class="cmd">
                <input type="text" placeholder="输入指令">
            </div>
        </div>
        <a href="#" class="toggle-menu"><i class="fa fa-bars"></i></a>
        <div class="main-navigation responsive-menu">
            <ul class="navigation">
                <li><a href="#top"><i class="fa fa-home"></i>首页</a></li>
                <li><a href="#projects"><i class="fa fa-newspaper-o"></i>工具</a></li>
                <li><a href="#about"><i class="fa fa-user"></i>关于我</a></li>
            </ul>
            <ul>
                <li><a href="/blog"><i class="fa fa-link"></i>BLOG</a></li>
            </ul>
        </div>
    </div>
</div>

<!-- SIDEBAR -->
<div class="sidebar-menu hidden-xs hidden-sm">
    <div class="top-section">
        <div class="profile-image">
            <img src="/static/cms/img/gx1727.jpg" alt="Volton">
        </div>
        <h3 class="profile-title">Gx1727</h3>
        <p class="profile-description">一个怀揣着梦想的老码农</p>
    </div> <!-- top-section -->
    <div class="cmd">
        <input type="text" placeholder="输入指令">
    </div>
    <div class="main-navigation">
        <ul class="navigation">
            <li><a href="#top"><i class="fa fa-globe"></i>首页</a></li>
            <li><a href="#projects"><i class="fa fa-paperclip"></i>工具</a></li>
            <li><a href="#about"><i class="fa fa-pencil"></i>关于我</a></li>
        </ul>
        <ul>
            <li><a href="/blog"><i class="fa fa-link"></i>BLOG</a></li>
        </ul>
    </div> <!-- .main-navigation -->
    <div class="social-icons">
    </div> <!-- .social-icons -->
</div> <!-- .sidebar-menu -->

<div class="banner-bg" id="top">
    <div class="banner-overlay"></div>
    <div class="welcome-text">
        <h2>砥砺前行 | 不忘初心</h2>
        <h5>没有平日的失败，就没有最终的成功。永远都不要放弃自己，勇往直前。</h5>
    </div>
</div>

<!-- MAIN CONTENT -->
<div class="main-content">
    <div class="fluid-container">
        <div class="content-wrapper">
            <!-- POSTS -->
            <?php $articles = get_articles(15);
            foreach($articles as $article) {
            ?>
            <article class="row article">
                <div class="col-md-4">
                    <div class="project-item">
                        <a href="/<?php echo $article['code'];?>" class="thumbnail">
                            <img class="lazyload" src="" data-src="<?php echo $article['img']; ?>" alt="<?php echo $article['title']; ?>">
                        </a>
                    </div>
                </div>
                <div class="col-md-8">
                    <header>
                        <a class="cat" href="/suiji" title="随记" >随记<i></i></a>
                        <h2><a href="/<?php echo $article['code']; ?>"><?php echo $article['title']; ?></a></h2>
                    </header>

                    <p class="meta">
                        <time class="time"><i class="glyphicon glyphicon-time"></i> <?php echo date('Y-m-d', $article['ptime']); ?></time>
                        <span class="views"><i class="glyphicon glyphicon-eye-open"></i> <?php echo $article['count']; ?></span>
                    </p>
                    <p class="note"><?php echo $article['abstract']; ?></p>
                    <p>
                        <?php if($article['tags']) {
                            foreach($article['tags'] as $name => $tag) {?>
                                <a href="/<?php echo $name;?>" class="tag"><span class="label label-success"> <?php echo $tag;?> </span></a>
                            <?php }
                        } ?>

                    </p>
                </div>
            </article>

            <?php } ?>

            <!-- PROJECTS -->
            <div class="page-section" id="projects">
                <div class="row">
                    <div class="col-md-12">
                        <h4 class="widget-title">工具</h4>
                        <div class="panel panel-default tools time">
                            <div class="panel-heading">日期、时间</div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="input-group">
                                            <span class="input-group-btn">
                                                <button class="btn btn-default input-lg" type="button" id="btn_get_time" style="font-size: 20px;">点这里得到当前时间戳</button>
                                            </span>
                                            <input type="text" class="form-control input-lg" style="font-size: 1.5em;font-weight: bold;" id="display_time">
                                            <span class="input-group-btn">
                                                <button class="btn btn-default input-lg" type="button" id="btn_format_time" style="font-size: 20px;">转换</button>
                                            </span>
                                        </div><!-- /input-group -->
                                        <div class="alert alert-success" role="alert" id="display_format_time">
                                            XXXX年XX月XX日　XX时：XX分：XX秒
                                        </div>
                                        <div class="input-group date">
                                            <input type="text" class="form-control input-lg year" value="<?php echo date('Y'); ?>" style="font-size: 1.5em;font-weight: bold;">
                                            <span class="input-group-addon">年</span>
                                            <input type="text" class="form-control input-lg mouth" value="<?php echo date('m'); ?>" style="font-size: 1.5em;font-weight: bold;">
                                            <span class="input-group-addon">月</span>
                                            <input type="text" class="form-control input-lg day" value="<?php echo date('d'); ?>" style="font-size: 1.5em;font-weight: bold;">
                                            <span class="input-group-addon">日</span>
                                            <input type="text" class="form-control input-lg hour" value="<?php echo date('H'); ?>" style="font-size: 1.5em;font-weight: bold;">
                                            <span class="input-group-addon">时</span>
                                            <input type="text" class="form-control input-lg minute" value="<?php echo date('i'); ?>" style="font-size: 1.5em;font-weight: bold;">
                                            <span class="input-group-addon">分</span>
                                            <input type="text" class="form-control input-lg second" value="0" style="font-size: 1.5em;font-weight: bold;">
                                            <span class="input-group-addon">秒</span>
                                            <span class="input-group-btn">
                                                <button class="btn btn-default input-lg" type="button" id="btn_format_date" style="font-size: 20px;">获取</button>
                                            </span>
                                        </div><!-- /input-group -->
                                        <div class="alert alert-success" role="alert" id="display_format_date"></div>
                                    </div><!-- /.col-lg-6 -->
                                </div><!-- /.row -->
                            </div>
                        </div>
                        <div class="panel panel-default tools pinyin">
                            <div class="panel-heading">拼音</div>
                            <div class="panel-body">
                                <textarea class="form-control" id="pinyinRes"></textarea>
                                <button class="btn btn-default" id="btn_pinyin">确定</button>
                                <div class="alert alert-success" role="alert" id="displayAllPinyin">
                                    全部
                                </div>
                                <div class="alert alert-info" role="alert" id="displayHeadPinyin">
                                    首字母
                                </div>
                                <div class="alert alert-warning" role="alert" id="displayHeadPinyinU">
                                    首字母大写
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default tools password">
                            <div class="panel-heading">密码</div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="input-group">
                                            <input type="text" class="form-control input-lg" style="font-size: 1.5em;font-weight: bold;">
                                            <span class="input-group-btn">
                                                <button class="btn btn-default input-lg" type="button" style="font-size: 20px;">生成随机密码</button>
                                            </span>
                                        </div><!-- /input-group -->
                                        <div class="alert alert-success" role="alert">
                                            <form class="form">
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" checked>  阿拉伯数字
                                                    </label>
                                                </div>
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" checked> 小写字母
                                                    </label>
                                                </div>
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" checked> 大写字母
                                                    </label>
                                                </div>
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox">  特殊字符 +=-@#~,.[]()!%^*$
                                                    </label>
                                                </div>
                                                <div>
                                                    <label>
                                                        <input type="text" style="width: 60px;" value="32">  随机密码长度
                                                    </label>
                                                </div>
                                            </form>
                                        </div>
                                    </div><!-- /.col-lg-6 -->
                                </div><!-- /.row -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row projects-holder">
                    <div class="col-md-4 col-sm-6">
                        <div class="project-item" tool="time">
                            <img src="/static/cms/img/1.jpg" alt="">
                            <div class="project-hover">
                                <div class="inside">
                                    <h5><a href="javascript:void(0);">日期、时间 工具</a></h5>
                                    <p>
                                        时间和时间戳之间的转换和时间戳生成工具，PHP常用的日期函数用法。
                                        <a href="#">详细</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <div class="project-item" tool="pinyin">
                            <img src="/static/cms/img/2.jpg" alt="">
                            <div class="project-hover">
                                <div class="inside">
                                    <h5><a href="javascript:void(0);">拼音</a></h5>
                                    <p>
                                        汉字转为拼音，所有汉字集合。
                                        <a href="#">详细</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <div class="project-item" tool="password">
                            <img src="/static/cms/img/3.jpg" alt="">
                            <div class="project-hover">
                                <div class="inside">
                                    <h5><a href="javascript:void(0);">密码生成</a></h5>
                                    <p>
                                         生成随机密码
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <div class="project-item">
                            <img src="/static/cms/img/4.jpg" alt="">
                            <div class="project-hover">
                                <div class="inside">
                                    <h5><a href="javascript:void(0);">虚位以待</a></h5>
                                    <p>敬请期待.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <div class="project-item">
                            <img src="/static/cms/img/5.jpg" alt="">
                            <div class="project-hover">
                                <div class="inside">
                                    <h5><a href="javascript:void(0);">虚位以待</a></h5>
                                    <p>敬请期待.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <div class="project-item">
                            <img src="/static/cms/img/6.jpg" alt="">
                            <div class="project-hover">
                                <div class="inside">
                                    <h5><a href="javascript:void(0);">虚位以待</a></h5>
                                    <p>敬请期待.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- .projects-holder -->
            </div>
            <hr>

            <!-- ABOUT -->
            <div class="page-section" id="about">
                <div class="row">
                    <div class="col-md-12">
                        <h4 class="widget-title">关于我</h4>
                        <div class="about-image">
                            <textarea class="form-control" style="min-height: 200px;"></textarea>
                        </div>
                        <p>一个在代码中混了十年的老农民，依然找不到方向</p>
                        <hr>
                    </div>
                </div> <!-- #about -->
            </div>

            <div class="row" id="footer">
                <div class="col-md-12 text-center">
                    <p class="copyright-text">Copyright &copy; 2018 gx1727 |  皖ICP备14017708号-3</p>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="/static/cms/js/vendor/jquery-1.10.2.min.js"></script>
<script src="/static/cms/js/lazyload.js"></script>
<script src="/static/cms/js/plugins.js"></script>
<script src="/static/cms/js/main.js"></script>
<script src="/static/cms/js/pinyin.js"></script>
</body>
</html>