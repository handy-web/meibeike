<!DOCTYPE html>
<html lang="zh-cmn-Hans">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>免费预约用户的电话和来源</title>
    <link rel="stylesheet" href="images/weui.css"/>
    <link rel="stylesheet" href="images/example.css"/>
</head>
<body ontouchstart>

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
      
      $r1 = mysql_query("select count(id) num from `meibeike_laolu`");      
      $total = mysql_fetch_assoc($r1);
      $total_num = $total['num'];

      $str = date('Y-m-d',time())." 00:00:00" ;     
      $starttime = strtotime($str);
      $time = time();      
      $r2 = mysql_query("select count(id) num from `meibeike_laolu` where timeYuyue >= {$starttime} and timeYuyue <= {$time}");
      $dayTotal = mysql_fetch_assoc($r2);  
      $day_num = $dayTotal['num'];

      // 从表中提取信息的sql语句
      //$strsql="select * from meibeike_laolu";
      //执行sql查询
      //mysql_select_db($mysql_database,$conn);
      //$result=mysql_query($sql);
      //$result=mysql_db_query("meibeike", $strsql, $con);
      // 获取查询结果
      // 定位到第一条记录
      //mysql_data_seek($result, 0);
      // 循环取出记录
?>
     <div class="weui_cells_title" style="color:#000;font-size:24px;font-weight:bold">总预约:<em><?php echo $total_num; ?></em>&nbsp;&nbsp;当天预约:<em><?php echo $day_num;?></em></div>
     <div class="weui_cells_title">日期  &nbsp;&nbsp;预约数</div>
     <div class="weui_cells">
<?php 
$result = mysql_query("SELECT  DATE_FORMAT(from_unixtime(timeYuyue),'%Y-%m-%d') as day,count(id) as day_num FROM meibeike_laolu group by day order by day asc",$con);
while($row = mysql_fetch_assoc($result))
{   if($row['day'] != '1970-01-01' && !empty($row['day'])){
        echo "<div class='weui_cell'><div class='weui_cell_bd weui_cell_primary'>{$row['day']}</div><div class='weui_cell_ft'>{$row['day_num']}</div></div>";
    }
}

}
mysql_free_result($result);
mysql_free_result($r1);
mysql_free_result($r2);
mysql_close($con);

?>

</div>
</body>
</html>