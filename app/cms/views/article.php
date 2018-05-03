<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $article['title']; ?>_第一房车</title>
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
            <?php lanmu($article['lanmu'] ? $article['lanmu']['name']: ''); ?>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container-fluid -->
    </nav>
    </div>
</header>
<div class="clearfix"></div>

<section class="container">
    <section>
        <section class="col-xs-12 col-md-8 arc bian p1em">
            <ol class="breadcrumb">
                <li><a href="/">主页</a></li>
                <?php if($article['lanmu']){?>
                <li><a href="/<?php echo $article['lanmu']['name']; ?>"><?php echo $article['lanmu']['title']; ?></a></li>
                <?php }?>
            </ol>
            <h1 class="text-center"><?php echo $article['title']; ?></h1>
            <section class="text-center post-meta"><span class="author">作者：<a href="https://www.bootcss.com/" target="_blank"><?php echo $article['editor']; ?></a></span> •
                <time class="post-date" datetime="<?php echo $article['date']; ?>" title="<?php echo $article['date']; ?>"><?php echo $article['date']; ?></time>
            </section>

            <blockquote><p class="lead"><?php echo $article['abstract']; ?></p></blockquote>

            <?php echo $article['page'][$article['index'] - 1]; ?>
            <div class="clearfix"></div>
            <ul class="text-right listtag">
                <?php if ($article['tags']) {
                    foreach ($article['tags'] as $tag_name => $tag_title) { ?>
                        <li class="label label-info"><a href="/<?php echo $tag_name; ?>"><?php echo $tag_title; ?></a></li>
                    <?php }
                } ?>
            </ul>

            <div class="text-center">
                <?php paging('/' . $article['code'] . '_', $article['index'], $article['sum']); ?>
            </div>
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
            <?php top(5, 'new'); ?>
        </section>
    </section>
</section>


<div class="clearfix"></div>
<!-- footer -->
<?php footer(); ?>
<!-- footer end -->
</body>
</html>