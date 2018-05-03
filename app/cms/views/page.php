<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $lanmu['title']; ?>_第一房车</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <script src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
    <script src="/static/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="/static/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="/static/css/01rv.css" crossorigin="anonymous">
</head>
<body>
<header>
    <nav class="navbar navbar-default">
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
                <a class="navbar-brand" style="padding:0 10px 0 4vw;" href="http://www.01rv.com"><img alt="Brand" style="max-width:100px;" src="/static/img/sitelogo.png"></a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <?php lanmu(); ?>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container-fluid -->

        <div class="topnav" style="display: none">
            <ul class="topnav_m right " style="display: none">
                <li><a href="#">自行式A型房车</a></li>
                <li><a href="#">自行式B型房车</a></li>
                <li><a href="#">自行式C型房车</a></li>
                <li><a href="#">自行式D型房车</a></li>
                <li role="separator" class="divider"></li>
                <li><a href="#">拖挂式A型房车</a></li>
                <li><a href="#">拖挂式B型房车</a></li>
                <li><a href="#">拖挂式C型房车</a></li>
                <li><a href="#">拖挂式D型房车</a></li>
            </ul>
        </div>
    </nav>
    </div>
</header>
<div class="clearfix"></div>

<section class="container">
    <section class="row">
        <section class="col-xs-12 col-md-8 bian p1em">
            <?php if ($articles) {
                foreach ($articles as $article) { ?>
                    <div class="listline">
                        <div class="col-xs-12 col-md-6">
                            <a href="/<?php echo $article['code']; ?>">
                                <img src="<?php echo $article['img']; ?>?imageView2/1/w/330/h/200/format/jpg/q/90"  alt="<?php echo $article['title']; ?>">
                            </a>
                        </div>
                        <div class="col-xs-12 col-md-6">
                            <p><a href="/<?php echo $article['code']; ?>"><h4><?php echo $article['title']; ?></h4></a></p>
                            <p class="listdiv"><?php echo mb_substr($article['abstract'], 0, 88).'...'; ?></p>
                            <ul class="text-left listtag">
                                <?php if ($article['tags']) {
                                    foreach ($article['tags'] as $tag_name => $tag_title) { ?>
                                        <li><a href="/<?php echo $tag_name; ?>"><?php echo $tag_title; ?></a></li>
                                    <?php }
                                } ?>
                            </ul>
                            <div class="clearfix"></div>
                            <p class="text-right rq">日期：<?php echo $article['date']; ?></p>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                <?php }
            } ?>

            <div class="clearfix"></div>
            <div class="text-center"> <?php paging('/' . $article_param['name'] . '/', $index, $sum); ?></div>
            <div class="clearfix"></div>

        </section>

        <section class="col-xs-12 col-md-4">
            <?php $zt_fengmian = get_articles('2', 'dazhuanti|nbzt', 'fengmian', 1); ?>
            <?php foreach($zt_fengmian as $article){?>
                <div class="thumbnail bian">
                    <a href="/<?php echo $article['url']; ?>"><img style="height: 200px;width: 100%;display: block;" src="<?php echo $article['img'];?>" alt="<?php echo $article['title'];?>"></a>
                    <div class="caption">
                        <h4><a href="/<?php echo $article['url']; ?>"><?php echo $article['title'];?></a></h4>
                        <p><?php echo $article['abstract'];?></p>
                    </div>
                </div>
            <?php }?>

            <?php top(5, 'new');?>
        </section>
    </section>
</section>
<!-- footer -->
<?php footer(); ?>
<!-- footer end -->
</body>
</html>