<?php if ($index > 2) { ?>
    <a href="<?php echo $base . ($index - 1); ?>" class="button previous">上一页</a>
<?php } else if ($index == 2) { ?>
    <a href="<?php echo rtrim($base, '_'); ?>" class="button previous">上一页</a>
<?php } ?>

<div class="pages">
    <?php for ($i = 1; $i <= $sum; $i++) { ?>
        <a href="<?php echo $i == 1 ? (rtrim($base, '_')) : ($base . $i); ?>" <?php if ($i == $index){ ?>class="active"<?php } ?>><?php echo $i; ?></a>
    <?php } ?>
</div>
<?php if ($index < $sum) { ?>
    <a href="<?php echo $base . ($index + 1); ?>" class="button next">下一页</a>
<?php } ?>

