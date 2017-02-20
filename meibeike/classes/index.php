<?php

$iweb = dirname(__FILE__)."/lib/iweb.php";
$config = dirname(__FILE__)."/config/config.php";
define( "WB_AKEY" , '686853336' );
define( "WB_SKEY" , '19c7105bd84f959983754d2f0ba43a27' );
define( "WB_CALLBACK_URL" , 'http://www.meibeike.com/index.php?controller=site&action=index_1' );
ini_set('session.gc_maxlifetime', "1800"); //
ini_set("session.cookie_lifetime","1800"); //
require($iweb);
IWeb::createWebApp($config)->run();
?>