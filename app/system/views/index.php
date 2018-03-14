<?php

//echo is_php(5.4);
//
//echo $hello;

//echo yes_no(1);
//print_r($user_list);
//echo web_url();
?>

<a href="<?php echo web_url();?>">dd</a>
<div>heloo</div>
<div>heloo, world</div>
<div id="msg"></div>
<div id="msg_md5"></div>
<div id="msg_1"></div>
<div id="msg_md5_1"></div>


<script src="<?php static_domain();?>/static/system/jquery.min.js" type="text/javascript"></script>
<script src="<?php static_domain();?>/static/system/captain.js" type="text/javascript"></script>
<script src="<?php static_domain();?>/static/system/jsbn.js"></script>
<script src="<?php static_domain();?>/static/system/jsbn2.js"></script>
<script src="<?php static_domain();?>/static/system/rsa.js"></script>
<script src="<?php static_domain();?>/static/system/sha1.js"></script>
<script src="<?php static_domain();?>/static/system/md5.min.js"></script>
<script>
    $(function(){
        $('#msg').html(secret('heloo'));
        $('#msg_md5').html(md5('heloo'));

        $('#msg_1').html(secret('heloo', 'world'));
        $('#msg_md5_1').html(md5('heloo')+ 'world');
    })
</script>
