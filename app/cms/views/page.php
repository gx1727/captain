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
            <li><a href="/#top"><i class="fa fa-globe"></i>首页</a></li>
            <li><a href="/#projects"><i class="fa fa-paperclip"></i>工具</a></li>
            <li><a href="/#about"><i class="fa fa-pencil"></i>关于我</a></li>
        </ul>
        <ul>
            <li><a href="/blog"><i class="fa fa-link"></i>BLOG</a></li>
        </ul>
    </div> <!-- .main-navigation -->
    <div class="social-icons">
    </div> <!-- .social-icons -->
</div> <!-- .sidebar-menu -->

<!-- MAIN CONTENT -->
<div class="main-content">
    <div class="fluid-container">
        <div class="content-wrapper">
            <!-- POSTS -->
            <?php foreach($articles as $article) { ?>

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

            <?php paging('/' . $article_param['name'] . '/', $index, $sum); ?>

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
<script>
    $(function() {
        $("img.lazyload").lazyload();
    });
</script>
</body>
</html>