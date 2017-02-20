<?php return array (
  'HOST' => 'www.meibeike.com',
  'logs' =>
  array (
    'path' => '/home/logs/gw_web',
    'type' => 'file',
  ),
  'DB' =>
  array (
    'type' => 'mysqli',
    'tablePre' => 'mbk_',
    'read' =>
    array (
      0 =>
      array (
        'host' => '192.168.2.51',
        'user' => 'root',
        'passwd' => '123456',
        'name' => 'meibeike',
      ),
    ),
    'write' =>
    array (
      'host' => '192.168.2.51',
      'user' => 'root',
      'passwd' => '123456',
      'name' => 'meibeike',
    ),
  ),
  'interceptor' =>
  array (
    0 => 'themeroute@onCreateController',
    1 => 'layoutroute@onCreateView',
    //2 => 'hookCreateAction@onCreateAction',
    //3 => 'hookFinishAction@onFinishAction',
  ),
  'langPath' => 'language',
  'viewPath' => 'views',
  'skinPath' => 'skin',
  'classes' => 'classes.*',
  'rewriteRule' => 'pathinfo',
  'theme' =>
  array (
    'pc' => 'default',
    'mobile' => 'default',
  ),
  'skin' =>
  array (
    'pc' => 'default',
    'mobile' => 'default',
  ),
  'timezone' => 'Etc/GMT-8',
  'upload' => 'upload',
  'dbbackup' => 'backup/database',
  'safe' => 'cookie',
  'lang' => 'zh_sc',
  'debug' =>false,
  'configExt' =>
  array (
    'site_config' => 'config/site_config.php',
  ),
  'encryptKey' => '975dfa9c72d600f3f221df24e5270e1d',
  'safeLevel' => 'normal',
  'tradeModel' => '1',
  'wx_ini'=>array(
      'appid' =>'wxc25b0878108a677f',
      'appsecrect' => '4d66c5819b6a4c330a66035c114e9dd9',
      'redirect_uri' => 'http://dev.meibeike.com'
  ),
)?>
