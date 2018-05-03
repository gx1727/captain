<div class="bian p1em">
    <div class="clearfix"></div>
    <div class="or-spacer">
        <div class="mask"></div>
        <span><i>最新</i></span>
    </div>
    <div class="right_list">
        <ul class="list-unstyled">
            <?php if ($articles) {
                foreach ($articles as $index => $article) { ?>
                    <li><em><?php echo $index + 1; ?></em><a href="/<?php echo $article['code']; ?>"> <?php echo $article['title']; ?></a></li>
                <?php }
            } ?>
        </ul>
    </div>
</div>
<div class="bian p1em mt20">
    <div class="clearfix"></div>
    <div class="or-spacer">
        <div class="mask"></div>
        <span><i>展会</i></span>
    </div>
    <div class="zhanhui">
        <?php $refit_articles = get_articles('2','chezhan', '', 'recommend'); ?>
        <?php if($refit_articles) {
            foreach($refit_articles as $index => $article) {
                if($index >= 1) {
                    break;
                }
                ?><a href="/<?php echo $article['code'];?>">
                        <img src="<?php echo $article['img'];?>?imageView2/1/w/320/h/180/format/jpg/q/90" alt="<?php echo $article['title'];?>">
                        <p><?php echo mb_substr($article['title'], 0, 30);?></p>
                    </a>
            <?php }
        } ?>
    </div>
</div>
<div class="bian p1em mt20">
    <div class="clearfix"></div>
    <div class="or-spacer">
        <div class="mask"></div>
        <span><i>视频</i></span>
    </div>
    <div class="zhanhui">
        <?php $refit_articles = get_articles('2','video', '', 'recommend'); ?>
        <?php if($refit_articles) {
            foreach($refit_articles as $index => $article) {
                if($index >= 1) {
                    break;
                }
                ?><a href="/<?php echo $article['code'];?>">
                        <img src="<?php echo $article['img'];?>?imageView2/1/w/320/h/180/format/jpg/q/90" alt="<?php echo $article['title'];?>">
                        <p><?php echo mb_substr($article['title'], 0, 30);?></p>
                    </a>
            <?php }
        } ?>
    </div>
</div>
<div class="bian p1em mt20">
    <div class="clearfix"></div>
    <div class="or-spacer">
        <div class="mask"></div>
        <span><i>荐车</i></span>
    </div>
    <div class="zhanhui">
        <?php $refit_articles = get_articles('2','news', '', 'recommend'); ?>
        <?php if($refit_articles) {
            foreach($refit_articles as $index => $article) {
                if($index >= 1) {
                    break;
                }
                ?><a href="/<?php echo $article['code'];?>">
                        <img src="<?php echo $article['img'];?>?imageView2/1/w/320/h/180/format/jpg/q/90" alt="<?php echo $article['title'];?>">
                        <p><?php echo mb_substr($article['title'], 0, 30);?></p>
                    </a>
            <?php }
        } ?>
    </div>
</div>