



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

 <div class="weui_cells_title">预约手机号 来源</div>
    <div class="weui_cells">

  <?php
	



$con = mysql_connect("192.168.5.101","mbkguanwang","mbkgw2015");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }
  
  
  else
    
    {
      
      
      mysql_select_db("meibeike", $con);
      
      
      

// 从表中提取信息的sql语句 
$strsql="select * from meibeike_laolu"; 
//执行sql查询 
//mysql_select_db($mysql_database,$conn);
//$result=mysql_query($sql);
$result=mysql_db_query("meibeike", $strsql, $con); 
// 获取查询结果 
// 定位到第一条记录 
mysql_data_seek($result, 0); 
// 循环取出记录 


$result = mysql_query("SELECT * FROM meibeike_laolu");

$i = 1;

while($row = mysql_fetch_array($result))
  {
    
    
    ?>
     <div class="weui_cell">
          
            <div class="weui_cell_bd weui_cell_primary">
                <p><?php echo $i.".".$row['tel'];$i++?></p>
            </div>
            <div class="weui_cell_ft"><?php echo $row['nickName'];?></div>
        </div>
        
        <?php 
  
  }

      
      
      
      
      
      
      
    }

mysql_close($con);




	
	
	
	?>