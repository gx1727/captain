<?php

//echo is_php(5.4);
//
//echo $hello;

//echo yes_no(1);
//print_r($user_list);
//echo web_url();
?>
<a href="<?php echo web_url();?>">dd</a>
<div id="msg"></div>
<div id="msg_md5"></div>
<div id="msg_1"></div>
<div id="msg_md5_1"></div>

<script src="<?php static_domain();?>/static/system/jquery.min.js" type="text/javascript"></script>
<script src="<?php static_domain();?>/static/system/captain_secret.js" type="text/javascript"></script>
<script>
    $(function(){
        $('#msg').html(get_secret('heloo'));
        $('#msg_md5').html(md5('heloo'));

        $('#msg_1').html(get_secret('heloo', 'world'));
        $('#msg_md5_1').html(md5('heloo')+ 'world');
    })
</script>
