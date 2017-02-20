<?php

/**
 * @copyright Copyright(c) 2011 jooyea.cn
 * @file Simple.php
 * @brief
 * @author webning
 * @date 2011-03-22
 * @version 0.6
 * @note
 */

/**
 * @brief Simple
 * @class Simple
 * @note
 */
require_once $_SERVER['DOCUMENT_ROOT'].'/plugins/'.'oauth/sina/API/saetv2.ex.class.php';
class Simple extends IController {
    
    public $layout = 'site_new';

    function init() {
        CheckRights::checkUserRights();
    }

    //退出登录
    function logout() {
        ISafe::clearAll();
        $callback = IFilter::act(IReq::get('callback','get'));
        if(empty($callback)){
        	$this->redirect('/site/sy');
        }else{
        	$this->redirect("{$callback}");
        }
        //$this->redirect('/site/index');
    }
    //第三方登录
    function third_login(){
        $api =  IFilter::act(IReq::get('api', 'get'));
        if(isset($api) && $api=='wb'){
            $o = new SaeTOAuthV2( WB_AKEY_PC , WB_SKEY_PC );
            $code_url = $o->getAuthorizeURL(WB_CALLBACK_URL_PC);
            $this->redirect("$code_url");
        }else if(isset($api) && $api == 'wx'){
            $file = $_SERVER['DOCUMENT_ROOT'].'/config/config.php';
            $wx_ini = require($file);
            $wx_config = $wx_ini['wx_ini'];
            $o = new WeiXin ($wx_config);
            $code_url = $o->getLoginUrl();
            $this->redirect("$code_url");
        }
    }
    
    function create_hkyz(){
        $GtSdk = new GeetestLib(CAPTCHA_ID, PRIVATE_KEY);
        session_start();
        $user_id = "service@meibeike.com";
        $status = $GtSdk->pre_process($user_id);
        $_SESSION['gtserver'] = $status;
        $_SESSION['user_id'] = $user_id;
        echo $GtSdk->get_response_str();
    }
    
    function verify_hkyz(){
        $GtSdk = new GeetestLib(CAPTCHA_ID, PRIVATE_KEY);
        session_start();
        $user_id = $_SESSION['user_id'];
        if ($_SESSION['gtserver'] == 1) {
            $result = $GtSdk->success_validate($_POST['geetest_challenge'], $_POST['geetest_validate'], $_POST['geetest_seccode'], $user_id);
            if ($result) {
                $message = '';
            } else{
                $message = '验证失败';//$result.'p1:'.$_POST['geetest_challenge'].'p2:'.$_POST['geetest_validate'].'p3:'.$_POST['geetest_seccode'];
            }
        }else{
            if ($GtSdk->fail_validate($_POST['geetest_challenge'],$_POST['geetest_validate'],$_POST['geetest_seccode'])) {
                $message = '';
            }else{
                $message = "验证失败";
            }
        }
        return $message;
    }
    //显示当前验证码
    function show_captcha() {
        echo ISafe::get('captcha');
    }
    /**
     * 检验刷新
     * @param unknown $mobile
     * @return boolean
     */
    function checkRefresh($mobile)
    {
       // if (!session_id()) 
            session_start();
        $ip = getenv("REMOTE_ADDR");
        if(empty($_SESSION["last_access"])){   //用户第一次打开页面 记录当前的时间保存在 session_start
            $_SESSION["last_access"] = time();
            $_SESSION["last_access_ip"] = $ip;
            $_SESSION["last_mobile"] = $mobile;
        }
        else
        {
            $time_passed = time()-$_SESSION["last_access"];
            $new_ip =   getenv("REMOTE_ADDR");
            if($time_passed < 1800 && $new_ip == $ip && $mobile != $_SESSION["last_mobile"])
            {
                $_SESSION["last_access"] = time();
                $_SESSION["last_mobile"] = $mobile;
                return true;
    
            }
            $_SESSION["last_access"] = time();
            $_SESSION["last_mobile"] = $mobile;
        }
        return false;
    }
    
	
	function show_test2()
	{
       echo "测试1<br/>";	  
		//$res = send_mail('123686810@qq.com','邮件标题（测试）','测试内容（测试）');
		$res=Db_Meicloud::add_email('123686810@qq.com','邮件标题（测试）','<p>请您点击下面这个链接验证邮箱：<br /><a href=\"http://www.meibeike.com/simple/reg_email_active/hash/c685e44e286e6d837c81c57cefc91e3e\">http://www.meibeike.com/simple/reg_email_active/hash/c685e44e286e6d837c81c57cefc91e3e</a>。<br />如果不能点击，请您把它复制到地址栏中打开。<br />本链接在3天后将自动失效。如果你没有请求该链接，则你的帐户安全，可以忽略该电子邮件。其他用户可能在尝试重置他们自己的密码时错误地输入了你的电子邮件地址。<br/>谢谢!<br/>MeiBeiKe 帐户团队</p>','','','');
		//$res=Db_Meicloud::add_email('123686810@qq.com','邮件标题（测试）','<p>请您点击下面这个链接验证邮箱：</p>','','','');
		
		echo  $res;
    }

	function send_mail($tomail, $subject, $body){
    require('class.phpmailer.php');
    $mail = new PHPMailer();
    $mail->IsSMTP(); 
    //$mail->SMTPDebug = 2;						//显示调试信息
    $mail->SMTPAuth = true;						
    //$mail->SMTPSecure = "ssl";				//启用ssl加密
    $mail->Host = "smtp.qiye.163.com";				//邮箱服务器名称
    $mail->Port = 25;							//邮箱服务端口
    $mail->Username = "service@meibeike.com";			//发件人邮箱地址
    $mail->Password = "woaiMBK666";					//发件人邮箱密码
    $mail->CharSet = "UTF-8";
    $mail->SetFrom('service@meibeike.com', '张三');		//发件人信息 邮箱地址，姓名
    $mail->Subject = $subject;
    $mail->MsgHTML($body);
    $mail->AddAddress($tomail, "");
    if (!$mail->Send())
    {
        $stat = 0;
    }
    else
    {
        $stat = 1;
    }
    return $stat;
   }

    function show_test() {
        echo "测试1<br/>";
		$email="123686810@qq.com";
		$user_id=133;



		$email = $email ? $email : IReq::get('email');
        $user_id = $user_id ? $user_id : IReq::get('user_id');

        if (!$email) {
            $email = ISafe::get("email");
        }
        if (!$user_id) {
            $user_id = ISafe::get("user_id");
        }

		echo $email."___mail<br/>";
		echo $user_id."___userid<br/>";

        $hash = IHash::md5(microtime(true) . mt_rand());

		echo $hash."_____,hash<br/>";

		//发送邮件
        $url = IUrl::creatUrl("/simple/reg_email_active/hash/{$hash}");
        $url = IUrl::getHost() . $url;
		echo $url."_____,url<br/>";

		$body = "请您点击下面这个链接验证邮箱：<br /><a href=\"{$url}\">{$url}</a>。<br />如果不能点击，请您把它复制到地址栏中打开。<br />本链接在3天后将自动失效。如果你没有请求该链接，则你的帐户安全，可以忽略该电子邮件。其他用户可能在尝试重置他们自己的密码时错误地输入了你的电子邮件地址。<br/>谢谢!<br/>MeiBeiKe 帐户团队";
		echo $body."_____,body<br/>";

		$subject = "美贝壳官网注册邮件";
		echo $subject."_____,subject<br/>";

		$smtp = new SendMail();
        $error = $smtp->getError();
		echo $error."_____,error<br/>";
        if ($error) {
            $return = array(
                'isError' => true,
                'message' => $error,
            );
            echo JSON::encode($return);
            return;
        }
        $status = $smtp->send($email, $subject, $body);
		$status2 = $smtp->send_mail($email, $subject, $body);

        echo $status;
		echo $status2."_____,status1<br/>";


    $smtpserver = "smtp.qiye.163.com";//SMTP服务器
	$smtpserverport =25;//SMTP服务器端口
	$smtpusermail = "service@meibeike.com";//SMTP服务器的用户邮箱
	$smtpemailto = "123686810@qq.com";//发送给谁
	$smtpuser = "service@meibeike.com";//SMTP服务器的用户帐号
	$smtppass = "woaiMBK666";//SMTP服务器的用户密码
	$mailtitle = "test";//邮件主题
	$mailcontent = $body;//邮件内容
	$mailtype = "HTML";//邮件格式（HTML/TXT）,TXT为文本邮件
	//************************ 配置信息 ****************************
	echo $mailtype."_____,mailtype<br/>";
	$smtp = new smtp($smtpserver,$smtpserverport,false,$smtpuser,$smtppass);//这里面的一个true是表示使用身份验证,否则不使用身份验证.
	echo $smtpserver."_____,smtpserver<br/>";
	$smtp->debug = true;//是否显示发送的调试信息
	$state = $smtp->sendmail($smtpemailto, $smtpusermail, $mailtitle, $mailcontent, $mailtype);

    echo $state."_____,status2<br/>";

		
		echo "测试2<br/>";
    }

    private function get_client_ip() {
        $ip = $_SERVER['REMOTE_ADDR'];
        if (isset($_SERVER['HTTP_CLIENT_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif(isset($_SERVER['HTTP_X_FORWARDED_FOR']) AND preg_match_all('#\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}#s', $_SERVER['HTTP_X_FORWARDED_FOR'], $matches)) {
            foreach ($matches[0] AS $xip) {
                if (!preg_match('#^(10|172\.16|192\.168)\.#', $xip)) {
                    $ip = $xip;
                    break;
                }
            }
        }
        return $ip;
    }
    
    //验证手机
    function verify_telphone() { 
        //$telphone = IFilter::act(IReq::get('mobile', 'get'));
       // var_dump($telphone);
        $message = "";
        $telphone = IFilter::act(IReq::get('mobile', 'post'));
        $purchase_time = IFilter::act(IReq::get('purchase_time','post'));
//         if ($this->checkRefresh($telphone)) {
//             $result['message'] = "短信发送失败！";
//             $result["code"] = 0;
//             echo json_encode($result);
//             exit ;
//         }
        $pattern = '/^(13|14|15|17|18)[0-9]{9}$/';
        if (!preg_match($pattern,$telphone)) {
            $message = '手机格式不正确!';
            $result['message'] = $message;
            $result["code"] = 0;
            echo json_encode($result);
            exit;           
        }     
        
        $obj = new IQuery("user u");
        $obj->join = "inner join mbk_appointment a on a.mei_id = u.meiid";
        $obj->where = "u.mobile = {$telphone} and a.purchase_time = {$purchase_time}";
        $obj->fields = "a.appointment_no"; 
        $res = $obj->find();
       
        if(!empty($res) && !empty($res[0]['appointment_no'])){
            //已经预约,返回预约号给用户
            $result['message'] = "您已经成功预约,预约号是{$res[0]['appointment_no']}";
            $result['aid'] = $res[0]['appointment_no'] ;
            $result["code"] = 2;
            echo json_encode($result);
            exit;
        }else{
            //没有预约，给用户发送验证码
            $vcode = rand(100000, 999999);
           //$vcode = Db_
            //ISafe::set("vcode", $vcode);
            //调用存储过程发短信
            //Db_Meicloud::sendMsg('验证码：' . $vcode . '，该验证码10分钟内有效。', "$telphone");
           // $flag = Db_Meicloud::sendMsg('美贝壳验证码：' . $vcode . '，该验证码十分钟内有效。预约云棒也同时奉送美贝壳账户，你可以以此手机号为MeiID登录美贝壳官网查询你的预约等。', "$telphone");
           // 截短短信内容，z.qiang 2016.6.9
           $flag = Db_Meicloud::sendMsg('美贝壳验证码：' . $vcode . '，该验证码十分钟内有效。', "$telphone");
         // $flag = Db_Meicloud::genVerifyCode($telphone);
            if($flag == true){
                $data['mobile'] = $telphone;
                $data['vcode'] = $vcode;
                $data['create_time'] = ITime::getDateTime() ;
                $res = $this->save_vcode($data);
                $result["code"] = 1;
                echo json_encode($result);
                exit;
            }else{
                $result['message'] = "短信发送失败";
                $result["code"] = 0;
                echo json_encode($result);
                exit;
            }
        }
    }
    
    private function save_vcode($data){
            $smsObj = new IModel('sms_vcode');
            $smsObj->setData($data);           
            $id = $smsObj->add();  
            return $id;
    }
  
    private function get_last_vcode($mobile){
        $db = IDBFactory::getDB();   
        /**容易引起sql注入，下次修改。z.qiang*/
        $res = $db->query("select vcode from  mbk_sms_vcode where mobile={$mobile} order by id desc limit 1");
        if(is_array($res) && !empty($res[0]['vcode'])){
            return $res[0]['vcode'];
        }else{
            return false ;
        }        
    }
    
    //手机预约
    function telphone_appoint(){
        $telphone = IFilter::act(IReq::get('telphone', 'post'));
        $web_from = ISafe::get('web_from');
//         if ($this->checkRefresh($telphone)) {
//             $result['message'] = "短信发送失败！";
//             $result["code"] = 0;
//             echo json_encode($result);
//             exit ;
//         }
        $purchase_time = IFilter::act(IReq::get('purchase_time', 'post'));
        $purchase_time = intval($purchase_time);
        $verify_code = IFilter::act(IReq::get('verify_code', 'post'));
        $last_vcode = $this->get_last_vcode($telphone);
        //滑块验证
        if(($r = $this->verify_hkyz()) != ''){
            $result['msg'] = $r;
            $result["code"] = 0;
            echo json_encode($result);
            exit;
        }
        
		$pattern = '/^[1-9][0-9]{5}$/';
        if (!preg_match($pattern,$verify_code)) {
            $message = '验证码格式不正确';
            $result['msg'] = $message;
            $result["code"] = 0;
            echo json_encode($result);
            exit;           
        }     
        if($last_vcode != $verify_code){
            $result['msg'] = "验证码输入不正确!";
            $result["code"] = 0;
            echo json_encode($result);
            exit;
        }
        $obj = new IQuery("user u");
        $obj->join = "inner join mbk_appointment a on a.mei_id = u.meiid";
        $obj->where = "u.mobile = {$telphone} and a.purchase_time={$purchase_time}";
        $obj->fields = "a.appointment_no"; 
        $res = $obj->find();
        if(!empty($res) && !empty($res[0]['appointment_no'])){
            //已经预约,返回预约号给用户
            $result['msg'] = "您已经成功预约,预约号是{$res[0]['appointment_no']}";
            $result["code"] = 0;
            echo json_encode($result);
            exit;
        }else{              
            //没有预约           
            $userObj = new IModel('user');
            $where = "mobile = '$telphone'";
            $userRow = $userObj->getObj($where);
           // var_dump($userRow);exit;
            if (!empty($userRow)) {                    
                        //已经注册过,调用预约接口
                        $meiid = $userRow['meiid'];
                        $flag = $this->handAppoint($userRow['meiid'],1,$purchase_time,$userRow['id'],$web_from);                      
                        if($flag!==false){
                            Db_Meicloud::sendMsg("恭喜您！预约成功，预约号为{$flag}。请记得9月3日11:00准时登录美贝壳官网抢购哟.", "$telphone");
                            $data['mobile'] = $telphone;
                            $data['vcode'] = "";
                            $data['create_time'] = ITime::getDateTime();
                            $result['code'] = 1;
                            $result['aid'] =  $flag;//返回页面预约号
                            echo json_encode($result);
                            exit;
                        }else{
                            $result['msg'] = "预约失败，请拨打公司热线咨询";
                            $result['code'] = 0;
                            echo json_encode($result);
                            exit;
                        }

            }else{
                    //未注册过，先调用注册接口，再调用预约接口
                    $username = '';//统一用户名，默认为空
                    $password = substr($telphone,-3,3).mt_rand(100,999);//随机生成密码             
                    $userType = 1;//官网用户
                    $meiid = Db_Meicloud::addUser($telphone, $username, md5($password), 1);                   
                    $userArray = array(
                        'username' => $username,
                        'password' => md5($password),
                        'mobile' => $telphone,
                        'type' => 2,//手机注册
                        'meiid' => $meiid
                    );
                    $userObj->setData($userArray);
                    $user_id = $userObj->add();
                    if ($user_id) {
                            //记录会员信息
                            $memberArray = array(
                                'user_id' => $user_id,
                                'time' => ITime::getDateTime(),
                                'telephone'=>$telphone
                            );                           
                            $memberObj = new IModel('member');
                            $memberObj->setData($memberArray);
                            $memberObj->add();
                            $flag = $this->handAppoint($meiid,$userType,$purchase_time,$user_id,$web_from); 
                                if($flag!==false){
                                    //Db_Meicloud::sendMsg("恭喜您！预约成功，预约号为{$flag}。请记得6月20日10：00准时登录美贝壳官网抢购哟.同时您还可以使用该手机号码进行登录，登录密码是{$password}，为保证帐户安全，登录后请尽快修改密码。", "$telphone");
                                   /**截短短信内容，z.qiang 2016.6.9*/
                                    Db_Meicloud::sendMsg("恭喜您预约成功！预约号为{$flag}。请于9月3日11点登录美贝壳官网抢购哟.您还可以使用该手机号登录，密码是{$password}。", "$telphone");
                                    $result['aid'] =  $flag;//返回页面预约号
                                    $result['code'] = 1;
                                    echo json_encode($result);
                                    exit;
                                }else{
                                    $result['msg'] = "预约失败，请拨打公司热线咨询";
                                    $result['code'] = 0;
                                    echo json_encode($result);
                                    exit;
                                }
                    } else {
                            $message = '系统异常，请联系技术人员';                            
                            $result['msg'] = $message;
                            $result['code'] = 0 ;
                            echo json_encode($result);
                    }
                }
            }       
        }
   
    
    //手动预约方法
    
    function handAppoint($meiid,$userType,$purchase_time,$id,$from){
        $ip = $this->get_client_ip();
        $res = Db_Meicloud::appoint_process();
        if($res == false){
            $appointment_no = "04".substr($id,-3,3).rand(10000,99999);
        }else{
            $appointment_no = $res;
        }
        //$appointment_no = ($id%1000).rand(10000,99999);
        $temp_data = Db_Meicloud::write_appoint3($appointment_no,$purchase_time,$userType,$id,"","",$from,$ip);
        ISafe::set("error_try", 0);  
        //echo  "'$appointment_no'".'---'.$temp_data;
        if(is_array($temp_data)){
            if($temp_data['ret']!=0){
                if($temp_data['ret']=='1'){
                    $obj = new IQuery("user u");
                    $obj->join = "inner join mbk_appointment a on a.mei_id = u.meiid";
                    $obj->where = "u.id = {$id}";
                    $obj->fields = "a.appointment_no";
                    $data = $obj->find();
                    $res = $data[0]['appointment_no'];
                }else{
                    $res = $appointment_no;   
                }
            }else{
                $res = false;
            }
        }else{
            $res = false;
        }
        
        return $res;
    }
    
	//验证手机注册
    function verify_mobile_captcha() {
        $mobile = IFilter::act(IReq::get('mobile', 'post'));
        //$captcha = IFilter::act(IReq::get('captcha', 'post'));
        $message = "";
        if (IValidate::mobi($mobile) == false) {
            $message = '手机格式不正确';
        }else if(($info = $this->verify_hkyz())!=''){
            $message = $info;
        }
        
        
        //else if (strtoupper($captcha) != strtoupper(ISafe::get('captcha'))) {
           // $message = '验证码输入不正确';
       // }

        if ($message == '') {
            if (Db_Meicloud::checkUserExist($mobile, 1)) {
                $message = '此手机已经被注册过，请重新更换。';
            }
        }

        if ($message == '') {
            $userObj = new IModel('user');
            $where = "mobile = '$mobile'";
            $userRow = $userObj->getObj($where);

            if (!empty($userRow)) {
                if ($mobile == $userRow['mobile']) {
                    $message = '此手机已经被注册过，请重新更换';
                }
            }
        }

        $result = array();
        $result['message'] = $message;
        if ($message != '') {
            $result["code"] = 0;
        } else {
            $result["code"] = 1;
        }

        echo json_encode($result);
    }

    //验证手机绑定
    function verify_mobile() {
        $mobile = IFilter::act(IReq::get('mobile', 'post'));

        $message = "";
        if (IValidate::mobi($mobile) == false) {
            $message = '手机格式不正确';
        }

        if ($message == '') {
            $userObj = new IModel('user');
            $where = "mobile = '$mobile'";
            $userRow = $userObj->getObj($where);

            if (!empty($userRow)) {
                if ($mobile == $userRow['mobile']) {
                    $message = '此手机已经被注册过，请重新更换';
                }
            }
        }

        $result = array();
        $result['message'] = $message;
        if ($message != '') {
            $result["code"] = 0;
        } else {
            $result["code"] = 1;
        }

        echo json_encode($result);
    }

    function verify_email_captcha() {
        $email = IFilter::act(IReq::get('email', 'post'));
        //$captcha = IFilter::act(IReq::get('captcha', 'post'));

        $message = "";
        if (IValidate::email($email) == false) {
            $message = '邮箱格式不正确';
        }else if(($info = $this->verify_hkyz())!=''){
            $message = $info;
        }
        
        //else if (strtoupper($captcha) != strtoupper(ISafe::get('captcha'))) {
            //$message = '验证码输入不正确';
        //}

        if ($message == '') {
            if (Db_Meicloud::checkUserExist($email, 0)) {
                $message = '此邮箱已经被注册过，请重新更换。';
            }
        }

        if ($message == '') {
            $userObj = new IModel('user');
            $where = "email = '$email'";
            $userRow = $userObj->getObj($where);

            if (!empty($userRow)) {
                if ($email == $userRow['email']) {
                    $message = '此邮箱已经被注册过，请重新更换';
                }
            }
        }

        $result = array();
        $result['message'] = $message;
        if ($message != '') {
            $result["code"] = 0;
        } else {
            $result["code"] = 1;
        }

        echo json_encode($result);
    }

    //单独验证验证码
    function verify_captcha() {
        $captcha = IFilter::act(IReq::get('captcha', 'post'));

        $message = "";
        if (strtoupper($captcha) != strtoupper(ISafe::get('captcha'))) {
            $message = '验证码输入不正确';
        }

        $result = array();
        $result['message'] = $message;
        if ($message != '') {
            $result["code"] = 0;
        } else {
            $result["code"] = 1;
        }
        echo json_encode($result);
    }

    function verify_password_name() {
        $username = IFilter::act(IReq::get('username', 'post'));
        $password = IFilter::act(IReq::get('password', 'post'));
        $repassword = IFilter::act(IReq::get('repassword', 'post'));

        $message = "";
        if (!Util::is_username($username)) {
            $message = '昵称必须是由2-20个字符，可以为字母，数字下划线和中文';
        } else if (!preg_match('|\S{6,32}|', $password)) {
            $message = '密码必须是字母，数字，下划线组成的6-32个字符';
        } else if ($password != $repassword) {
            $message = '2次密码输入不一致';
        }

        //不验证
        /*
          if ($message == '') {
          $userObj = new IModel('user');
          $where = "username = '$username'";
          $userRow = $userObj->getObj($where);

          if (!empty($userRow)) {
          if ($username == $userRow['username']) {
          $message = '此昵称已经被注册过，请重新更换';
          }
          }
          } */

        $result = array();
        $result['message'] = $message;
        if ($message != '') {
            $result["code"] = 0;
        } else {
            $result["code"] = 1;
        }

        echo json_encode($result);
    }

    
    //用户注册
    function reg_act() {
        $email = IFilter::act(IReq::get('email', 'post'));
        $mobile = IFilter::act(IReq::get('mobile', 'post'));
        $username = IFilter::act(IReq::get('username', 'post'));
        $password = IFilter::act(IReq::get('password', 'post'));
        $repassword = IFilter::act(IReq::get('repassword', 'post'));
        //$captcha = IFilter::act(IReq::get('captcha', 'post'));
        $callback = IFilter::act(IReq::get('callback'), 'text');
        $type = IFilter::act(IReq::get('type'), 'text'); //$type=1邮箱 2短信
        $message = '';

        if ($type == 1) {
            /* 邮箱注册信息校验 */
            if (IValidate::email($email) == false) {
                $message = '邮箱格式不正确';
            } else if (!Util::is_username($username)) {
                $message = '用户名必须是由2-20个字符，可以为字数，数字下划线和中文';
            } else if (!preg_match('|\S{6,12}|', $password)) {
                $message = '密码必须是字母，数字，下划线组成的6-12个字符';
            } else if ($password != $repassword) {
                $message = '2次密码输入不一致';
            } /*else if ($captcha != ISafe::get('captcha')) {
                $message = '验证码输入不正确';
            }*/ else {
                //从存储过程取
                if (Db_Meicloud::checkUserExist($email, 0)) {
                    $message = '此邮箱已经被注册过，请重新更换。';
                } else {
                    $userObj = new IModel('user');
                    $where = 'email = "' . $email . '"';
                    $userRow = $userObj->getObj($where);

                    if (!empty($userRow) && $email == $userRow['email']) {
                        $message = '此邮箱已经被注册过，请重新更换';
                    }
                }
            }
            $active = 0; //需要邮件激活
        } else if ($type == 2) {
           
            /* 短信注册校验 */
            if (IValidate::mobi($mobile) == false) {
                $message = '手机号码格式不正确';
            } else if (!Util::is_username($username)) {
                $message = '用户名必须是由2-20个字符，可以为字数，数字下划线和中文';
            } else if (!preg_match('|\S{6,12}|', $password)) {
                $message = '密码必须是字母，数字，下划线组成的6-12个字符';
            } else if ($password != $repassword) {
                $message = '2次密码输入不一致';
            } /*else if ($captcha != ISafe::get('captcha')) {
                $message = '验证码输入不正确';
            }*/ else {
                //从存储过程取
                if (Db_Meicloud::checkUserExist($mobile, 1)) {
                    $message = '此手机已经被注册过，请重新更换。';
                } else {
                    $userObj = new IModel('user');
                    $where = 'mobile = "' . $mobile . '"';
                    $userRow = $userObj->getObj($where);
                    if (!empty($userRow)) {
                        $message = '此手机已经被注册过，请重新更换';
                    }
                }
            }
            $active = 1; //需要邮件激活
        } else {
            $message = '系统错误！';
        }

        //校验通过
        if ($message == '') {
            //调用存储过程同步
            /* try {
              if ($type == 1) {
              //邮箱
              $pret = Db_Meicloud::addUser($email, $username, $password, 0);
              } else {
              //短信
              $pret = Db_Meicloud::addUser($mobile, $username, $password, 1);
              }
              } catch (Exception $e) {
              var_dump($e . getMessage());
              } */
            //user表
            if ($type == 1) {
                //存储过程写入
                //$meiid = Db_Meicloud::addUser($email . "_tmp" . time(), $username, md5($password), 0);
                //调用私有云存储过程，进行注册，z.qiang 20160619
                $meiid = Db_Meicloud::addUser($email, $username, md5($password), 0);
                $userArray = array(
                    'username' => $username,
                    'password' => md5($password),
                    'email' => $email,
                    'mobile' => $mobile,
                    'type' => $type,
                    'active' => $active,
                    'meiid' => $meiid   //赋值meiid
                );
            } else {
                //存储过程写入
                $meiid = Db_Meicloud::addUser($mobile, $username, md5($password), 1);
                $userArray = array(
                    'username' => $username,
                    'password' => md5($password),
                    'email' => $email,
                    'mobile' => $mobile,
                    'type' => $type,
                    'active' => $active,
                    'meiid' => $meiid
                );
            }
            $userObj->setData($userArray);
            $user_id = $userObj->add();

            if ($user_id) {
                //member表
                $memberArray = array(
                    'user_id' => $user_id,
                    'time' => ITime::getDateTime(),
                );
                $memberObj = new IModel('member');
                $memberObj->setData($memberArray);
                $memberObj->add();

                //用户私密数据 邮箱注册只有激活后才可登陆
                ISafe::set('email', $email);
                ISafe::set('mobile', $mobile);
                ISafe::set('username', $username);
                ISafe::set('user_id', $user_id);
                ISafe::set('user_pwd', $userArray['password']);
                ISafe::set('type', $type);
                ISafe::set('active', $active);
                $dataArray = array();
                $domain = strstr($email, '@');
                $email_prox = "mail" . $domain;
                $email_prox = str_replace("@", ".", $email_prox);
                //$dataArray["email_prox"]=$email_prox;
                ISafe::set('email_prox', $email_prox);
                //如果是邮箱验证
                if ($type == 1) {
                    //发送验证邮件
                    $this->reg_email_send($email, $user_id);
                    ISafe::set("no_del", 1);
                }

                //发送页面数据
                //$this->setRenderData($dataArray);
                //自定义跳转页面
                $callback = $callback ? urlencode($callback) : '';
                $this->redirect('/simple/success?message=' . urlencode("注册成功！") . '&callback=' . $callback);
            } else {
                $message = '注册失败';
            }
        }

        //出错信息展示
        if ($message != '') {
            $this->email = $email;
            $this->username = $username;

            $this->redirect('reg', false);
            //var_dump($message);
            Util::showMessage($message);
        }
    }

    //身份验证邮件
    function verify_email_send() {
        $email = ISafe::get('email');
        $user_id = ISafe::get('user_id');
        //找回密码（ctarget=3）时，无email
        if (!$email) {
            $email = IFilter::act(IReq::get('email'));
        }
        if (!$user_id) {
            $user_id = IFilter::act(IReq::get('user_id'));
        }

        $ctarget = IFilter::act(IReq::get('ctarget'));

        $hash = IHash::md5(microtime(true) . mt_rand());
        //记录邮件验证
        $email_active = new IModel("email_active");
        $email_active->setData(array('hash' => $hash, 'user_id' => $user_id, 'addtime' => time()));
        $email_id = $email_active->add();
        //发送邮件 判断是修改邮箱还是修改手机
        if ($ctarget == 1) {
            $url = IUrl::creatUrl("/simple/modify_email/hash/{$hash}");
            $url = IUrl::getHost() . $url;
            $body = "您正在美贝壳官网上申请修改邮箱绑定。请您点击下面这个链接验证邮箱：<a href=\"{$url}\">{$url}</a>。<br />如果不能点击，请您把它复制到地址栏中打开。<br />本链接在3天后将自动失效。如果你没有请求该链接，则你的帐户安全，可以忽略该电子邮件。其他用户可能在尝试重置他们自己的密码时错误地输入了你的电子邮件地址。<br/>谢谢!<br/>MeiBeiKe 帐户团队";
            $subject = "美贝壳官网邮箱验证";
        } else if ($ctarget == 2) {
            $url = IUrl::creatUrl("/simple/modify_mobile_by_email/hash/{$hash}");
            $url = IUrl::getHost() . $url;
            $body = "您正在美贝壳官网上申请修改手机绑定。请您点击下面这个链接验证邮箱：<a href=\"{$url}\">{$url}</a>。<br />如果不能点击，请您把它复制到地址栏中打开。<br />本链接在3天后将自动失效。如果你没有请求该链接，则你的帐户安全，可以忽略该电子邮件。其他用户可能在尝试重置他们自己的密码时错误地输入了你的电子邮件地址。<br/>谢谢!<br/>MeiBeiKe 帐户团队";
            $subject = "美贝壳官网邮箱验证";
        } else if ($ctarget == 3) {
            $url = IUrl::creatUrl("/simple/fogot_password/way/1/hash/{$hash}");
            $url = IUrl::getHost() . $url;
            $body = "您正在美贝壳官网上申请找回密码。请您点击下面这个链接修改密码：<a href=\"{$url}\">{$url}</a>。<br />如果不能点击，请您把它复制到地址栏中打开。<br />本链接在3天后将自动失效。如果你没有请求该链接，则你的帐户安全，可以忽略该电子邮件。其他用户可能在尝试重置他们自己的密码时错误地输入了你的电子邮件地址。<br/>谢谢!<br/>MeiBeiKe 帐户团队";
            $subject = "美贝壳官网找回密码";
        } else {
            $return = array(
                'isError' => true,
                'message' => "修改失败",
            );
            echo JSON::encode($return);
            return;
        }
        $smtp = new SendMail();
        $error = $smtp->getError();
        if ($error) {
            $return = array(
                'isError' => true,
                'message' => $error,
            );
            echo JSON::encode($return);
            return;
        }
        //$status = $smtp->send($email, $subject, $body);
		$status=Db_Meicloud::add_email($email, $subject, $body,'','','');
        $email_list = explode("@", $email);
        $server = "mail." . $email_list[1];
        $return = array(
            'isError' => false,
            'message' => $status,
            'server' => "http://" . $server,
        );
        //var_dump(ISafe::get("user_id"));
        echo JSON::encode($return);
        return;
    }

    //发送激活邮件
    function reg_email_send($email = '', $user_id = '') {
        $email = $email ? $email : IReq::get('email');
        $user_id = $user_id ? $user_id : IReq::get('user_id');

        if (!$email) {
            $email = ISafe::get("email");
        }
        if (!$user_id) {
            $user_id = ISafe::get("user_id");
        }

        $hash = IHash::md5(microtime(true) . mt_rand());
        //记录邮件验证
        $email_active = new IModel("email_active");
        $email_active->setData(array('hash' => $hash, 'email' => $email, 'user_id' => $user_id, 'addtime' => time()));
        $email_id = $email_active->add();
        //发送邮件
        $url = IUrl::creatUrl("/simple/reg_email_active/hash/{$hash}");
        $url = IUrl::getHost() . $url;
        $body = "请您点击下面这个链接验证邮箱：<br /><a href=\"{$url}\">{$url}</a>。<br />如果不能点击，请您把它复制到地址栏中打开。<br />本链接在3天后将自动失效。如果你没有请求该链接，则你的帐户安全，可以忽略该电子邮件。其他用户可能在尝试重置他们自己的密码时错误地输入了你的电子邮件地址。<br/>谢谢!<br/>MeiBeiKe 帐户团队";
        $subject = "美贝壳官网注册邮件";
        $smtp = new SendMail();
        $error = $smtp->getError();
        if ($error) {
            $return = array(
                'isError' => true,
                'message' => $error,
            );
            echo JSON::encode($return);
            return;
        }
        //$status = $smtp->send($email, $subject, $body);
		$status=Db_Meicloud::add_email($email, $subject, $body,'','','');
        return $status;
    }

    //邮件激活
    function reg_email_active() {
        $hash = IReq::get("hash");
        if (!$hash) {
            //throw new IHttpException("参数不完整", 0);
            $this->redirect("/site/error/msg/" . urlencode("参数不完整！"));
            exit;
        }
        $hash = IFilter::act($hash, 'string');
        $tb = new IModel("email_active");
        $addtime = time() - 3600 * 72;
        $row = $tb->getObj(" hash ='$hash' AND addtime>$addtime ");
        if (!$row) {
            //throw new IHttpException("本链接已失效，请重新申请邮件激活", 0);
            $this->redirect("/site/error/msg/" . urlencode("本链接已失效，请重新申请邮件激活"));
            exit;
        }
        $user_id = $row["user_id"];
        //更新meiid
        $userDB = new IModel("user");
        $userinfo = $userDB->getObj("id = $user_id");
        if ($userinfo["active"] == 0) {
            //存储过程更新
            $meiid = Db_Meicloud::addUser($userinfo["email"], $userinfo["username"], $userinfo["password"], 0);

            $userDB->setData(array("active" => 1, "meiid" => $meiid));
            $userDB->update("id = $user_id");
            ISafe::set("active", 1);
        }
        //获得登录状态
        $userObj = new IModel('user as u,member as m');
        $where = "u.id =  $user_id and m.status = 1 and u.id = m.user_id";
        $userRow = $userObj->getObj($where);
        $this->loginAfter($userRow);

        $user = new IModel("user");
        //更新用户激活状态
        $user->setData(array("active" => 1));
        $user->update("id = {$user_id}");

        $this->redirect("/site/success/message/" . urlencode("邮箱激活成功！"));
    }

    //发送手机验证码
    function verify_mobile_send() {
        $mobile = IReq::get('mobile');
        $vcode = rand(100000, 999999);          
        $flag = Db_Meicloud::sendMsg('验证码：' . $vcode . '，该验证码10分钟内有效。', "$mobile");
        //$flag = true;
        if($flag == true){
            $data['mobile'] = $mobile;
            $data['vcode'] = $vcode;
            $data['create_time'] = ITime::getDateTime() ;
            $res = $this->save_vcode($data);
            $result["code"] = 1;
            echo json_encode($result);
            exit;
        }else{
            $result['message'] = "短信发送失败";
            $result["code"] = 0;
            echo json_encode($result);
            exit;
        }
    }
    
    //验证手机验证码
    function verify_reg_vcode(){
        $verify_code = IFilter::act(IReq::get('verify_code', 'post'));
        $mobile = IFilter::act(IReq::get('mobile', 'post'));
        $last_vcode = $this->get_last_vcode($mobile);       
        $pattern = '/^[1-9][0-9]{5}$/';
        if (!preg_match($pattern,$verify_code)) {
            $message = '验证码格式不正确';
            $result['message'] = $message;
            $result["code"] = 0;
            echo json_encode($result);
            exit;
        }else if($last_vcode != $verify_code){
            $result['message'] = "验证码输入不正确!";
            $result["code"] = 0;
            echo json_encode($result);
            exit;
        }else{
            $result["code"] = 1;
            echo json_encode($result);
            exit;
        }
                
    }
    //邮件激活
    function modify_email() {
        $hash = IReq::get("hash");
        if (!$hash) {
            //throw new IHttpException("参数不完整", 0);
            $this->redirect("/site/error/msg/" . urlencode("参数不完整"));
            exit;
        }
        $hash = IFilter::act($hash, 'string');
        $tb = new IModel("email_active");
        $addtime = time() - 3600 * 72;
        $row = $tb->getObj(" hash ='$hash' AND addtime>$addtime ");
        if (!$row) {
            //throw new IHttpException("本链接已失效，请重新申请邮件激活", 0);
            $this->redirect("/site/error/msg/" . urlencode("本链接已失效，请重新申请邮件激活"));
            exit;
        }
        $user_id = $row["user_id"];
        //获得登录状态
        $userObj = new IModel('user as u,member as m');
        $where = "u.id =  $user_id and m.status = 1 and u.id = m.user_id";
        $userRow = $userObj->getObj($where);
        $this->loginAfter($userRow);
        $this->redirect("/ucenter/modify_email");
    }

    //邮件激活
    function modify_mobile_by_email() {
        $hash = IReq::get("hash");
        if (!$hash) {
            //throw new IHttpException("参数不完整", 0);
            $this->redirect("/site/error/msg/" . urlencode("参数不完整"));
            exit;
        }
        $hash = IFilter::act($hash, 'string');
        $tb = new IModel("email_active");
        $addtime = time() - 3600 * 72;
        $row = $tb->getObj(" hash ='$hash' AND addtime>$addtime ");
        if (!$row) {
            //throw new IHttpException("本链接已失效，请重新申请邮件激活", 0);
            $this->redirect("/site/error/msg/" . urlencode("本链接已失效，请重新申请邮件激活"));
            exit;
        }
        $user_id = $row["user_id"];
        //获得登录状态
        $userObj = new IModel('user as u,member as m');
        $where = "u.id =  $user_id and m.status = 1 and u.id = m.user_id";
        $userRow = $userObj->getObj($where);
        $this->loginAfter($userRow);
        $this->redirect("/ucenter/mobile_bind");
    }

    //邮件激活(手机验证)
    function modify_email_by_mobile() {
        $mobile = IReq::get("mobile");
        $vcode = IReq::get("vcode");
        if ((!$mobile) || (!$vcode)) {
            $this->redirect("/site/error/msg/" . urlencode("参数不完整！"));
            exit;
        }
        //验证mobile和vcode
        if ($mobile != ISafe::get("mobile")) {
            $this->redirect("/site/error/msg/" . urlencode("手机来源非法！"));
            exit;
        }
        if ($vcode != ISafe::get("vcode")) {
            //throw new IHttpException("用户校验码不匹配！", 0);
            $this->redirect("/site/error/msg/" . urlencode("用户校验码不匹配！"));
            exit;
        }

        $this->redirect("/ucenter/modify_email");
    }

    //手机激活(手机验证)
    function modify_mobile_by_mobile() {
        $mobile = IReq::get("mobile");
        $vcode = IReq::get("vcode");
        if ((!$mobile) || (!$vcode)) {
            $this->redirect("/site/error/msg/" . urlencode("参数不完整！"));
            exit;
        }
        //验证mobile和vcode
        if ($mobile != ISafe::get("mobile")) {
            $this->redirect("/site/error/msg/" . urlencode("手机来源非法！"));
            exit;
        }
        if ($vcode != ISafe::get("vcode")) {
            //throw new IHttpException("用户校验码不匹配！", 0);
            $this->redirect("/site/error/msg/" . urlencode("用户校验码不匹配！"));
            exit;
        }

        $this->redirect("/ucenter/mobile_bind");
    }

    function check_mobile() {
        $mobile = IReq::get("mobile");
        if ($mobile != ISafe::get("mobile")) {
            echo "请正确填写已绑定的手机号！";
            return;
        }
        echo 1;
        return;
    }

    function get_failed_cont() {
        if (!ISafe::get("error_try")) {
            echo 0;
        } else {
            echo ISafe::get("error_try");
        }
        return;
    }

    function check_login() {
        $login_info = IFilter::act(IReq::get('login_info', 'post'));
        $password = IReq::get('password', 'post');
        $captcha = IFilter::act(IReq::get('captcha', 'post'));
        $message = '';

        //如果已经失败过5次，则需要验证验证码
        if (ISafe::get("error_try") >= 5) {
            if (strtoupper($captcha) != strtoupper(ISafe::get('captcha'))) {
                $message = '验证码输入不正确';
            }
        }
        if (!$message) {
            if ($login_info == '' || $login_info == '输入手机号码/输入邮箱') {
                $message = '请填写手机或者邮箱';
            } else if (!preg_match('|\S{6,32}|', $password)) {
                $message = '密码格式不正确,请输入6-32个字符';
            } else {
                /* if ($userRow = CheckRights::isValidUser($login_info, md5($password))) {
                  //$this->loginAfter($userRow);
                  } else {
                  $message = '用户名和密码不匹配';
                  } */
                //由存储过程取数据
               // echo "$login_info";
              //  echo md5($password);
                if ($userRow = Db_Meicloud::userLogin($login_info, md5($password), 0)) {
                    
                } else if ($userRow = Db_Meicloud::userLogin($login_info, md5($password), 1)) {
                    
                } else if ($userRow = CheckRights::isValidUser($login_info, md5($password))) {
                    
                } else {
                    $message = '用户名和密码不匹配';
                }
            }
        }


        $result = array();
        $result['message'] = $message;
        if ($message != '') {
            $result['code'] = 0;
            if (!ISafe::get("error_try")) {
                ISafe::set("error_try", 1);
            } else {
                ISafe::set("error_try", ISafe::get("error_try") + 1);
            }
            if (ISafe::get("error_try") >= 5) {
                $result["valid_required"] = 1;
            }
        } else {
            $result['code'] = 1;
            ISafe::set("error_try", 0);
        }

        echo json_encode($result);
    }
    
    ///写入预约信息
    function write_appoint() {  
        //var_dump($res);exit;
      //PROCEDURE proc_appointment_add(IN v_appointment_no varchar(32),IN v_purchase_time int, IN v_user_type int, IN v_mei_id int,IN v_vendor_id varchar(32),IN v_pic_url varchar(128) , OUT v_result int)

        $v_mei_id = IFilter::act(IReq::get('v_mei_id', 'post'));
//         var_dump($v_mei_id);
//        $a =  substr($v_mei_id,-3,3);
     //  var_dump($a);
        $v_user_type = IFilter::act(IReq::get('v_user_type', 'post'));
        $v_purchase_time = IFilter::act(IReq::get('v_purchase_time', 'post'));
        //$v_appointment_no = Order_Class::createServiceNum();
        $res = Db_Meicloud::appoint_process();
        //var_dump($res);
       // exit;
        if($res == false){
            
            $v_appointment_no = substr($v_mei_id,-3,3).rand(10000,99999);
        }else{
            $v_appointment_no = $res;
        }
        
        //$v_appointment_no = $v_mei_id.rand(10000,99999);
        $v_vendor_id = IFilter::act(IReq::get('v_vendor_id', 'post'));
        $v_pic_url = IFilter::act(IReq::get('v_pic_url', 'post'));
        $message2="美ID：".$v_mei_id."用户类型：".$v_user_type."预约号：".$v_appointment_no."抢购时间：".$v_purchase_time."厂商编号：".$v_vendor_id."图片地址：".$v_pic_url; 
        //if($v_mei_id>0) {$message="美ID2：".$v_mei_id; }
        
        
        //print_r temp_1;
        
        //$message=$temp_1;
        $message="";
        if(empty($v_mei_id)) {$message="您还没有登录！";}
        if(empty($v_purchase_time)) {$message="预订时间不能为空!";}
        if(empty($v_user_type)) {$message="帐户类型不能为空!";}
        

        $result = array();
        $result['message'] = $message;
        if ($message != '') {
            $result['code'] = 0;
            if (!ISafe::get("error_try")) {
                ISafe::set("error_try", 1);
            } else {
                ISafe::set("error_try", ISafe::get("error_try") + 1);
            }
            if (ISafe::get("error_try") >= 5) {
                $result["valid_required"] = 1;
            }
        } else {
            $temp_1=Db_Meicloud::write_appoint($v_appointment_no,$v_purchase_time,$v_user_type,$v_mei_id,$v_vendor_id, $v_pic_url);
            $result['code'] = 1;
            $result['aid'] = $v_appointment_no ;
            ISafe::set("error_try", 0);
        }

        echo json_encode($result);
    }
    
    
    ///写入预约信息
    function write_appoint3() {
        //var_dump($res);exit;
        //PROCEDURE proc_appointment_add(IN v_appointment_no varchar(32),IN v_purchase_time int, IN v_user_type int, IN v_mei_id int,IN v_vendor_id varchar(32),IN v_pic_url varchar(128) , OUT v_result int)
        $web_from = ISession::get("from_site");
//         var_dump("web_from:".$web_from);
//         exit;
        $ip = $this->get_client_ip();
        $v_mei_id = IFilter::act(IReq::get('v_mei_id', 'post'));
        //         var_dump($v_mei_id);
        //        $a =  substr($v_mei_id,-3,3);
        //  var_dump($a);
        $v_user_type = IFilter::act(IReq::get('v_user_type', 'post'));
        $v_purchase_time = IFilter::act(IReq::get('v_purchase_time', 'post'));
        //$v_appointment_no = Order_Class::createServiceNum();
        $res = Db_Meicloud::appoint_process();
        //var_dump($res);
        // exit;
        if($res == false){
    
            $v_appointment_no = "04".substr($v_mei_id,-3,3).rand(10000,99999);
        }else{
            $v_appointment_no = $res;
        }
    
        //$v_appointment_no = $v_mei_id.rand(10000,99999);
        $v_vendor_id = IFilter::act(IReq::get('v_vendor_id', 'post'));
        $v_pic_url = IFilter::act(IReq::get('v_pic_url', 'post'));
        $message2="美ID：".$v_mei_id."用户类型：".$v_user_type."预约号：".$v_appointment_no."抢购时间：".$v_purchase_time."厂商编号：".$v_vendor_id."图片地址：".$v_pic_url;
        //if($v_mei_id>0) {$message="美ID2：".$v_mei_id; }
    
    
        //print_r temp_1;
    
        //$message=$temp_1;
        $message="";
        if(empty($v_mei_id)) {$message="您还没有登录！";}
        if(empty($v_purchase_time)) {$message="预订时间不能为空!";}
        if(empty($v_user_type)) {$message="帐户类型不能为空!";}
    
    
        $result = array();
        $result['message'] = $message;
        if ($message != '') {
            $result['code'] = 0;
            if (!ISafe::get("error_try")) {
                ISafe::set("error_try", 1);
            } else {
                ISafe::set("error_try", ISafe::get("error_try") + 1);
            }
            if (ISafe::get("error_try") >= 5) {
                $result["valid_required"] = 1;
            }
        } else {
            $temp_1=Db_Meicloud::write_appoint3($v_appointment_no,$v_purchase_time,$v_user_type,$v_mei_id,$v_vendor_id, $v_pic_url, $web_from,$ip);
            if(is_array($temp_1)){
                if($temp_1['ret']!=0){                
                    if($temp_1['ret']=='1'){
                        $obj = new IQuery("user u");
                        $obj->join = "inner join mbk_appointment a on a.mei_id = u.meiid";
                        $obj->where = "u.id = {$v_mei_id} and a.purchase_time = {$v_purchase_time}";
                        $obj->fields = "a.appointment_no";
                        $res = $obj->find();
                        $result['code'] = 2;
                        $result['aid'] = $res[0]['appointment_no'] ;
                        $result['message'] = "您已经预约，预约号是{$res[0]['appointment_no']}";
                    }else{
                        $result['code'] = 1;
                        $result['aid'] =  $v_appointment_no;
                        $result['message'] = "{$temp_1['ret']}";
                    }
                }else{
                    $result['code'] = 0;
                    $result['message'] = '预约失败,请联系技术人员';
                }
            }else{
                $result['code'] = 0;
                $result['message'] = '系统异常!!!';
            }
            ISafe::set("error_try", 0);
        }
    
        echo json_encode($result);
    }

    //用户登录
    function login_act() {
        /* $isajax = IReq::get('isajax');
          if($isajax){
          return $this->login_act_ajax();
          } */
        $login_info = IFilter::act(IReq::get('login_info', 'post'));
        $password = IReq::get('password', 'post');
        $remember = IFilter::act(IReq::get('remember', 'post'));
        $autoLogin = IFilter::act(IReq::get('autoLogin', 'post'));
        $callback = IFilter::act(IReq::get('callback'), 'text');
        $orderCallback = IFilter::act(IReq::get('orderCallback'), 'post');
        $expire = time() + 60 * 60 * 24 * 7;
        setCookie("111_aaa","nameamtuofo",$expire,"/");
        $message = '';

        if ($login_info == '') {
            $message = '请填写手机或者邮箱';
        } else if (!preg_match('|\S{6,32}|', $password)) {
            $message = '密码格式不正确,请输入6-32个字符';
        } else {
            //echo md5($password)."登录1";
            if (Db_Meicloud::userLogin($login_info, md5($password), 0) || Db_Meicloud::userLogin($login_info, md5($password), 1) || CheckRights::isValidUser($login_info, md5($password))) {
                $userRow = CheckRights::isValidUser($login_info, md5($password));
                $this->loginAfter($userRow);

                //记住帐号
                if ($remember == 1) {
                    ICookie::set('loginName', $login_info);
                }

                //自动登录
                if ($autoLogin == 1) {
                    ICookie::set('autoLogin', $autoLogin);
                }

                //自定义跳转页面
                if ($callback && !strpos($callback, 'reg') && !strpos($callback, 'login')) {
                	if(!empty($orderCallback)){
                		$this->redirect($orderCallback);
                	}else{
                		$this->redirect($callback);
                	}                    
                } else {
                    $this->redirect('/site/index');
                }
            } else {
                $message = '用户名和密码不匹配';
            }
        }

        //错误信息		-- 新版本不会走到这里 kevin
        if ($message != '') {
            $this->message = $message;
            $_GET['callback'] = $callback;
            $this->redirect('login', false);
        }
    }

    //用户登录(ajax)
    public function login_act_ajax() {
        $login_info = IFilter::act(IReq::get('login_info'));
        $password = IReq::get('password');
        //echo $password."登录2";
        $message = '';

        if ($login_info == '') {
            $message = '请填写手机或者邮箱';
        } else if (!preg_match('|\S{6,32}|', $password)) {
            $message = '密码格式不正确,请输入6-32个字符';
        } else {
            //echo md5($password)."登录3";
            if (Db_Meicloud::userLogin($login_info, md5($password), 0) || Db_Meicloud::userLogin($login_info, md5($password), 1) || CheckRights::isValidUser($login_info, md5($password))) {
                $userRow = CheckRights::isValidUser($login_info, md5($password));
                $this->loginAfter($userRow);
            } else {
                $message = '用户名和密码不匹配';
            }
        }

        $result = array();
        $result['message'] = $message;
        if ($message != '') {
            $result['code'] = 0;
        } else {
            $result['code'] = 1;
        }

        echo json_encode($result);
    }

    //登录后的处理
    function loginAfter($userRow) {
        
        //用户私密数据
//     	$expire = time() + 60 * 60 * 24 * 7;
//     	setCookie("222_bbb","nameamtuofo",$expire,"/");
//     	setCookie("333_ccc",$userRow['mobile'],$expire,"/");
        ISafe::set('user_id', $userRow['id']);
        ISafe::set('username', $userRow['username']);
        ISafe::set('head_ico', $userRow['head_ico']);
        ISafe::set('user_pwd', $userRow['password']);
        ISafe::set('last_login', $userRow['last_login']);
        ISafe::set('email', $userRow['email']);
        ISafe::set('mobile', $userRow['mobile']);
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
    }

    //商品加入购物车[ajax]
    function joinCart() {
        $link = IReq::get('link');
        $goods_id = intval(IReq::get('goods_id'));
        $goods_num = IReq::get('goods_num') === null ? 1 : intval(IReq::get('goods_num'));
        $type = IFilter::act(IReq::get('type'));

        //加入购物车
        $cartObj = new Cart();
        $addResult = $cartObj->add($goods_id, $goods_num, $type);

        if ($link != '') {
            if ($addResult === false) {
                $this->cart(false);
                Util::showMessage($cartObj->getError());
            } else {
                $this->redirect($link);
            }
        } else {
            if ($addResult === false) {
                $result = array(
                    'isError' => true,
                    'message' => $cartObj->getError(),
                );
            } else {
                $result = array(
                    'isError' => false,
                    'message' => '添加成功',
                );
            }
            echo JSON::encode($result);
        }
    }

    //根据goods_id获取货品
    function getProducts() {
        $id = IFilter::act(IReq::get('id'), 'int');
        $productObj = new IModel('products');
        $productsList = $productObj->query('goods_id = ' . $id, 'sell_price,id,spec_array,goods_id', 'store_nums', 'desc', 7);
        if ($productsList) {
            foreach ($productsList as $key => $val) {
                $productsList[$key]['specData'] = Block::show_spec($val['spec_array']);
            }
            echo JSON::encode($productsList);
        }
    }

    //删除购物车
    function removeCart() {
        $link = IReq::get('link');
        $goods_id = intval(IReq::get('goods_id'));
        $type = IReq::get('type');

        $cartObj = new Cart();
        $cartInfo = $cartObj->getMyCart();
        $delResult = $cartObj->del($goods_id, $type);

        if ($link != '') {
            if ($delResult === false) {
                $this->cart(false);
                Util::showMessage($cartObj->getError());
            } else {
                $this->redirect($link);
            }
        } else {
            if ($delResult === false) {
                $result = array(
                    'isError' => true,
                    'message' => $cartObj->getError(),
                );
            } else {
                $goodsRow = $cartInfo[$type]['data'][$goods_id];
                $cartInfo['sum'] -= $goodsRow['sell_price'] * $goodsRow['count'];
                $cartInfo['count'] -= $goodsRow['count'];

                $result = array(
                    'isError' => false,
                    'data' => $cartInfo,
                );
            }

            echo JSON::encode($result);
        }
    }

    //清空购物车
    function clearCart() {
        $cartObj = new Cart();
        $cartObj->clear();
        $this->redirect('cart');
    }

    //购物车div展示
    function showCart() {
        $cartObj = new Cart();
        $cartList = $cartObj->getMyCart();
        $data['data'] = array_merge($cartList['goods']['data'], $cartList['product']['data']);
        $data['count'] = $cartList['count'];
        $data['sum'] = $cartList['sum'];
        echo JSON::encode($data);
    }

    //购物车页面及商品价格计算[复杂]
    function cart($redirect = false) {
        //防止页面刷新
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);

        //开始计算购物车中的商品价格
        $countObj = new CountSum();
        $result = $countObj->cart_count();

        //返回值
        $this->final_sum = $result['final_sum'];
        $this->promotion = $result['promotion'];
        $this->proReduce = $result['proReduce'];
        $this->sum = $result['sum'];
        $this->goodsList = $result['goodsList'];
        $this->productList = $result['productList'];
        $this->count = $result['count'];
        $this->reduce = $result['reduce'];
        $this->weight = $result['weight'];

        //渲染视图
        $this->redirect('cart', $redirect);
    }

    //计算促销规则[ajax]
    function promotionRuleAjax() {
        $promotion = array();
        $proReduce = 0;

        //总金额满足的促销规则
        if ($this->user['user_id']) {
            $final_sum = intval(IReq::get('final_sum'));

            //获取 user_group
            $groupObj = new IModel('member as m,user_group as g');
            $groupRow = $groupObj->getObj('m.user_id = ' . $this->user['user_id'] . ' and m.group_id = g.id', 'g.*');
            $groupRow['id'] = empty($groupRow) ? 0 : $groupRow['id'];

            $proObj = new ProRule($final_sum);
            $proObj->setUserGroup($groupRow['id']);

            $promotion = $proObj->getInfo();
            $proReduce = $final_sum - $proObj->getSum();
        }

        $result = array(
            'promotion' => $promotion,
            'proReduce' => $proReduce,
        );

        echo JSON::encode($result);
    }

    //购物车寄存功能[写入]
    function deposit_cart_set() {
        $is_ajax = IReq::get('is_ajax');

        //必须为登录用户
        if ($this->user['user_id'] == null) {
            $callback = "/simple/cart";
            $this->redirect('/simple/login?callback={$callback}');
        }

        //获取购物车中的信息
        $cartObj = new Cart();
        $myCartInfo = $cartObj->getMyCart();

        /* 寄存的数据
          格式：goods => array (id => count);
         */
        $depositArray = array();

        if (isset($myCartInfo['goods']['id']) && !empty($myCartInfo['goods']['id'])) {
            foreach ($myCartInfo['goods']['id'] as $id) {
                $depositArray['goods'][$id] = $myCartInfo['goods']['data'][$id]['count'];
            }
        }

        if (isset($myCartInfo['product']['id']) && !empty($myCartInfo['product']['id'])) {
            foreach ($myCartInfo['product']['id'] as $id) {
                $depositArray['product'][$id] = $myCartInfo['product']['data'][$id]['count'];
            }
        }

        if (empty($depositArray)) {
            $isError = true;
            $message = '您的购物车中没有商品';
        } else {
            $isError = false;
            $dataArray = array(
                'user_id' => $this->user['user_id'],
                'content' => serialize($depositArray),
                'create_time' => ITime::getDateTime(),
            );

            $goodsCarObj = new IModel('goods_car');
            $goodsCarRow = $goodsCarObj->getObj('user_id = ' . $this->user['user_id']);
            $goodsCarObj->setData($dataArray);

            if (empty($goodsCarRow)) {
                $goodsCarObj->add();
            } else {
                $goodsCarObj->update('user_id = ' . $this->user['user_id']);
            }
            $message = '寄存成功';
        }

        //ajax方式
        if ($is_ajax == 1) {
            $result = array(
                'isError' => $isError,
                'message' => $message,
            );

            echo JSON::encode($result);
        }

        //传统跳转方式
        else {
            //页面跳转
            $this->cart();
            if (isset($message)) {
                Util::showMessage($message);
            }
        }
    }

    //购物车寄存功能[读取]ajax
    function deposit_cart_get() {
        //isError:0正常;1错误
        $result = array('isError' => 1, 'message' => '');

        //必须为登录用户
        if ($this->user['user_id'] == null) {
            $result['message'] = '用户尚未登录';
            echo JSON::encode($result);
            return;
        }

        $goodsCatObj = new IModel('goods_car');
        $goodsCarRow = $goodsCatObj->getObj('user_id = ' . $this->user['user_id']);

        if (!isset($goodsCarRow['content'])) {
            $result['message'] = '您没有寄存任何商品';
            echo JSON::encode($result);
            return;
        }

        $depositContent = unserialize($goodsCarRow['content']);

        //获取购物车中的信息
        $cartObj = new Cart();
        $myCartInfo = $cartObj->getMyCartStruct();

        if (isset($depositContent['goods'])) {
            foreach ($depositContent['goods'] as $id => $count) {
                $depositGoods = $cartObj->getUpdateCartData($myCartInfo, $id, $count, 'goods');
                $myCartInfo = $depositGoods;
            }
        }

        if (isset($depositContent['product'])) {
            foreach ($depositContent['product'] as $id => $count) {
                $depositProducts = $cartObj->getUpdateCartData($myCartInfo, $id, $count, 'product');
                $myCartInfo = $depositProducts;
            }
        }

        //写入购物车
        $cartObj->setMyCart($myCartInfo);
        $result['isError'] = 0;
        echo JSON::encode($result);
    }

    //清空寄存购物车
    function deposit_cart_clear() {
        //必须为登录用户
        if ($this->user['user_id'] == null) {
            $this->redirect('/simple/login?callback=/simple/cart');
        }

        $goodsCarObj = new IModel('goods_car');
        $goodsCarObj->del('user_id = ' . $this->user['user_id']);
        $this->cart();
        Util::showMessage('操作成功');
    }

    public function checkFcode($param){
    	if(empty($param)){
    		$user_id = $this->user['user_id'];
    		if(empty($user_id)){
    			Probability::ajaxmsg("请先登录",0);
    		}
    		$fcode = IFilter::act(IReq::get('fcode'));
    		$fQuery = new IQuery('fm f');
    		$fQuery->join = "inner join mbk_fm_activity fa on fa.id = f.aid";
    		$fQuery->fields = "fa.start_time,fa.end_time,fa.prize_type,fa.prize_limit,f.use_time";
    		$fQuery->where = "f.f_code = '{$fcode}'";
    		$r = $fQuery->find();
    		if(empty($r)){
    			Probability::ajaxmsg("此F码不存在",0);
    		}else{
    			$fArr = $r[0];
    			$start_time = strtotime($fArr['start_time']);
    			$end_time = strtotime($fArr['end_time']);
    			$currentTime = time();
    			if(!empty($fArr['use_time'])){
    				Probability::ajaxmsg("此F码已被使用",0);
    			}else if($currentTime < $start_time || $currentTime > $end_time){
    				Probability::ajaxmsg("此F码已过期",0);
    			}else{
    				if($fArr['prize_type'] == '0'){
    					//代金券
    					Probability::ajaxmsg("{$fArr['prize_limit']}",1);
    				}else if($fArr['prize_type'] == '1'){
    					Probability::ajaxmsg("",1);
    				}
    			}
    		}
    	}else{
    		$fcode = $param;
    		$fQuery = new IQuery('fm f');
    		$fQuery->join = "inner join mbk_fm_activity fa on fa.id = f.aid";
    		$fQuery->fields = "fa.start_time,fa.end_time,fa.prize_type,fa.prize_limit,fa.prize_comment,f.use_time";
    		$fQuery->where = "f.f_code = '{$fcode}'";
    		$r = $fQuery->find();
    		if(empty($r)){
    			return "此F码不存在";
    		}else{
    			$fArr = $r[0];
    			$start_time = strtotime($fArr['start_time']);
    			$end_time = strtotime($fArr['end_time']);
    			$currentTime = time();
    			if(!empty($fArr['use_time'])){
    				return "此F码已被使用";
    			}else if($currentTime < $start_time || $currentTime > $end_time){
    				return false;
    			}else{
    				if($fArr['prize_type'] == '0'){
    					//代金券
    					$data['prize_type'] = $fArr['prize_type'];
    					$data['prize_limit'] = $fArr['prize_limit'];
    					return $data;
    				}else if($fArr['prize_type'] == '1'){
    					$data['prize_type'] = $fArr['prize_type'];
    					$data['prize_comment'] = $fArr['prize_comment'];
    				}
    			}
    		}
    	}
    }
    
    //填写订单信息cart2
    function cart2() {
		$this->menu_name = "cart2";
        $id = IFilter::act(IReq::get('id'), 'int');
        $type = IFilter::act(IReq::get('type'));
        $promo = IFilter::act(IReq::get('promo'));
        $active_id = IFilter::act(IReq::get('active_id'), 'int');
        $buy_num = IReq::get('num') ? IFilter::act(IReq::get('num'), 'int') : 1;
        $tourist = IReq::get('tourist'); //游客方式购物
        //活动购买方式
        if ($promo == 'groupon' && $active_id != '') {
            $hashId = $this->user['user_id'] ? $this->user['user_id'] : ICookie::get("regiment_{$active_id}");
			
            //此团购还存在已经报名但是未付款的情况
            if (regiment::hasJoined($active_id, $hashId) == true) {
                IError::show(403, '您已经参加过此次团购，请先完成支付');
                exit;
            }

            //团购已经达到限定的人数
            if (regiment::isFull($active_id) == true) {
                IError::show(403, '此团购的参加人数已满');
                exit;
            }
        }

        //必须为登录用户
        if ($tourist === null && $this->user['user_id'] == null) {
            if ($id == 0 || $type == '') {
                $this->redirect('/simple/login?tourist&callback=/simple/cart2');
            } else {
                $url = '/simple/login?tourist&callback=/simple/cart2/id/' . $id . '/type/' . $type . '/num/' . $buy_num;
                $url .= $promo ? '/promo/' . $promo : '';
                $url .= $active_id ? '/active_id/' . $active_id : '';
                $this->redirect($url);
            }
        }

        //游客的user_id默认为0
        $user_id = ($this->user['user_id'] == null) ? 0 : $this->user['user_id'];

        //获取收货地址
        $addressObj = new IModel('address');
        $addressList = $addressObj->query('user_id = ' . $user_id);

        //更新$addressList数据
        foreach ($addressList as $key => $val) {
            $temp = area::name($val['province'], $val['city'], $val['area']);

            $addressList[$key]['province_val'] = $temp[$val['province']];
            $addressList[$key]['city_val'] = $temp[$val['city']];
            $addressList[$key]['area_val'] = $temp[$val['area']];
            if ($val['default'] == 1) {
                $this->defaultAddressId = $val['id'];
            }
        }

        //获取用户的道具红包和用户的习惯方式
        $this->prop = array();
        $memberObj = new IModel('member');
        $memberRow = $memberObj->getObj('user_id = ' . $user_id, 'prop,custom');

        if (isset($memberRow['prop']) && ($propId = trim($memberRow['prop'], ','))) {
            $porpObj = new IModel('prop');
            $this->prop = $porpObj->query('id in (' . $propId . ') and NOW() between start_time and end_time and type = 0 and is_close = 0 and is_userd = 0 and is_send = 1', 'id,name,value,card_name');
        }

        if (isset($memberRow['custom']) && $memberRow['custom'] != '') {
            $this->custom = unserialize($memberRow['custom']);
        } else {
            $this->custom = array(
                'payment' => '',
                'delivery' => '',
            );
        }

        //计算商品
        $countSumObj = new CountSum();

        //判断是特定活动还是购物车
        if ($id != 0 && $type != '') {
            $result = $countSumObj->direct_count($id, $type, $buy_num, $promo, $active_id);
            $this->gid = $id;
            $this->type = $type;
            $this->num = $buy_num;
            $this->promo = $promo;
            $this->active_id = $active_id;
        } else {
            //计算购物车中的商品价格
            $result = $countSumObj->cart_count();
        }

        if ($result['count'] == 0) {
            $this->redirect('/simple/cart');
            exit;
        }

        //返回值
        $this->final_sum = $result['final_sum'];
        $this->promotion = $result['promotion'];
        $this->proReduce = $result['proReduce'];
        $this->sum = $result['sum'];
        $this->goodsList = $result['goodsList'];
        $this->productList = $result['productList'];
        $this->count = $result['count'];
        $this->reduce = $result['reduce'];
        $this->weight = $result['weight'];
        $this->freeFreight = $result['freeFreight'];

        //收货地址列表
        $this->addressList = $addressList;

        //获取商品税金
        $this->goodsTax = $countSumObj->getGoodsTax($this->sum);
		$data['callback'] = '/site/sy';
		$this->setRenderData($data);
        //渲染页面
        $this->redirect('cart2');
    }
    
    
    //填写订单信息cart2
    function cart2_wap() {
    	//$this->layout = '';
        $this->menu_name = "cart2_wap";
        $id = IFilter::act(IReq::get('id'), 'int');
        $type = IFilter::act(IReq::get('type'));
        $promo = IFilter::act(IReq::get('promo'));
        $active_id = IFilter::act(IReq::get('active_id'), 'int');
        $buy_num = IReq::get('num') ? IFilter::act(IReq::get('num'), 'int') : 1;
        $tourist = IReq::get('tourist'); //游客方式购物
        //活动购买方式
        if ($promo == 'groupon' && $active_id != '') {
            $hashId = $this->user['user_id'] ? $this->user['user_id'] : ICookie::get("regiment_{$active_id}");
    
            //此团购还存在已经报名但是未付款的情况
            if (regiment::hasJoined($active_id, $hashId) == true) {
                IError::show(403, '您已经参加过此次团购，请先完成支付');
                exit;
            }
    
            //团购已经达到限定的人数
            if (regiment::isFull($active_id) == true) {
                IError::show(403, '此团购的参加人数已满');
                exit;
            }
        }
    
        //必须为登录用户
        if ($tourist === null && $this->user['user_id'] == null) {
            if ($id == 0 || $type == '') {
                $this->redirect('/simple/login?tourist&callback=/simple/cart2');
            } else {
                $url = '/simple/login?tourist&callback=/simple/cart2/id/' . $id . '/type/' . $type . '/num/' . $buy_num;
                $url .= $promo ? '/promo/' . $promo : '';
                $url .= $active_id ? '/active_id/' . $active_id : '';
                $this->redirect($url);
            }
        }
    
        //游客的user_id默认为0
        $user_id = ($this->user['user_id'] == null) ? 0 : $this->user['user_id'];
    
        //获取收货地址
        $addressObj = new IModel('address');
        $addressList = $addressObj->query('user_id = ' . $user_id);
    
        //更新$addressList数据
        foreach ($addressList as $key => $val) {
            $temp = area::name($val['province'], $val['city'], $val['area']);
    
            $addressList[$key]['province_val'] = $temp[$val['province']];
            $addressList[$key]['city_val'] = $temp[$val['city']];
            $addressList[$key]['area_val'] = $temp[$val['area']];
            if ($val['default'] == 1) {
                $this->defaultAddressId = $val['id'];
            }
        }
    
        //获取用户的道具红包和用户的习惯方式
        $this->prop = array();
        $memberObj = new IModel('member');
        $memberRow = $memberObj->getObj('user_id = ' . $user_id, 'prop,custom');
    
        if (isset($memberRow['prop']) && ($propId = trim($memberRow['prop'], ','))) {
            $porpObj = new IModel('prop');
            $this->prop = $porpObj->query('id in (' . $propId . ') and NOW() between start_time and end_time and type = 0 and is_close = 0 and is_userd = 0 and is_send = 1', 'id,name,value,card_name');
        }
    
        if (isset($memberRow['custom']) && $memberRow['custom'] != '') {
            $this->custom = unserialize($memberRow['custom']);
        } else {
            $this->custom = array(
                'payment' => '',
                'delivery' => '',
            );
        }
    
        //计算商品
        $countSumObj = new CountSum();
    
        //判断是特定活动还是购物车
        if ($id != 0 && $type != '') {
            $result = $countSumObj->direct_count($id, $type, $buy_num, $promo, $active_id);
            $this->gid = $id;
            $this->type = $type;
            $this->num = $buy_num;
            $this->promo = $promo;
            $this->active_id = $active_id;
        } else {
            //计算购物车中的商品价格
            $result = $countSumObj->cart_count();
        }
    
        if ($result['count'] == 0) {
            $this->redirect('/simple/cart');
            exit;
        }
    
        //返回值
        $this->final_sum = $result['final_sum'];
        $this->promotion = $result['promotion'];
        $this->proReduce = $result['proReduce'];
        $this->sum = $result['sum'];
        $this->goodsList = $result['goodsList'];
        $this->productList = $result['productList'];
        $this->count = $result['count'];
        $this->reduce = $result['reduce'];
        $this->weight = $result['weight'];
        $this->freeFreight = $result['freeFreight'];
    
        //收货地址列表
        $this->addressList = $addressList;
    
        //获取商品税金
        $this->goodsTax = $countSumObj->getGoodsTax($this->sum);
    
        //渲染页面
        $this->redirect('cart2_wap');
    }

    /**
     * 生成订单
     */
    function cart3() {
		$this->menu_name = "cart3";
        $accept_name = IFilter::act(IReq::get('accept_name'));
        $province = IFilter::act(IReq::get('province'), 'int');
        $city = IFilter::act(IReq::get('city'), 'int');
        $area = IFilter::act(IReq::get('area'), 'int');
        $address = IFilter::act(IReq::get('address'));
        $mobile = IFilter::act(IReq::get('mobile'));
        $telphone = IFilter::act(IReq::get('telphone'));
        $zip = IFilter::act(IReq::get('zip'));
        $delivery_id = IFilter::act(IReq::get('delivery_id'), 'int');
        $accept_time = IFilter::act(IReq::get('accept_time'));
        $payment = IFilter::act(IReq::get('payment'), 'int');
        $order_message = IFilter::act(IReq::get('message'));
        $ticket_id = IFilter::act(IReq::get('ticket_id'), 'int');
        $taxes = IFilter::act(IReq::get('taxes'), 'float');
        $insured = IFilter::act(IReq::get('insured'), 'float');
        $tax_title = IFilter::act(IReq::get('tax_title'), 'text');
        $gid = IFilter::act(IReq::get('direct_gid'), 'int');
        $num = IFilter::act(IReq::get('direct_num'), 'int');
        $type = IFilter::act(IReq::get('direct_type')); //商品或者货品
        $promo = IFilter::act(IReq::get('direct_promo'));
        $active_id = IFilter::act(IReq::get('direct_active_id'), 'int');
        $fcode = IFilter::act(IReq::get('fcode'));
        $order_no = Order_Class::createOrderNum();
        $order_type = 0;
        //是否开发票
        $is_note = IFilter::act(IReq::get('is_note'),'int');
        $dataArray = array();
		
        //防止表单重复提交
        if (IReq::get('timeKey') != null) {
            if (ISafe::get('timeKey') == IReq::get('timeKey')) {
                IError::show(403, '订单数据不能被重复提交');
                exit;
            } else {
                ISafe::set('timeKey', IReq::get('timeKey'));
            }
        }

        if ($province == 0 || $city == 0 || $area == 0) {
            IError::show(403, '请填写收货地址的省市地区');
        }

        if ($delivery_id == 0) {
            IError::show(403, '请选择配送方式');
        }

        $user_id = ($this->user['user_id'] == null) ? 0 : $this->user['user_id'];

        //活动特殊处理
        if ($promo != '' && $active_id != '') {
            //团购
            if ($promo == 'groupon') {
                $hashId = $user_id ? $user_id : ICookie::get("regiment_{$active_id}");

                //此团购还存在已经报名但是未付款的情况
                if (regiment::hasJoined($active_id, $hashId) == true) {
                    IError::show(403, '您已经参加过此次团购，请先完成支付');
                    exit;
                }

                //团购已经达到限定的人数
                if (regiment::isFull($active_id) == true) {
                    IError::show(403, '此团购的参加人数已满');
                    exit;
                }

                $order_type = 1;

                //团购开始报名
                $joinUserId = $user_id ? $user_id : null;
                $resultData = regiment::join($active_id, $joinUserId);
                $is_success = '';

                if ($resultData['flag'] == true) {
                    $regimentRelationObj = new IModel('regiment_user_relation');
                    $regimentRelationObj->setData(array('order_no' => $order_no));
                    $is_success = $regimentRelationObj->update('id = ' . $resultData['relation_id']);
                }

                if ($is_success == '' || $resultData['flag'] == false) {
                    $errorMsg = ( isset($resultData['data']) && $resultData['data'] != '' ) ? $resultData['data'] : '团购报名失败';
                    IError::show(403, $errorMsg);
                    exit;
                }
            }
            //限时抢购
            else if ($promo == 'time') {
                $order_type = 2;
            }
        }

        //付款方式,判断是否为货到付款
        $deliveryObj = new IModel('delivery');
        $deliveryRow = $deliveryObj->getObj('id = ' . $delivery_id);

        if ($deliveryRow['type'] == 0 && $payment == 0) {
            IError::show(403, '请选择正确的支付方式');
        } else if ($deliveryRow['type'] == 1) {
            $payment = 0;
        }

        
        
        //计算费用
        $countSumObj = new CountSum();

        //直接购买商品方式
        if ($type != '' && $gid != 0) {
            //计算$gid商品
            $goodsResult = $countSumObj->direct_count($gid, $type, $num, $promo, $active_id);
        } else {
            //计算购物车中的商品价格$goodsResult
            $goodsResult = $countSumObj->cart_count();

            //清空购物车
            $cartObj = new Cart();
            $cartObj->clear();
        }

        //判断商品商品是否存在
        if (empty($goodsResult['goodsList']) && empty($goodsResult['productList'])) {
            IError::show(403, '商品数据不存在');
            exit;
        }

        //获取红包减免金额
        if ($ticket_id != '') {
            $memberObj = new IModel('member');
            $memberRow = $memberObj->getObj('user_id = ' . $user_id, 'prop,custom');

            if (ISafe::get('ticket_' . $ticket_id) == $ticket_id || stripos(',' . trim($memberRow['prop'], ',') . ',', ',' . $ticket_id . ',') !== false) {
                $propObj = new IModel('prop');
                $ticketRow = $propObj->getObj('id = ' . $ticket_id . ' and NOW() between start_time and end_time and type = 0 and is_close = 0 and is_userd = 0 and is_send = 1');
                if (!empty($ticketRow)) {
                    $dataArray['prop'] = $ticket_id;
                }

                //锁定红包状态
                $propObj->setData(array('is_close' => 2));
                $propObj->update('id = ' . $ticket_id);
            }
        }

        $paymentObj = new IModel('payment');
        $paymentRow = $paymentObj->getObj('id = ' . $payment, 'type,name');
        $paymentName = $paymentRow['name'];
        $paymentType = $paymentRow['type'];
		//F码有效并且为代金券时订单减去代金券金额
		if(!empty($fcode)){
			$callRes = $this->checkFcode($fcode);
		}
		if(is_string($callRes)){
			IError::show(403, $callRes);
			exit;
		}else if(is_array($callRes)){
			if($callRes['prize_type'] == '0'){
				$goodsResult['final_sum'] = $goodsResult['final_sum'] - intval($callRes['prize_limit']);
				$fm = new IModel('fm');
				$fmData['user_meiid'] = $this->user['user_id'];
				$fmData['use_time'] = ITime::getDateTime();
				$fmData['order_number'] = $order_no;
				$where = "f_code = '{$fcode}'";
				$fm->setData($fmData);
				$r = $fm->update($where);
				if($r == false){
					IError::show(403, "F码使用异常");
					exit;
				}
			}else if($callRes['prize_type'] == '1'){
				
			}
		}
        //最终订单金额计算
        $orderData = $countSumObj->countOrderFee($goodsResult['sum'], $goodsResult['final_sum'], $goodsResult['weight'], $province, $delivery_id, $payment, $goodsResult['freeFreight'], $insured);

        //生成的订单数据
        $dataArray = array(
            'order_no' => $order_no,
            'user_id' => $user_id,
            'accept_name' => $accept_name,
            'pay_type' => $payment,
            'distribution' => $delivery_id,
            'postcode' => $zip,
            'telphone' => $telphone,
            'province' => $province,
            'city' => $city,
            'area' => $area,
            'address' => $address,
            'mobile' => $mobile,
            'create_time' => ITime::getDateTime(),
            'postscript' => $order_message,
            'accept_time' => $accept_time,
            'exp' => $goodsResult['exp'],
            'point' => $goodsResult['point'],
            'type' => $order_type,
            //红包道具
            'prop' => isset($dataArray['prop']) ? $dataArray['prop'] : null,
            //商品价格
            'payable_amount' => $goodsResult['sum'],
            'real_amount' => $goodsResult['final_sum'],
            //运费价格
            'payable_freight' => $orderData['deliveryOrigPrice'],
            'real_freight' => $orderData['deliveryPrice'],
            //手续费
            'pay_fee' => $orderData['paymentPrice'],
            //税金
            //'invoice' => $taxes ? 1 : 0,
            'invoice' => $is_note ? 1 : 0,
            'invoice_title' => $tax_title,
            'taxes' => $taxes,
            //优惠价格
            'promotions' => $goodsResult['proReduce'] + $goodsResult['reduce'] + (isset($ticketRow['value']) ? $ticketRow['value'] : 0),
            //订单应付总额
            'order_amount' => $orderData['orderAmountPrice'] - (isset($ticketRow['value']) ? $ticketRow['value'] : 0),
            //订单保价
            'if_insured' => $insured ? 1 : 0,
            'insured' => $insured,
        );
        $dataArray['order_amount'] = $dataArray['order_amount'] <= 0 ? 0 : $dataArray['order_amount'];

        $orderObj = new IModel('order');
        $orderObj->setData($dataArray);

        $this->order_id = $orderObj->add();

        if ($this->order_id == false) {
            IError::show(403, '订单生成错误');
        }

        /* 将订单中的商品插入到order_goods表 */
        $orderInstance = new Order_Class();
        $orderInstance->insertOrderGoods($this->order_id, $goodsResult);

        //记录用户默认习惯的数据
        if (!isset($memberRow['custom'])) {
            $memberObj = new IModel('member');
            $memberRow = $memberObj->getObj('user_id = ' . $user_id, 'custom');
        }

        $memberData = array(
            'custom' => serialize(
                    array(
                        'payment' => $payment,
                        'delivery' => $delivery_id,
                    )
            ),
        );
        $memberObj->setData($memberData);
        $memberObj->update('user_id = ' . $user_id);

        //收货地址的处理
        if ($user_id) {
            $addressObj = new IModel('address');

            //如果用户之前没有收货地址,那么会自动记录此次的地址信息并且为默认
            $addressRow = $addressObj->getObj('user_id = ' . $user_id);
            if (empty($addressRow)) {
                $addressData = array('default' => '1', 'user_id' => $user_id, 'accept_name' => $accept_name, 'province' => $province, 'city' => $city, 'area' => $area, 'address' => $address, 'zip' => $zip, 'telphone' => $telphone, 'mobile' => $mobile);
                $addressObj->setData($addressData);
                $addressObj->add();
            } else {
                //如果用户有收货地址,但是没有设置默认项,那么会自动设置此次地址信息为默认
                $radio_address = intval(IReq::get('radio_address'));
                if ($radio_address != 0) {
                    $addressDefRow = $addressObj->getObj('user_id = ' . $user_id . ' and `default` = 1');
                    if (empty($addressDefRow)) {
                        $addressData = array('default' => 1);
                        $addressObj->setData($addressData);
                        $addressObj->update('user_id = ' . $user_id . ' and id = ' . $radio_address);
                    }
                }
            }
        }

        //获取备货时间
        $siteConfigObj = new Config("site_config");
        $site_config = $siteConfigObj->getInfo();
        $this->stockup_time = isset($site_config['stockup_time']) ? $site_config['stockup_time'] : 2;

        //数据渲染
        $this->order_num = $dataArray['order_no'];
        $this->final_sum = $dataArray['order_amount'];
        $this->payment = $paymentName;
        $this->paymentType = $paymentType;
        $this->delivery = $deliveryRow['name'];
        $this->tax_title = $tax_title;
        $this->deliveryType = $deliveryRow['type'];
        $config = new IModel('config');
        $r = $config->getObj("config_type = '订单配置'");
        $this->payLine = $r['field1'];
        //订单金额为0时，订单自动完成
        if ($this->final_sum <= 0) {
            $order_id = Order_Class::updateOrderStatus($dataArray['order_no']);
            if ($order_id != '') {
                if ($user_id) {
                    $this->redirect('/site/success/message/' . urlencode("订单确认成功，等待发货") . '/?callback=ucenter/order_detail/id/' . $order_id);
                } else {
                    $this->redirect('/site/success/message/' . urlencode("订单确认成功，等待发货"));
                }
            } else {
                IError::show(403, '订单修改失败');
            }
        } else {
            $this->setRenderData($dataArray);
            $this->redirect('cart3');
        }
    }

    
    function cart3_wap() {
    	//$this->layout = '';
        $this->menu_name = "cart3";
        $accept_name = IFilter::act(IReq::get('accept_name'));
        $province = IFilter::act(IReq::get('province'), 'int');
        $city = IFilter::act(IReq::get('city'), 'int');
        $area = IFilter::act(IReq::get('area'), 'int');
        $address = IFilter::act(IReq::get('address'));
        $mobile = IFilter::act(IReq::get('mobile'));
        $telphone = IFilter::act(IReq::get('telphone'));
        $zip = IFilter::act(IReq::get('zip'));
        $delivery_id = IFilter::act(IReq::get('delivery_id'), 'int');
        $accept_time = IFilter::act(IReq::get('accept_time'));
        $payment = IFilter::act(IReq::get('payment'), 'int');
        $order_message = IFilter::act(IReq::get('message'));
        $ticket_id = IFilter::act(IReq::get('ticket_id'), 'int');
        $taxes = IFilter::act(IReq::get('taxes'), 'float');
        $insured = IFilter::act(IReq::get('insured'), 'float');
        $tax_title = IFilter::act(IReq::get('tax_title'), 'text');
        $gid = IFilter::act(IReq::get('direct_gid'), 'int');
        $num = IFilter::act(IReq::get('direct_num'), 'int');
        $type = IFilter::act(IReq::get('direct_type')); //商品或者货品
        $promo = IFilter::act(IReq::get('direct_promo'));
        $active_id = IFilter::act(IReq::get('direct_active_id'), 'int');
        $order_no = Order_Class::createOrderNum();
        $order_type = 0;
        $dataArray = array();
    
        //防止表单重复提交
        if (IReq::get('timeKey') != null) {
            if (ISafe::get('timeKey') == IReq::get('timeKey')) {
                IError::show(403, '订单数据不能被重复提交');
                exit;
            } else {
                ISafe::set('timeKey', IReq::get('timeKey'));
            }
        }
    
        if ($province == 0 || $city == 0 || $area == 0) {
            IError::show(403, '请填写收货地址的省市地区');
        }
    
        if ($delivery_id == 0) {
            IError::show(403, '请选择配送方式');
        }
    
        $user_id = ($this->user['user_id'] == null) ? 0 : $this->user['user_id'];
    
        //活动特殊处理
        if ($promo != '' && $active_id != '') {
            //团购
            if ($promo == 'groupon') {
                $hashId = $user_id ? $user_id : ICookie::get("regiment_{$active_id}");
    
                //此团购还存在已经报名但是未付款的情况
                if (regiment::hasJoined($active_id, $hashId) == true) {
                    IError::show(403, '您已经参加过此次团购，请先完成支付');
                    exit;
                }
    
                //团购已经达到限定的人数
                if (regiment::isFull($active_id) == true) {
                    IError::show(403, '此团购的参加人数已满');
                    exit;
                }
    
                $order_type = 1;
    
                //团购开始报名
                $joinUserId = $user_id ? $user_id : null;
                $resultData = regiment::join($active_id, $joinUserId);
                $is_success = '';
    
                if ($resultData['flag'] == true) {
                    $regimentRelationObj = new IModel('regiment_user_relation');
                    $regimentRelationObj->setData(array('order_no' => $order_no));
                    $is_success = $regimentRelationObj->update('id = ' . $resultData['relation_id']);
                }
    
                if ($is_success == '' || $resultData['flag'] == false) {
                    $errorMsg = ( isset($resultData['data']) && $resultData['data'] != '' ) ? $resultData['data'] : '团购报名失败';
                    IError::show(403, $errorMsg);
                    exit;
                }
            }
            //限时抢购
            else if ($promo == 'time') {
                $order_type = 2;
            }
        }
    
        //付款方式,判断是否为货到付款
        $deliveryObj = new IModel('delivery');
        $deliveryRow = $deliveryObj->getObj('id = ' . $delivery_id);
    
        if ($deliveryRow['type'] == 0 && $payment == 0) {
            IError::show(403, '请选择正确的支付方式');
        } else if ($deliveryRow['type'] == 1) {
            $payment = 0;
        }
    
        //计算费用
        $countSumObj = new CountSum();
    
        //直接购买商品方式
        if ($type != '' && $gid != 0) {
            //计算$gid商品
            $goodsResult = $countSumObj->direct_count($gid, $type, $num, $promo, $active_id);
        } else {
            //计算购物车中的商品价格$goodsResult
            $goodsResult = $countSumObj->cart_count();
    
            //清空购物车
            $cartObj = new Cart();
            $cartObj->clear();
        }
    
        //判断商品商品是否存在
        if (empty($goodsResult['goodsList']) && empty($goodsResult['productList'])) {
            IError::show(403, '商品数据不存在');
            exit;
        }
    
        //获取红包减免金额
        if ($ticket_id != '') {
            $memberObj = new IModel('member');
            $memberRow = $memberObj->getObj('user_id = ' . $user_id, 'prop,custom');
    
            if (ISafe::get('ticket_' . $ticket_id) == $ticket_id || stripos(',' . trim($memberRow['prop'], ',') . ',', ',' . $ticket_id . ',') !== false) {
                $propObj = new IModel('prop');
                $ticketRow = $propObj->getObj('id = ' . $ticket_id . ' and NOW() between start_time and end_time and type = 0 and is_close = 0 and is_userd = 0 and is_send = 1');
                if (!empty($ticketRow)) {
                    $dataArray['prop'] = $ticket_id;
                }
    
                //锁定红包状态
                $propObj->setData(array('is_close' => 2));
                $propObj->update('id = ' . $ticket_id);
            }
        }
    
        $paymentObj = new IModel('payment');
        $paymentRow = $paymentObj->getObj('id = ' . $payment, 'type,name');
        $paymentName = $paymentRow['name'];
        $paymentType = $paymentRow['type'];
    
        //最终订单金额计算
        $orderData = $countSumObj->countOrderFee($goodsResult['sum'], $goodsResult['final_sum'], $goodsResult['weight'], $province, $delivery_id, $payment, $goodsResult['freeFreight'], $insured);
    
        //生成的订单数据
        $dataArray = array(
            'order_no' => $order_no,
            'user_id' => $user_id,
            'accept_name' => $accept_name,
            'pay_type' => $payment,
            'distribution' => $delivery_id,
            'postcode' => $zip,
            'telphone' => $telphone,
            'province' => $province,
            'city' => $city,
            'area' => $area,
            'address' => $address,
            'mobile' => $mobile,
            'create_time' => ITime::getDateTime(),
            'postscript' => $order_message,
            'accept_time' => $accept_time,
            'exp' => $goodsResult['exp'],
            'point' => $goodsResult['point'],
            'type' => $order_type,
            //红包道具
            'prop' => isset($dataArray['prop']) ? $dataArray['prop'] : null,
            //商品价格
            'payable_amount' => $goodsResult['sum'],
            'real_amount' => $goodsResult['final_sum'],
            //运费价格
            'payable_freight' => $orderData['deliveryOrigPrice'],
            'real_freight' => $orderData['deliveryPrice'],
            //手续费
            'pay_fee' => $orderData['paymentPrice'],
            //税金
            'invoice' => $taxes ? 1 : 0,
            'invoice_title' => $tax_title,
            'taxes' => $taxes,
            //优惠价格
            'promotions' => $goodsResult['proReduce'] + $goodsResult['reduce'] + (isset($ticketRow['value']) ? $ticketRow['value'] : 0),
            //订单应付总额
            'order_amount' => $orderData['orderAmountPrice'] - (isset($ticketRow['value']) ? $ticketRow['value'] : 0),
            //订单保价
            'if_insured' => $insured ? 1 : 0,
            'insured' => $insured,
        );
    
        $dataArray['order_amount'] = $dataArray['order_amount'] <= 0 ? 0 : $dataArray['order_amount'];
    
        $orderObj = new IModel('order');
        $orderObj->setData($dataArray);
    
        $this->order_id = $orderObj->add();
    
        if ($this->order_id == false) {
            IError::show(403, '订单生成错误');
        }
    
        /* 将订单中的商品插入到order_goods表 */
        $orderInstance = new Order_Class();
        $orderInstance->insertOrderGoods($this->order_id, $goodsResult);
    
        //记录用户默认习惯的数据
        if (!isset($memberRow['custom'])) {
            $memberObj = new IModel('member');
            $memberRow = $memberObj->getObj('user_id = ' . $user_id, 'custom');
        }
    
        $memberData = array(
            'custom' => serialize(
                array(
                    'payment' => $payment,
                    'delivery' => $delivery_id,
                )
            ),
        );
        $memberObj->setData($memberData);
        $memberObj->update('user_id = ' . $user_id);
    
        //收货地址的处理
        if ($user_id) {
            $addressObj = new IModel('address');
    
            //如果用户之前没有收货地址,那么会自动记录此次的地址信息并且为默认
            $addressRow = $addressObj->getObj('user_id = ' . $user_id);
            if (empty($addressRow)) {
                $addressData = array('default' => '1', 'user_id' => $user_id, 'accept_name' => $accept_name, 'province' => $province, 'city' => $city, 'area' => $area, 'address' => $address, 'zip' => $zip, 'telphone' => $telphone, 'mobile' => $mobile);
                $addressObj->setData($addressData);
                $addressObj->add();
            } else {
                //如果用户有收货地址,但是没有设置默认项,那么会自动设置此次地址信息为默认
                $radio_address = intval(IReq::get('radio_address'));
                if ($radio_address != 0) {
                    $addressDefRow = $addressObj->getObj('user_id = ' . $user_id . ' and `default` = 1');
                    if (empty($addressDefRow)) {
                        $addressData = array('default' => 1);
                        $addressObj->setData($addressData);
                        $addressObj->update('user_id = ' . $user_id . ' and id = ' . $radio_address);
                    }
                }
            }
        }
    
        //获取备货时间
        $siteConfigObj = new Config("site_config");
        $site_config = $siteConfigObj->getInfo();
        $this->stockup_time = isset($site_config['stockup_time']) ? $site_config['stockup_time'] : 2;
    
        //数据渲染
        $this->order_num = $dataArray['order_no'];
        $this->final_sum = $dataArray['order_amount'];
        $this->payment = $paymentName;
        $this->paymentType = $paymentType;
        $this->delivery = $deliveryRow['name'];
        $this->tax_title = $tax_title;
        $this->deliveryType = $deliveryRow['type'];
    
        //订单金额为0时，订单自动完成
        if ($this->final_sum <= 0) {
            $order_id = Order_Class::updateOrderStatus($dataArray['order_no']);
            if ($order_id != '') {
                if ($user_id) {
                    $this->redirect('/site/success/message/' . urlencode("订单确认成功，等待发货") . '/?callback=ucenter/order_detail/id/' . $order_id);
                } else {
                    $this->redirect('/site/success/message/' . urlencode("订单确认成功，等待发货"));
                }
            } else {
                IError::show(403, '订单修改失败');
            }
        } else {
            $this->setRenderData($dataArray);
            $this->redirect('cart3_wap');
        }
    }
    
    //到货通知处理动作
    function arrival_notice() {
        $user_id = IFilter::act(ISafe::get('user_id'), 'int');
        $email = IFilter::act(IReq::get('email'));
        $mobile = IFilter::act(IReq::get('mobile'));
        $goods_id = IFilter::act(IReq::get('goods_id'), 'int');
        $register_time = date('Y-m-d H:i:s');

        if (!$goods_id) {
            IError::show(403, '商品ID不存在');
        }

        $model = new IModel('notify_registry');
        $obj = $model->getObj("email = '{$email}' and user_id = '{$user_id}' and goods_id = '$goods_id'");
        if (empty($obj)) {
            $model->setData(array('email' => $email, 'user_id' => $user_id, 'mobile' => $mobile, 'goods_id' => $goods_id, 'register_time' => $register_time));
            $model->add();
        } else {
            $model->setData(array('email' => $email, 'user_id' => $user_id, 'mobile' => $mobile, 'goods_id' => $goods_id, 'register_time' => $register_time, 'notify_status' => 0));
            $model->update('id = ' . $obj['id']);
        }
        $this->redirect('arrival_result');
    }

    //到货通知登记页面
    function arrival() {
        $this->redirect('arrival');
    }

    //添加收藏夹
    function favorite_add() {
        $goods_id = intval(IReq::get('goods_id'));
        $message = '';

        if ($goods_id == 0) {
            $message = '商品id值不能为空';
        } else if (!isset($this->user['user_id']) || !$this->user['user_id']) {
            $message = '请先登录';
        } else {
            $favoriteObj = new IModel('favorite');
            $goodsRow = $favoriteObj->getObj('user_id = ' . $this->user['user_id'] . ' and rid = ' . $goods_id);
            if ($goodsRow) {
                $message = '您已经收藏过此件商品';
            } else {
                $catObj = new IModel('category_extend');
                $catRow = $catObj->getObj('goods_id = ' . $goods_id);
                $cat_id = $catRow ? $catRow['category_id'] : 0;

                $dataArray = array(
                    'user_id' => $this->user['user_id'],
                    'rid' => $goods_id,
                    'time' => ITime::getDateTime(),
                    'cat_id' => $cat_id,
                );
                $favoriteObj->setData($dataArray);
                $favoriteObj->add();
            }
        }

        if ($message == '') {
            $result = array(
                'isError' => false,
                'message' => '收藏成功',
            );
        } else {
            $result = array(
                'isError' => true,
                'message' => $message,
            );
        }

        echo JSON::encode($result);
    }

    /**
     * @brief 商户的增加动作
     */
    public function seller_reg() {
        $seller_name = IFilter::act(IReq::get('seller_name'));
        $email = IFilter::act(IReq::get('email'));
        $password = IFilter::act(IReq::get('password'));
        $repassword = IFilter::act(IReq::get('repassword'));
        $truename = IFilter::act(IReq::get('true_name'));
        $phone = IFilter::act(IReq::get('phone'));
        $mobile = IFilter::act(IReq::get('mobile'));
        $province = IFilter::act(IReq::get('province'), 'int');
        $city = IFilter::act(IReq::get('city'), 'int');
        $area = IFilter::act(IReq::get('area'), 'int');
        $address = IFilter::act(IReq::get('address'));
        $home_url = IFilter::act(IReq::get('home_url'));

        if ($password == '') {
            $errorMsg = '请输入密码！';
        }

        if ($password != $repassword) {
            $errorMsg = '两次输入的密码不一致！';
        }

        //创建商家操作类
        $sellerDB = new IModel("seller");
        if ($sellerDB->getObj("seller_name = '{$seller_name}'")) {
            $errorMsg = "登录用户名重复";
        } else if ($sellerDB->getObj("true_name = '{$truename}'")) {
            $errorMsg = "商户真实全程重复";
        }

        //操作失败表单回填
        if (isset($errorMsg)) {
            $this->sellerRow = $_POST;
            $this->redirect('seller_edit', false);
            Util::showMessage($errorMsg);
        }

        //待更新的数据
        $sellerRow = array(
            'true_name' => $truename,
            'phone' => $phone,
            'mobile' => $mobile,
            'email' => $email,
            'address' => $address,
            'province' => $province,
            'city' => $city,
            'area' => $area,
            'home_url' => $home_url,
            'is_lock' => 1,
        );

        //商户资质上传
        if (isset($_FILES['paper_img']['name']) && $_FILES['paper_img']['name']) {
            $uploadObj = new PhotoUpload();
            $uploadObj->setIterance(false);
            $photoInfo = $uploadObj->run();
            if (isset($photoInfo['paper_img']['img']) && file_exists($photoInfo['paper_img']['img'])) {
                $sellerRow['paper_img'] = $photoInfo['paper_img']['img'];
            }
        }

        $sellerRow['seller_name'] = $seller_name;
        $sellerRow['password'] = md5($password);
        $sellerRow['create_time'] = ITime::getDateTime();

        $sellerDB->setData($sellerRow);
        $sellerDB->add();

        $this->redirect('/site/success?message=' . urlencode("申请成功！请耐心等待管理员的审核"));
    }

    //验证手机号是否已存在
    public function check_mobile_exist() {
        $mobile = IReq::get('mobile');
        $Obj = new IModel("user");
        $res = $Obj->getObj("mobile = '$mobile'");
        if (!$res) {
            echo 1;
        } else {
            echo 0;
        }
    }

    //忘记密码_选择修改方式
    public function fogot_password_auth() {
        $username = IReq::get('username');
        //判断是手机还是邮箱
        if(strpos($username, "@")){
            $checktype = 1;
        }else{
            $checktype = 2;
        }
        $userObj = new IModel('user');
        $where = "mobile = '$username' or email='$username'";
        $userRow = $userObj->getObj($where);
        $userRow["checktype"] = $checktype;
        if (!$userRow) {
            IError::show(403, '用户信息不存在');
        }
        $this->setRenderData($userRow);
        $this->redirect("auth", false);
    }

    //忘记密码修改页面
    public function fogot_password() {
        $way = IReq::get('way');


        if ($way == 1) {
            //邮件找回
            $hash = IReq::get('hash');
            if (!$hash) {
                IError::show(403, '您无权访问此页面');
            }
            $tb = new IModel("email_active");
            $addtime = time() - 3600 * 72;
            $row = $tb->getObj(" hash ='$hash' AND addtime>$addtime ");
            if (!$row) {
                IError::show(403, "本链接已失效，请重新申请找回密码");
                exit;
            }
            $user_id = $row["user_id"];
            //获得登录状态
            //$userObj = new IModel('user as u,member as m');
            //$where = "u.id =  $user_id and m.status = 1 and u.id = m.user_id";
            //$userRow = $userObj->getObj($where);
            //$this->loginAfter($userRow);
        } else if ($way == 2) {
            $mobile =  IFilter::act(IReq::get("telphone","post"));
            $last_vcode = $this->get_last_vcode($mobile);
            $verify_code = IFilter::act(IReq::get("vcode","post"));
            //短信找回
            $vcode = IReq::get("vcode");
            if (!$vcode) {
                IError::show(403, '您无权访问此页面');
            }
            if ($last_vcode != $verify_code) {
                IError::show(403, '您输入的验证码错误！');
            }
            $user_id = IReq::get("user_id");
        } else {
            IError::show(403, '您无权访问此页面');
        }
        $this->setRenderData(array("user_id" => $user_id, "way" => $way));

        $this->redirect("fogot_password");
        
    }
      

    function check_mobile_vcode() {
        $mobile =  IFilter::act(IReq::get("mobile","post"));
        $last_vcode = $this->get_last_vcode($mobile);
        $verify_code = IFilter::act(IReq::get("vcode","post"));
        $pattern = '/^[0-9]{6}/';
        if(!preg_match($pattern,$verify_code)){
            echo 0;
        }else{
            if($last_vcode != $verify_code){
                echo  0 ;
            }else{
                echo 1 ;
            }
        }
    }

    //找回密码验证用户信息和验证码
    function verify_username_fg() {
        $username = IFilter::act(IReq::get('username', 'post'));
        $captcha = IFilter::act(IReq::get('captcha', 'post'));

        $message = "";
        if (strtoupper($captcha) != strtoupper(ISafe::get('captcha'))) {
            $message = '验证码输入不正确';
        }

        if ($message == '') {
            //先取从meicloud用户信息
            Db_Meicloud::getUserInfo($username, 0); //邮箱
            Db_Meicloud::getUserInfo($username, 1); //手机
            $userObj = new IModel('user');
            $where = "mobile = '$username' or email='$username' or username='$username'";
            $userRow = $userObj->getObj($where);

            if (empty($userRow)) {
                $message = '您输入的用户信息不存在';
            }
        }

        $result = array();
        $result['message'] = $message;
        if ($message != '') {
            $result["code"] = 0;
        } else {
            $result["code"] = 1;
        }

        echo json_encode($result);
    }

    public function do_restore_password() {
        $way = IReq::get('way');
        $user_id = IReq::get('user_id');

        $password = IReq::get('password');
        $repassword = IReq::get('repassword');

        $userObj = new IModel('user');
        $where = 'id = ' . $user_id;
        $userRow = $userObj->getObj($where);

        if (!preg_match('|\w{6,32}|', $password)) {
            $message = '新密码格式不正确，请重新输入';
        } elseif ($password != $repassword) {
          $message = '二次密码输入的不一致，请重新输入';
        } else {
            $passwordMd5 = md5($password);
            //存储过程修改密码
            if ($way == 1) {
                Db_Meicloud::resetUserPwd($userRow["email"], 0, $passwordMd5);
            } else if ($way == 2) {
                Db_Meicloud::resetUserPwd($userRow["mobile"], 1, $passwordMd5);
            }
            $dataArray = array(
                'password' => $passwordMd5,
            );

            $userObj->setData($dataArray);
            $result = $userObj->update($where);
            if ($result) {
                //获得登录状态
                /* 2015-11-11 王要求不登录
                $umObj = new IModel('user as u,member as m');
                $where = "u.id =  $user_id and m.status = 1 and u.id = m.user_id";
                $userRow = $umObj->getObj($where);
                $this->loginAfter($userRow);
                 */

                $message = '密码修改成功';
                //echo '<script type="text/javascript">alert("' . $message . '")</script>';
                $this->redirect('/site/index');
                //exit(1);
            } else {
                $message = '新旧密码一致，请确认';
            }
        }

        $this->setRenderData(array("user_id" => $user_id, "way" => $way));
        $this->redirect('fogot_password', false);
        Util::showMessage($message);
    }

    public function test_proc() {
        //$pret = Db_Meicloud::addUser("18676720015", "lrc12311", MD5("111111"), 1);
        ISafe::set("usertype", "1");
        ISafe::set("password", MD5("222221"));
        ISafe::set("mobile", "18676720015");
        $pret = Db_Meicloud::userLogin("Ooo4@aa.com", 0, MD5("123456"));
        //$pret = Db_Meicloud::resetUserPwd("241934148@qq.com", 0, "123123123");
        //$pret = Db_Meicloud::checkUserExist("18676720046", 1);
        //$pret = Db_Meicloud::modifyUserPwd(MD5("222223"));
        //$pret = Db_Meicloud::add_shortmessage('testeeeeee', '18676720046');
        var_dump($pret);
    }
    /* 抽奖活动开始  zhaopeijun 2016-06-16*/
    private function getUserInfo (){
        $user_id = $this->user['user_id'];
        if(empty($user_id)) return "请先登录";
        else{
            $o = new IModel('user');
            $where = "id={$user_id}";
            $user = $o->getObj($where);
            if(!empty($user) && is_array($user)){
                $userInfo['id'] = $user['id'] ;
                $userInfo['meiid'] = $user['meiid'] ;
                return $userInfo ;
            }else{
                return "账号不存在";
            }
        }
    }
    
    private function get_prize_arr($activity_type){
        $pobj = new IQuery("prize p");
        $pobj->join = "inner join mbk_activity a on p.activity_type=a.activity_type";
        $pobj->where = "a.activity_type='{$activity_type}'";
        $pobj->fields = "p.id,p.field1,p.field3,p.description";
        $result = $pobj->find();
        if(!is_array($result)){
            return "活动奖品不存在";
        }
    
        foreach ($result as $key=>$val){
            if($val['field3'] == '0') {
                continue ;
            }else{
                $arr[$val['id']] = $val['field3'];
                $proArr[$val['id']] = $val;
            }
        }
        $pro = new Probability();
        $pid = $pro->get_rand($arr);//根据概率取得奖品ID
        $prizeName = $proArr[$pid]['field1'];
        $prizeDescription = $proArr[$pid]['description'];
        if(!$pid){
            return "随机获取奖品失败";
        }else{
            $prizeArr['id'] = $pid ;
            $prizeArr['name'] = $prizeName ;
            $prizeArr['description'] = $prizeDescription ;
            return $prizeArr;
        }
    }
    
    function appointment_lottery(){
        //判断是否登录，账号是否存在
        $userInfo = $this->getUserInfo();
        $purchase_time = IFilter::act(IReq::get('purchase_time','post'));
        if(!is_array($userInfo)){
            $msg['code'] = 2 ;
            $msg['message'] = $userInfo;
            echo json_encode($msg);
            exit;
        }
        $ps = DB_Meicloud::appointment_query($userInfo['id'],$purchase_time);
    
        //判断是否预约
        if($ps == 0){
            $activity_type = '2016mbkjulycj';
            $time = ITime::getDateTime();
            $ip = $this->get_client_ip();
            $is_lottery = DB_Meicloud::check_lottery($userInfo['id'],$userInfo['meiid'],$activity_type);
            //判断是否抽奖
            if($is_lottery == '0'){
                $msg['message'] = "您在此次活动中已经抽过奖了";
                $msg['code'] = 0;
                echo json_encode($msg);
                exit;
            }else if($is_lottery == '-1'){
                $msg['message'] = "查询中奖纪录失败";
                $msg['code'] = 0;
                echo json_encode($msg);
                exit;
            }
            $pr = $this->get_prize_arr($activity_type);
            if(!is_array($pr)){
                $msg['message'] = $pr;
                $msg['code'] = 0;
                echo json_encode($msg);
                exit;
            }
           
            //抽奖代码
            $r = DB_Meicloud::add_lottery($userInfo['id'],$userInfo['meiid'],$pr['id'],$activity_type,$time,$ip);
            if($r== '1'){
                $msg['message'] = "恭喜您抽奖成功";
                $msg['code'] = 1;
                $msg['name'] = $pr['name'];
                $msg['description'] = $pr['description'];
                echo json_encode($msg);
                exit;
            }else if($r== '0'){
                $msg['message'] = "您已经抽过奖了";
                $msg['code'] = 0;
                echo json_encode($msg);
                exit;
            }else if($r == '-1'){
                $msg['message'] = "中奖纪录保存失败";
                $msg['code'] = 0;
                echo json_encode($msg);
                exit;
            }
        }else if($ps == '1'){
            $msg['code'] = 0;
            $msg['message'] = "您在此次活动中还未预约";
            echo json_encode($msg);
            exit;
        }else{
            $msg['code'] = 0;
            $msg['message'] = "预约记录查询失败";
            echo json_encode($msg);
            exit;
        }
    }
	
    public function crontabDelOverdueOrder(){
    	$config = new IModel('config');
    	$r = $config->getObj("config_type = '订单配置'");
    	$timeArea = intval($r['field1']*3600);
    	$orderGoodsModel = new IModel('order_goods');
    	$orderGoodsModel->del("order_id in (select id from `mbk_order` where pay_status = 0 and (unix_timestamp(sysdate())-unix_timestamp(create_time))>{$timeArea})");
    	$orderModel = new IModel('order');
    	$where = 'pay_status = 0 and (unix_timestamp(sysdate())-unix_timestamp(create_time))>'.$timeArea;
    	$orderModel->del($where);

    }
}
