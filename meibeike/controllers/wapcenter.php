<?php

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

class WapCenter extends IController {
	public $layout = 'wapcenter_layouts';
    function init() {
        CheckRights::checkUserRights();
        if(empty($this->user['user_id'])) {
        	$this->redirect('/wap/login');
        }
    }
    
    function myprize(){
    	$this->menu_name = '我的奖品-美贝壳';
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
    	$this->setRenderData($goods);
    	$this->redirect('myprize');
    }
    
    private function getWaitReviewCount(){
    	$fQuery = new IQuery('fm_apply');
    	$fQuery->where = "apply_meiid = {$this->user['user_id']} and apply_status = 0";
    	$fQuery->fields = "count(*) total";
    	$res = $fQuery->find();
    	if(is_array($res)){
    		$waitReviewCount = $res[0]['total'];
    		$waitReviewCount = empty($waitReviewCount) ? 0 : $waitReviewCount;
    		return $waitReviewCount;
    	}else{
    		return 0;
    	}
    }
    
	private function getFcount(){
		$fQuery = new IQuery('fm f');
		$fQuery->join = "inner join mbk_fm_activity fa on f.aid = fa.id";
		$fQuery->where = "owner_meiid = {$this->user['user_id']}";
		$fQuery->fields = "count(*) total";
		$total = $fQuery->find();
		$fQuery->where = "owner_meiid = {$this->user['user_id']} and f.use_time is not null";
		$fQuery->fields = "count(*) used";
		$used = $fQuery->find();
		if(is_array($total)){
			$count['total'] = $total[0]['total'];
			$count['used'] = $used[0]['used'];
			$count['unused'] = $total[0]['total'] - $used[0]['used'];
			return $count;
		}
	}
	
	
	function myfcode(){
		$this->menu_name = '我的F码-美贝壳';
		$page = IReq::get('page');
		if (! $page) {
			$page = 1;
		}
		$status = IFilter::act(IReq::get('status'));
		if(!isset($status)){
			Util::successJump("无效参数",'/wap/index');
		}
		$where = "";
		if($status == '0'){
			$where .= " and sysdate()  between fa.start_time and fa.end_time and f.use_time is null";//未使用
		}else if($status == '1'){
			$where .= " and sysdate()  between fa.start_time and fa.end_time and f.use_time is not null";//已使用
		}else if($status == '2'){
			$where .= " and sysdate() not between fa.start_time and fa.end_time and f.use_time is null";
		}
		$count = $this->getFcount();
		$fQuery = new IQuery('fm f');        
		//$fQuery->page = $page;
		//$fQuery->pagesize = 20;
		//$fQuery->pagelength = $count['total'];
		$fQuery->join = "inner join mbk_fm_activity fa on f.aid = fa.id";
		$fQuery->fields = 'f.id,f.f_code,fa.start_time,fa.end_time,fa.activity_name,f.use_time,f.is_shared';
		$fQuery->where = "owner_meiid = {$this->user['user_id']}".$where;
		$data['flist'] = $fQuery->find();
		//$pagebar = $fQuery->getPageBar();
		//$data['pagebar'] = $pagebar;
		//$data['used'] = $count['used'] ;
		//$data['unused'] = $count['unused'];
		//$data['callback'] = '/tool/myfcode';
		$waitReviewCount = $this->getWaitReviewCount();
		$data['waitReviewCount'] = $waitReviewCount;
		$this->setRenderData($data);
		$this->redirect('myfcode');
	}
    
		function do_apply_fcode(){
		$contact_way = IFilter::act(IReq::get('contact_way','post'));
		$patt = '/^(13|14|15|17|18)[0-9]{9}$/';
		$result = array();
		if(!empty($contact_way)){
			if(preg_match('/^\d+$/',$contact_way)){
				if(!preg_match($patt,$contact_way)){
					$result['status'] = 0;
					$result['message'] = "请输入正确的手机号";
					echo json_encode($result);
					exit;
				}
			}else{
				if(IValidate::email($contact_way)==false){
					$result['status'] = 0;
					$result['message'] = "请输入正确的邮箱";
					echo json_encode($result);
					exit;
				}
			}
		}
		
		$waitReviewCount = $this->getWaitReviewCount();
		
		if($waitReviewCount>0){
			$result['status'] = 0;
			$result['message'] = "您的F码申请正在处理中,请稍候再试";
			echo json_encode($result);
			exit;
		}
		
		$apply_comment = IFilter::act(IReq::get('apply_comment','post'));
		$from_site = ISession::get("from_site");
		$apply_from = empty($from_site)?'unkown' : $from_site;
		$aModel = new IModel('fm_apply');
		$data['apply_meiid'] = $this->user['user_id'];
		$data['apply_comment'] = $apply_comment;
		$data['apply_from'] = $apply_from ;
		$data['apply_time'] = ITime::getDateTime();
		$data['ip'] = IClient::getIp();		
		if(!empty($contact_way)){
			$data['contact_way'] = $contact_way;
		}
		$aModel->setData($data);
		$res = $aModel->add();
		if($res){
			$result['status'] = 1;
			$result['message'] = "申请成功";
			echo json_encode($result);
			exit;
		}else{
			$result['status'] = 0;
			$result['message'] = "申请失败";
			echo json_encode($result);
			exit;
		}
		
	}
	
	
    function myorder(){
    	$this->menu_name = '我的订单-美贝壳';
    	$this->initPayment();
    	$config = new IModel('config');
    	$r = $config->getObj("config_type = '订单配置'");
    	$timeArea = intval($r['field1']*3600);
    	$data['current'] = time();
    	$data['diff'] = $timeArea;
    	$this->setRenderData($data);
    	$this->redirect('myorder');
    }
    
    private function initPayment() {
    	$payment = new IQuery('payment');
    	$payment->fields = 'id,name,type';
    	$payments = $payment->find();
    	$items = array();
    	foreach ($payments as $pay) {
    		$items[$pay['id']]['name'] = $pay['name'];
    		$items[$pay['id']]['type'] = $pay['type'];
    	}
    	$this->payments = $items;
    }
    //我的预约
    function myreser(){
    	$this->menu_name = '我的预约-美贝壳';
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
    	$userObj->where = "a.id=".$user1_id;
    	$user2_id = $userObj->find();
    	$goods["mei2_id"]=$user2_id[0]["meiid"];
    	$appointObj = new IQuery("appointment as a");
    	$appointObj->fields = " a.*,ma.activity_desc,ma.purchase_date";
    	$appointObj->join = "inner join mbk_activity ma on a.purchase_time = ma.activity_code";
    	$appointObj->where = "a.mei_id=".$goods["mei2_id"];
    	$mei_id = $appointObj->find();
    	if(empty($user2_id[0]["mobile"])) {$goods["mei_id"]=$user2_id[0]["email"];} else {$goods["mei_id"]=$user2_id[0]["mobile"];}
    	$goods["ad_img"] = $imgs;
    	$goods["nickname"]=$user2_id[0]["username"];
    	$goods["appoint_code"]=$mei_id[0]["appointment_no"];
    	$goods["createtime"]=$mei_id[0]["create_time"];
    	$goods['order'] = $mei_id;
    	$this->setRenderData($goods);
    	$this->redirect('myreser');
    }
    
    function orderstate(){
    	$this->menu_name = '订单详情-美贝壳';
    	$id = IFilter::act(IReq::get('id'), 'int');
    	
    	//获取订单信息
    	$order = new IModel('order');
    	$order_info = $order->getObj('id = ' . $id . ' and user_id = ' . $this->user['user_id']);
    	
    	if (!$order_info) {
    		IError::show(403, '订单信息不存在');
    	}
    	
    	//查询地区
    	$this->area = area::name($order_info['province'], $order_info['city'], $order_info['area']);
    	$this->order_info = $order_info;
    	
    	//取得支付方式
    	$paymentDB = new IModel('payment');
    	$payRow = $paymentDB->getObj('id = ' . $order_info['pay_type']);
    	if ($payRow) {
    		$this->pay_name = $payRow['name'];
    		$this->pay_note = $payRow['note'];
    	}
    	
    	//物流单号
    	$tb_delivery_doc = new IQuery('delivery_doc as dd');
    	$tb_delivery_doc->join = 'left join freight_company as fc on dd.freight_id = fc.id';
    	$tb_delivery_doc->fields = 'dd.delivery_code,fc.freight_name';
    	$tb_delivery_doc->where = 'order_id = ' . $id;
    	$delivery_info = $tb_delivery_doc->find();
    	if ($delivery_info) {
    		$this->deliveryRow = current($delivery_info);
    	}
    	$this->redirect('orderstate',false);
    }
    
    function account(){
    	$this->menu_name = '账号设置-美贝壳';
    	$query = new IQuery('address');
    	$query->where = 'user_id = ' . $this->user['user_id'];
    	$address = $query->find();
    	$areas = array();
    	
    	if ($address) {
    		foreach ($address as $ad) {
    			$temp = area::name($ad['province'], $ad['city'], $ad['area']);
    			$areas[$ad['province']] = $temp[$ad['province']];
    			$areas[$ad['city']] = $temp[$ad['city']];
    			$areas[$ad['area']] = $temp[$ad['area']];
    		}
    	}
    	
    	$this->areas = $areas;
    	//var_dump($address);
    	$this->address = $address;
    	$this->count = count($address);
    	$this->maxcount = 5;
    	$this->redirect('account');
    }
    
    function address(){
    	$this->menu_name = '收货地址-美贝壳';
    	$this->menu_name = "address";
		if (IWeb::$app->config['tradeModel'] != 1) {
			IError::show(403, "本站已经关闭交易");
            exit;
		}
        //取得自己的地址
        $query = new IQuery('address');
        $query->where = 'user_id = ' . $this->user['user_id'];
        $address = $query->find();
        $areas = array();

        if ($address) {
            foreach ($address as $ad) {
                $temp = area::name($ad['province'], $ad['city'], $ad['area']);
                $areas[$ad['province']] = $temp[$ad['province']];
                $areas[$ad['city']] = $temp[$ad['city']];
                $areas[$ad['area']] = $temp[$ad['area']];
            }
        }

        $this->areas = $areas;
        //var_dump($address);
        $this->address = $address;
        $this->count = count($address);
        $this->maxcount = 5;
        $this->redirect('address');
    }
    public function address_del() {
        $id = IFilter::act(IReq::get('id'), 'int');
        $model = new IModel('address');
        $model->del('id = ' . $id . ' and user_id = ' . $this->user['user_id']);
        $this->redirect('address');
    }
	//修改密码
    function change_password() {
    	$user_id = $this->user['user_id'];
    	$fpassword = IReq::get('fpassword');
    	$password = IReq::get('password');
    	$repassword = IReq::get('repassword');
    	
    	$userObj = new IModel('user');
    	$where = 'id = ' . $user_id;
    	$userRow = $userObj->getObj($where);
    	$message = '';
    	if (!preg_match('|\w{6,32}|', $password)) {
    		$message = '新密码格式不正确，请重新输入'.$password;
    	} else if ($password != $repassword) {
    		$message = '二次密码输入的不一致，请重新输入';
    	} else if (md5($fpassword) != $userRow['password']) {
    		$message = '原始密码输入错误';
    	} else {
    		$passwordMd5 = md5($password);
    		//调用存储过程修改密码
    		$result = Db_Meicloud::modifyUserPwd($passwordMd5);
    		if ($result == '1') {
    			ISafe::set('user_pwd', $passwordMd5);
    			Probability::ajaxmsg("恭喜您修改成功",1);
    		} else {
    			Probability::ajaxmsg("修改失败",0);
    			//Probability::ajaxmsg("{$fpassword}--{$password}---{$repassword}",0);
    		}
    	}
		
    	if(!empty($message)){
    		Probability::ajaxmsg($message,0);
    	}
    }
	
	function modify_username(){
		if(empty($this->user['user_id'])){
			Probability::ajaxmsg("请先登录",0);
		}
		$username = IFilter::act(IReq::get('username','post'));
		$userModel = new IModel('user');
		$data['username'] = $username;
		$userModel->setData($data);
		$r = $userModel->update("id = {$this->user['user_id']}");
		if($r){
			ISafe::set('username',$username);
			Probability::ajaxmsg("修改成功",1);
		}else{
			Probability::ajaxmsg("修改失败,请重新输入",0);
		}
	}

    function personal(){
    	$this->menu_name = '个人中心-美贝壳';
    	$this->redirect('personal');
    }
	
	function myfcode_rule(){
	 	$this->menu_name = 'F码使用规则-美贝壳';
    	$this->redirect('myfcode_rule');
    }
	 
   public function address_edit() {
	    $id = intval(IReq::get('id'));
	    $accept_name = IFilter::act(IReq::get('accept_name'));
	    $province = intval(IReq::get('province'));
	    $city = intval(IReq::get('city'));
	    $area = intval(IReq::get('area'));
	    $address = IFilter::act(IReq::get('address'));
	    $zip = IFilter::act(IReq::get('zip'));
	    $telphone = IFilter::act(IReq::get('telphone'));
	    $mobile = IFilter::act(IReq::get('mobile'));
	    $accept_time = IFilter::act(IReq::get('accept_time'));
	    //$default = IReq::get('default') != 1 ? 0 : 1;
	    $user_id = $this->user['user_id'];
	
	    $model = new IModel('address');
	    $data = array('user_id' => $user_id, 'accept_name' => $accept_name, 'province' => $province, 'city' => $city, 'area' => $area, 'address' => $address, 'zip' => $zip, 'telphone' => $telphone, 'mobile' => $mobile, 'accept_time' => $accept_time);
	
	    //如果设置为首选地址则把其余的都取消首选
	    /* if ($default == 1) {
	      $model->setData(array('default' => 0));
	      $model->update("user_id = " . $this->user['user_id']);
	      } */
	
	    $model->setData($data);
	
	    if ($id == '') {
	        $model->add();
	    } else {
	        $model->update('id = ' . $id);
	    }
	    $this->redirect('address');
	}
	
	public function delFcode(){
		$id = IFilter::act(IReq::get('id'));
		$fModel = new IModel('fm');
		$where = "id = {$id} and owner_meiid = {$this->user['user_id']}";
		$r = $fModel->del($where);
		if($r){
			Util::successJump("删除成功！",'/wapcenter/myfcode/status/2');
		}else{
			Util::successJump("删除成功！",'/wapcenter/myfcode/status/2');
		}
	}
}



?>
