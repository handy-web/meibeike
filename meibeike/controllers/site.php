﻿<?php

/**
 * @copyright Copyright(c) 2011 jooyea.cn
 * @file site.php
 * @brief
 * @author webning
 * @date 2011-03-22
 * @version 0.6
 * @note
 */

/**
 * @brief Site
 * @class Site
 * @note
 */

require_once $_SERVER['DOCUMENT_ROOT'] . '/plugins/'.'oauth/sina/API/saetv2.ex.class.php';
class Site extends IController {

    public $layout = 'site_new';
 

    function init() {
        CheckRights::checkUserRights();
        
        //获取url来源
        $web_from = reserve::getFromSite();
        
    }

    function index() {
    	$this->thirdLogin();
		$this->menu_name = "index";
        $siteConfigObj = new Config("site_config");
        $site_config = $siteConfigObj->getInfo();
        $index_slide = isset($site_config['index_slide']) ? unserialize($site_config['index_slide']) : array();
        $this->index_slide = $index_slide;

        //商品数据装载
        //卖家ID为0
        $seller_id = 0;
        $goodsObj = new IModel("goods");
        $goods = $goodsObj->getObj("seller_id = $seller_id");
        $goods_id = $goods["id"];
        $photoObj = new IQuery("goods_photo as p");
        $photoObj->join = " inner join goods_photo_relation as r on r.goods_id = $goods_id and p.id = r.photo_id";
        $photoObj->fields = " p.img";
        $imgs = $photoObj->find();
        $goods["ad_img"] = $imgs;
        
        
        $this->setRenderData($goods);
        //var_dump($goods);
        /*$showmsg = IReq::get('showmsg');
        if($showmsg){
            Util::showMessage($showmsg);           
        }*/
        if($this->check_wap()){
            //$this->redirect('/wap/vip_buy');
            $this->redirect('/wap/yuyue');
        }else{
            $this->redirect('yuyue_new');
            //$this->redirect('vip_buy');
        }
        //$this->redirect('index_3');
    }
    
    private function thirdLogin(){
    	if(isset($_REQUEST['state']) && isset($_REQUEST['code'])){
    		$file = $_SERVER['DOCUMENT_ROOT'].'/config/config.php';
    		$wx_ini = require($file);
    		$wx_config = $wx_ini['wx_ini'];
    		$parms['code'] = $_REQUEST['code'];
    		$wxObj = new WeiXin ($wx_config);
    		try{
    			$token = $wxObj->getAccessToken($parms);
    		}catch(Exception $e){
    			echo 'login fail';
    			//var_dump($e);
    			exit;
    		}
    		if(isset($token['access_token']) && $token['access_token']!=''){
    			$obj = new IQuery("user u");
    			$account_type = 8;//微信登录
    			$obj->join = "inner join mbk_third_user tu on tu.uid = u.id";
    			$obj->where = "u.type = {$account_type} and tu.unionid = '{$token['openid']}'";
    			$obj->fields = "tu.unionid,u.password";
    			$res = $obj->find();
    			if(!empty($res[0]) && is_array($res)){
    				$login_info = $res[0]['unionid'];
    				$password = $res[0]['password'];
    				if (Db_Meicloud::userLogin($login_info, $password, $account_type) || CheckRights::isThirdValidUser($login_info, $password,$account_type)){
    					$userRow = CheckRights::isThirdValidUser($login_info,$password,$account_type);
    					$this->loginAfter($userRow);
    				}
    			}else{
    				$openId = $token['openid'] ;//微信调用access_token接口返回信息用户标识是openid
    				$arguments['access_token'] = $token['access_token'];
    				$arguments['openid'] = $openId;
    				$userInfo = $wxObj->getUserInfo($arguments);
    				$flag = $this->third_register($openId,$account_type,$userInfo['nickname']);
    				if(is_array($flag)){
    					ISafe::set('username', $flag['user_name']);
    					ISafe::set('user_id', $flag['user_id']);
    					ISafe::set('user_pwd', $flag['password']);
    					ISafe::set('type', $flag['type']);
    					$third_user_info['uid'] = $flag['user_id'];
    					$third_user_info['unionid'] = $openId;
    					$third_user_info['account_type'] = $flag['type'];
    					$device_type = $this->check_device_type();
    					$third_user_info['device_type'] = $device_type ;
    					$third_user_info['user_source'] = $this->get_referersite();
    					$tUser = new IModel('third_user');
    					$tUser->setData($third_user_info);
    					$tUser->add();
    				}else{
    					echo '<script>alert("登录失败")</script>';
    				}
    			}
    		}
    		//微信登录
    	}else if(isset($_REQUEST['code'])){
    		//微博登录
    		$keys = array();
    		$keys['code'] = $_REQUEST['code'];
    		$keys['redirect_uri'] = WB_CALLBACK_URL_PC;
    		$o = new SaeTOAuthV2( WB_AKEY_PC , WB_SKEY_PC );
    		try{
    			$token = $o->getAccessToken( 'code', $keys ) ;
    		}catch(Exception $e){
    			echo 'login fail';
    			//var_dump($e);
    			exit;
    		}
    		if(isset($token['access_token']) && $token['access_token']!=''){
    			$client_id = Probability::get_client_ip();
    			ISession::set('token',$token);
    			setcookie( 'weibojs_'.$client_id, http_build_query($token));
    			$obj = new IQuery("user u");
    			$account_type = 9;//微博登录
    			$obj->join = "inner join mbk_third_user tu on tu.uid = u.id";
    			$obj->where = "u.type = {$account_type} and tu.unionid = '{$token['uid']}'";
    			$obj->fields = "tu.unionid,u.password";
    			$res = $obj->find();
    			if(!empty($res[0]) && is_array($res)){
    				$login_info = $res[0]['unionid'];
    				$password = $res[0]['password'];
    				if (Db_Meicloud::userLogin($login_info, $password, $account_type) || CheckRights::isThirdValidUser($login_info, $password,$account_type)){
    					$userRow = CheckRights::isThirdValidUser($login_info,$password,$account_type);
    					$this->loginAfter($userRow);
    				}
    				//if (($r1 = Db_Meicloud::userLogin($login_info, $password, $account_type)) || ($r2 = CheckRights::isThirdValidUser($login_info, $password,$account_type))) {
    				//$userRow = CheckRights::isThirdValidUser($login_info,$password);
    				//$this->loginAfter($userRow);
    				//} else {
    				// echo '<script>alert("账号信息有误")</script>';exit;
    				//echo "<script>alert('{$r2}')</script>";exit;
    				//}
    			}else{
    				$openId = $token['uid'] ;
    				$flag = $this->third_register($openId,$account_type);
    				if(is_array($flag)){
    					ISafe::set('username', $flag['user_name']);
    					ISafe::set('user_id', $flag['user_id']);
    					ISafe::set('user_pwd', $flag['password']);
    					ISafe::set('type', $flag['type']);
    					$third_user_info['uid'] = $flag['user_id'];
    					$third_user_info['unionid'] = $openId;
    					$third_user_info['account_type'] = $flag['type'];
    					$device_type = $this->check_device_type();
    					$third_user_info['device_type'] = $device_type ;
    					$third_user_info['user_source'] = $this->get_referersite();
    					$tUser = new IModel('third_user');
    					$tUser->setData($third_user_info);
    					$tUser->add();
    				}else{
    					echo '<script>alert("登录失败")</script>';
    				}
    			}
    		}
    	}
    }
    
function fq_test() {
		$this->menu_name = "fq_test";
        $siteConfigObj = new Config("site_config");
        $site_config = $siteConfigObj->getInfo();
        $index_slide = isset($site_config['index_slide']) ? unserialize($site_config['index_slide']) : array();
        $this->index_slide = $index_slide;

        //商品数据装载
        //卖家ID为0
        $seller_id = 0;
        $goodsObj = new IModel("goods");
        $goods = $goodsObj->getObj("seller_id = $seller_id");
        $goods_id = $goods["id"];
        $photoObj = new IQuery("goods_photo as p");
        $photoObj->join = " inner join goods_photo_relation as r on r.goods_id = $goods_id and p.id = r.photo_id";
        $photoObj->fields = " p.img";
        $imgs = $photoObj->find();
        $goods["ad_img"] = $imgs;
        
        
        $this->setRenderData($goods);
        //var_dump($goods);
        /*$showmsg = IReq::get('showmsg');
        if($showmsg){
            Util::showMessage($showmsg);           
        }*/
      
        $this->redirect('fq_test');
    }

    function index_1() {    
        if (isset($_REQUEST['code'])) {

            $keys = array();
            $keys['code'] = $_REQUEST['code'];
            $keys['redirect_uri'] = WB_CALLBACK_URL;
            $o = new SaeTOAuthV2( WB_AKEY , WB_SKEY );
	     
            $token = $o->getAccessToken( 'code', $keys ) ;
 
            if(isset($token['access_token']) && $token['access_token']!=''){ 
                session_start();
                $access_token = $token['access_token'];
                $_SESSION['access_token'] = $access_token;
                $uid = $token['uid'];
                $Obj = new IModel('member');
                $num = $Obj->query("access_token='{$access_token}' and sina_uid={$uid}","count(*) num");
                if($num['num']<1){
                    $data['access_token'] =   $access_token;
                    $data['sina_uid'] = $uid;
                    $Obj->setData($data);
                    $Obj->add();
                }
            } 
        }        

		$this->menu_name = "index";
        $siteConfigObj = new Config("site_config");
        $site_config = $siteConfigObj->getInfo();
        $index_slide = isset($site_config['index_slide']) ? unserialize($site_config['index_slide']) : array();
        $this->index_slide = $index_slide;

        //商品数据装载
        //卖家ID为0
        $seller_id = 0;
        $goodsObj = new IModel("goods");
        $goods = $goodsObj->getObj("seller_id = $seller_id");
        $goods_id = $goods["id"];
        $photoObj = new IQuery("goods_photo as p");
        $photoObj->join = " inner join goods_photo_relation as r on r.goods_id = $goods_id and p.id = r.photo_id";
        $photoObj->fields = " p.img";
        $imgs = $photoObj->find();
        $goods["ad_img"] = $imgs;


        $this->setRenderData($goods);
        //var_dump($goods);
        /*$showmsg = IReq::get('showmsg');
        if($showmsg){
            Util::showMessage($showmsg);
        }*/

        $this->redirect('index_1');
    }

	function sy() {    
        if (isset($_REQUEST['code'])) {

            $keys = array();
            $keys['code'] = $_REQUEST['code'];
            $keys['redirect_uri'] = WB_CALLBACK_URL;
            $o = new SaeTOAuthV2( WB_AKEY , WB_SKEY );
	     
            $token = $o->getAccessToken( 'code', $keys ) ;
 
            if(isset($token['access_token']) && $token['access_token']!=''){ 
                session_start();
                $access_token = $token['access_token'];
                $_SESSION['access_token'] = $access_token;
                $uid = $token['uid'];
                $Obj = new IModel('member');
                $num = $Obj->query("access_token='{$access_token}' and sina_uid={$uid}","count(*) num");
                if($num['num']<1){
                    $data['access_token'] =   $access_token;
                    $data['sina_uid'] = $uid;
                    $Obj->setData($data);
                    $Obj->add();
                }
            } 
        }        

		$this->menu_name = "index";
        $siteConfigObj = new Config("site_config");
        $site_config = $siteConfigObj->getInfo();
        $index_slide = isset($site_config['index_slide']) ? unserialize($site_config['index_slide']) : array();
        $this->index_slide = $index_slide;

        //商品数据装载
        //卖家ID为0
        $seller_id = 0;
        $goodsObj = new IModel("goods");
        $goods = $goodsObj->getObj("seller_id = $seller_id");
        $goods_id = $goods["id"];
        $photoObj = new IQuery("goods_photo as p");
        $photoObj->join = " inner join goods_photo_relation as r on r.goods_id = $goods_id and p.id = r.photo_id";
        $photoObj->fields = " p.img";
        $imgs = $photoObj->find();
        $goods["ad_img"] = $imgs;

        $goods["callback"] = "/site/sy/";
        $this->setRenderData($goods);
        //var_dump($goods);
        /*$showmsg = IReq::get('showmsg');
        if($showmsg){
            Util::showMessage($showmsg);
        }*/

        $this->redirect('sy');
    }

	function vip_buy() {
		$this->menu_name = "vip_buy";
    
        $siteConfigObj = new Config("site_config");
        $site_config = $siteConfigObj->getInfo();
        $index_slide = isset($site_config['index_slide']) ? unserialize($site_config['index_slide']) : array();
        $this->index_slide = $index_slide;

        //商品数据装载
        //卖家ID为0
        $seller_id = 0;
        $goodsObj = new IModel("goods");
        $goods = $goodsObj->getObj("seller_id = $seller_id");
        $goods_id = $goods["id"];
        
       
        
       
        $goods["callback"] = "/site/vip_buy/";

		$goods['end_time'] = strtotime('2016-09-03 11:00:00');
		$goods['current_time'] = time();
		
        $this->setRenderData($goods);
     

        $this->redirect('vip_buy');
    }
	
	function check_user_power() {
	    
		$userid = IFilter::act(IReq::get('userid'), 'int');
		$goodid = IFilter::act(IReq::get('goodid'), 'int');
		$time=ITime::getDateTime();
		//判断时间
		if (strtotime("2016-09-03 11:00:00")>strtotime($time)) {
		    $i=2;
		} elseif(!$this->user['user_id'])
		{
			$i=4;
		}elseif($userid>0)
		{
			//获取MeiID
			$userObj = new IModel("user");
			$user = $userObj->getObj("id = $userid");
			$meiid = $user["meiid"];

			//获取OrderID
			$appointmentObj = new IModel("appointment");
			$appointment = $appointmentObj->getObj("mei_id = $meiid");
			$order_id = $appointment["order_id"];
			if($order_id<=0) {$i=1;}
			else {
			//获取OrderID
			$vgoods_Obj = new IModel("goods");
			$vgoods = $vgoods_Obj->getObj("id = $goodid");
			$store_nums = $vgoods["store_nums"];
			if($store_nums<=0) {$i=3;} 
			}
		}
		else
		{$i=0;}
		



		if($i==0)
		{
		$result['message'] = "可以抢购";
        $result["code"] = 0;
		}
		if($i==1)
		{
		$result['message'] = "您还没有预约，无法进行抢购";
        $result["code"] = 1;
		}

		if($i==2)
		{
		$result['message'] = "还未到抢购时间哦，请耐心等待！";
        $result["code"] = 1;
		}
		if($i==3)
		{
		$result['message'] = "本次活动已经结束，请下次再参加！";
        $result["code"] = 1;
		}
		if($i==4)
		{
		$result['message'] = "您还没有登录，无法进行抢购";
        $result["code"] = 4;
		}

        echo json_encode($result);
        exit;

		return 0;
		}

    function Activity() {
		$this->menu_name = "index";
        $siteConfigObj = new Config("site_config");
        $site_config = $siteConfigObj->getInfo();
        $index_slide = isset($site_config['index_slide']) ? unserialize($site_config['index_slide']) : array();
        $this->index_slide = $index_slide;

        //商品数据装载
        //卖家ID为0
        $seller_id = 0;
        $goodsObj = new IModel("goods");
        $goods = $goodsObj->getObj("seller_id = $seller_id");
        $goods_id = $goods["id"];
        $photoObj = new IQuery("goods_photo as p");
        $photoObj->join = " inner join goods_photo_relation as r on r.goods_id = $goods_id and p.id = r.photo_id";
        $photoObj->fields = " p.img";
        $imgs = $photoObj->find();
        $goods["ad_img"] = $imgs;


        $this->setRenderData($goods);
        //var_dump($goods);
        /*$showmsg = IReq::get('showmsg');
        if($showmsg){
            Util::showMessage($showmsg);
        }*/

        $this->redirect('Activity');
    }

    
    function index_test2() {
          $v_id=$_POST["v_id"] ;
          $v_purchase_time=$_POST["v_purchase_time"] ;
          $mei_id=$_POST["v_purchase_meiid"] ;
          $v_url="";
          $file = $_FILES['hw_file']['name'];      
          $file_hzz=substr($file, strrpos($file, '.') + 1);
    
          //echo "V_ID: " . $v_id . "<br />"; 
          //echo "用户: " . $mei_id. "<br />"; 
          //echo "图片: " . $file. "<br />"; 
          //echo "图片: " . substr($file, strrpos($file, '.') + 1). "<br />"; 
          //move_uploaded_file($_FILES["hw_file"]["tmp_name"], "upload/".date('YmdHis').rand(10000,99999).".".$file_hzz); 
          //echo "后缀: " . end(explode('.', $file)). "<br />"; 
          
          //echo "V_ID: " . $v_id . "<br />"; 
          //echo "图片: " . $file. "<br />"; 
          
          if ((($_FILES["hw_file"]["type"] == "image/gif")|| ($_FILES["hw_file"]["type"] == "image/jpeg")|| ($_FILES["hw_file"]["type"] == "image/pjpeg"))&& ($_FILES["hw_file"]["size"] < 1000000)) 
          { 
            if ($_FILES["hw_file"]["error"] > 0) 
            { echo "Return Code: " . $_FILES["hw_file"]["error"] . "<br />"; 
              } 
            else 
            { 
                if (file_exists("upload/" .$v_id.".".$file_hzz)) 
                { 
                  move_uploaded_file($_FILES["hw_file"]["tmp_name"], "upload/".$v_id."_1.".$file_hzz); 
                  $v_url="http://www.meibeike.com/"."upload/" . $v_id."_1.".$file_hzz;
                  
                } 
                else 
                { 
				  $file_name_picture=date('YmdHis').rand(10000,99999);
                  //move_uploaded_file($_FILES["hw_file"]["tmp_name"], "upload/huaweipic/" . $_FILES["hw_file"]["name"]); 
                  //move_uploaded_file($_FILES["hw_file"]["tmp_name"], "upload/".$v_id.".".$file_hzz); 
                  move_uploaded_file($_FILES["hw_file"]["tmp_name"], "upload/".$file_name_picture.".".$file_hzz); 
                  $v_url="http://www.meibeike.com/"."upload/" .$file_name_picture.".".$file_hzz;                  
                } 
               
                 echo "Stored in: " . $v_url."_". $v_purchase_time."_". $mei_id."_". $v_id."_". $v_url."<br/>"; 
                //$v_appointment_no=Order_Class::createServiceNum();
                
                $v_user_type=2;
                $v_mei_id=$mei_id;
                $v_appointment_no=$v_mei_id.rand(10000,99999);
                $v_vendor_id=$v_id;
                $v_pic_url=$v_url;
                //echo "Stored in: " . $v_appointment_no."_". $v_user_type."_". $v_mei_id."_". $v_vendor_id."_". $v_pic_url."<br/>"; 
                $temp_1=Db_Meicloud::write_appoint($v_appointment_no,$v_purchase_time,$v_user_type,$v_mei_id,$v_vendor_id, $v_pic_url);
                //echo "Stored in: " . $v_appointment_no."_". $v_user_type."_". $v_mei_id."_". $v_vendor_id."_". $v_pic_url."<br/>"; 
               
                $url = "/site/file_ok/";
                echo "<script language='javascript' type='text/javascript'>";  
                echo "window.location.href='$url'";  
                echo "</script>";  
                
                
            } 
          } 
          else 
          { 
            //echo "无效文件"; 
            $url = "/site/index_3/";
            echo "<script language='javascript' type='text/javascript'>";  
            echo "alert('请上传jpg.png.gif格式的图片且大小不超过1M!');window.location.href='$url'";  
            echo "</script>";  
          } 
    }
	function index_test() {
		$this->menu_name = "index";
        $siteConfigObj = new Config("site_config");
        $site_config = $siteConfigObj->getInfo();
        $index_slide = isset($site_config['index_slide']) ? unserialize($site_config['index_slide']) : array();
        $this->index_slide = $index_slide;

        //商品数据装载
        //卖家ID为0
        $seller_id = 0;
        $goodsObj = new IModel("goods");
        $goods = $goodsObj->getObj("seller_id = $seller_id");
        $goods_id = $goods["id"];
        $photoObj = new IQuery("goods_photo as p");
        $photoObj->join = " inner join goods_photo_relation as r on r.goods_id = $goods_id and p.id = r.photo_id";
        $photoObj->fields = " p.img";
        $imgs = $photoObj->find();
        $goods["ad_img"] = $imgs;


        $this->setRenderData($goods);
        //var_dump($goods);
        /*$showmsg = IReq::get('showmsg');
        if($showmsg){
            Util::showMessage($showmsg);
        }*/

        $this->redirect('index_test');
    }
	
  function index_3() {
		$this->menu_name = "index_3";
		$url_id = IReq::get('id');

		if($url_id==1) {$this->redirect('order_ok');}
        $siteConfigObj = new Config("site_config");
        $site_config = $siteConfigObj->getInfo();
        $index_slide = isset($site_config['index_slide']) ? unserialize($site_config['index_slide']) : array();
        $this->index_slide = $index_slide;

        //商品数据装载
        //卖家ID为0
        $seller_id = 0;
        $goodsObj = new IModel("goods");
        $goods = $goodsObj->getObj("seller_id = $seller_id");
        $goods_id = $goods["id"];
        
        $photoObj = new IQuery("appointment_stats as p");
        $photoObj->fields = " p.*";
        $count_order = $photoObj->find();
        
        
        $goods["ad_img"] = $count_order;
        $goods["order_num"]=$count_order[0]["appointment_sum"];
        $goods["order_num1"]=$count_order[0]["from_offical"];
        $goods["order_num2"]=$count_order[0]["from_weixin"];        
        $goods["order_num3"]=$count_order[0]["from_other"];        
        
        $goods["callback"] = "/site/index_3/";

		$this->url_id = $url_id;


        $this->setRenderData($goods);
        //var_dump($goods);
        /*$showmsg = IReq::get('showmsg');
        if($showmsg){
            Util::showMessage($showmsg);
        }*/            
        
		if($this->check_wap()){
            $this->redirect('index_wap');
        }else{
            $this->redirect('yuyue');
        }

        //$this->redirect('yuyue');
   
    }

	

    function yuyue() {
        Header("Location: /site/yuyue_new");
        if(isset($_REQUEST['state']) && isset($_REQUEST['code'])){
            //微信登录
        }else if(isset($_REQUEST['code'])){
            //微博登录
            $keys = array();
            $keys['code'] = $_REQUEST['code'];
            $keys['redirect_uri'] = WB_CALLBACK_URL_PC;
            $o = new SaeTOAuthV2( WB_AKEY_PC , WB_SKEY_PC );
            try{
                $token = $o->getAccessToken( 'code', $keys ) ;
            }catch(Exception $e){
                echo 'login fail';
                exit;
            }
            if(isset($token['access_token']) && $token['access_token']!=''){
                $obj = new IQuery("user u");
                $account_type = 9;//微博登录
                $obj->join = "inner join mbk_third_user tu on tu.uid = u.id";
                $obj->where = "u.type = {$account_type} and tu.unionid = {$token['uid']}";
                $obj->fields = "tu.unionid,u.password";
                $res = $obj->find();
                if(!empty($res[0]) && is_array($res)){
                    $login_info = $res[0]['unionid'];
                    $password = $res[0]['password'];
                    if (($r1 = Db_Meicloud::userLogin($login_info, $password, $account_type)) || ($r2 = CheckRights::isThirdValidUser($login_info, $password,$account_type))) {
                        $userRow = CheckRights::isValidUser($login_info,$password);
                        $this->loginAfter($userRow);
                    } else {
                       // echo '<script>alert("账号信息有误")</script>';exit;
                        echo "<script>alert('{$r2}')</script>";exit;
                    }
                }else{
                    $openId = $token['uid'] ;
                    $flag = $this->third_register($openId,$account_type);
                    if(is_array($flag)){
                        ISafe::set('username', $flag['user_name']);
                        ISafe::set('user_id', $flag['user_id']);
                        ISafe::set('user_pwd', $flag['password']);
                        ISafe::set('type', $flag['type']);
                        $third_user_info['uid'] = $flag['user_id'];
                        $third_user_info['unionid'] = $token['uid'];
                        $third_user_info['account_type'] = $flag['type'];
                        $device_type = $this->check_device_type();
                        $third_user_info['device_type'] = $device_type ;
                        $third_user_info['user_source'] = $this->get_referersite();
                        $tUser = new IModel('third_user');
                        $tUser->setData($third_user_info);
                        $tUser->add();
                    }else{
                        echo '<script>alert("登录失败")</script>';
                    }
                }
            }
        }
        
        if($this->check_wap()){
            $from = 'wap';
        }else{
            $from ='pc';
        }
        $fr = IReq::get('fr','get');
        $web_from = $this->get_referersite();
        $web_from = empty($web_from) ? $from : $web_from;
        $final_from = empty($fr) ? $web_from : $fr;
        ISafe::set('web_from',$final_from);
        
		$this->menu_name = "yuyue";
		$url_id = IReq::get('id');

		if($url_id==1) {$this->redirect('order_ok');}
        $siteConfigObj = new Config("site_config");
        $site_config = $siteConfigObj->getInfo();
        $index_slide = isset($site_config['index_slide']) ? unserialize($site_config['index_slide']) : array();
        $this->index_slide = $index_slide;

        //商品数据装载
        //卖家ID为0
        $seller_id = 0;
        $goodsObj = new IModel("goods");
        $goods = $goodsObj->getObj("seller_id = $seller_id");
        $goods_id = $goods["id"];
        
        $photoObj = new IQuery("appointment_stats as p");
        $photoObj->fields = " p.*";
        $count_order = $photoObj->find();
        
        
        $goods["ad_img"] = $count_order;
        $goods["order_num"]= round(($count_order[0]["appointment_sum"]/10000),2).'万';
        $goods["order_num1"]= round(($count_order[0]["from_offical"]/10000),2).'万';
        $goods["order_num2"]= round(($count_order[0]["from_weixin"]/10000),2).'万';
        $goods["order_num3"]= round(($count_order[0]["from_other"]/10000),2).'万';  
        
        $goods["callback"] = "/site/yuyue/";

		$this->url_id = $url_id;


        $this->setRenderData($goods);
        //var_dump($goods);
        /*$showmsg = IReq::get('showmsg');
        if($showmsg){
            Util::showMessage($showmsg);
        }*/            
        
		if($this->check_wap()){
            $this->redirect('index_wap');
        }else{
            $this->redirect('yuyue');
        }

        //$this->redirect('yuyue');
   
    }

    
    function yuyue_new() {  

        $this->menu_name = "yuyue_new";
        $url_id = IReq::get('id');
    
        if($url_id==1) {$this->redirect('order_ok');}
        $siteConfigObj = new Config("site_config");
        $site_config = $siteConfigObj->getInfo();
        $index_slide = isset($site_config['index_slide']) ? unserialize($site_config['index_slide']) : array();
        $this->index_slide = $index_slide;
    
        //商品数据装载
        //卖家ID为0
        $seller_id = 0;
        $goodsObj = new IModel("goods");
        $goods = $goodsObj->getObj("seller_id = $seller_id");
        $goods_id = $goods["id"];

        $photoObj = new IQuery("appointment_stats as p");
        $photoObj->fields = " p.*";
        $count_order = $photoObj->find();
    
        $user1_id=$this->user['user_id'];
        if(!empty($user1_id)){
        	$userObj = new IQuery("user as a");
        	$userObj->fields = " a.*";
        	//$userObj->where = "a.id=157";
        	$userObj->where = "a.id=".$user1_id;
        	$user2_id = $userObj->find();
        	
        	//$goods["mei2_id"]=$user2_id[0]["meiid"]
        	
        	$meiid = empty($user2_id[0]["mobile"]) ? $user2_id[0]["email"] :$user2_id[0]["mobile"];
        	 
        	$goods["meiid"]=$meiid;
        }

        $goods["ad_img"] = $count_order;
        $goods["order_num"]= round(($count_order[0]["appointment_sum"]/10000),2).'万';
        $goods["order_num1"]= round(($count_order[0]["from_offical"]/10000),2).'万';
        $goods["order_num2"]= round(($count_order[0]["from_weixin"]/10000),2).'万';
        $goods["order_num3"]= round(($count_order[0]["from_other"]/10000),2).'万';
    
        //商品信息
        $tb_goods = new IModel('goods');
        $goods_info = $tb_goods->getObj("id=9 AND is_del=0");
        
        $goods["callback"] = "/site/yuyue_new/";
    
        $this->url_id = $url_id;
    
    	$username = ISafe::get('username');
    	$goods['username'] = $username;
    	
        $this->setRenderData($goods);

        $this->redirect('yuyue_new');

    }
    
    
    
    function yuyue_test() {
        if(isset($_REQUEST['state']) && isset($_REQUEST['code'])){
            //微信登录
        }else if(isset($_REQUEST['code'])){
            //微博登录
            $keys = array();
            $keys['code'] = $_REQUEST['code'];
            $keys['redirect_uri'] = WB_CALLBACK_URL_PC;
            $o = new SaeTOAuthV2( WB_AKEY_PC , WB_SKEY_PC );
            try{
    			$token = $o->getAccessToken_test( 'code', $keys ) ;
            }catch(Exception $e){
                echo 'incorrect code !'.'<br>Exception:<br>';
                var_dump($e);
                echo '<br>token:<br>';
                var_dump($token);
                echo '<br/>login fail';
                exit;
            }
            if(isset($token['access_token']) && $token['access_token']!=''){
                $obj = new IQuery("user u");
                $account_type = 9;//微博登录
                $obj->join = "inner join mbk_third_user tu on tu.uid = u.id";
                $obj->where = "u.type = {$account_type} and tu.unionid = {$token['uid']}";
                $obj->fields = "tu.unionid,u.password";
                $res = $obj->find();
                if(!empty($res[0]) && is_array($res)){
                    $login_info = $res[0]['unionid'];
                    $password = $res[0]['password'];
                    if (($r1 = Db_Meicloud::userLogin($login_info, $password, $account_type)) || ($r2 = CheckRights::isThirdValidUser($login_info, $password,$account_type))) {
                        $userRow = CheckRights::isValidUser($login_info,$password);
                        $this->loginAfter($userRow);
                    } else {
                        // echo '<script>alert("账号信息有误")</script>';exit;
                        echo "<script>alert('{$r2}')</script>";exit;
                    }
                }else{
                    $openId = $token['uid'] ;
                    $flag = $this->third_register($openId,$account_type);
                    if(is_array($flag)){
                        ISafe::set('username', $flag['user_name']);
                        ISafe::set('user_id', $flag['user_id']);
                        ISafe::set('user_pwd', $flag['password']);
                        ISafe::set('type', $flag['type']);
                        $third_user_info['uid'] = $flag['user_id'];
                        $third_user_info['unionid'] = $token['uid'];
                        $third_user_info['account_type'] = $flag['type'];
                        $device_type = $this->check_device_type();
                        $third_user_info['device_type'] = $device_type ;
                        $third_user_info['user_source'] = $this->get_referersite();
                        $tUser = new IModel('third_user');
                        $tUser->setData($third_user_info);
                        $tUser->add();
                    }else{
                        echo '<script>alert("登录失败")</script>';
                    }
                }
            }
        }
        $this->menu_name = "yuyue";
        $url_id = IReq::get('id');
    
        if($url_id==1) {$this->redirect('order_ok');}
        $siteConfigObj = new Config("site_config");
        $site_config = $siteConfigObj->getInfo();
        $index_slide = isset($site_config['index_slide']) ? unserialize($site_config['index_slide']) : array();
        $this->index_slide = $index_slide;
    
        //商品数据装载
        //卖家ID为0
        $seller_id = 0;
        $goodsObj = new IModel("goods");
        $goods = $goodsObj->getObj("seller_id = $seller_id");
        $goods_id = $goods["id"];
    
        $photoObj = new IQuery("appointment_stats as p");
        $photoObj->fields = " p.*";
        $count_order = $photoObj->find();
    
    
        $goods["ad_img"] = $count_order;
        $goods["order_num"]=$count_order[0]["appointment_sum"];
        $goods["order_num1"]=$count_order[0]["from_offical"];
        $goods["order_num2"]=$count_order[0]["from_weixin"];
        $goods["order_num3"]=$count_order[0]["from_other"];
    
        $goods["callback"] = "/site/yuyue/";
    
        $this->url_id = $url_id;
    
    
        $this->setRenderData($goods);
        //var_dump($goods);
        /*$showmsg = IReq::get('showmsg');
         if($showmsg){
         Util::showMessage($showmsg);
        }*/
    
        if($this->check_wap()){
            $this->redirect('index_wap');
        }else{
            $this->redirect('yuyue');
        }
    
        //$this->redirect('yuyue');
      
    }
    
    function loginAfter($userRow) {
        //用户私密数据
        ICookie::set('user_id', $userRow['id']);
        ISafe::set('username', $userRow['username']);
        ISafe::set('user_pwd', $userRow['password']);
        ICookie::set('last_login', $userRow['last_login']);
        ISafe::set('email', $userRow['email']);
        ISafe::set('active', $userRow['active']);
        //更新最后一次登录时间
        $memberObj = new IModel('member');
        $dataArray = array(
            'last_login' => ITime::getDateTime(),
        );
        $memberObj->setData($dataArray);
        $where = 'user_id = ' . $userRow["id"];
        $memberObj->update($where);
        $memberRow = $memberObj->getObj($where, 'exp');
    
        //根据经验值分会员组
        $groupObj = new IModel('user_group');
        $groupRow = $groupObj->getObj($memberRow['exp'] . ' between minexp and maxexp and minexp > 0 and maxexp > 0', 'id', 'discount', 'desc');
        if (!empty($groupRow)) {
            $dataArray = array('group_id' => $groupRow['id']);
            $memberObj->setData($dataArray);
            $memberObj->update('user_id = ' . $userRow["id"]);
        }
        echo '<script>window.location.href="/"</script>';
    }
    
    //获取网址来源
    function get_referersite()
    {
        $urlname = "";
        if (isset($_SERVER['HTTP_REFERER']))
        {     //有来源信息
            $urlname = $_SERVER['HTTP_REFERER'];
  
        }
        else
        {   //没有来源信息
            $agent          = $_SERVER["HTTP_USER_AGENT"];
    
            $search_weixin  = "MicroMessenger";
            $search_mqq     = "MQQBrowser";
            $weixin         = preg_match("/\b{$search_weixin}\b/",$agent);//记录匹配情况，用于入站判断。
            $mqq            = preg_match("/\b{$search_mqq}\b/",$agent);
            if ($weixin)
            {
                $urlname    = "微信";
            }
            else if ($mqq)
            {
                $urlname    = "手机QQ";
            } else
            {
                $urlname    = "";
            }
        }
        return $urlname;
    }
    //检测设备类型
    function check_device_type(){
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        if(stristr($user_agent,'iPad')) {
            $fb_fs= 5;
        }else if(stristr($user_agent,'Android')) {
            $fb_fs= 4;
        }else if(stristr($user_agent,'Linux')){
            $fb_fs= 3;
        }else if(stristr($user_agent,'iPhone')){
            $fb_fs= 2;
        }else{
            $fb_fs= 1;
        }
        return $fb_fs;
    }
    
    //第三方注册
    function third_register($openId,$type,$nickname = ''){
        //$username = '@贝贝_'.$openId;//统一用户名
        if($type == '9'){
        	$username = '@sina_'.$openId;//微博用户名
        }else if($type == '8'){
        	$username = $nickname;
        }
        $password = mt_rand(100000,999999);//随机生成密码
        $meiid = Db_Meicloud::addUser($openId, $username, md5($password), $type);
        //echo $openId.'---'. $username.'---'.$password.'---'.$type.'---'.$meiid;
        $userArray = array(
            'username' => $username,
            'password' => md5($password),
            'type' => $type,
            'meiid' => $meiid
        );
        $userObj = new IModel('user');
        $userObj->setData($userArray);
        $user_id = $userObj->add();
        if ($user_id) {
            //记录会员信息
            $memberArray = array(
                'user_id' => $user_id,
                'time' => ITime::getDateTime(),
            );
            $memberObj = new IModel('member');
            $memberObj->setData($memberArray);
            $memberObj->add();
            $userInfo = array('user_id'=>$user_id,'user_name'=>$username,'password'=>$password,'type'=>$type);
            return $userInfo;
        }else{
            return false;
        }
    }
    
  function index_wap() {
  		$this->layout = '';
        if($this->check_wap()){
              $from = 'wap';
        }else{
              $from ='pc';
        }       
        $fr = IReq::get('fr','get');            
        $web_from = $this->get_referersite();
        $web_from = empty($web_from) ? $from : $web_from;
        $final_from = empty($fr) ? $web_from : $fr;
        ISafe::set('web_from',$final_from);
		$this->menu_name = "index_wap";
		$url_id = IReq::get('id');

		if($url_id==1) {$this->redirect('order_ok');}
        $siteConfigObj = new Config("site_config");
        $site_config = $siteConfigObj->getInfo();
        $index_slide = isset($site_config['index_slide']) ? unserialize($site_config['index_slide']) : array();
        $this->index_slide = $index_slide;

        //商品数据装载
        //卖家ID为0
        $seller_id = 0;
        $goodsObj = new IModel("goods");
        $goods = $goodsObj->getObj("seller_id = $seller_id");
        $goods_id = $goods["id"];
        
        $photoObj = new IQuery("appointment_stats as p");
        $photoObj->fields = " p.*";
        $count_order = $photoObj->find();
        
        
        $goods["ad_img"] = $count_order;
      
        $goods["order_num"]= round(($count_order[0]["appointment_sum"]/10000),2).'万';
        $goods["order_num1"]= round(($count_order[0]["from_offical"]/10000),2).'万';
        $goods["order_num2"]= round(($count_order[0]["from_weixin"]/10000),2).'万';
        $goods["order_num3"]= round(($count_order[0]["from_other"]/10000),2).'万';
        
        $goods["callback"] = "/site/index_3/";

		$this->url_id = $url_id;


        $this->setRenderData($goods);
        //var_dump($goods);
        /*$showmsg = IReq::get('showmsg');
        if($showmsg){
            Util::showMessage($showmsg);
        }*/            
  
        $this->redirect('index_wap');
   
    }

    function index_wap_first() {
    	$this->layout = '';
        $this->menu_name = "index_wap_first";
        $this->redirect('index_wap_first');        
    }
    
    function wap_success(){
       $this->layout = '';
       $data['aid'] = $_GET['aid'];
       $this->setRenderData($data);
       $this->redirect('wap_success');
    }
	function hbsm_wap(){
		$this->layout = '';
		$this->redirect('hbsm_wap');
	}
	 
	function index_4() {
		$this->menu_name = "index_3";
		$url_id = IReq::get('id');

		if($url_id==1) {$this->redirect('order_ok');}
        $siteConfigObj = new Config("site_config");
        $site_config = $siteConfigObj->getInfo();
        $index_slide = isset($site_config['index_slide']) ? unserialize($site_config['index_slide']) : array();
        $this->index_slide = $index_slide;

        //商品数据装载
        //卖家ID为0
        $seller_id = 0;
        $goodsObj = new IModel("goods");
        $goods = $goodsObj->getObj("seller_id = $seller_id");
        $goods_id = $goods["id"];
        
        $photoObj = new IQuery("appointment_stats as p");
        $photoObj->fields = " p.*";
        $count_order = $photoObj->find();
        
        
        $goods["ad_img"] = $count_order;
        $goods["order_num"]=$count_order[0]["appointment_sum"];
        $goods["order_num1"]=$count_order[0]["from_offical"];
        $goods["order_num2"]=$count_order[0]["from_weixin"];        
        $goods["order_num3"]=$count_order[0]["from_other"];        
        
        $goods["callback"] = "/site/index_3/";

		$this->url_id = $url_id;


        $this->setRenderData($goods);
        //var_dump($goods);
        /*$showmsg = IReq::get('showmsg');
        if($showmsg){
            Util::showMessage($showmsg);
        }*/            
        
        if($this->check_wap()){
            $this->redirect('index_wap');
        }else{
            $this->redirect('index_4');
        }
        // $this->redirect('index_3');
   
    }
    
    function checkSubstrs($list,$str){
        $flag = false;
        for($i=0;$i<count($list);$i++){
            if(strpos($str,$list[$i]) > 0){
                $flag = true;
                break;
            }
        }
        return $flag;
    }
    
    function check_wap() {
        if (isset($_SERVER['HTTP_VIA'])) return true;
        if (isset($_SERVER['HTTP_X_NOKIA_CONNECTION_MODE'])) return true;
        if (isset($_SERVER['HTTP_X_UP_CALLING_LINE_ID'])) return true;
        if (strpos(strtoupper($_SERVER['HTTP_ACCEPT']),"VND.WAP.WML") > 0) {
            // Check whether the browser/gateway says it accepts WML.
            $br = "WML";
        } else {
            $browser = isset($_SERVER['HTTP_USER_AGENT']) ? trim($_SERVER['HTTP_USER_AGENT']) : '';
            if(empty($browser)) return true;
            $mobile_os_list=array('Google Wireless Transcoder','Windows CE','WindowsCE','Symbian','Android','armv6l','armv5','Mobile','CentOS','mowser','AvantGo','Opera Mobi','J2ME/MIDP','Smartphone','Go.Web','Palm','iPAQ');
    
            $mobile_token_list=array('Profile/MIDP','Configuration/CLDC-','160×160','176×220','240×240','240×320','320×240','UP.Browser','UP.Link','SymbianOS','PalmOS','PocketPC','SonyEricsson','Nokia','BlackBerry','Vodafone','BenQ','Novarra-Vision','Iris','NetFront','HTC_','Xda_','SAMSUNG-SGH','Wapaka','DoCoMo','iPhone','iPod');
    
            $found_mobile=$this->checkSubstrs($mobile_os_list,$browser) ||
            $this->checkSubstrs($mobile_token_list,$browser);
            if($found_mobile)
                $br ="WML";
            else $br = "WWW";
        }
        if($br == "WML") {
            return true;
        } else {
            return false;
        }
    }

	 function myorder() {
	    	$this->menu_name = "index";
    
        $siteConfigObj = new Config("site_config");
        $site_config = $siteConfigObj->getInfo();
        $index_slide = isset($site_config['index_slide']) ? unserialize($site_config['index_slide']) : array();
        $this->index_slide = $index_slide;

        //商品数据装载
        //卖家ID为0
        $seller_id = 0;
        $goodsObj = new IModel("goods");
        $goods = $goodsObj->getObj("seller_id = $seller_id");
        $goods_id = $goods["id"];
        
        $photoObj = new IQuery("goods_photo as p");
        $photoObj->join = " inner join goods_photo_relation as r on r.goods_id = $goods_id and p.id = r.photo_id";
        $photoObj->fields = " p.*";
        $imgs = $photoObj->find();
        
        $user1_id=$this->user['user_id'];
        $userObj = new IQuery("user as a");
        $userObj->fields = " a.*";
        //$userObj->where = "a.id=157";
        $userObj->where = "a.id=".$user1_id;
        $user2_id = $userObj->find();
        
        //$goods["mei2_id"]=$user2_id[0]["meiid"]
        $goods["mei2_id"]=$user2_id[0]["meiid"];
        $appointObj = new IQuery("appointment as a");
        $appointObj->fields = " a.*,ma.activity_desc,ma.purchase_date";
        $appointObj->join = "inner join mbk_activity ma on a.purchase_time = ma.activity_code";
        //$appointObj->where = "a.order_id=10";
        $appointObj->where = "a.mei_id=".$goods["mei2_id"];
        //$appointObj->where = "a.mei_id=".$mei2_id;
        $mei_id = $appointObj->find();

        if(empty($user2_id[0]["mobile"])) {$goods["mei_id"]=$user2_id[0]["email"];} else {$goods["mei_id"]=$user2_id[0]["mobile"];}
        
        $goods["ad_img"] = $imgs;
        //$goods["mei_id"]=$user2_id[0]["username"];
        
        $goods["nickname"]=$user2_id[0]["username"];        
        $goods["appoint_code"]=$mei_id[0]["appointment_no"];
        $goods["createtime"]=$mei_id[0]["create_time"];
        
        $goods['order'] = $mei_id;
        //$goods["callback"] = "/site/myorder/";


        $this->setRenderData($goods);
        //var_dump($goods);
        /*$showmsg = IReq::get('showmsg');
        if($showmsg){
            Util::showMessage($showmsg);
        }*/

        $this->redirect('myorder');
    }
	
    function myprize() {
        $this->menu_name = "index";
    
        $siteConfigObj = new Config("site_config");
        $site_config = $siteConfigObj->getInfo();
        $index_slide = isset($site_config['index_slide']) ? unserialize($site_config['index_slide']) : array();
        $this->index_slide = $index_slide;
  
        $photoObj = new IQuery("goods_photo as p");
        $photoObj->fields = " p.*";
        $imgs = $photoObj->find();
    
        $user1_id=$this->user['user_id'];
        $userObj = new IQuery("user as a");
        $userObj->fields = " a.*";
        //$userObj->where = "a.id=157";
        $userObj->where = "a.id=".$user1_id;
        $user2_id = $userObj->find();
    
        $obj = new IQuery("lottery_log l");
        $obj->join = "inner join mbk_prize p on l.prize_id = p.id  inner join mbk_activity a on a.activity_type = p.activity_type";
        $obj->fields = "a.activity_desc,l.time,p.field1,l.status";
        $obj->where = "l.uid=".$user1_id;
        $prize = $obj->find();
        $goods['prize'] = $prize;
        $goods["ad_img"] = $imgs;
        $goods["nickname"]=$user2_id[0]["username"];
        //$goods["callback"] = "/site/myprize/";
    
        $this->setRenderData($goods);
        //var_dump($goods);
        /*$showmsg = IReq::get('showmsg');
         if($showmsg){
         Util::showMessage($showmsg);
        }*/
    
        $this->redirect('myprize');
    }
    
   function order_ok() {
	    	$this->menu_name = "index";
    
        $siteConfigObj = new Config("site_config");
        $site_config = $siteConfigObj->getInfo();
        $index_slide = isset($site_config['index_slide']) ? unserialize($site_config['index_slide']) : array();
        $this->index_slide = $index_slide;

        //商品数据装载
        //卖家ID为0
        $seller_id = 0;
        $goodsObj = new IModel("goods");
        $goods = $goodsObj->getObj("seller_id = $seller_id");
        $goods_id = $goods["id"];
        
        $photoObj = new IQuery("goods_photo as p");
        $photoObj->join = " inner join goods_photo_relation as r on r.goods_id = $goods_id and p.id = r.photo_id";
        $photoObj->fields = " p.*";
        $imgs = $photoObj->find();
        
        $user1_id=$this->user['user_id'];
        $userObj = new IQuery("user as a");
        $userObj->fields = " a.*";
        //$userObj->where = "a.id=157";
        $userObj->where = "a.id=".$user1_id;
        $user2_id = $userObj->find();
        
        //$goods["mei2_id"]=$user2_id[0]["meiid"]
        $goods["mei2_id"]=$user2_id[0]["meiid"];
        $appointObj = new IQuery("appointment as a");
        $appointObj->fields = " a.*";    
        //$appointObj->where = "a.order_id=10";
        $appointObj->where = "a.mei_id=".$goods["mei2_id"];
        $appointObj->order = "a.order_id desc";
        //$appointObj->where = "a.mei_id=".$mei2_id;
        $mei_id = $appointObj->find();
      
        if(empty($user2_id[0]["mobile"])) {$goods["mei_id"]=$user2_id[0]["email"];} else {$goods["mei_id"]=$user2_id[0]["mobile"];}
        
        $goods["ad_img"] = $imgs;
        //$goods["mei_id"]=$user2_id[0]["username"];
        
        $goods["nickname"]=$user2_id[0]["username"];        
        $goods["appoint_code"]=$mei_id[0]["appointment_no"];
        $goods["callback"] = "/site/index_3/";


        $this->setRenderData($goods);
        //var_dump($goods);
        /*$showmsg = IReq::get('showmsg');
        if($showmsg){
            Util::showMessage($showmsg);
        }*/

        $this->redirect('order_ok');
    }
	

  function file_ok() {
	    	$this->menu_name = "index";
    
        $siteConfigObj = new Config("site_config");
        $site_config = $siteConfigObj->getInfo();
        $index_slide = isset($site_config['index_slide']) ? unserialize($site_config['index_slide']) : array();
        $this->index_slide = $index_slide;

        //商品数据装载
        //卖家ID为0
        $seller_id = 0;
        $goodsObj = new IModel("goods");
        $goods = $goodsObj->getObj("seller_id = $seller_id");
        $goods_id = $goods["id"];
        
        $photoObj = new IQuery("goods_photo as p");
        $photoObj->join = " inner join goods_photo_relation as r on r.goods_id = $goods_id and p.id = r.photo_id";
        $photoObj->fields = " p.*";
        $imgs = $photoObj->find();
        
        $user1_id=$this->user['user_id'];
        $userObj = new IQuery("user as a");
        $userObj->fields = " a.*";
        //$userObj->where = "a.id=157";
        $userObj->where = "a.id=".$user1_id;
        $user2_id = $userObj->find();
        
        //$goods["mei2_id"]=$user2_id[0]["meiid"]
        $goods["mei2_id"]=$user2_id[0]["meiid"];
        $appointObj = new IQuery("appointment as a");
        $appointObj->fields = " a.*";
        //$appointObj->where = "a.order_id=10";
        $appointObj->where = "a.mei_id=".$goods["mei2_id"];
        //$appointObj->where = "a.mei_id=".$mei2_id;
        $mei_id = $appointObj->find();
      
        if(empty($user2_id[0]["mobile"])) {$goods["mei_id"]=$user2_id[0]["email"];} else {$goods["mei_id"]=$user2_id[0]["mobile"];}
        
        $goods["ad_img"] = $imgs;
        //$goods["mei_id"]=$user2_id[0]["username"];
        
        $goods["nickname"]=$user2_id[0]["username"];        
        $goods["appoint_code"]=$mei_id[0]["appointment_no"];
        $goods["callback"] = "/site/index_3/";


        $this->setRenderData($goods);
        //var_dump($goods);
        /*$showmsg = IReq::get('showmsg');
        if($showmsg){
            Util::showMessage($showmsg);
        }*/

        $this->redirect('file_ok');
    }
	
  
  function huaweia_ok() {
		$this->menu_name = "index";
    
        $siteConfigObj = new Config("site_config");
        $site_config = $siteConfigObj->getInfo();
        $index_slide = isset($site_config['index_slide']) ? unserialize($site_config['index_slide']) : array();
        $this->index_slide = $index_slide;

        //商品数据装载
        //卖家ID为0
        $seller_id = 0;
        $goodsObj = new IModel("goods");
        $goods = $goodsObj->getObj("seller_id = $seller_id");
        $goods_id = $goods["id"];
        
        $photoObj = new IQuery("goods_photo as p");
        $photoObj->join = " inner join goods_photo_relation as r on r.goods_id = $goods_id and p.id = r.photo_id";
        $photoObj->fields = " p.*";
        $imgs = $photoObj->find();
        
       $user1_id=$this->user['user_id'];
        $userObj = new IQuery("user as a");
        $userObj->fields = " a.*";
        //$userObj->where = "a.id=157";
        $userObj->where = "a.id=".$user1_id;
        $user2_id = $userObj->find();
        
        //$goods["mei2_id"]=$user2_id[0]["meiid"]
        $goods["mei2_id"]=$user2_id[0]["meiid"];
        $appointObj = new IQuery("appointment as a");
        $appointObj->fields = " a.*";
        //$appointObj->where = "a.order_id=10";
        $appointObj->where = "a.mei_id=".$goods["mei2_id"];
        //$appointObj->where = "a.mei_id=".$mei2_id;
        $mei_id = $appointObj->find();
      
        if(empty($user2_id[0]["mobile"])) {$goods["mei_id"]=$user2_id[0]["email"];} else {$goods["mei_id"]=$user2_id[0]["mobile"];}
        
        $goods["ad_img"] = $imgs;
        //$goods["mei_id"]=$user2_id[0]["username"];
        
        $goods["nickname"]=$user2_id[0]["username"];        
        $goods["appoint_code"]=$mei_id[0]["appointment_no"];
        $goods["callback"] = "/site/index_3/";


        $this->setRenderData($goods);
        //var_dump($goods);
        /*$showmsg = IReq::get('showmsg');
        if($showmsg){
            Util::showMessage($showmsg);
        }*/

        $this->redirect('huaweia_ok');
    }
    
    function huaweib_ok() {
		$this->menu_name = "index";
    
        $siteConfigObj = new Config("site_config");
        $site_config = $siteConfigObj->getInfo();
        $index_slide = isset($site_config['index_slide']) ? unserialize($site_config['index_slide']) : array();
        $this->index_slide = $index_slide;

        //商品数据装载
        //卖家ID为0
        $seller_id = 0;
        $goodsObj = new IModel("goods");
        $goods = $goodsObj->getObj("seller_id = $seller_id");
        $goods_id = $goods["id"];
        
        $photoObj = new IQuery("goods_photo as p");
        $photoObj->join = " inner join goods_photo_relation as r on r.goods_id = $goods_id and p.id = r.photo_id";
        $photoObj->fields = " p.*";
        $imgs = $photoObj->find();
        
        $user1_id=$this->user['user_id'];
        $userObj = new IQuery("user as a");
        $userObj->fields = " a.*";
        //$userObj->where = "a.id=157";
        $userObj->where = "a.id=".$user1_id;
        $user2_id = $userObj->find();
        
        //$goods["mei2_id"]=$user2_id[0]["meiid"]
        $goods["mei2_id"]=$user2_id[0]["meiid"];
        $appointObj = new IQuery("appointment as a");
        $appointObj->fields = " a.*";
        //$appointObj->where = "a.order_id=10";
        $appointObj->where = "a.mei_id=".$goods["mei2_id"];
        //$appointObj->where = "a.mei_id=".$mei2_id;
        $mei_id = $appointObj->find();
      
        if(empty($user2_id[0]["mobile"])) {$goods["mei_id"]=$user2_id[0]["email"];} else {$goods["mei_id"]=$user2_id[0]["mobile"];}
        
        $goods["ad_img"] = $imgs;
        //$goods["mei_id"]=$user2_id[0]["username"];
        
        $goods["nickname"]=$user2_id[0]["username"];        
        $goods["appoint_code"]=$mei_id[0]["appointment_no"];
        $goods["callback"] = "/site/index_3/";


        $this->setRenderData($goods);
        //var_dump($goods);
        /*$showmsg = IReq::get('showmsg');
        if($showmsg){
            Util::showMessage($showmsg);
        }*/

        $this->redirect('huaweib_ok');
    }
	
	
  function vip_order() {
		$this->menu_name = "index";
    
        $siteConfigObj = new Config("site_config");
        $site_config = $siteConfigObj->getInfo();
        $index_slide = isset($site_config['index_slide']) ? unserialize($site_config['index_slide']) : array();
        $this->index_slide = $index_slide;

        //商品数据装载
        //卖家ID为0
        $seller_id = 0;
        $goodsObj = new IModel("goods");
        $goods = $goodsObj->getObj("seller_id = $seller_id");
        $goods_id = $goods["id"];
        
        $photoObj = new IQuery("goods_photo as p");
        $photoObj->join = " inner join goods_photo_relation as r on r.goods_id = $goods_id and p.id = r.photo_id";
        $photoObj->fields = " p.*";
        $imgs = $photoObj->find();
        
        $appointObj = new IQuery("appointment as a");
        $appointObj->fields = " a.*";
        $appointObj->where = "a.order_id=10";
        $mei_id = $appointObj->find();
      
        
        //var_dump($mei_id)
        $goods["ad_img"] = $imgs;
        $goods["mei_id"]=$mei_id[0]["mei_id"];
        $goods["nickname"]="13430981860";        
        $goods["appoint_code"]=$mei_id[0]["appointment_no"];
        $goods["callback"] = "/site/index_3/";


        $this->setRenderData($goods);
        //var_dump($goods);
        /*$showmsg = IReq::get('showmsg');
        if($showmsg){
            Util::showMessage($showmsg);
        }*/

        $this->redirect('vip_order');
    }
	
	
    function vip_goods_order() {
    	$this->menu_name = "index";
    
    	$siteConfigObj = new Config("site_config");
    	$site_config = $siteConfigObj->getInfo();
    	$index_slide = isset($site_config['index_slide']) ? unserialize($site_config['index_slide']) : array();
    	$this->index_slide = $index_slide;
    
    	//商品数据装载
    	//卖家ID为0
    	$seller_id = 0;
    	$goodsObj = new IModel("goods");
    	$goods = $goodsObj->getObj("seller_id = $seller_id");
    	$goods_id = $goods["id"];
    
    	$photoObj = new IQuery("goods_photo as p");
    	$photoObj->join = " inner join goods_photo_relation as r on r.goods_id = $goods_id and p.id = r.photo_id";
    	$photoObj->fields = " p.*";
    	$imgs = $photoObj->find();
    
    	$appointObj = new IQuery("appointment as a");
    	$appointObj->fields = " a.*";
    	$appointObj->where = "a.order_id=10";
    	$mei_id = $appointObj->find();
    
    
    	//var_dump($mei_id)
    	$goods["ad_img"] = $imgs;
    	$goods["mei_id"]=$mei_id[0]["mei_id"];
    	$goods["nickname"]="13430981860";
    	$goods["appoint_code"]=$mei_id[0]["appointment_no"];
    	$goods["callback"] = "/site/index_3/";
    
    
    	$this->setRenderData($goods);
    	//var_dump($goods);
    	/*$showmsg = IReq::get('showmsg');
    	 if($showmsg){
    	Util::showMessage($showmsg);
    	}*/
    
    	$this->redirect('vip_goods_order');
    }
    
  
	function news() {
		$this->menu_name = "news";
		
		$page = IReq::get('page');
		if (! $page) {
			$page = 1;
		}
		
		$numperpage = 20;
		$this->curPage = $page;
		
		$count = $this->getnewscount();
		$this->totalPage = (int)(($count - 1) / $numperpage) + 1;
		
		if ($page <= 1) {
			$this->lastPage = 1;
		} else {
			$this->lastPage = $page - 1;
		}
		
		if ($page >= $this->totalPage) {
			$this->nextPage = $this->totalPage;
		} else {
			$this->nextPage = $page + 1;
		}

		$this->newslist = $this->getnewslist($numperpage, $page);
		
		$this->redirect('news');
	}
	
	function getnewscount() {
		$db = IDBFactory::getDB();		
		$res = $db->query("select count(*) from  mbk_help where cat_id=9999");
		
		return $res[0]["count(*)"];
	}
	
	function getnewslist($numperpage, $page) {
		// mbbs_forum_forum name  mbbs_forum_post
		$start = $numperpage * ($page - 1) ;
		
		$db = IDBFactory::getDB();
 		$res = $db->query("select id, name, content, dateline from  mbk_help where cat_id=9999 order by `sort` asc, `id` desc limit $start, $numperpage");
		
		return $res;
	}
	
	function newsunit() {
		$this->menu_name = "newsunit";
		
		$newid = IReq::get('id');
		$db = IDBFactory::getDB();
		$res = $db->query("select id, name, content, dateline, sort,desp from  mbk_help where id=$newid");
		$this->newinfo = array(
			"exists" => 0,
		);
		
		$sortNow = 0;
		if ($res && $res[0]) {
			$this->newinfo = array(
				"exists" => 1,
				"name" => $res[0]["name"],
				"dateline" => $res[0]["dateline"],
				"content" => $res[0]["content"],
				"desp" => $res[0]["desp"],
			);
			$sortNow = $res[0]["sort"];
			$this->newtitle = $res[0]["name"];
			$this->newdescription = $res[0]["desp"];
			
 		} else {
			$this->newtitle = "无";
			$this->newdescription =  "无";
 		}
		
		$aLast = "";
		$aNext = "";
		
		// 上下篇
		$sqlLast = "select id from mbk_help where cat_id=9999 and (sort>$sortNow or (sort=$sortNow and id<$newid)) order by `sort` asc, `id` desc limit 1";
		$sqlNext = "select id from mbk_help where cat_id=9999 and (sort<$sortNow or (sort=$sortNow and id>$newid)) order by `sort` desc, `id` asc limit 1";
		
		$res = $db->query($sqlLast);
		if ($res && $res[0]) {
			$aLast = IUrl::creatUrl("/site/newsunit/id/") . $res[0]["id"];
		}
		
		$res = $db->query($sqlNext);
		if ($res && $res[0]) {
			$aNext = IUrl::creatUrl("/site/newsunit/id/") . $res[0]["id"];
		}
		$this->aLast = $aLast;
		$this->aNext = $aNext;
		
		$this->redirect('newsunit');
	}

    function design() {
		$this->menu_name = "yunbang";
        $this->sub_menu_name = "design";
        $id = IFilter::act(IReq::get('id'), 'int');
        $goodsObj = new IModel("goods");
        if (!$id) {
            $goods = $goodsObj->getObj();
            $id = $goods["id"];
        } else {
            $goods = $goodsObj->getObj("id = $id ");
        }

        //取“设计”的附加属性
        $AttrObj = new IQuery("attribute as at");
        $AttrObj->join = "inner join goods_attribute as ga on at.id = ga.attribute_id inner join pics as pc on ga.attribute_value=pc.id";
        $AttrObj->where = "ga.goods_id = $id and at.name like '%设计%' and at.type in (4,5)";
        $AttrObj->order = "at.name";
        $AttrObj->fields = "at.type, pc.name, pc.content, pc.value";
        $goods["attrbutes"] = $AttrObj->find();
        $this->setRenderData($goods);
        //var_dump($goods["attrbutes"]);
        $this->redirect('design');
    }

    function protect() {
		$this->menu_name = "yunbang";
        $this->sub_menu_name = "protect";
        
        $this->redirect('protect');
    }

    function picture() {
		$this->menu_name = "yunbang";
        $this->sub_menu_name = "picture";
        $id = IFilter::act(IReq::get('id'), 'int');
        $goodsObj = new IModel("goods");
        if (!$id) {
            $goods = $goodsObj->getObj();
            $id = $goods["id"];
        } else {
            $goods = $goodsObj->getObj("id = $id ");
        }

        //取“设计”的附加属性
        $AttrObj = new IQuery("attribute as at");
        $AttrObj->join = "inner join goods_attribute as ga on at.id = ga.attribute_id inner join pics as pc on ga.attribute_value=pc.id";
        $AttrObj->where = "ga.goods_id = $id and at.name like '%照片%' and at.type in (4,5)";
        $AttrObj->order = "at.name";
        $AttrObj->fields = "at.type, pc.name, pc.content, pc.value";
        $goods["attrbutes"] = $AttrObj->find();
        $this->setRenderData($goods);
        //var_dump($goods["attrbutes"]);
        $this->redirect('picture');
    }

	

    function wireless() {
		$this->menu_name = "yunbang";
        $this->sub_menu_name = "wireless";
        $id = IFilter::act(IReq::get('id'), 'int');
        $goodsObj = new IModel("goods");
        if (!$id) {
            $goods = $goodsObj->getObj();
            $id = $goods["id"];
        } else {
            $goods = $goodsObj->getObj("id = $id ");
        }

        //取“设计”的附加属性
        $AttrObj = new IQuery("attribute as at");
        $AttrObj->join = "inner join goods_attribute as ga on at.id = ga.attribute_id inner join pics as pc on ga.attribute_value=pc.id";
        $AttrObj->where = "ga.goods_id = $id and at.name like '%无线%' and at.type in (4,5)";
        $AttrObj->order = "at.name";
        $AttrObj->fields = "at.type, pc.name, pc.content, pc.value";
        $goods["attrbutes"] = $AttrObj->find();
        $this->setRenderData($goods);
        //var_dump($goods["attrbutes"]);
        $this->redirect('wireless');
    }

    function performance() {
		$this->menu_name = "yunbang";
        $this->sub_menu_name = "performance";
        $id = IFilter::act(IReq::get('id'), 'int');
        $goodsObj = new IModel("goods");
        if (!$id) {
            $goods = $goodsObj->getObj();
            $id = $goods["id"];
        } else {
            $goods = $goodsObj->getObj("id = $id ");
        }

        //取“设计”的附加属性
        $AttrObj = new IQuery("attribute as at");
        $AttrObj->join = "inner join goods_attribute as ga on at.id = ga.attribute_id inner join pics as pc on ga.attribute_value=pc.id";
        $AttrObj->where = "ga.goods_id = $id and at.name like '%性能%' and at.type in (4,5)";
        $AttrObj->order = "at.name";
        $AttrObj->fields = "at.type, pc.name, pc.content, pc.value";
        $goods["attrbutes"] = $AttrObj->find();
        $this->setRenderData($goods);
        //var_dump($goods["attrbutes"]);
        $this->redirect('performance');
    }

    function parameter() {
		$this->menu_name = "yunbang";
        $this->sub_menu_name = "parameter";
        $id = IFilter::act(IReq::get('id'), 'int');
        $goodsObj = new IModel("goods");
        if (!$id) {
            $goods = $goodsObj->getObj();
            $id = $goods["id"];
        } else {
            $goods = $goodsObj->getObj("id = $id ");
        }

        //取“参数”的附加属性
        $AttrObj = new IQuery("attribute as at");
        $AttrObj->join = "inner join goods_attribute as ga on at.id = ga.attribute_id inner join pics as pc on ga.attribute_value=pc.id";
        $AttrObj->where = "ga.goods_id = $id and at.name = '参数' and at.type in (4,5)";
        $AttrObj->order = "at.name";
        $AttrObj->fields = "at.type, pc.name, pc.content, pc.value";
        $goods["attrbutes"] = $AttrObj->find();
        $this->setRenderData($goods);
        //var_dump($goods["attrbutes"]);
        $this->redirect('parameter');
    }

    function feature() {
		$this->menu_name = "yunbang";
        $this->sub_menu_name = "feature";
        $id = IFilter::act(IReq::get('id'), 'int');
        $goodsObj = new IModel("goods");
        if (!$id) {
            $goods = $goodsObj->getObj();
            $id = $goods["id"];
        } else {
            $goods = $goodsObj->getObj("id = $id ");
        }
        //取“功能”的附加属性
        $AttrObj = new IQuery("attribute as at");
        $AttrObj->join = "inner join goods_attribute as ga on at.id = ga.attribute_id inner join pics as pc on ga.attribute_value=pc.id";
        $AttrObj->where = "ga.goods_id = $id and at.name like '%功能%' and at.type in (4,5)";
        $AttrObj->order = "at.name";
        $AttrObj->fields = "at.type, pc.name, pc.content, pc.value";
        $goods["attrbutes"] = $AttrObj->find();
        $this->setRenderData($goods);
        $this->redirect('feature');
    }

    function overview() {
		$this->menu_name = "yunbang";
        $this->sub_menu_name = "overview";
        $id = IFilter::act(IReq::get('id'), 'int');
        $goodsObj = new IModel("goods");
        if (!$id) {
            $goods = $goodsObj->getObj();
            $id = $goods["id"];
        } else {
            $goods = $goodsObj->getObj("id = $id ");
        }
        //取“概述”的附加属性
        $AttrObj = new IQuery("attribute as at");
        $AttrObj->join = "inner join goods_attribute as ga on at.id = ga.attribute_id inner join pics as pc on ga.attribute_value=pc.id";
        $AttrObj->where = "ga.goods_id = $id and at.name like '%概述%' and at.type in (4,5)";
        $AttrObj->order = "at.name";
        $AttrObj->fields = "at.type, pc.name, pc.content, pc.value";
        $goods["attrbutes"] = $AttrObj->find();
        $goods["callback"] = "/site/overview/";
        $this->setRenderData($goods);
        $this->redirect('overview');
    }

	function video() {
		$this->menu_name = "yunbang";
        $this->sub_menu_name = "video";
        $id = IFilter::act(IReq::get('id'), 'int');
        $goodsObj = new IModel("goods");
        if (!$id) {
            $goods = $goodsObj->getObj();
            $id = $goods["id"];
        } else {
            $goods = $goodsObj->getObj("id = $id ");
        }
        //取“概述”的附加属性
        $AttrObj = new IQuery("attribute as at");
        $AttrObj->join = "inner join goods_attribute as ga on at.id = ga.attribute_id inner join pics as pc on ga.attribute_value=pc.id";
        $AttrObj->where = "ga.goods_id = $id and at.name like '%概述%' and at.type in (4,5)";
        $AttrObj->order = "at.name";
        $AttrObj->fields = "at.type, pc.name, pc.content, pc.value";
        $goods["attrbutes"] = $AttrObj->find();
        $this->setRenderData($goods);
        $this->redirect('video');
    }

	function crowdfunding() {
		
        $this->redirect('crowdfunding');
    }
	function vcrowddefi() {
		
        $this->redirect('vcrowddefi');
    }
	function dajine() {
		
        $this->redirect('dajine');
    }
	function waijirenshi() {
		
        $this->redirect('waijirenshi');
    }
	function weizhifu() {
		
        $this->redirect('weizhifu');
    }
	function zhichijiaocheng() {
		
        $this->redirect('zhichijiaocheng');
    }

	function price() {
		$this->menu_name = "yunbang";
        $this->sub_menu_name = "price";
        $id = IFilter::act(IReq::get('id'), 'int');
        $goodsObj = new IModel("goods");
        if (!$id) {
            $goods = $goodsObj->getObj();
            $id = $goods["id"];
        } else {
            $goods = $goodsObj->getObj("id = $id ");
        }
        //取“概述”的附加属性
        $AttrObj = new IQuery("attribute as at");
        $AttrObj->join = "inner join goods_attribute as ga on at.id = ga.attribute_id inner join pics as pc on ga.attribute_value=pc.id";
        $AttrObj->where = "ga.goods_id = $id and at.name like '%概述%' and at.type in (4,5)";
        $AttrObj->order = "at.name";
        $AttrObj->fields = "at.type, pc.name, pc.content, pc.value";
        $goods["attrbutes"] = $AttrObj->find();
        $this->setRenderData($goods);
        $this->redirect('price');
    }

	function system() {
		$this->menu_name = "yunbang";
        $this->sub_menu_name = "system";
        $id = IFilter::act(IReq::get('id'), 'int');
        $goodsObj = new IModel("goods");
        if (!$id) {
            $goods = $goodsObj->getObj();
            $id = $goods["id"];
        } else {
            $goods = $goodsObj->getObj("id = $id ");
        }
        //取“系统”的附加属性
        $AttrObj = new IQuery("attribute as at");
        $AttrObj->join = "inner join goods_attribute as ga on at.id = ga.attribute_id inner join pics as pc on ga.attribute_value=pc.id";
        $AttrObj->where = "ga.goods_id = $id and at.name like '%系统%' and at.type in (4,5)";
        $AttrObj->order = "at.name";
        $AttrObj->fields = "at.type, pc.name, pc.content, pc.value";
        $goods["attrbutes"] = $AttrObj->find();
        $this->setRenderData($goods);
        $this->redirect('system');
    }

    function download() {
		$this->menu_name = "download";
        $id = IFilter::act(IReq::get('id'), 'int');
        $goodsObj = new IModel("goods");
        if (!$id) {
            $goods = $goodsObj->getObj();
            $id = $goods["id"];
        } else {
            $goods = $goodsObj->getObj("id = $id ");
        }
        //取“系统”的附加属性
        $AttrObj = new IQuery("attribute as at");
        $AttrObj->join = "inner join goods_attribute as ga on at.id = ga.attribute_id inner join pics as pc on ga.attribute_value=pc.id";
        $AttrObj->where = "ga.goods_id = $id and at.name like '%系统%' and at.type in (4,5)";
        $AttrObj->order = "at.name";
        $AttrObj->fields = "at.type, pc.name, pc.content, pc.value";
        $goods["attrbutes"] = $AttrObj->find();
		//$goods["downurl"]=Db_Meicloud::geturl();
		$goods["downurl"]="http://d.meibeike.com//mcfs/ota/pcclient/CloudBar_setup_v1.0.2.1.exe";
	//$goods["downurl"]=Db_Meicloud::userLogin("13430981860", md5("gsd123456789"), 0)
		$goods["callback"] = "/site/download/";
		$this->setRenderData($goods);
        $this->redirect('download');
    }

    //[首页]商品搜索
    function search_list() {
        $this->word = IFilter::act(IReq::get('word'), 'text');
        $cat_id = IFilter::act(IReq::get('cat'), 'int');

        if (preg_match("|^[\w\x7f-\xff]+$|", $this->word)) {
            if ($cat_id > 0) {
                $tb_goods = new IQuery('goods as go');
                $tb_goods->join = "left join category_extend as ca on go.id = ca.goods_id";
                $tb_goods->where = "go.name like '%$this->word%' or FIND_IN_SET('$this->word',go.search_words) and go.is_del = 0 and ca.category_id = $cat_id";
                $tb_goods->fields = "count(*) as num";
                $goodsNum = $tb_goods->find();
                $this->findSum = $goodsNum[0]['num'];
            } else {
                $goodsObj = new IModel('goods');
                $goodsNum = $goodsObj->getObj('name like "%' . $this->word . '%" or FIND_IN_SET("' . $this->word . '",search_words) and is_del=0', 'count(*) as num');
                $this->findSum = $goodsNum['num'];
            }

            //搜索关键字
            $tb_sear = new IModel('search');
            $search_info = $tb_sear->getObj('keyword = "' . $this->word . '"', 'id');

            //如果是第一页，相应关键词的被搜索数量才加1
            if ($search_info && intval(IReq::get('page')) < 2) {
                //禁止刷新+1
                $allow_sep = "30";
                $flag = false;
                $time = ICookie::get('step');
                if (isset($time)) {
                    if (time() - $time > $allow_sep) {
                        ICookie::set('step', time());
                        $flag = true;
                    }
                } else {
                    ICookie::set('step', time());
                    $flag = true;
                }
                if ($flag) {
                    $tb_sear->setData(array('num' => 'num + 1'));
                    $tb_sear->update('id=' . $search_info['id'], 'num');
                }
            } elseif (!$search_info) {
                //如果数据库中没有这个词的信息，则新添
                $tb_sear->setData(array('keyword' => $this->word, 'num' => 1));
                $tb_sear->add();
            }
        } else {
            IError::show(403, '请输入正确的查询关键词');
        }
        $this->cat_id = $cat_id;
        $this->redirect('search_list');
    }

    //[site,ucenter头部分]自动完成
    function autoComplete() {
        $word = IFilter::act(IReq::get('word'));
        $isError = true;
        $data = array();

        if ($word != '' && $word != '%' && $word != '_') {
            $wordObj = new IModel('keyword');
            $wordList = $wordObj->query('word like "' . $word . '%" and word != "' . $word . '"', 'word, goods_nums', '', '', 10);

            if (!empty($wordList)) {
                $isError = false;
                $data = $wordList;
            }
        }

        //json数据
        $result = array(
            'isError' => $isError,
            'data' => $data,
        );

        echo JSON::encode($result);
    }

    //[首页]邮箱订阅
    function email_registry() {
        $email = IReq::get('email');
        $result = array('isError' => true);

        if (!IValidate::email($email)) {
            $result['message'] = '请填写正确的email地址';
        } else {
            $emailRegObj = new IModel('email_registry');
            $emailRow = $emailRegObj->getObj('email = "' . $email . '"');

            if (!empty($emailRow)) {
                $result['message'] = '此email已经订阅过了';
            } else {
                $dataArray = array(
                    'email' => $email,
                );
                $emailRegObj->setData($dataArray);
                $status = $emailRegObj->add();
                if ($status == true) {
                    $result = array(
                        'isError' => false,
                        'message' => '订阅成功',
                    );
                } else {
                    $result['message'] = '订阅失败';
                }
            }
        }
        echo JSON::encode($result);
    }

    //[列表页]商品
    function pro_list() {
        $this->catId = IFilter::act(IReq::get('cat'), 'int'); //分类id
        $this->childId = goods_class::catChild($this->catId); //获取子分类

        if ($this->catId == 0) {
            IError::show(403, '缺少分类ID');
        } else {
            //查找分类信息
            $catObj = new IModel('category');
            $this->catRow = $catObj->getObj('id = ' . $this->catId);

            if (empty($this->catRow)) {
                IError::show(403, '此分类不存在');
            }

            $this->spec = array();
            $modelObj = new IModel('model');
            $modelRow = $modelObj->getObj('id = ' . $this->catRow['model_id']);

            if (isset($modelRow['spec_ids']) && $modelRow['spec_ids']) {
                $specObj = new IModel('spec');
                $this->spec = $specObj->query("id in (" . $modelRow['spec_ids'] . ")");
            }

            $this->redirect('pro_list');
        }
    }

    //咨询
    function consult() {
        $this->goods_id = IFilter::act(IReq::get('id'), 'int');
        $this->callback = IReq::get('callback');

        if ($this->goods_id == 0) {
            IError::show(403, '缺少商品ID参数');
        }

        $goodsObj = new IModel('goods');
        $goodsRow = $goodsObj->getObj('id = ' . $this->goods_id);

        if (!$goodsRow) {
            IError::show(403, '商品数据不存在');
        }

        //获取次商品的评论数和平均分(保留小数点后一位)
        $commentObj = new IModel('comment');
        $commentRow = $commentObj->getObj('goods_id = ' . $this->goods_id, 'count(*) as comments,sum(`point`)/count(*) as apoint');
        $goodsRow['apoint'] = round($commentRow['apoint'], 1);
        $goodsRow['comments'] = $commentRow['comments'];

        $this->goodsRow = $goodsRow;
        $this->redirect('consult');
    }

    //咨询动作
    function consult_act() {
        $goods_id = IFilter::act(IReq::get('goods_id', 'post'), 'int');
        $captcha = IFilter::act(IReq::get('captcha', 'post'));
        $question = IFilter::act(IReq::get('question', 'post'));
        $type = IFilter::act(IReq::get('type'), 'int');
        $callback = IReq::get('callback');
        $message = '';

        if ($captcha != ISafe::get('captcha')) {
            $message = '验证码输入不正确';
        } else if (!trim($question)) {
            $message = '咨询内容不能为空';
        } else if ($goods_id == 0) {
            $message = '商品ID不能为空';
        } else {
            $goodsObj = new IModel('goods');
            $goodsRow = $goodsObj->getObj('id = ' . $goods_id);
            if (empty($goodsRow)) {
                $message = '不存在此商品';
            }
        }

        if ($message != '') {
            $this->callback = $callback;
            $this->goods_id = $goods_id;
            $dataArray = array(
                'type' => $type,
                'question' => $question,
            );
            $this->consultRow = $dataArray;

            //渲染goods数据
            $goodsObj = new IModel('goods');
            $goodsRow = $goodsObj->getObj('id = ' . $this->goods_id);

            //获取次商品的评论数和平均分(保留小数点后一位)
            $commentObj = new IModel('comment');
            $commentRow = $commentObj->getObj('goods_id = ' . $this->goods_id, 'count(*) as comments,sum(`point`)/count(*) as apoint');
            $goodsRow['apoint'] = round($commentRow['apoint'], 1);
            $goodsRow['comments'] = $commentRow['comments'];
            $this->goodsRow = $goodsRow;

            $this->redirect('consult', false);
            Util::showMessage($message);
        } else {
            $dataArray = array(
                'question' => $question,
                'goods_id' => $goods_id,
                'user_id' => isset($this->user['user_id']) ? $this->user['user_id'] : 0,
                'time' => ITime::getDateTime(),
                'type' => $type,
            );

            $referObj = new IModel('refer');
            $referObj->setData($dataArray);
            $referObj->add();

            $this->redirect('success?callback=/site/products/id/' . $goods_id);
        }
    }

    //公告详情页面
    function notice_detail() {
        $this->notice_id = IFilter::act(IReq::get('id'), 'int');
        if ($this->notice_id == '') {
            IError::show(403, '缺少公告ID参数');
        } else {
            $noObj = new IModel('announcement');
            $this->noticeRow = $noObj->getObj('id = ' . $this->notice_id);
            if (empty($this->noticeRow)) {
                IError::show(403, '公告信息不存在');
            }
            $this->redirect('notice_detail');
        }
    }

    //咨询详情页面
    function article_detail() {
        $this->article_id = IFilter::act(IReq::get('id'), 'int');
        if ($this->article_id == '') {
            IError::show(403, '缺少咨询ID参数');
        } else {
            $articleObj = new IModel('article');
            $this->articleRow = $articleObj->getObj('id = ' . $this->article_id);
            if (empty($this->articleRow)) {
                IError::show(403, '资讯文章不存在');
                exit;
            }

            //关联商品
            $relationObj = new IQuery('relation as r');
            $relationObj->join = ' left join goods as go on r.goods_id = go.id ';
            $relationObj->where = ' r.article_id = ' . $this->article_id . ' and go.id is not null ';

            $this->relationList = $relationObj->find();
            $this->redirect('article_detail');
        }
    }

    //商品展示
    function products() {
        $goods_id = IFilter::act(IReq::get('id'), 'int');

        if (!$goods_id) {
            IError::show(403, "传递的参数不正确");
            exit;
        }

		
        //使用商品id获得商品信息
        $tb_goods = new IModel('goods');
        $goods_info = $tb_goods->getObj('id=' . $goods_id . " AND is_del=0");
        if (!$goods_info) {
            IError::show(403, "这件商品不存在");
            exit;
        }

        //品牌名称
        if ($goods_info['brand_id']) {
            $tb_brand = new IModel('brand');
            $brand_info = $tb_brand->getObj('id=' . $goods_info['brand_id']);
            if ($brand_info) {
                $goods_info['brand'] = $brand_info['name'];
            }
        }

        //获取商品分类
        $categoryObj = new IModel('category_extend as ca,category as c');
        $categoryRow = $categoryObj->getObj('ca.goods_id = ' . $goods_id . ' and ca.category_id = c.id', 'c.id,c.name');
        $goods_info['category'] = $categoryRow ? $categoryRow['id'] : 0;

        //商品图片
        $tb_goods_photo = new IQuery('goods_photo_relation as g');
        $tb_goods_photo->fields = 'p.id AS photo_id,p.img ';
        $tb_goods_photo->join = 'left join goods_photo as p on p.id=g.photo_id ';
        $tb_goods_photo->where = ' g.goods_id=' . $goods_id;
        $goods_info['photo'] = $tb_goods_photo->find();
        foreach ($goods_info['photo'] as $key => $val) {
            //对默认第一张图片位置进行前置
            if ($val['img'] == $goods_info['img']) {
                $temp = $goods_info['photo'][0];
                $goods_info['photo'][0] = $val;
                $goods_info['photo'][$key] = $temp;
            }
        }

        //商品是否参加促销活动(团购，抢购)
        $goods_info['promo'] = IReq::get('promo') ? IReq::get('promo') : '';
        $goods_info['active_id'] = IReq::get('active_id') ? IFilter::act(IReq::get('active_id'), 'int') : '';
        if ($goods_info['promo']) {
            switch ($goods_info['promo']) {
                //团购
                case 'groupon': {
                        $tb_regiment = new IModel('regiment');
                        $goods_info['regiment'] = $tb_regiment->getObj('goods_id = ' . $goods_id . ' and NOW() between start_time and end_time');
                    }
                    break;

                //抢购
                case 'time': {
                        $tb_promotion = new IModel('promotion');
                        $goods_info['promotion'] = $tb_promotion->getObj('type=1 and `condition`=' . $goods_id . ' and  NOW() between start_time and end_time');
                    }
                    break;

                default: {
                        IError::show(403, "活动不存在");
                        exit;
                    }
                    break;
            }
        }

        //获得扩展属性
        $tb_attribute_goods = new IQuery('goods_attribute as g');
        $tb_attribute_goods->join = 'left join attribute as a on a.id=g.attribute_id ';
        $tb_attribute_goods->fields = ' a.name,g.attribute_value ';
        $tb_attribute_goods->where = " goods_id='" . $goods_id . "' and attribute_id!=''";
        $goods_info['attribute'] = $tb_attribute_goods->find();

        //[数据挖掘]最终购买此商品的用户ID列表
        $tb_good = new IQuery('order_goods as og');
        $tb_good->join = 'left join order as o on og.order_id=o.id ';
        $tb_good->fields = 'DISTINCT o.user_id';
        $tb_good->where = 'og.goods_id = ' . $goods_id;
        $tb_good->limit = 5;
        $bugGoodInfo = $tb_good->find();
        if ($bugGoodInfo) {
            $shop_goods_array = array();
            foreach ($bugGoodInfo as $key => $val) {
                $shop_goods_array[] = $val['user_id'];
            }
            $goods_info['buyer_id'] = join(',', $shop_goods_array);
        }

        //购买记录
        $tb_shop = new IQuery('order_goods as og');
        $tb_shop->join = 'left join order as o on o.id=og.order_id';
        $tb_shop->fields = 'count(*) as totalNum';
        $tb_shop->where = 'og.goods_id=' . $goods_id . ' and o.status = 5';
        $shop_info = $tb_shop->find();
        $goods_info['buy_num'] = 0;
        if ($shop_info) {
            $goods_info['buy_num'] = $shop_info[0]['totalNum'];
        }

        //购买前咨询
        $tb_refer = new IModel('refer');
        $refeer_info = $tb_refer->getObj('goods_id=' . $goods_id, 'count(*) as totalNum');
        $goods_info['refer'] = 0;
        if ($refeer_info) {
            $goods_info['refer'] = $refeer_info['totalNum'];
        }

        //网友讨论
        $tb_discussion = new IModel('discussion');
        $discussion_info = $tb_discussion->getObj('goods_id=' . $goods_id, 'count(*) as totalNum');
        $goods_info['discussion'] = 0;
        if ($discussion_info) {
            $goods_info['discussion'] = $discussion_info['totalNum'];
        }

        //获得商品的价格区间
        $tb_product = new IModel('products');
        $product_info = $tb_product->getObj('goods_id=' . $goods_id, 'max(sell_price) as maxSellPrice ,min(sell_price) as minSellPrice,max(market_price) as maxMarketPrice,min(market_price) as minMarketPrice');
        $goods_info['maxSellPrice'] = '';
        $goods_info['minSellPrice'] = '';
        $goods_info['minMarketPrice'] = '';
        $goods_info['maxMarketPrice'] = '';
        if ($product_info) {
            $goods_info['maxSellPrice'] = $product_info['maxSellPrice'];
            $goods_info['minSellPrice'] = $product_info['minSellPrice'];
            $goods_info['minMarketPrice'] = $product_info['minMarketPrice'];
            $goods_info['maxMarketPrice'] = $product_info['maxMarketPrice'];
        }

        //获得会员价
        $countsumInstance = new countsum();
        $goods_info['group_price'] = $countsumInstance->getGroupPrice($goods_id, 'goods');

        //获取商家信息
        if ($goods_info['seller_id']) {
            $sellerDB = new IModel('seller');
            $goods_info['seller'] = $sellerDB->getObj('id = ' . $goods_info['seller_id']);
        }

        //增加浏览次数
        if (!ISafe::get('visit' . $goods_id)) {
            $tb_goods->setData(array('visit' => 'visit + 1'));
            $tb_goods->update('id = ' . $goods_id, 'visit');
            ISafe::set('visit' . $goods_id, '1');
        }

		$goods_info["callback"]='/site/products/id/'. $goods_id;


        $this->setRenderData($goods_info);
        $this->redirect('products');
    }


    //新商品展示
    function products_new() {
    	$goods_id = IFilter::act(IReq::get('id'), 'int');
    
    	if (!$goods_id) {
    		IError::show(403, "传递的参数不正确");
    		exit;
    	}
    
    
    	//使用商品id获得商品信息
    	$tb_goods = new IModel('goods');
    	$goods_info = $tb_goods->getObj('id=' . $goods_id . " AND is_del=0");
    	if (!$goods_info) {
    		IError::show(403, "这件商品不存在");
    		exit;
    	}
    
    	//品牌名称
    	if ($goods_info['brand_id']) {
    		$tb_brand = new IModel('brand');
    		$brand_info = $tb_brand->getObj('id=' . $goods_info['brand_id']);
    		if ($brand_info) {
    			$goods_info['brand'] = $brand_info['name'];
    		}
    	}
    
    	//获取商品分类
    	$categoryObj = new IModel('category_extend as ca,category as c');
    	$categoryRow = $categoryObj->getObj('ca.goods_id = ' . $goods_id . ' and ca.category_id = c.id', 'c.id,c.name');
    	$goods_info['category'] = $categoryRow ? $categoryRow['id'] : 0;
    
    	//商品图片
    	$tb_goods_photo = new IQuery('goods_photo_relation as g');
    	$tb_goods_photo->fields = 'p.id AS photo_id,p.img ';
    	$tb_goods_photo->join = 'left join goods_photo as p on p.id=g.photo_id ';
    	$tb_goods_photo->where = ' g.goods_id=' . $goods_id;
    	$goods_info['photo'] = $tb_goods_photo->find();
    	foreach ($goods_info['photo'] as $key => $val) {
    		//对默认第一张图片位置进行前置
    		if ($val['img'] == $goods_info['img']) {
    			$temp = $goods_info['photo'][0];
    			$goods_info['photo'][0] = $val;
    			$goods_info['photo'][$key] = $temp;
    		}
    	}
    
    	//商品是否参加促销活动(团购，抢购)
    	$goods_info['promo'] = IReq::get('promo') ? IReq::get('promo') : '';
    	$goods_info['active_id'] = IReq::get('active_id') ? IFilter::act(IReq::get('active_id'), 'int') : '';
    	if ($goods_info['promo']) {
    		switch ($goods_info['promo']) {
    			//团购
    			case 'groupon': {
    				$tb_regiment = new IModel('regiment');
    				$goods_info['regiment'] = $tb_regiment->getObj('goods_id = ' . $goods_id . ' and NOW() between start_time and end_time');
    			}
    			break;
    
    			//抢购
    			case 'time': {
    				$tb_promotion = new IModel('promotion');
    				$goods_info['promotion'] = $tb_promotion->getObj('type=1 and `condition`=' . $goods_id . ' and  NOW() between start_time and end_time');
    			}
    			break;
    
    			default: {
    				IError::show(403, "活动不存在");
    				exit;
    			}
    			break;
    		}
    	}
    
    	//获得扩展属性
    	$tb_attribute_goods = new IQuery('goods_attribute as g');
    	$tb_attribute_goods->join = 'left join attribute as a on a.id=g.attribute_id ';
    	$tb_attribute_goods->fields = ' a.name,g.attribute_value ';
    	$tb_attribute_goods->where = " goods_id='" . $goods_id . "' and attribute_id!=''";
    	$goods_info['attribute'] = $tb_attribute_goods->find();
    
    	//[数据挖掘]最终购买此商品的用户ID列表
    	$tb_good = new IQuery('order_goods as og');
    	$tb_good->join = 'left join order as o on og.order_id=o.id ';
    	$tb_good->fields = 'DISTINCT o.user_id';
    	$tb_good->where = 'og.goods_id = ' . $goods_id;
    	$tb_good->limit = 5;
    	$bugGoodInfo = $tb_good->find();
    	if ($bugGoodInfo) {
    		$shop_goods_array = array();
    		foreach ($bugGoodInfo as $key => $val) {
    			$shop_goods_array[] = $val['user_id'];
    		}
    		$goods_info['buyer_id'] = join(',', $shop_goods_array);
    	}
    
    	//购买记录
    	$tb_shop = new IQuery('order_goods as og');
    	$tb_shop->join = 'left join order as o on o.id=og.order_id';
    	$tb_shop->fields = 'count(*) as totalNum';
    	$tb_shop->where = 'og.goods_id=' . $goods_id . ' and o.status = 5';
    	$shop_info = $tb_shop->find();
    	$goods_info['buy_num'] = 0;
    	if ($shop_info) {
    		$goods_info['buy_num'] = $shop_info[0]['totalNum'];
    	}
    
    	//购买前咨询
    	$tb_refer = new IModel('refer');
    	$refeer_info = $tb_refer->getObj('goods_id=' . $goods_id, 'count(*) as totalNum');
    	$goods_info['refer'] = 0;
    	if ($refeer_info) {
    		$goods_info['refer'] = $refeer_info['totalNum'];
    	}
    
    	//网友讨论
    	$tb_discussion = new IModel('discussion');
    	$discussion_info = $tb_discussion->getObj('goods_id=' . $goods_id, 'count(*) as totalNum');
    	$goods_info['discussion'] = 0;
    	if ($discussion_info) {
    		$goods_info['discussion'] = $discussion_info['totalNum'];
    	}
    
    	//获得商品的价格区间
    	$tb_product = new IModel('products');
    	$product_info = $tb_product->getObj('goods_id=' . $goods_id, 'max(sell_price) as maxSellPrice ,min(sell_price) as minSellPrice,max(market_price) as maxMarketPrice,min(market_price) as minMarketPrice');
    	$goods_info['maxSellPrice'] = '';
    	$goods_info['minSellPrice'] = '';
    	$goods_info['minMarketPrice'] = '';
    	$goods_info['maxMarketPrice'] = '';
    	if ($product_info) {
    		$goods_info['maxSellPrice'] = $product_info['maxSellPrice'];
    		$goods_info['minSellPrice'] = $product_info['minSellPrice'];
    		$goods_info['minMarketPrice'] = $product_info['minMarketPrice'];
    		$goods_info['maxMarketPrice'] = $product_info['maxMarketPrice'];
    	}
    
    	//获得会员价
    	$countsumInstance = new countsum();
    	$goods_info['group_price'] = $countsumInstance->getGroupPrice($goods_id, 'goods');
    
    	//获取商家信息
    	if ($goods_info['seller_id']) {
    		$sellerDB = new IModel('seller');
    		$goods_info['seller'] = $sellerDB->getObj('id = ' . $goods_info['seller_id']);
    	}
    
    	//增加浏览次数
    	if (!ISafe::get('visit' . $goods_id)) {
    		$tb_goods->setData(array('visit' => 'visit + 1'));
    		$tb_goods->update('id = ' . $goods_id, 'visit');
    		ISafe::set('visit' . $goods_id, '1');
    	}
    
    	$goods_info["callback"]='/site/products_new/id/'. $goods_id;
    
    
    	$this->setRenderData($goods_info);
    	$this->redirect('products_new');
    }
    
    
    function products_wap() {
    	//$this->layout = '';
        $goods_id = IFilter::act(IReq::get('id'), 'int');
    
        if (!$goods_id) {
            IError::show(403, "传递的参数不正确");
            exit;
        }
    
    
        //使用商品id获得商品信息
        $tb_goods = new IModel('goods');
        $goods_info = $tb_goods->getObj('id=' . $goods_id . " AND is_del=0");
        if (!$goods_info) {
            IError::show(403, "这件商品不存在");
            exit;
        }
    
        //品牌名称
        if ($goods_info['brand_id']) {
            $tb_brand = new IModel('brand');
            $brand_info = $tb_brand->getObj('id=' . $goods_info['brand_id']);
            if ($brand_info) {
                $goods_info['brand'] = $brand_info['name'];
            }
        }
    
        //获取商品分类
        $categoryObj = new IModel('category_extend as ca,category as c');
        $categoryRow = $categoryObj->getObj('ca.goods_id = ' . $goods_id . ' and ca.category_id = c.id', 'c.id,c.name');
        $goods_info['category'] = $categoryRow ? $categoryRow['id'] : 0;
    
        //商品图片
        $tb_goods_photo = new IQuery('goods_photo_relation as g');
        $tb_goods_photo->fields = 'p.id AS photo_id,p.img ';
        $tb_goods_photo->join = 'left join goods_photo as p on p.id=g.photo_id ';
        $tb_goods_photo->where = ' g.goods_id=' . $goods_id;
        $goods_info['photo'] = $tb_goods_photo->find();
        foreach ($goods_info['photo'] as $key => $val) {
            //对默认第一张图片位置进行前置
            if ($val['img'] == $goods_info['img']) {
                $temp = $goods_info['photo'][0];
                $goods_info['photo'][0] = $val;
                $goods_info['photo'][$key] = $temp;
            }
        }
    
        //商品是否参加促销活动(团购，抢购)
        $goods_info['promo'] = IReq::get('promo') ? IReq::get('promo') : '';
        $goods_info['active_id'] = IReq::get('active_id') ? IFilter::act(IReq::get('active_id'), 'int') : '';
        if ($goods_info['promo']) {
            switch ($goods_info['promo']) {
                //团购
                case 'groupon': {
                    $tb_regiment = new IModel('regiment');
                    $goods_info['regiment'] = $tb_regiment->getObj('goods_id = ' . $goods_id . ' and NOW() between start_time and end_time');
                }
                break;
    
                //抢购
                case 'time': {
                    $tb_promotion = new IModel('promotion');
                    $goods_info['promotion'] = $tb_promotion->getObj('type=1 and `condition`=' . $goods_id . ' and  NOW() between start_time and end_time');
                }
                break;
    
                default: {
                    IError::show(403, "活动不存在");
                    exit;
                }
                break;
            }
        }
    
        //获得扩展属性
        $tb_attribute_goods = new IQuery('goods_attribute as g');
        $tb_attribute_goods->join = 'left join attribute as a on a.id=g.attribute_id ';
        $tb_attribute_goods->fields = ' a.name,g.attribute_value ';
        $tb_attribute_goods->where = " goods_id='" . $goods_id . "' and attribute_id!=''";
        $goods_info['attribute'] = $tb_attribute_goods->find();
    
        //[数据挖掘]最终购买此商品的用户ID列表
        $tb_good = new IQuery('order_goods as og');
        $tb_good->join = 'left join order as o on og.order_id=o.id ';
        $tb_good->fields = 'DISTINCT o.user_id';
        $tb_good->where = 'og.goods_id = ' . $goods_id;
        $tb_good->limit = 5;
        $bugGoodInfo = $tb_good->find();
        if ($bugGoodInfo) {
            $shop_goods_array = array();
            foreach ($bugGoodInfo as $key => $val) {
                $shop_goods_array[] = $val['user_id'];
            }
            $goods_info['buyer_id'] = join(',', $shop_goods_array);
        }
    
        //购买记录
        $tb_shop = new IQuery('order_goods as og');
        $tb_shop->join = 'left join order as o on o.id=og.order_id';
        $tb_shop->fields = 'count(*) as totalNum';
        $tb_shop->where = 'og.goods_id=' . $goods_id . ' and o.status = 5';
        $shop_info = $tb_shop->find();
        $goods_info['buy_num'] = 0;
        if ($shop_info) {
            $goods_info['buy_num'] = $shop_info[0]['totalNum'];
        }
    
        //购买前咨询
        $tb_refer = new IModel('refer');
        $refeer_info = $tb_refer->getObj('goods_id=' . $goods_id, 'count(*) as totalNum');
        $goods_info['refer'] = 0;
        if ($refeer_info) {
            $goods_info['refer'] = $refeer_info['totalNum'];
        }
    
        //网友讨论
        $tb_discussion = new IModel('discussion');
        $discussion_info = $tb_discussion->getObj('goods_id=' . $goods_id, 'count(*) as totalNum');
        $goods_info['discussion'] = 0;
        if ($discussion_info) {
            $goods_info['discussion'] = $discussion_info['totalNum'];
        }
    
        //获得商品的价格区间
        $tb_product = new IModel('products');
        $product_info = $tb_product->getObj('goods_id=' . $goods_id, 'max(sell_price) as maxSellPrice ,min(sell_price) as minSellPrice,max(market_price) as maxMarketPrice,min(market_price) as minMarketPrice');
        $goods_info['maxSellPrice'] = '';
        $goods_info['minSellPrice'] = '';
        $goods_info['minMarketPrice'] = '';
        $goods_info['maxMarketPrice'] = '';
        if ($product_info) {
            $goods_info['maxSellPrice'] = $product_info['maxSellPrice'];
            $goods_info['minSellPrice'] = $product_info['minSellPrice'];
            $goods_info['minMarketPrice'] = $product_info['minMarketPrice'];
            $goods_info['maxMarketPrice'] = $product_info['maxMarketPrice'];
        }
    
        //获得会员价
        $countsumInstance = new countsum();
        $goods_info['group_price'] = $countsumInstance->getGroupPrice($goods_id, 'goods');
    
        //获取商家信息
        if ($goods_info['seller_id']) {
            $sellerDB = new IModel('seller');
            $goods_info['seller'] = $sellerDB->getObj('id = ' . $goods_info['seller_id']);
        }
    
        //增加浏览次数
        if (!ISafe::get('visit' . $goods_id)) {
            $tb_goods->setData(array('visit' => 'visit + 1'));
            $tb_goods->update('id = ' . $goods_id, 'visit');
            ISafe::set('visit' . $goods_id, '1');
        }
    
        $goods_info["callback"]='/site/products/id/'. $goods_id;
    
    
        $this->setRenderData($goods_info);
        $this->redirect('products_wap');
    }
    
    
 //商品展示
    function timebuy() {
        $goods_id = IFilter::act(IReq::get('id'), 'int');

        if (!$goods_id) {
            IError::show(403, "传递的参数不正确");
            exit;
        }

		
        //使用商品id获得商品信息
        $tb_goods = new IModel('goods');
        $goods_info = $tb_goods->getObj('id=' . $goods_id . " AND is_del=0");
        if (!$goods_info) {
            IError::show(403, "这件商品不存在");
            exit;
        }

        //品牌名称
        if ($goods_info['brand_id']) {
            $tb_brand = new IModel('brand');
            $brand_info = $tb_brand->getObj('id=' . $goods_info['brand_id']);
            if ($brand_info) {
                $goods_info['brand'] = $brand_info['name'];
            }
        }

        //获取商品分类
        $categoryObj = new IModel('category_extend as ca,category as c');
        $categoryRow = $categoryObj->getObj('ca.goods_id = ' . $goods_id . ' and ca.category_id = c.id', 'c.id,c.name');
        $goods_info['category'] = $categoryRow ? $categoryRow['id'] : 0;

        //商品图片
        $tb_goods_photo = new IQuery('goods_photo_relation as g');
        $tb_goods_photo->fields = 'p.id AS photo_id,p.img ';
        $tb_goods_photo->join = 'left join goods_photo as p on p.id=g.photo_id ';
        $tb_goods_photo->where = ' g.goods_id=' . $goods_id;
        $goods_info['photo'] = $tb_goods_photo->find();
        foreach ($goods_info['photo'] as $key => $val) {
            //对默认第一张图片位置进行前置
            if ($val['img'] == $goods_info['img']) {
                $temp = $goods_info['photo'][0];
                $goods_info['photo'][0] = $val;
                $goods_info['photo'][$key] = $temp;
            }
        }

        //商品是否参加促销活动(团购，抢购)
        $goods_info['promo'] = IReq::get('promo') ? IReq::get('promo') : '';
        $goods_info['active_id'] = IReq::get('active_id') ? IFilter::act(IReq::get('active_id'), 'int') : '';
        if ($goods_info['promo']) {
            switch ($goods_info['promo']) {
                //团购
                case 'groupon': {
                        $tb_regiment = new IModel('regiment');
                        $goods_info['regiment'] = $tb_regiment->getObj('goods_id = ' . $goods_id . ' and NOW() between start_time and end_time');
                    }
                    break;

                //抢购
                case 'time': {
                        $tb_promotion = new IModel('promotion');
                        $goods_info['promotion'] = $tb_promotion->getObj('type=1 and `condition`=' . $goods_id . ' and  NOW() between start_time and end_time');
                    }
                    break;

                default: {
                        IError::show(403, "活动不存在");
                        exit;
                    }
                    break;
            }
        }

        //获得扩展属性
        $tb_attribute_goods = new IQuery('goods_attribute as g');
        $tb_attribute_goods->join = 'left join attribute as a on a.id=g.attribute_id ';
        $tb_attribute_goods->fields = ' a.name,g.attribute_value ';
        $tb_attribute_goods->where = " goods_id='" . $goods_id . "' and attribute_id!=''";
        $goods_info['attribute'] = $tb_attribute_goods->find();

        //[数据挖掘]最终购买此商品的用户ID列表
        $tb_good = new IQuery('order_goods as og');
        $tb_good->join = 'left join order as o on og.order_id=o.id ';
        $tb_good->fields = 'DISTINCT o.user_id';
        $tb_good->where = 'og.goods_id = ' . $goods_id;
        $tb_good->limit = 5;
        $bugGoodInfo = $tb_good->find();
        if ($bugGoodInfo) {
            $shop_goods_array = array();
            foreach ($bugGoodInfo as $key => $val) {
                $shop_goods_array[] = $val['user_id'];
            }
            $goods_info['buyer_id'] = join(',', $shop_goods_array);
        }

        //购买记录
        $tb_shop = new IQuery('order_goods as og');
        $tb_shop->join = 'left join order as o on o.id=og.order_id';
        $tb_shop->fields = 'count(*) as totalNum';
        $tb_shop->where = 'og.goods_id=' . $goods_id . ' and o.status = 5';
        $shop_info = $tb_shop->find();
        $goods_info['buy_num'] = 0;
        if ($shop_info) {
            $goods_info['buy_num'] = $shop_info[0]['totalNum'];
        }

        //购买前咨询
        $tb_refer = new IModel('refer');
        $refeer_info = $tb_refer->getObj('goods_id=' . $goods_id, 'count(*) as totalNum');
        $goods_info['refer'] = 0;
        if ($refeer_info) {
            $goods_info['refer'] = $refeer_info['totalNum'];
        }

        //网友讨论
        $tb_discussion = new IModel('discussion');
        $discussion_info = $tb_discussion->getObj('goods_id=' . $goods_id, 'count(*) as totalNum');
        $goods_info['discussion'] = 0;
        if ($discussion_info) {
            $goods_info['discussion'] = $discussion_info['totalNum'];
        }

        //获得商品的价格区间
        $tb_product = new IModel('products');
        $product_info = $tb_product->getObj('goods_id=' . $goods_id, 'max(sell_price) as maxSellPrice ,min(sell_price) as minSellPrice,max(market_price) as maxMarketPrice,min(market_price) as minMarketPrice');
        $goods_info['maxSellPrice'] = '';
        $goods_info['minSellPrice'] = '';
        $goods_info['minMarketPrice'] = '';
        $goods_info['maxMarketPrice'] = '';
        if ($product_info) {
            $goods_info['maxSellPrice'] = $product_info['maxSellPrice'];
            $goods_info['minSellPrice'] = $product_info['minSellPrice'];
            $goods_info['minMarketPrice'] = $product_info['minMarketPrice'];
            $goods_info['maxMarketPrice'] = $product_info['maxMarketPrice'];
        }

        //获得会员价
        $countsumInstance = new countsum();
        $goods_info['group_price'] = $countsumInstance->getGroupPrice($goods_id, 'goods');

        //获取商家信息
        if ($goods_info['seller_id']) {
            $sellerDB = new IModel('seller');
            $goods_info['seller'] = $sellerDB->getObj('id = ' . $goods_info['seller_id']);
        }

        //增加浏览次数
        if (!ISafe::get('visit' . $goods_id)) {
            $tb_goods->setData(array('visit' => 'visit + 1'));
            $tb_goods->update('id = ' . $goods_id, 'visit');
            ISafe::set('visit' . $goods_id, '1');
        }

		$goods_info["callback"]='/site/timebuy/id/'. $goods_id;


        $this->setRenderData($goods_info);
        $this->redirect('timebuy');
    }



    //商品讨论更新
    function discussUpdate() {
        $goods_id = IFilter::act(IReq::get('id'), 'int');
        $content = IFilter::act(IReq::get('content'), 'text');
        $captcha = IReq::get('captcha');
        $return = array('isError' => true, 'message' => '');

        if (!$this->user['user_id']) {
            $return['message'] = '请先登录系统';
        } else if ($captcha != ISafe::get('captcha')) {
            $return['message'] = '验证码输入不正确';
        } else if (trim($content) == '') {
            $return['message'] = '内容不能为空';
        } else {
            $return['isError'] = false;

            //插入讨论表
            $tb_discussion = new IModel('discussion');
            $dataArray = array(
                'goods_id' => $goods_id,
                'user_id' => $this->user['user_id'],
                'time' => date('Y-m-d H:i:s'),
                'contents' => $content,
            );
            $tb_discussion->setData($dataArray);
            $tb_discussion->add();

            $return['time'] = $dataArray['time'];
            $return['contents'] = $content;
            $return['username'] = $this->user['username'];
        }
        echo JSON::encode($return);
    }

    //获取货品数据
    function getProduct() {
        $jsonData = JSON::decode(IReq::get('specJSON'));
        if (!$jsonData) {
            echo JSON::encode(array('flag' => 'fail', 'message' => '规格值不符合标准'));
            exit;
        }

        $goods_id = IFilter::act(IReq::get('goods_id'), 'int');
        $specJSON = IFilter::act(IReq::get('specJSON'));

        //获取货品数据
        $tb_products = new IModel('products');
        $procducts_info = $tb_products->getObj("goods_id = " . $goods_id . " and spec_array = '" . $specJSON . "'");

        //匹配到货品数据
        if (!$procducts_info) {
            echo JSON::encode(array('flag' => 'fail', 'message' => '没有找到相关货品'));
            exit;
        }

        //获得会员价
        $countsumInstance = new countsum();
        $group_price = $countsumInstance->getGroupPrice($procducts_info['id'], 'product');

        //会员价格
        if ($group_price !== null) {
            $procducts_info['group_price'] = $group_price;
        }

        echo JSON::encode(array('flag' => 'success', 'data' => $procducts_info));
    }
    
    
    //获取货品数据 新
    function getProductsInfo() {
        $tz = IReq::get('tz_name');
        $ccbing = IReq::get('ccbing');
        $ccbang = IReq::get('ccbang');

        $goods_id = IFilter::act(IReq::get('goods_id'), 'int');

        //获取货品数据
        $tb_products = new IModel('products');
        if($tz == "母棒+存储棒"){
            $procducts_info = $tb_products->getObj("goods_id = $goods_id and spec_array like '%\"$tz\"%' and spec_array like '%\"$ccbang\"%'");
        }else if ($tz == "母棒+存储碟"){
            $procducts_info = $tb_products->getObj("goods_id = $goods_id and spec_array like '%\"$tz\"%' and spec_array like '%\"$ccbing\"%'");
        }else{
            $procducts_info = $tb_products->getObj("goods_id = $goods_id and spec_array like '%\"$tz\"%' and spec_array like '%\"$ccbing\"%' and spec_array like '%\"$ccbang\"%'");
        }
        //匹配到货品数据
        if (!$procducts_info) {
            echo JSON::encode(array('flag' => 'fail', 'message' => '没有找到相关货品'));
            exit;
        }

        //获得会员价
        $countsumInstance = new countsum();
        $group_price = $countsumInstance->getGroupPrice($procducts_info['id'], 'product');

        //会员价格
        if ($group_price !== null) {
            $procducts_info['group_price'] = $group_price;
        }

        echo JSON::encode(array('flag' => 'success', 'data' => $procducts_info));
    }

    //顾客评论ajax获取
    function comment_ajax() {
        $goods_id = IFilter::act(IReq::get('goods_id'), 'int');
        $page = IFilter::act(IReq::get('page'), 'int') ? IReq::get('page') : 1;

        $commentDB = new IQuery('comment as c');
        $commentDB->join = 'left join goods as go on c.goods_id = go.id AND go.is_del = 0 left join user as u on u.id = c.user_id';
        $commentDB->fields = 'u.head_ico,u.username,c.time,c.comment_time,c.contents,c.point';
        $commentDB->where = 'c.goods_id = ' . $goods_id . ' and c.status = 1';
        $commentDB->order = 'c.id desc';
        $commentDB->page = $page;
        $data = $commentDB->find();
        $pageHtml = $commentDB->getPageBar("javascript:void(0);", 'onclick="comment_ajax([page])"');

        echo JSON::encode(array('data' => $data, 'pageHtml' => $pageHtml));
    }

    //购买记录ajax获取
    function history_ajax() {
        $goods_id = IFilter::act(IReq::get('goods_id'), 'int');
        $page = IFilter::act(IReq::get('page'), 'int') ? IReq::get('page') : 1;

        $orderGoodsDB = new IQuery('order_goods as og');
        $orderGoodsDB->join = 'left join order as o on og.order_id = o.id left join user as u on o.user_id = u.id';
        $orderGoodsDB->fields = 'o.user_id,og.goods_price,og.goods_nums,o.create_time as completion_time,u.username';
        $orderGoodsDB->where = 'og.goods_id = ' . $goods_id . ' and o.status = 5';
        $orderGoodsDB->order = 'o.create_time desc';
        $orderGoodsDB->page = $page;

        $data = $orderGoodsDB->find();
        $pageHtml = $orderGoodsDB->getPageBar("javascript:void(0);", 'onclick="history_ajax([page])"');

        echo JSON::encode(array('data' => $data, 'pageHtml' => $pageHtml));
    }

    //讨论数据ajax获取
    function discuss_ajax() {
        $goods_id = IFilter::act(IReq::get('goods_id'), 'int');
        $page = IFilter::act(IReq::get('page'), 'int') ? IReq::get('page') : 1;

        $discussDB = new IQuery('discussion as d');
        $discussDB->join = 'left join user as u on d.user_id = u.id';
        $discussDB->where = 'd.goods_id = ' . $goods_id;
        $discussDB->order = 'd.id desc';
        $discussDB->fields = 'u.username,d.time,d.contents';
        $discussDB->page = $page;

        $data = $discussDB->find();
        $pageHtml = $discussDB->getPageBar("javascript:void(0);", 'onclick="discuss_ajax([page])"');

        echo JSON::encode(array('data' => $data, 'pageHtml' => $pageHtml));
    }

    //买前咨询数据ajax获取
    function refer_ajax() {
        $goods_id = IFilter::act(IReq::get('goods_id'), 'int');
        $page = IFilter::act(IReq::get('page'), 'int') ? IReq::get('page') : 1;

        $referDB = new IQuery('refer as r');
        $referDB->join = 'left join user as u on r.user_id = u.id';
        $referDB->where = 'r.goods_id = ' . $goods_id;
        $referDB->order = 'r.id desc';
        $referDB->fields = 'u.username,u.head_ico,r.time,r.question,r.reply_time,r.answer';
        $referDB->page = $page;

        $data = $referDB->find();
        $pageHtml = $referDB->getPageBar("javascript:void(0);", 'onclick="refer_ajax([page])"');

        echo JSON::encode(array('data' => $data, 'pageHtml' => $pageHtml));
    }

    //评论列表页
    function comments_list() {
        $id = IFilter::act(IReq::get("id"), 'int');
        $type = IFilter::act(IReq::get("type"));

        $type_config = array('bad' => '1', 'middle' => '2,3,4', 'good' => '5');

        if (!isset($type_config[$type])) {
            $type = null;
        } else {
            $type = $type_config[$type];
        }

        $data['comment_list'] = array();

        $query = new IQuery("comment AS a");
        $query->fields = "a.*,b.username,b.head_ico";
        $query->join = "left join user AS b ON a.user_id=b.id";
        $query->where = " a.goods_id = {$id} ";

        if ($type !== null)
            $query->where = " a.goods_id={$id} AND a.status=1  AND a.point IN ($type)";
        else
            $query->where = "a.goods_id={$id} AND a.status=1 ";

        $query->order = "a.id DESC";
        $query->page = IReq::get('page') ? intval(IReq::get('page')) : 1;
        $query->pagesize = 10;

        $data['comment_list'] = $query->find();
        $this->comment_query = $query;

        if ($data['comment_list']) {
            $user_ids = array();
            foreach ($data['comment_list'] as $value) {
                $user_ids[] = $value['user_id'];
            }
            $user_ids = implode(",", array_unique($user_ids));
            $query = new IQuery("member AS a");
            $query->join = "left join user_group AS b ON a.user_id IN ({$user_ids}) AND a.group_id=b.id";
            $query->fields = "a.user_id,b.group_name";
            $user_info = $query->find();
            $user_info = Util::array_rekey($user_info, 'user_id');

            foreach ($data['comment_list'] as $key => $value) {
                $data['comment_list'][$key]['user_group_name'] = isset($user_info[$value['user_id']]['group_name']) ? $user_info[$value['user_id']]['group_name'] : '';
            }
        }

        $data = array_merge($data, Comment_Class::get_comment_info($id));
        $this->data = $data;
        $this->redirect('comments_list');
    }

    //提交评论页
    function comments() {
        if (IReq::get('id') === null) {
            IError::show(403, "传递的参数不完整");
        }

        $id = intval(IReq::get('id'));

        if (!isset($this->user['user_id']) || $this->user['user_id'] === null) {
            IError::show(403, "登录后才允许评论");
        }

        $can_submit = Comment_Class::can_comment($id, $this->user['user_id']);
        if ($can_submit[0] == -1) {
            IError::show(403, "没有这条数据");
        }

        $this->can_submit = $can_submit[0] == 1;
        $this->comment = $can_submit[1];
        $this->comment_info = Comment_Class::get_comment_info($this->comment['goods_id']);
        $this->redirect("comments");
    }

    /**
     * @brief 进行商品评论 ajax操作
     */
    public function comment_add() {
        if (!isset($this->user['user_id']) || $this->user['user_id'] === null) {
            die("未登录用户不能评论");
        }

        if (IReq::get('id') === null) {
            die("传递的参数不完整");
        }

        $id = IFilter::act(IReq::get('id'), 'int');
        $data = array();
        $data['point'] = IFilter::act(IReq::get('point'), 'float');
        $data['contents'] = IFilter::act(IReq::get("contents"), 'content');
        $data['status'] = 1;

        if ($data['point'] == 0) {
            die("请选择分数");
        }

        $can_submit = Comment_Class::can_comment($id, $this->user['user_id']);
        if ($can_submit[0] != 1) {
            die("您不能评论此件商品");
        }

        $data['comment_time'] = date("Y-m-d", ITime::getNow());

        $tb_comment = new IModel("comment");
        $tb_comment->setData($data);
        $re = $tb_comment->update("id={$id}");

        if ($re) {
            //同步更新goods表,comments,grade
            $commentRow = $tb_comment->getObj('id = ' . $id);

            $goodsDB = new IModel('goods');
            $goodsDB->setData(array(
                'comments' => 'comments + 1',
                'grade' => 'grade + ' . $commentRow['point'],
            ));
            $goodsDB->update('id = ' . $commentRow['goods_id'], array('grade', 'comments'));

            echo "success";
        } else {
            die("评论失败");
        }
    }

    function pic_show() {
        $this->layout = "";
        $this->redirect("pic_show");
    }

    function help() {
        $id = intval(IReq::get("id"));
//        $cat_id = intval(IReq::get("cat_id"));
        $is_menu = intval(IReq::get("is_menu"));
		
		// 显示html的标题，关键字，描述
//		if ($cat_id == 6) {
//			$this->show_title_key = "aboutus";
//		} elseif ($cat_id == 8) {
//			$this->show_title_key = "protocol";	
//		} elseif ($cat_id == 0) {
//			//$this->show_title_key = "contactus";	
//		}
				
//        $tb_help = new IModel("help");
//        if(!$cat_id){
//            $res = $tb_help->getObj("id={$id}");
//            $cat_id = $res["cat_id"];
//        }
//        $help_row = $tb_help->query("cat_id = $cat_id","*","sort","ASC");
//        
//        if (!$help_row || !is_array($help_row)) {
//            IError::show(404, "您查找的页面已经不存在了");
//        }
//        $this->help_row = $help_row;
        //var_dump($help_row);
        
//        $catDB = new IModel("help_category");
//        $help_cat = $catDB->getObj("id = ".$cat_id);
//        $this->help_cat = $help_cat;
//        $this->help_id = $id;
        $catDB = new IModel("help");
        $help = $catDB->getObj("id = ".$id);
        $this->help = $help;
        $this->help_cat_id = $help["cat_id"];
        $this->help_id = $id;
		
		$this->menu_key_name = $help["name"];

        $this->redirect("help");
    }

    function help_list() {
        $id = intval(IReq::get("id"));
        $tb_help_cat = new IModel("help_category");
        $cat_row = $tb_help_cat->query("id={$id}");
        if ($cat_row) {
            $this->cat_row = end($cat_row);
        } else {
            $this->cat_row = array('id' => 0, 'name' => '站点帮助');
        }
        $this->redirect("help_list");
    }

    function groupon() {
        /**
         * 团购清理
         */
        $t = regiment::time_limit();
        $join_time = time() - $t * 60;
        $join_time = date("Y-m-d H:i:s", $join_time);
        $tb = new IModel("regiment_user_relation");
        $list = $tb->query("join_time<'{$join_time}'");
        $order_no = array();
        if ($list) {
            foreach ($list as $key => $value) {
                if ($value['order_no'] != "") {
                    $order_no[] = trim($value['order_no']);
                }
            }
            $order_no = array_unique($order_no);
            //找出没有付款的订单
            $order_no = "'" . implode("','", $order_no) . "'";
            $tb = new IModel("order");
            $tb->setData(array('if_del' => 1));
            $list = $tb->update("pay_status=0 AND order_no IN ({$order_no})");
        }
        /**
         * 团购清理结束
         */
        $id = IReq::get("id");
        $regiment_list = Regiment::getList($id);
        $regiment_list = $regiment_list['list'];
        $i = 1;
        foreach ($regiment_list as $key => $value) {
            $regiment_list[$key]['order_num'] = sprintf("%02s", $i);
            $i++;
        }
        $this->regiment_list = $regiment_list;

        //往期团购
        $this->ever_list = Regiment::getEverList();
        $this->redirect("groupon");
    }

    //团购列表
    function groupon_list() {
        $page = intval(IReq::get("page"));
        if ($page === null) {
            $page = 1;
        }

        $re = Regiment::getEverListByPage($page);
        $this->list = $re['list'];
        $this->query = $re['query'];
        $this->redirect("groupon_list");
    }

    //加入团购
    function groupon_join() {
        $id = intval(IReq::get("id"));
        if ($id === null) {
            $re = array('flag' => false, 'data' => '没有这条数据');
            die(JSON::encode($re));
        }

        $re = Regiment::join($id, isset($this->user['user_id']) ? $this->user['user_id'] : null );

        die(JSON::encode($re));
    }
    
    
    public function test_book2() {
        
        var_dump(IUrl::getHost().IUrl::creatUrl("/block/callback/_id/".$payment_id));
        return ;
        $goods_id = 1;
        $product_id = 1;
        $accept_name = 'test';
        $province = 1;
        $city = 1;
        $area = 1;
        $address = 'test';
        $mobile = '123123123123';
        $telphone = '123123123';
        $postcode = '1';
        $delivery_id = 1;
        $accept_time = 1;
        $pay_type = 1;
        $order_message = '12321321';
        $invoice_type = 1;
        $invoice_title = '';
        $goods_nums = 1;
        $order_no = 'SD141554846393';
        //$address_id = IFilter::act(IReq::get('address_id'));
        $dataArray = array();

        //防止表单重复提交
        /*if (IReq::get('timeKey') != null) {
            if (ISafe::get('timeKey') == IReq::get('timeKey')) {
                IError::show(403, '订单数据不能被重复提交');
                exit;
            }
        }*/
        
        $orderObj = new IModel('order');
        //使用order_no防止重复提交
        /*$isexist = $orderObj->getObj(" order_no = '$order_no'");
        if($isexist){
            IError::show(403, '订单数据不能被重复提交');
            exit;
        }*/

        /*if ($address_id && $address_id != 0) {
            //从地址表里取信息
            $addressDB = new IModel("address");
            $iaddress = $addressDB->getObj("id = $address_id");
            $province = $iaddress["province"];
            $city = $iaddress["city"];
            $area = $iaddress["area"];
            $address = $iaddress["address"];
            $accept_time = $iaddress["accept_time"];
            $accept_name = $iaddress["accept_name"];
            $postcode = $iaddress["zip"];
            $mobile = $iaddress["mobile"];
            $telphone = $iaddress["telphone"];
        }*/

        if ($province == 0 || $city == 0 || $area == 0) {
            IError::show(403, '请填写收货地址的省市地区');
        }

        if ($delivery_id == 0) {
            IError::show(403, '请选择配送方式');
        }

        //$user_id = ($this->user['user_id'] == null) ? 0 : $this->user['user_id'];
        $user_id = 1;
        if ($pay_type == 0) {
            IError::show(403, '请选择正确的支付方式');
        }

        //计算费用
        $goodsDB = new IModel("goods");
        $goods = $goodsDB->getObj("id = $goods_id");
        $price = $goods_nums * $goods["sell_price"];
        $specArray = array('name' => $goods['name'], 'value' => '');
        if ($product_id) {
            $productsDB = new IModel("products");
            $products = $productsDB->getObj("id = $product_id");
            $price = $goods_nums * $products["sell_price"];
            $products["img"] = $goods["img"];
            //规格
            $spec = block::show_spec($products['spec_array']);
            foreach ($spec as $skey => $svalue) {
                $specArray['value'] .= $skey . ':' . $svalue . ',';
            }
            $specArray['value'] = IFilter::addSlash(trim($specArray['value'], ','));
        } else {
            $products = $goods;
        }

        //生成的订单数据
        $dataArray = array(
            'order_no' => $order_no,
            'user_id' => $user_id,
            'accept_name' => $accept_name,
            'pay_type' => $pay_type,
            'distribution' => 1,
            'postcode' => $postcode,
            'telphone' => $telphone,
            'province' => $province,
            'city' => $city,
            'area' => $area,
            'address' => $address,
            'mobile' => $mobile,
            'create_time' => ITime::getDateTime(),
            'postscript' => $order_message,
            'accept_time' => $accept_time,
            'exp' => 0,
            'point' => 0,
            'type' => 0,
            //红包道具
            'prop' => null,
            //商品价格
            'payable_amount' => $price,
            'real_amount' => $price,
            //运费价格
            'payable_freight' => 0,
            'real_freight' => 0,
            //手续费
            'pay_fee' => 0,
            //发票
            'invoice' => 1,
            'invoice_type' => $invoice_type,
            'invoice_title' => $invoice_title,
            'taxes' => 0,
            //优惠价格
            'promotions' => 0,
            //订单应付总额
            'order_amount' => $price,
            //订单保价
            'if_insured' => 0,
            'insured' => 0,
        );

        $dataArray['order_amount'] = $dataArray['order_amount'] <= 0 ? 0 : $dataArray['order_amount'];

        
        $orderObj->setData($dataArray);

        $this->order_id = $orderObj->add();

        if ($this->order_id == false) {
            IError::show(403, '订单生成错误');
        }

        /* 将订单中的商品插入到order_goods表 */
        $order_goods = new IModel("order_goods");
        $dataArray = array("order_id" => $this->order_id,
            "goods_id" => $goods_id,
            "product_id" => $product_id,
            "img" => $products["img"],
            "goods_price" => $products["sell_price"],
            "real_price" => $products["sell_price"],
            "goods_array" => JSON::encode($specArray),
            "is_send" => 0,
            "delivery_id" => $delivery_id);


        $order_goods->setData($dataArray);
        $order_goods->add();
        //更新防重复提交标签
        //ISafe::set('timeKey', IReq::get('timeKey'));
        //跳转支付页
        $data = array("order_id"=>$this->order_id,
            "goods_id"=>$goods_id,
            "product_id"=>$products_id,
            "accept_name"=>$accept_name,
            "province"=>$province,
            "city"=>$city,
            "area"=>$area,
            "address"=>$address,
            "mobile"=>$mobile,
            "telphone"=>$telphone,
            "post_code"=>$post_code,
            "pay_type"=>$pay_type,
            "accept_time"=>$accept_time,
            "invoice_type"=>$invoice_type,
            "invoice_title"=>$invoice_title,
            "goods_price"=>$products["sell_price"],
            "goods_img"=>$products["img"],
            "goods_nums"=>$goods_nums,
            "goods_array"=> JSON::encode($specArray),
            "order_no"=>$order_no,
            "order_amount" => $price,
            );
        $this->setRenderData($data);
        $this->redirect("ucenter/book2");
        //直接支付
        //$this->redirect("/block/dopay/order_id/" . $this->order_id, true);
    }
    

    


	
    
    /*
     * 商品订购页面(未登录)
     */

    public function book() {
						if (IWeb::$app->config['tradeModel'] != 1) {
							IError::show(403, "本站已经关闭交易");
							exit;
						}
						//$user_id = intval($this->user['user_id']);
						$goods_id = IFilter::act(IReq::get('goods_id'), 'int');

						//使用商品id获得商品信息
						$tb_goods = new IModel('goods');
						//如果没商品ID，则取第一个未删除的商品
						if ($goods_id) {
							$goods_info = $tb_goods->getObj('id=' . $goods_id . " AND is_del=0");
						} else {
							$goods_info = $tb_goods->getObj("is_del=0");
							$goods_id = $goods_info["id"];
						}
						if (!$goods_info) {
							IError::show(403, "商品不存在");
							exit;
						}

						//品牌名称
						/* if ($goods_info['brand_id']) {
						  $tb_brand = new IModel('brand');
						  $brand_info = $tb_brand->getObj('id = ' . $goods_info['brand_id']);
						  if ($brand_info) {
						  $goods_info['brand'] = $brand_info['name'];
						  }
						  } */

						//获取商品分类
						/* $categoryObj = new IModel('category_extend as ca, category as c');
						  $categoryRow = $categoryObj->getObj('ca.goods_id = ' . $goods_id . ' and ca.category_id = c.id', 'c.id, c.name');
						  $goods_info['category'] = $categoryRow ? $categoryRow['id'] : 0; */

						//商品图片
						$tb_goods_photo = new IQuery('goods_photo_relation as g');
						$tb_goods_photo->fields = 'p.id AS photo_id, p.img ';
						$tb_goods_photo->join = 'left join goods_photo as p on p.id = g.photo_id ';
						$tb_goods_photo->where = ' g.goods_id = ' . $goods_id;
						$goods_info['photo'] = $tb_goods_photo->find();
						foreach ($goods_info['photo'] as $key => $val) {
							//对默认第一张图片位置进行前置
							if ($val['img'] == $goods_info['img']) {
								$temp = $goods_info['photo'][0];
								$goods_info['photo'][0] = $val;
								$goods_info['photo'][$key] = $temp;
							}
						}
						//处理规格
						if ($goods_info["spec_array"]) {
							$goods_info["spec_array"] = JSON::decode($goods_info["spec_array"]);

							//业务特性
							foreach ($goods_info["spec_array"] as $item) {
								//套装
								if ($item["name"] == "套装") {
									//$goods_info["tz"] = explode(',', trim($item['value'], ','));
									$goods_info["tz"] = array("母棒+存储棒","母棒+存储碟","母棒+存储棒+存储碟");
									//var_dump($goods_info["tz"]);
									//取套装图集
									//母棒+存储棒
									$AttrObj = new IQuery("attribute as at");
									$AttrObj->join = "inner join goods_attribute as ga on at.id = ga.attribute_id inner join pics as pc on ga.attribute_value=pc.id";
									$AttrObj->where = "ga.goods_id = $goods_id and pc.name ='母棒+存储棒' and at.type in (4,5)";
									$AttrObj->order = "at.name";
									$AttrObj->fields = "at.type, pc.name, pc.content, pc.value";
									$res = $AttrObj->find();
									$tmp_pics =  JSON::decode($res[0]["value"]);
									$goods_info["cs_zb_ccbang"] = $tmp_pics[0];
									array_shift($tmp_pics);
									$goods_info["tz_zb_ccbang"] = $tmp_pics;
									//母棒+存储碟
									$AttrObj = new IQuery("attribute as at");
									$AttrObj->join = "inner join goods_attribute as ga on at.id = ga.attribute_id inner join pics as pc on ga.attribute_value=pc.id";
									$AttrObj->where = "ga.goods_id = $goods_id and pc.name ='母棒+存储碟' and at.type in (4,5)";
									$AttrObj->order = "at.name";
									$AttrObj->fields = "at.type, pc.name, pc.content, pc.value";
									$res = $AttrObj->find();
									$tmp_pics =  JSON::decode($res[0]["value"]);
									$goods_info["cs_zb_ccbing"] = $tmp_pics[0];
									array_shift($tmp_pics);
									$goods_info["tz_zb_ccbing"] = $tmp_pics;
									//母棒+存储棒+存储碟
									$AttrObj = new IQuery("attribute as at");
									$AttrObj->join = "inner join goods_attribute as ga on at.id = ga.attribute_id inner join pics as pc on ga.attribute_value=pc.id";
									$AttrObj->where = "ga.goods_id = $goods_id and pc.name ='母棒+存储棒+存储碟' and at.type in (4,5)";
									$AttrObj->order = "at.name";
									$AttrObj->fields = "at.type, pc.name, pc.content, pc.value";
									$res = $AttrObj->find();
									$tmp_pics =  JSON::decode($res[0]["value"]);
									$goods_info["cs_zb_ccbang_ccbing"] = $tmp_pics[0];
									array_shift($tmp_pics);
									$goods_info["tz_zb_ccbang_ccbing"] = $tmp_pics;
									$goods_info["tz_pics"] = array("母棒+存储棒+存储碟"=>$goods_info["tz_zb_ccbang_ccbing"],
										"母棒+存储棒"=>$goods_info["tz_zb_ccbang"],
										"母棒+存储碟"=>$goods_info["tz_zb_ccbing"]);
									//var_dump($goods_info["tz_pics"]);
								}
								//母棒
								elseif ($item["name"] == "母棒") {
									$goods_info["zb"] = explode(',', trim($item['value'], ','));
									//磁盘容量带出价格
									/*$productDB = new IModel("products");
									foreach ($goods_info["volume"] as $k => $v) {
										$products_info = $productDB->getObj("goods_id = $goods_id and spec_array like '%$v%'");
										$goods_info["volume"][$k] = array("id" => $item["id"], "name" => $item["name"], "type" => $item["type"], "value" => $v, "price" => $products_info["sell_price"]);
									}*/
								}
								//存储棒
								elseif($item["name"] == "存储棒"){
									$goods_info["ccbang"] = explode(',', trim($item['value'], ','));
									$new_array = array();
									foreach($goods_info["ccbang"] as $ccb){
										if($ccb != "无"){
											array_push($new_array, $ccb);
										}
									}
									sort($new_array);
									$goods_info["ccbang"] = $new_array;
								}
								//存储碟
								elseif($item["name"] == "存储碟"){
									$goods_info["ccbing"] = explode(',', trim($item['value'], ','));
									$new_array = array();
									foreach($goods_info["ccbing"] as $ccb){
										if($ccb != "无"){
											array_push($new_array, $ccb);
										}
									}
									sort($new_array);
									$goods_info["ccbing"] = $new_array;
								}

								
							}
						}


						//获得扩展属性
						$tb_attribute_goods = new IQuery('goods_attribute as g');
						$tb_attribute_goods->join = 'left join attribute as a on a.id = g.attribute_id inner join pics as pc on g.attribute_value=pc.id';
						$tb_attribute_goods->fields = ' a.name, g.attribute_value, pc.value ';
						$tb_attribute_goods->where = " goods_id='" . $goods_id . "' and attribute_id!='' and pc.name = 'colors'";
						$goods_info['attribute'] = $tb_attribute_goods->find();

						//增加浏览次数
						if (!ISafe::get('visit' . $goods_id)) {
							$tb_goods->setData(array('visit' => 'visit + 1'));
							$tb_goods->update('id = ' . $goods_id, 'visit');
							ISafe::set('visit' . $goods_id, '1');
						}

						//var_dump($goods_info);
						//购买中登录设置回调
						$goods_info["callback"]="/site/book";
						
						
						$this->setRenderData($goods_info);
						$this->redirect('book');
					}





    /*
     * 商品订购页面(未登录)
     */

    public function buy() {
						if (IWeb::$app->config['tradeModel'] != 2) {
							IError::show(403, "本站已经关闭交易");
							exit;
						}

						$goods_id = IFilter::act(IReq::get('id'), 'int');

						if (!$goods_id) {
							IError::show(403, "传递的参数不正确");
							exit;
						}
						//$user_id = intval($this->user['user_id']);
				//        $goods_id = IFilter::act(IReq::get('goods_id'), 'int');


						
						//使用商品id获得商品信息
						$tb_goods = new IModel('goods');
						//如果没商品ID，则取第一个未删除的商品
						if ($goods_id) {
							$goods_info = $tb_goods->getObj('id=' . $goods_id . " AND is_del=0");
						} else {
							$goods_info = $tb_goods->getObj("is_del=0");
							$goods_id = $goods_info["id"];
						}
						if (!$goods_info) {
							IError::show(403, "商品不存在");
							exit;
						}

						//品牌名称
						/* if ($goods_info['brand_id']) {
						  $tb_brand = new IModel('brand');
						  $brand_info = $tb_brand->getObj('id = ' . $goods_info['brand_id']);
						  if ($brand_info) {
						  $goods_info['brand'] = $brand_info['name'];
						  }
						  } */

						//获取商品分类
						/* $categoryObj = new IModel('category_extend as ca, category as c');
						  $categoryRow = $categoryObj->getObj('ca.goods_id = ' . $goods_id . ' and ca.category_id = c.id', 'c.id, c.name');
						  $goods_info['category'] = $categoryRow ? $categoryRow['id'] : 0; */

						//商品图片
						$tb_goods_photo = new IQuery('goods_photo_relation as g');
						$tb_goods_photo->fields = 'p.id AS photo_id, p.img ';
						$tb_goods_photo->join = 'left join goods_photo as p on p.id = g.photo_id ';
						$tb_goods_photo->where = ' g.goods_id = ' . $goods_id;
						$goods_info['photo'] = $tb_goods_photo->find();
						foreach ($goods_info['photo'] as $key => $val) {
							//对默认第一张图片位置进行前置
							if ($val['img'] == $goods_info['img']) {
								$temp = $goods_info['photo'][0];
								$goods_info['photo'][0] = $val;
								$goods_info['photo'][$key] = $temp;
							}
						}
						//处理规格
						if ($goods_info["spec_array"]) {
							$goods_info["spec_array"] = JSON::decode($goods_info["spec_array"]);

							//业务特性
							foreach ($goods_info["spec_array"] as $item) {
								//套装
								if ($item["name"] == "套装") {
									$goods_info["tz"] = explode(',', trim($item['value'], ','));
									//$goods_info["tz"] = array("母棒+存储棒","母棒+存储碟","母棒+存储棒+存储碟");
									//var_dump($goods_info["tz"]);
									//取套装图集
									//母棒+存储棒
									$AttrObj = new IQuery("attribute as at");
									$AttrObj->join = "inner join goods_attribute as ga on at.id = ga.attribute_id inner join pics as pc on ga.attribute_value=pc.id";
									$AttrObj->where = "ga.goods_id = $goods_id and pc.name ='母棒+存储棒' and at.type in (4,5)";
									$AttrObj->order = "at.name";
									$AttrObj->fields = "at.type, pc.name, pc.content, pc.value";
									$res = $AttrObj->find();
									$tmp_pics =  JSON::decode($res[0]["value"]);
									$goods_info["cs_zb_ccbang"] = $tmp_pics[0];
									array_shift($tmp_pics);
									$goods_info["tz_zb_ccbang"] = $tmp_pics;
									//母棒+存储碟
									$AttrObj = new IQuery("attribute as at");
									$AttrObj->join = "inner join goods_attribute as ga on at.id = ga.attribute_id inner join pics as pc on ga.attribute_value=pc.id";
									$AttrObj->where = "ga.goods_id = $goods_id and pc.name ='母棒+存储碟' and at.type in (4,5)";
									$AttrObj->order = "at.name";
									$AttrObj->fields = "at.type, pc.name, pc.content, pc.value";
									$res = $AttrObj->find();
									$tmp_pics =  JSON::decode($res[0]["value"]);
									$goods_info["cs_zb_ccbing"] = $tmp_pics[0];
									array_shift($tmp_pics);
									$goods_info["tz_zb_ccbing"] = $tmp_pics;
									//母棒+存储棒+存储碟
									$AttrObj = new IQuery("attribute as at");
									$AttrObj->join = "inner join goods_attribute as ga on at.id = ga.attribute_id inner join pics as pc on ga.attribute_value=pc.id";
									$AttrObj->where = "ga.goods_id = $goods_id and pc.name ='母棒+存储棒+存储碟' and at.type in (4,5)";
									$AttrObj->order = "at.name";
									$AttrObj->fields = "at.type, pc.name, pc.content, pc.value";
									$res = $AttrObj->find();
									$tmp_pics =  JSON::decode($res[0]["value"]);
									$goods_info["cs_zb_ccbang_ccbing"] = $tmp_pics[0];
									array_shift($tmp_pics);
									$goods_info["tz_zb_ccbang_ccbing"] = $tmp_pics;
									$goods_info["tz_pics"] = array("母棒+存储棒+存储碟"=>$goods_info["tz_zb_ccbang_ccbing"],
										"母棒+存储棒"=>$goods_info["tz_zb_ccbang"],
										"母棒+存储碟"=>$goods_info["tz_zb_ccbing"]);
									//var_dump($goods_info["tz_pics"]);
								}
								//母棒
								elseif ($item["name"] == "母棒") {
									$goods_info["zb"] = explode(',', trim($item['value'], ','));
									//磁盘容量带出价格
									/*$productDB = new IModel("products");
									foreach ($goods_info["volume"] as $k => $v) {
										$products_info = $productDB->getObj("goods_id = $goods_id and spec_array like '%$v%'");
										$goods_info["volume"][$k] = array("id" => $item["id"], "name" => $item["name"], "type" => $item["type"], "value" => $v, "price" => $products_info["sell_price"]);
									}*/
								}
								//存储棒
								elseif($item["name"] == "存储棒"){
									$goods_info["ccbang"] = explode(',', trim($item['value'], ','));
									$new_array = array();
									foreach($goods_info["ccbang"] as $ccb){
										if($ccb != "无"){
											array_push($new_array, $ccb);
										}
									}
									sort($new_array);
									$goods_info["ccbang"] = $new_array;
								}
								//存储碟
								elseif($item["name"] == "存储碟"){
									$goods_info["ccbing"] = explode(',', trim($item['value'], ','));
									$new_array = array();
									foreach($goods_info["ccbing"] as $ccb){
										if($ccb != "无"){
											array_push($new_array, $ccb);
										}
									}
									sort($new_array);
									$goods_info["ccbing"] = $new_array;
								}

								
							}
						}


						//获得扩展属性
						$tb_attribute_goods = new IQuery('goods_attribute as g');
						$tb_attribute_goods->join = 'left join attribute as a on a.id = g.attribute_id inner join pics as pc on g.attribute_value=pc.id';
						$tb_attribute_goods->fields = ' a.name, g.attribute_value, pc.value ';
						$tb_attribute_goods->where = " goods_id='" . $goods_id . "' and attribute_id!='' and pc.name = 'colors'";
						$goods_info['attribute'] = $tb_attribute_goods->find();

						//增加浏览次数
						if (!ISafe::get('visit' . $goods_id)) {
							$tb_goods->setData(array('visit' => 'visit + 1'));
							$tb_goods->update('id = ' . $goods_id, 'visit');
							ISafe::set('visit' . $goods_id, '1');
						}

						//var_dump($goods_info);
						//购买中登录设置回调
						$goods_info["callback"]="/site/buy";
						
						
						$this->setRenderData($goods_info);
						$this->redirect('buy');
					}

















    
    //压力测试下订单
    public function book2_test() {
        $goods_id = 2;
        $product_id = 15;
        $accept_name = "test";
        $province = 110000;
        $city = 110100;
        $area = 110101;
        $address = "test";
        $mobile = "12312313";
        $telphone = "123213123";
        $postcode = "12312";
        $delivery_id = 1;
        $accept_time = 1;
        $pay_type = 1;
        $order_message = "test";
        $invoice_type = 1;
        $invoice_title = "test";
        $goods_nums = 1;
        $order_no = str_replace("0.","",str_replace(" ","",microtime())).sprintf("%06d",ISafe::get("user_id")). rand(1000, 9999);
        //$address_id = IFilter::act(IReq::get('address_id'));
        $dataArray = array();

        //防止表单重复提交
        /*if (IReq::get('timeKey') != null) {
            if (ISafe::get('timeKey') == IReq::get('timeKey')) {
                IError::show(403, '订单数据不能被重复提交');
                exit;
            }
        }*/
        
        $orderObj = new IModel('order');
        //使用order_no防止重复提交
        $isexist = $orderObj->getObj(" order_no = '$order_no'");
        if($isexist){
            IError::show(403, '订单数据不能被重复提交');
            exit;
        }

        /*if ($address_id && $address_id != 0) {
            //从地址表里取信息
            $addressDB = new IModel("address");
            $iaddress = $addressDB->getObj("id = $address_id");
            $province = $iaddress["province"];
            $city = $iaddress["city"];
            $area = $iaddress["area"];
            $address = $iaddress["address"];
            $accept_time = $iaddress["accept_time"];
            $accept_name = $iaddress["accept_name"];
            $postcode = $iaddress["zip"];
            $mobile = $iaddress["mobile"];
            $telphone = $iaddress["telphone"];
        }*/

        if ($province == 0 || $city == 0 || $area == 0) {
            IError::show(403, '请填写收货地址的省市地区');
        }

        if ($delivery_id == 0) {
            IError::show(403, '请选择配送方式');
        }

        $user_id = 19;

        if ($pay_type == 0) {
            IError::show(403, '请选择正确的支付方式');
        }

        //计算费用
        $goodsDB = new IModel("goods");
        $goods = $goodsDB->getObj("id = $goods_id");
        $price = $goods_nums * $goods["sell_price"];
        $specArray = array('name' => $goods['name'], 'value' => '');
        if ($product_id) {
            $productsDB = new IModel("products");
            $products = $productsDB->getObj("id = $product_id");
            $price = $goods_nums * $products["sell_price"];
            $products["img"] = $goods["img"];
            //规格
            $spec = block::show_spec($products['spec_array']);
            foreach ($spec as $skey => $svalue) {
                $specArray['value'] .= $skey . ':' . $svalue . ',';
            }
            $specArray['value'] = IFilter::addSlash(trim($specArray['value'], ','));
        } else {
            $products = $goods;
        }

        //生成的订单数据
        $dataArray = array(
            'order_no' => $order_no,
            'user_id' => $user_id,
            'accept_name' => $accept_name,
            'pay_type' => $pay_type,
            'distribution' => 1,
            'postcode' => $postcode,
            'telphone' => $telphone,
            'province' => $province,
            'city' => $city,
            'area' => $area,
            'address' => $address,
            'mobile' => $mobile,
            'create_time' => ITime::getDateTime(),
            'postscript' => $order_message,
            'accept_time' => $accept_time,
            'exp' => 0,
            'point' => 0,
            'type' => 0,
            //红包道具
            'prop' => null,
            //商品价格
            'payable_amount' => $price,
            'real_amount' => $price,
            //运费价格
            'payable_freight' => 0,
            'real_freight' => 0,
            //手续费
            'pay_fee' => 0,
            //发票
            'invoice' => 1,
            'invoice_type' => $invoice_type,
            'invoice_title' => $invoice_title,
            'taxes' => 0,
            //优惠价格
            'promotions' => 0,
            //订单应付总额
            'order_amount' => $price,
            //订单保价
            'if_insured' => 0,
            'insured' => 0,
        );

        $dataArray['order_amount'] = $dataArray['order_amount'] <= 0 ? 0 : $dataArray['order_amount'];

        
        $orderObj->setData($dataArray);

        $this->order_id = $orderObj->add();

        if ($this->order_id == false) {
            //IError::show(403, '订单生成错误');
        }

        /* 将订单中的商品插入到order_goods表 */
        $order_goods = new IModel("order_goods");
        $dataArray = array("order_id" => $this->order_id,
            "goods_id" => $goods_id,
            "product_id" => $product_id,
            "img" => $products["img"],
            "goods_price" => $products["sell_price"],
            "real_price" => $products["sell_price"],
            "goods_array" => JSON::encode($specArray),
            "is_send" => 0,
            "delivery_id" => $delivery_id);


        $order_goods->setData($dataArray);
        $order_goods->add();
        //更新防重复提交标签
        //ISafe::set('timeKey', IReq::get('timeKey'));
        //跳转支付页
        $data = array("order_id"=>$this->order_id,
            "goods_id"=>$goods_id,
            "product_id"=>$products_id,
            "accept_name"=>$accept_name,
            "province"=>$province,
            "city"=>$city,
            "area"=>$area,
            "address"=>$address,
            "mobile"=>$mobile,
            "telphone"=>$telphone,
            "post_code"=>$post_code,
            "pay_type"=>$pay_type,
            "accept_time"=>$accept_time,
            "invoice_type"=>$invoice_type,
            "invoice_title"=>$invoice_title,
            "goods_price"=>$products["sell_price"],
            "goods_img"=>$products["img"],
            "goods_nums"=>$goods_nums,
            "goods_array"=> JSON::encode($specArray),
            "order_no"=>$order_no,
            "order_amount" => $price,
            );
        $this->setRenderData($data);
        //var_dump($data);
        //$this->redirect("index",false);
        //直接支付
        //$this->redirect("/block/dopay/order_id/" . $this->order_id, true);
    }
    
    
    public function getpicsinfo(){
        $goods_id = IFilter::act(IReq::get('goods_id'), 'int');
        $name = IReq::get('tz_name');
        
        $AttrObj = new IQuery("attribute as at");
        $AttrObj->join = "inner join goods_attribute as ga on at.id = ga.attribute_id inner join pics as pc on ga.attribute_value=pc.id";
        $AttrObj->where = "ga.goods_id = $goods_id and pc.name ='$name' and at.type in (4,5)";
        $AttrObj->order = "at.name";
        $AttrObj->fields = "at.type, pc.name, pc.content, pc.value";
        $res = $AttrObj->find();
        echo $res[0]["content"];
        
    }
    
    //压力测试订单列表
    public function order_test() {
        $object = IWeb::$app->getController();
	$object->user = array(
			'user_id'  => 62,    //测试用户ID
			'email' => 'lllrrrccc@263.net',   //测试用户邮箱
                        'mobile' => '',    //测试用户手机
                        'type' => 1,
			'username' => 'johnlee',   //测试用户昵称
			'head_ico' => '',
			'user_pwd' => 'e10adc3949ba59abbe56e057f20f883e', //测试用户密码MD5加密串
                        'active' => '1'
                    
		);
        $this->redirect('order_test');
    }
}
