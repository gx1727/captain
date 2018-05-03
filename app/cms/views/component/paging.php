<nav aria-label="Page navigation center-block">
    <ul class="pagination ">
        <?php if ($index > 2) { ?>
            <li>
                <a href="<?php echo $base . ($index - 1); ?>" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
        <?php } else if ($index == 2) { ?>
            <li>
                <a href="<?php echo rtrim($base, '_'); ?>" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
        <?php } ?>

        <?php for ($i = 1; $i <= $sum; $i++) { ?>
            <li <?php if ($i == $index){ ?>class="active"<?php } ?>><a href="<?php echo $i == 1 ? (rtrim($base, '_')) : ($base . $i); ?>"><?php echo $i; ?></a></li>
        <?php } ?>
        <?php if ($index < $sum) { ?>
            <li>
                <a href="<?php echo $base . ($index + 1); ?>" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        <?php } ?>
    </ul>
</nav>