<?php
/**
 * Created by PhpStorm.
 * User: gx1727
 * Date: 2018/4/3
 * Time: 9:24
 */

$guest['/weixin/oauth2'] = 'weixin:captain\weixin\Weixin@oauth2';
$guest['/weixin/*'] = 'weixin:captain\weixin\Weixin@index';
