<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
    <ul class="nav navbar-nav navbar-right">
        <?php foreach($lanmu as $lanmu_item){?>
            <li <?php if($lanmu_item['cs_name'] === $current_lanmu) { ?>class="active"<?php } ?>><a href="/<?php echo $lanmu_item['cs_name'];?>"><?php echo $lanmu_item['cs_title'];?></a></li>
        <?php } ?>
    </ul>
</div>