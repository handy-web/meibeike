

<?php


$tel = $_POST["tel"];
$fr = $_POST["fr"];

$key = $_POST["key"];

if($fr == "")

{ $fr = "direct";}


if($tel == "")

{ $tel = "13500001234";}

?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns:wb="http://open.weibo.com/wb">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<title>提交成功！</title>
<link rel="stylesheet" href="images/index_new.css">
<link rel="stylesheet" type="text/css" href="images/default.css">
<link rel="stylesheet" href="images/red.css">
<script type="text/javascript" charset="UTF-8" src="images/jquery-1.9.0.min.js"></script>
<script type="text/javascript" charset="UTF-8" src="images/validate.js"></script>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=8">
</head>
<body style="margin: 0px auto; zoom: 1;">
<div class="" style="display: none; position: absolute;">
  <div class="aui_outer">
    <table class="aui_border">
      <tbody>
        <tr>
          <td class="aui_nw"></td>
          <td class="aui_n"></td>
          <td class="aui_ne"></td>
        </tr>
        <tr>
          <td class="aui_w"></td>
          <td class="aui_c"><div class="aui_inner">
              <table class="aui_dialog">
                <tbody>
                  <tr>
                    <td colspan="2" class="aui_header"><div class="aui_titleBar">
                        <div class="aui_title" style="cursor: move; display: block;"></div>
                        <a class="aui_close" href="javascript:/*加载完成*/;" style="display: block;">×</a></div></td>
                  </tr>
                  <tr>
                    <td class="aui_icon" style="display: none;"><div class="aui_iconBg" style="background: none;"></div></td>
                    <td class="aui_main" style="width: auto; height: auto;"><div class="aui_content" style="padding: 20px 25px;"></div></td>
                  </tr>
                  <tr>
                    <td colspan="2" class="aui_footer"><div class="aui_buttons" style="display: none;"></div></td>
                  </tr>
                </tbody>
              </table>
            </div></td>
          <td class="aui_e"></td>
        </tr>
        <tr>
          <td class="aui_sw"></td>
          <td class="aui_s"></td>
          <td class="aui_se" style="cursor: se-resize;"></td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
<iframe id="sina_anywhere_iframe" style="display: none;"></iframe>
<input type="hidden" value="index_wap" id="hidden_menu_name">
<input type="hidden" value="" id="hidden_user_id">
<input type="hidden" value="" id="act">
<div class="wrapper_all">
<div class="header" style="display: none;">
  <div class="header_content"> <a href="http://www.meibeike.com/site/" class="logo" style="padding-top:5px;overflow:visible;"> <img src="images/logo.png" style="border: 0px;"> </a>
    <label class="logo_label"></label>
  
  </div>
  <style type="text/css">
                </style>
  <div class="header-menu-wrapper" style="height: 40px; line-height: 40px; border: 0px;">
    <div class="header-container">
      <div class="account">
        <div class="not_login"> <a id="login" class="normal-link" href="http://www.meibeike.com/simple/login?callback=/site/index_3/">登录</a> &nbsp;|&nbsp; <a id="register" class="normal-link" href="http://www.meibeike.com/simple/reg_phone?callback=/site/index_3/">注册</a> </div>
      </div>
    </div>
    <div class="header-shadow"></div>
  </div>
</div>
<link rel="stylesheet" type="text/css" href="images/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="images/default_wap.css">
<!--  <script type = "text/javascript" >  
          function getRTime(){
        var EndTime= new Date('2016/06/20 10:00:00'); 
        var NowTime = new Date();
        var t =EndTime.getTime() - NowTime.getTime();
        var d=Math.floor(t/1000/60/60/24);
        var h=Math.floor(t/1000/60/60%24);
        var m=Math.floor(t/1000/60%60);
        var s=Math.floor(t/1000%60);

        document.getElementById("timer").innerHTML = '仅剩<span>'+d+'</span>天<span>'+h+'</span>时<span>'+m+'</span>分<span>'+s+'</span>秒';

    }
    setInterval(getRTime,1000); 
        </script> -->
<div id="wrapper" style="max-width: 640px; min-width: 320px; margin: 0 auto;overflow: hidden;">
<div class="container">
  <div class="top"><img style="margin-right:5%" src="images/logo.png">
    <wb:login-button type="3,2" onlogin="login" onlogout="logout">
      <!--  <div class="WB_loginButton WB_widgets"><a href="javascript:void(0);"><img src="images/loginButton_24.png"></a></div> -->
    </wb:login-button>
    <!--  <a style="float:right;font-size:16px;font-weight:bold;color:#e51626" href="http://www.meibeike.com/site/index_wap_first">云棒详情</a>  -->
  </div>
<?php
//$con = mysql_connect("192.168.5.101","mbkguanwang","mbkgw2015");
$con = mysql_connect("localhost","root","");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }
  else
    {
      mysql_select_db("meibeike", $con);
      $sql1 = "select count(id) num from `meibeike_laolu` where tel = {$tel}";
      $s1 = mysql_query($sql1,$con);
      $data = mysql_fetch_assoc($s1);
      if(is_array($data) && $data['num']>0){
          echo "<script>alert('您已成功预约');</script>";
      }else{
          $timeYuyue = time();
          $sql2 = "INSERT INTO meibeike_laolu (tel, fr, nickName, timeYuyue) VALUES ('".$tel."', '".$fr."', '".$key."', '".$timeYuyue."')";          
          mysql_query($sql2);
          $res = mysql_insert_id();
              if(!empty($res)){
                  echo "<script>alert('您已成功预约，我们会第一时间与您联系');</script>";
              }else{
                  echo "<script>alert('预约失败');</script>";
              }
          }
      }      
   mysql_close($con);
?>
  <p class="headline h3" style="margin-top:1%; margin:15px;">&nbsp;</p>
  <p style="margin:20px;color:#e51626" class="headline h2">预约成功！</p>
  <p class="headline h4" style="color: #666666;">我们会第一时间与您联系</p>
  <p class="headline">
    <!-- a href="#detail">观看云棒视频</a>&gt;&nbsp;&nbsp;-->
     <a href="http://www.meibeike.com/site/sy" style="text-decoration:underline">进入官网</a> </p>
</div>
<!-- div class="container" style="margin: 20px 0;">
          <p id="timer" class="headline hh">仅剩<span>20</span>天<span>12</span>时<span>33</span>分<span>59</span>秒<p>
      </div-->



      <script>
var _hmt = _hmt || [];
(function() {
  var hm = document.createElement("script");
  hm.src = "//hm.baidu.com/hm.js?dd6ea620e2361b9d88ebaa9a60eed24b";
  var s = document.getElementsByTagName("script")[0]; 
  s.parentNode.insertBefore(hm, s);
})();
</script>



</body>
</html>
