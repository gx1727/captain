<!DOCTYPE HTML>
<html>
<head>
    <title>gx1727</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!--[if lte IE 8]><script src="/static/cms/js/ie/html5shiv.js"></script><![endif]-->
    <link rel="stylesheet" href="/static/cms/css/main.css" />
    <!--[if lte IE 8]><link rel="stylesheet" href="/static/cms/css/ie8.css" /><![endif]-->
</head>
<body>
<div id="content">
    <div class="inner">
        <?php foreach($articles as $article) { ?>
            <article class="box post post-excerpt">
                <header>
                    <h2><a href="/<?php echo $article['code'] ?>" style="font-size: 70%;"><?php echo $article['title'] ?></a></h2>

                    <?php if($article['tags']) {
                        foreach($article['tags']as $name => $title){ ?>
                            <span class="tag"><a href="/<?php echo $name;?>"><?php echo $title;?></a></span>
                        <?php }
                    }  ?>

                </header>
                <div class="info" style="color: #999;text-align: center;">
                        <span class="date" style="margin-bottom: 0;">
                            <span class="month"><?php echo date('m', $article['ptime']); ?>月</span>
                            <span class="day"><?php echo date('d', $article['ptime']); ?></span>
                            <span class="year">, <?php echo date('Y', $article['ptime']); ?></span>
                        </span>
                    <?php echo date('Y', $article['ptime']); ?>
                </div>
                <a href="/<?php echo $article['code'] ?>" class="image featured"><img src="<?php echo $article['img'] ?>" alt="<?php echo $article['title'] ?>" /></a>
                <p><?php echo $article['abstract'] ?></p>
            </article>
        <?php } ?>
        <div class="pagination">
            <?php paging('/' . $article_param['name'] . '/', $index, $sum); ?>
        </div>
    </div>


</div>

<!-- Sidebar -->
<div id="sidebar">

    <!-- Logo -->
    <h1 id="logo"><a href="/">GX1727</a></h1>

    <!-- Nav -->
    <nav id="nav">
        <ul>
            <li class="current"><a href="/">主页</a></li>
            <li><a href="/tools">工具</a></li>
            <li><a href="/">杂项</a></li>
            <li><a href="/aboutme">关于我</a></li>
        </ul>
    </nav>

    <!-- Search -->
    <section class="box search">
        <form method="post" action="#">
            <input type="text" class="text" name="search" placeholder="输入 ..." />
        </form>
    </section>

    <!-- Text -->
    <section class="box text-style1">
        <div class="inner">
            <p>
                <strong>每日一语:</strong><br/>
                恢弘志士之气，不宜妄自菲薄。
            </p>
        </div>
    </section>

    <!-- Recent Posts -->
    <section class="box recent-posts">
        <header>
            <h2>分类</h2>
        </header>
        <ul>
            <li><a href="/suiji">随记</a></li>
        </ul>
    </section>

    <!-- Copyright -->
    <ul id="copyright">
        <li>&copy; gx1727 <p>皖ICP备14017708号-3</p></li><li>Design: <a href="http://html5up.net">HTML5 UP</a></li>
    </ul>

</div>

<!-- Scripts -->
<script src="/static/cms/js/jquery.min.js"></script>
<script src="/static/cms/js/skel.min.js"></script>
<script src="/static/cms/js/util.js"></script>
<!--[if lte IE 8]><script src="/static/cms/js/ie/respond.min.js"></script><![endif]-->
<script src="/static/cms/js/main.js"></script>

</body>
</html>