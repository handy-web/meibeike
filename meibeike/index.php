<?php

$iweb = dirname(__FILE__)."/lib/iweb.php";
$config = dirname(__FILE__)."/config/config.php";
define( "WB_AKEY" , '1488520935' );
define( "WB_SKEY" , '2adc3e15d238818cb94688cc760414b2' );
define( "WB_CALLBACK_URL" , 'http://www.meibeike.com/index.php?controller=site&action=index_wap' );
define( "WB_AKEY_PC" , '2421379745' );
define( "WB_SKEY_PC" , '77730bf6e1f92a6ec7ec21f2dd95eeea' );
define( "WB_CALLBACK_URL_PC" , 'http://www.meibeike.com/index.php?controller=site&action=yuyue' );
define("CAPTCHA_ID", "6f6d42f2f71ef52137ed53519c1587ef");
define("PRIVATE_KEY", "20a12b13977e3eb47a726b9c1ed05b45");
ini_set('session.gc_maxlifetime', "1800"); 
ini_set("session.cookie_lifetime","1800"); 
require($iweb);
IWeb::createWebApp($config)->run();
?>