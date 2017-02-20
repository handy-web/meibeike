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

class Tool extends IController {

    public $layout = 'site_new';

    function test(){
    	$wxObj = new WeiXin();
    	if(!isset($_COOKIE['mpaccesstoken'])){
    		$token = $wxObj->getMpAccessToken();
    		if(is_array($token) && isset($token['access_token'])){
    			setCookie('mpaccesstoken',$token['access_token'],time()+6000,'/');
    		}
    	}
    }
    
    function test1(){
    	$parms['access_token'] = $_COOKIE['mpaccesstoken'];
    	$wxObj = new WeiXin();
    	if(!isset($_COOKIE['jsapi_tiket'])){
    		$jsapi_tiket = $wxObj->getJsapiTicket($parms);
    		if($jsapi_tiket['errcode'] == '0' && isset($jsapi_tiket['ticket'])){
    			setCookie('jsapi_tiket',$jsapi_tiket['tiket'],time()+6000,'/');
    		}
    	}
    }
    
    function test2(){
    	$file = $_SERVER['DOCUMENT_ROOT'].'/config/config.php';
    	$wx_ini = require($file);
    	$wx_config = $wx_ini['wx_ini'];
    	$url = IFilter::act(IReq::get('url','post'));
    	$wxObj = new WeiXin();
    	$parms['jsapi_tiket'] = $_COOKIE['jsapi_tiket'];
    	$parms['timestamp'] = time();
    	$parms['noncestr'] = rand(100000,999999);
    	$parms['url'] = $url;
    	$signature = $wxObj->createSign($parms);
    	if($signature != false){
    		$data['status'] = 1;
    		$data['appId'] = $wx_config['APPID'];
    		$data['timestamp'] = time();
    		$data['noncestr'] = $parms['noncestr'];
    		$data['signature'] = $signature;
    		Probability::ajaxmsg($data);
    	}else{
    		Probability::ajaxmsg("签名失败",0);
    	}
    }
    
    function init() {
        CheckRights::checkUserRights();
        Reserve::getFromSite();
    }

	function advert_video(){
		$this->redirect('advert_video');
	}
	
	function detail_video(){
		$this->redirect('detail_video');
		
	}
	
	function account(){
		if(empty($this->user)){
			$this->redirect('/simple/login');
		}
		$this->menu_name = 'account';
		//$data['callback'] = "/tool/account";
		//$this->setRenderData($data);
		$this->redirect('account');
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
	
	function customer(){
		$this->menu_name = 'customer';
		$data['callback'] = "/tool/customer";
		$this->setRenderData($data);
		$this->redirect('customer');
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
		if(empty($this->user)){
			$this->redirect('/simple/login');
		}
		$page = IReq::get('page');
		if (! $page) {
			$page = 1;
		}
		$count = $this->getFcount();
		$fQuery = new IQuery('fm f');        
		$fQuery->page = $page;
		$fQuery->pagesize = 20;
		$fQuery->pagelength = $count['total'];
		$fQuery->join = "inner join mbk_fm_activity fa on f.aid = fa.id";
		$fQuery->fields = 'f.id,f.f_code,fa.start_time,fa.end_time,fa.activity_name,f.use_time,f.is_shared';
		$fQuery->where = "owner_meiid = {$this->user['user_id']}";
		$data['flist'] = $fQuery->find();
		$pagebar = $fQuery->getPageBar();
		$data['pagebar'] = $pagebar;
		$data['used'] = $count['used'] ;
		$data['unused'] = $count['unused'];
		//$data['callback'] = '/tool/myfcode';
		$waitReviewCount = $this->getWaitReviewCount();
		$data['waitReviewCount'] = $waitReviewCount;
		$this->setRenderData($data);
		$this->redirect('myfcode');
	}
	
	function suggestion(){
		$content = IFilter::act(IReq::get('content','post'));
		$suggestion = IFilter::act(IReq::get('suggestion_type','post'));
		$contact_way = IFilter::act(IReq::get('contact_way','post'));
		if(empty($this->user['user_id'])){
			$result['status'] = 2;
			$result['message'] = "请先登录";
			echo json_encode($result);
			exit;
		}
		$data['user_id'] = $this->user['user_id'];
		$aModel = new IModel('suggestion');		
		$data['content'] = $content;
		if(!empty($contact_way)){
			$data['contact_way'] = $contact_way;
		}
		$data['suggestion_type'] = $suggestion;
		$data['time'] = ITime::getDateTime();
		$data['ip'] = IClient::getIp();
		$aModel->setData($data);
		$r = $aModel->add();
		if($r){
			$result['status'] = 1;
			$result['message'] = "感谢您的反馈意见，我们会尽快处理";
			echo json_encode($result);
			exit;
		}else{
			$result['status'] = 0;
			$result['message'] = "您的反馈意见提交失败,请重新提交";
			echo json_encode($result);
			exit;
		}
	}
	
	public function order() {
		if(empty($this->user)){
			$this->redirect('/simple/login');
		}
		$this->menu_name = "order";
		if (IWeb::$app->config['tradeModel'] != 1) {
			IError::show(403, "本站已经关闭交易");
			exit;
		}
		$this->initPayment();
		$this->redirect('order');
	}
	
	/**
	 * @brief 初始化支付方式
	 */
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

	
	public function diy(){
		//获取商品信息
		$tb_goods = new IModel('goods');
		$goods_info_4 = $tb_goods->getObj("id = 4 AND is_del=0");
		$goods_info_7 = $tb_goods->getObj("id = 7 AND is_del=0");
		$goods_info_3 = $tb_goods->getObj("id = 3 AND is_del=0");
		$data['goods_info_7'] = $goods_info_7;
		$data['goods_info_4'] = $goods_info_4;
		$data['goods_info_3'] = $goods_info_3;
		//获取产品列表
		$productObj = new IModel('products');
		$productsList3 = $productObj->query('goods_id = 3', 'sell_price,id,spec_array,goods_id');
		$productsList7 = $productObj->query('goods_id = 7', 'sell_price,id,spec_array,goods_id');
		$productsList4 = $productObj->query('goods_id = 4', 'sell_price,id,spec_array,goods_id');
		$data['productsList3'] = $productsList3;
		$data['productsList7'] = $productsList7;
		$data['productsList4'] = $productsList4;
		$data["callback"]='/tool/diy';
		$this->setRenderData($data);
		$this->redirect('diy');
	}
	
	
	public function cart_diy_2(){
		$this->menu_name = "cart_diy_2";
		$id3 = IFilter::act(IReq::get('id3'), 'int');
		$id7 = IFilter::act(IReq::get('id7'), 'int');
		$id4 = IFilter::act(IReq::get('id4'), 'int');
		$buy_num3 = IReq::get('num3') ? IFilter::act(IReq::get('num3'), 'int') : 1;
		$buy_num7 = IReq::get('num7') ? IFilter::act(IReq::get('num7'), 'int') : 1;
		$buy_num4 = IReq::get('num4') ? IFilter::act(IReq::get('num4'), 'int') : 1;
		$type = IFilter::act(IReq::get('type'));		
		//必须为登录用户
		if (empty($this->user['user_id'])) {
			$this->redirect('/simple/login?callback=/tool/cart_diy_2');
		}
		if ($id3 == 0 && $id7 == 0 && $id4 == 0) {
			IError::show(403, '请至少选择一种商品');
			exit;
		}
		$user_id = $this->user['user_id'];
		
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
		$result3 = array();
		$result7 = array();
		$result4 = array();
		//判断是特定活动还是购物车
		if($type != ''){
			if($id3 != 0){
				$result3 = $countSumObj->direct_count($id3, $type, $buy_num3, "", "");
				$this->gid3 = $id3;
				$this->num3 = $buy_num3;
			}
			
			if($id7 != 0){
				$result7 = $countSumObj->direct_count($id7, $type, $buy_num7, "", "");
				$this->gid7 = $id7;
				$this->num7 = $buy_num7;
			}
			
			if($id4 != 0){
				$result4 = $countSumObj->direct_count($id4, $type, $buy_num4, "", "");
				$this->gid4 = $id4;
				$this->num4 = $buy_num4;
			}
		}

		$this->final_sum = $result3['final_sum'] + $result7['final_sum'] + $result4['final_sum'];
		$this->sum = $result3['sum'] + $result7['sum'] + $result4['sum'];
		$this->productList3 = $result3['productList'];
		$this->productList7 = $result7['productList'];
		$this->productList4 = $result4['productList'];
		$this->type = $type;
		//收货地址列表
		$this->addressList = $addressList;
		
		//获取商品税金
		$this->goodsTax = $countSumObj->getGoodsTax($this->sum);
		$data['callback'] = '/site/sy';
		$this->setRenderData($data);
		$this->redirect('cart_diy_2');
	}
	
	public function cart_diy_3(){
		$this->menu_name = "cart_diy_3";
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
		$gid3 = IFilter::act(IReq::get('direct_gid3'), 'int');
		$gid7 = IFilter::act(IReq::get('direct_gid7'), 'int');
		$gid4 = IFilter::act(IReq::get('direct_gid4'), 'int');
		$num3 = IFilter::act(IReq::get('direct_num3'), 'int');
		$num7 = IFilter::act(IReq::get('direct_num7'), 'int');
		$num4 = IFilter::act(IReq::get('direct_num4'), 'int');
		$type = IFilter::act(IReq::get('direct_type')); //商品或者货品
		$promo = IFilter::act(IReq::get('direct_promo'));
		$active_id = IFilter::act(IReq::get('direct_active_id'), 'int');
		$fcode = IFilter::act(IReq::get('fcode'));
		$order_no = Order_Class::createOrderNum();
		$order_type = 0;
		//是否开发票
		$is_note = IFilter::act(IReq::get('is_note'),'int');
		
		$dataArray = array();
		if(empty($gid3) && empty($gid7) && empty($gid4)){
			IError::show(403, '请至少选择一种商品');
			exit;
		}
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
		$goodsResult3 = array();
		$goodsResult7 = array();
		$goodsResult4 = array();
		if($type != ''){
			if($gid3 != 0){
				$goodsResult3 = $countSumObj->direct_count($gid3, $type, $num3,"","");
				if (empty($goodsResult3['goodsList']) && empty($goodsResult3['productList'])) {
					IError::show(403, '母棒相关数据不存在');
					exit;
				}
			}
			
			if($gid7 != 0){
				$goodsResult7 = $countSumObj->direct_count($gid7, $type, $num7, "", "");
				if (empty($goodsResult7['goodsList']) && empty($goodsResult7['productList'])) {
					IError::show(403, '存储碟相关数据不存在');
					exit;
				}
			}
			
			if($gid4 != 0){
				$goodsResult4 = $countSumObj->direct_count($gid4, $type, $num4,"", "");
				if (empty($goodsResult4['goodsList']) && empty($goodsResult4['productList'])) {
					IError::show(403, '存储棒相关数据不存在');
					exit;
				}
			}
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
		$total_price = $goodsResult3['final_sum'] + $goodsResult7['final_sum'] + $goodsResult4['final_sum'];
		$total_sum = $goodsResult3['sum'] + $goodsResult7['sum'] + $goodsResult4['sum'];
		$total_weight = $goodsResult3['weight'] + $goodsResult7['weight'] + $goodsResult4['weight'];
		$total_freeFreight = $goodsResult3['freeFreight'] + $goodsResult7['freeFreight'] + $goodsResult4['freeFreight'];
		//F码有效并且为代金券时订单减去代金券金额
		if(!empty($fcode)){
			$callRes = $this->checkFcode($fcode);
		}
		if(is_string($callRes)){
			IError::show(403, $callRes);
			exit;
		}else if(is_array($callRes)){
			if($callRes['prize_type'] == '0'){
				$total_price = $total_price - intval($callRes['prize_limit']);
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
		$orderData = $countSumObj->countOrderFee($total_sum, $total_price, $total_weight, $province, $delivery_id, $payment, $total_freeFreight, $insured);
		
		
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
				'exp' => $goodsResult3['exp'] + $goodsResult7['exp']+ $goodsResult4['exp'],
				'point' => $goodsResult3['point'] + $goodsResult7['point'] + $goodsResult4['point'],
				'type' => $order_type,
				//红包道具
				'prop' => isset($dataArray['prop']) ? $dataArray['prop'] : null,
				//商品价格
				'payable_amount' => $total_sum,
				'real_amount' => $total_price,
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
		'promotions' => $goodsResult3['proReduce'] + $goodsResult7['proReduce'] + $goodsResult4['proReduce'] + $goodsResult3['reduce'] + $goodsResult7['reduce'] + $goodsResult4['reduce'] + (isset($ticketRow['value']) ? $ticketRow['value'] : 0),
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
		
		if(!empty($goodsResult3['productList'][0])) {
			$goodsList['productList'][] = $goodsResult3['productList'][0];
		}
		
		if(!empty($goodsResult7['productList'][0])) {
			$goodsList['productList'][] = $goodsResult7['productList'][0];
		}
		
		if(!empty($goodsResult4['productList'][0])){
			$goodsList['productList'][] = $goodsResult4['productList'][0];
		}
				
		$orderInstance->insertOrderGoods($this->order_id, $goodsList);
		
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
			$this->redirect('cart_diy_3');
		}
	}
	
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
	
	public function mark(){
		$fcode = IFilter::act(IReq::get('fcode'), 'post');
		$user_id = $this->user['user_id'];
		$userModel = new IModel('fm');
		$data['is_shared'] = 1;
		$userModel->setData($data);
		$r = $userModel->update("owner_meiid = {$user_id} and f_code = '{$fcode}'");
		if($r){
			Probability::ajaxmsg("标记成功",1);
		}else{
			Probability::ajaxmsg("标记失败",0);
		}
	}
	
}
