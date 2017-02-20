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

class Wap extends IController {
	
	public $layout = 'wap_layouts';
	
    function init() {
        CheckRights::checkUserRights();
    }

    function index() {  
    	$this->menu_key_name = '美贝壳官网首页';  
        $data['callback'] = '/wap/index';
        $this->setRenderData($data);
        $this->redirect('index');
    }
    
   
    function news(){
    	$this->menu_key_name = '新闻中心-美贝壳';
    	$where = "cat_id = 9999";
    	$data['list'] = $this->getProblemList($where);
    	$data['callback'] = '/wap/news';
    	$this->setRenderData($data);
    	$this->redirect('news');
    }
    
    function news_detail(){
    	$this->menu_key_name = '新闻详情-美贝壳';
    	$id = IFilter::act(IReq::get('id'));
    	$where = "id = {$id}";
    	$data['detail'] = $this->getProblemDetail($where);
    	$data['callback'] = '/wap/news_detail';
    	$this->setRenderData($data);
    	$this->redirect('news_detail');
    }
    
    function customer(){
    	$this->menu_key_name = '客服中心-美贝壳';
    	$data['list'] = $this->getProblemList('cat_id = 9998',2);
    	$data['callback'] = '/wap/customer';
    	$this->setRenderData($data);
    	$this->redirect('customer');
    }
    
    function software(){
    	$this->menu_key_name = '软件中心-美贝壳';
    	$data['callback'] = '/wap/software';
    	$this->setRenderData($data);
    	$this->redirect('software');
    }
    
	function advert_video(){
	 	$this->menu_name = '首页云棒广告-美贝壳';
		$this->layout = '';
    	$this->redirect('advert_video');
    }
	
	function vip_buy(){
	 	$this->menu_name = '预约抢购-美贝壳';
		$this->layout = '';
		$data['callback'] = '/wap/vip_buy';
		$data['end_time'] = strtotime('2016-09-03 11:00:00');
		$data['current_time'] = time();
		$this->setRenderData($data);
    	$this->redirect('vip_buy');
    }
	function vip_buy_ok(){
	 	$this->menu_name = '抢购结束-美贝壳';
		$this->layout = '';
    	$this->redirect('vip_buy_ok');
    }
	
	
	function index_video(){
	 	$this->menu_name = '首页云棒广告-美贝壳';
    	$this->redirect('index_video');
    }
    function customer_more(){
    	$this->menu_key_name = '更多常见问题-美贝壳';
    	$where = "cat_id = 9998";
    	$data['list'] = $this->getProblemList($where);
    	$data['callback'] = '/wap/customer_more';
    	$this->setRenderData($data);
    	$this->redirect('customer_more');
    }
    
    function customer_content(){
    	$this->menu_key_name = '常见问题详情-美贝壳';
    	$id = IFilter::act(IReq::get('id'));
    	$data['detail'] = $this->getProblemDetail('id = '.$id);
    	$data['callback'] = '/wap/product_content';
    	$this->setRenderData($data);
    	$this->redirect('customer_content');
    }
    
    function product_yin(){
    	$this->menu_key_name = '银组合-美贝壳';
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
    	
    	$goods_info["callback"]='/wap/product_yin/id/'. $goods_id;
    	$this->setRenderData($goods_info);
    	$this->redirect('product_yin');
    }
    
    function product_collo(){
    	$this->menu_key_name = '金甲-银霸组合-美贝壳';
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
    	
    	$goods_info["callback"]='/wap/product_collo/id/'. $goods_id;
    	$this->setRenderData($goods_info);
    	$this->redirect('product_collo');
    }
    
    function getProduct() {    
    	$goods_id = IFilter::act(IReq::get('goods_id'), 'int'); 
    	//获取货品数据
    	$tb_products = new IModel('products');
    	$procducts_info = $tb_products->getObj("goods_id = " . $goods_id);
    
    	//匹配到货品数据
    	if (!$procducts_info) {
    		echo JSON::encode(array('flag' => 'fail', 'message' => '没有找到相关货品'));
    		exit;
    	}
    
    	echo JSON::encode(array('flag' => 'success', 'data' => $procducts_info));
    }
    
    function cart2(){
    	$this->menu_name = "购买页面-美贝壳";
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
    			$this->redirect('/wap/login?tourist&callback=/wap/cart2');
    		} else {
    			$url = '/wap/login?tourist&callback=/wap/cart2/id/' . $id . '/type/' . $type . '/num/' . $buy_num;
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
    	
    	//返回值
    	$this->final_sum = sprintf('%.2f',$result['final_sum']);
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
    	$data['callback'] = '/wap/index';
    	$this->setRenderData($data);
    	$this->redirect('cart2');
    }
    
    public function cart3(){
    	$this->menu_name = "下单页面-美贝壳";
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
    	$this->order_name = $goodsResult['productList'][0]['name'];
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
    
    function product_diy(){
    	$this->menu_key_name = 'DIY-美贝壳';
    	$data['callback'] = '/wap/product_diy';
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
    	$this->setRenderData($data);
    	$this->redirect('product_diy');
    }
        
    public function cart_diy_2(){
    	$this->menu_name = "DIY购买-美贝壳";
    	$id3 = IFilter::act(IReq::get('id3'), 'int');
    	$id7 = IFilter::act(IReq::get('id7'), 'int');
    	$id4 = IFilter::act(IReq::get('id4'), 'int');
    	$buy_num3 = IReq::get('num3') ? IFilter::act(IReq::get('num3'), 'int') : 1;
    	$buy_num7 = IReq::get('num7') ? IFilter::act(IReq::get('num7'), 'int') : 1;
    	$buy_num4 = IReq::get('num4') ? IFilter::act(IReq::get('num4'), 'int') : 1;
    	$type = IFilter::act(IReq::get('type'));
    	//必须为登录用户
    	if (empty($this->user['user_id'])) {
    		$this->redirect('/wap/login?callback=/wap/cart_diy_2');
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
    	$data['callback'] = '/wap/index';
    	$this->setRenderData($data);
    	$this->redirect('cart_diy_2');
    }
    
    public function cart_diy_3(){
    	$this->menu_name = "支付-美贝壳";
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
    	$this->order_name = '';
    	if(!empty($goodsResult3['productList'])){
    		$this->order_name .= ' '.$goodsResult3['productList'][0]['name'];
    	}
       	if(!empty($goodsResult7['productList'])){
    		$this->order_name .= ' '.$goodsResult7['productList'][0]['name'];
    	}
    	if(!empty($goodsResult4['productList'])){
    		$this->order_name .= ' '.$goodsResult4['productList'][0]['name'];
    	}
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
    
    
    
    private function getProblemList($where,$limit=''){
    	$helpModel = new IModel('help');
    	$data = $helpModel->query($where,'id,name,dateline',false,'',$limit);
    	return $data;
    }
    
    private function getProblemDetail($where){
    	$helpModel = new IModel('help');
    	$data = $helpModel->getObj($where,'id,name,content,dateline');
    	return $data;
    }
    
    public function login(){
    	$this->menu_key_name = '登录-美贝壳';
    	$this->redirect('login');
    }
    
    public function login_act() {
    	$login_info = IFilter::act(IReq::get('login_info', 'post'));
    	$password = IReq::get('password', 'post');
    	$callback = IFilter::act(IReq::get('callback'), 'text');
    	$orderCallback = IFilter::act(IReq::get('orderCallback'),'text');
    	$autoLogin = IFilter::act(IReq::get('autoLogin', 'post'));
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
    				$this->redirect('/wap/index');
    			}
    		} else {
    			$message = '用户名和密码不匹配';
    		}
    	}
    
    	//错误信息		-- 新版本不会走到这里 kevin
    	if ($message != '') {
    		$this->message = $message;
    		$_GET['callback'] = $callback;
    		$this->redirect('login',false);
    	}
    }
    
    function loginAfter($userRow) {
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
    
    public  function logout() {
    	ISafe::clearAll();
    	$callback = IFilter::act(IReq::get('callback','get'));
    	if(empty($callback)){
    		$this->redirect('/wap/index');
    	}else{
    		$this->redirect("{$callback}");
    	}
    }
    
    public function mail_reg(){
    	$this->menu_key_name = '邮箱注册-美贝壳';
    	$this->redirect('mail_reg');
    }
    
    public function phone_reg(){
    	$this->menu_key_name = '手机注册-美贝壳';
    	$this->redirect('phone_reg');
    }
    
    public function success_reg(){
    	$callback = IFilter::act(IReq::get('callback'));
    	$type = IFilter::act(IReq::get('type'));
    	$email = IFilter::act(IReq::get('email'));
    	$this->menu_key_name = '注册成功-美贝壳';
    	$data['callback'] = $callback;
    	$data['type'] = $type;
    	$data['email'] = $email;
    	$data['toemail'] = 'http://mail.'.str_replace('@','',strchr($email,'@'));
    	$this->setRenderData($data);
    	$this->redirect('success_reg');
    }
    
    public function verify_telphone(){
    	$telphone = IFilter::act(IReq::get('mobile', 'post'));
    	$pattern = '/^(13|14|15|17|18)[0-9]{9}$/';
    	$message = "";
    	if (!preg_match($pattern,$telphone)) {
			Probability::ajaxmsg('手机号格式不正确',0);
    	}else{
    		if (Db_Meicloud::checkUserExist($telphone, 1)) {
    			$message = '此手机已经被注册过，请重新更换。';
    		} else {
    			$userObj = new IModel('user');
    			$where = 'mobile = "' . $telphone . '"';
    			$userRow = $userObj->getObj($where);
    			if (!empty($userRow)) {
    				$message = '此手机已经被注册过，请重新更换';
    			}
    		}
    		if($message != ''){
    			Probability::ajaxmsg($message,0);
    		}else{
    			$vcode = rand(100000, 999999);
    			//$flag = Db_Meicloud::sendMsg('注册美贝壳官网验证码：' . $vcode . '，该验证码十分钟内有效。', "$telphone");
    			$flag = true;
    			if($flag == true){
    				$data['mobile'] = $telphone;
    				$data['vcode'] = $vcode;
    				$data['create_time'] = ITime::getDateTime() ;
    				$res = $this->save_vcode($data);
    				Probability::ajaxmsg("短信发送成功",1);
    			}else{
    				Probability::ajaxmsg("短信发送失败",0);
    			}
    		}
    	}
    }
    
    public function verify_telphone_reg(){
    	$vcode = IFilter::act(IReq::get('vcode', 'post'));
    	$mobile = IFilter::act(IReq::get('mobile', 'post'));
    	$password = IFilter::act(IReq::get('password', 'post'));
    	$repassword = IFilter::act(IReq::get('repassword', 'post'));
    	$message = $this->verify_vcode($mobile,$vcode);
    	/* 短信注册校验 */
    	if (IValidate::mobi($mobile) == false) {
    		$message = '手机号码格式不正确';
    	} else if (!preg_match('|\S{6,12}|', $password)) {
    		$message = '密码必须是字母，数字，下划线组成的6-12个字符';
    	} else if ($password != $repassword) {
    		$message = '2次密码输入不一致';
    	}else if(($info = $this->verify_hkyz())!=''){
    		$message = $info;
    	}else {
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
   		
   		if($message == ''){
   			Probability::ajaxmsg("验证成功",1);
   		}else{
   			Probability::ajaxmsg($message,0);
   		}
    }
    
    //用户注册
    function reg_act() {
    	$email = IFilter::act(IReq::get('email', 'post'));
    	$mobile = IFilter::act(IReq::get('mobile', 'post'));
    	$password = IFilter::act(IReq::get('password', 'post'));
    	$repassword = IFilter::act(IReq::get('repassword', 'post'));
    	$username = '贝贝';//手机端注册昵称默认为贝贝
    	$vcode = IFilter::act(IReq::get('vcode', 'post'));
    	$callback = IFilter::act(IReq::get('callback'), 'text');
    	$type = IFilter::act(IReq::get('type'), 'text'); //$type=1邮箱 2短信
    	$message = '';
    	if ($type == 1) {
    		/* 邮箱注册信息校验 */
    		if (IValidate::email($email) == false) {
    			$message = '邮箱格式不正确';
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
    		$message = $this->verify_vcode($mobile,$vcode);
    		/* 短信注册校验 */
    		if (IValidate::mobi($mobile) == false) {
    			$message = '手机号码格式不正确';
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
    		if ($type == 1) {
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
    			$callback = $callback ? urlencode($callback) : '';
    			$callback = $callback ? urlencode($callback) : '';
    			if($type == '1'){
    				$this->redirect('/wap/success_reg?callback=' . $callback.'&type='.$type.'&email='.$email);
    			}else if($type == '2'){
    				$this->redirect('/wap/success_reg?callback=' . $callback.'&type='.$type);
    			}
    		} else {
    			$message = '注册失败';
    		}
    	}
    
    	//出错信息展示
    	if ($message != '') {
    		$this->email = $email;
    		if($type == '1'){
    			$this->redirect('mail_reg', false);
    		}else if($type == '2'){
    			$this->redirect('phone_reg', false);
    		}
    		
    		Util::showMessage($message);
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
    	$res = $db->query("select vcode from  mbk_sms_vcode where mobile={$mobile} order by id desc limit 1");
    	if(is_array($res) && !empty($res[0]['vcode'])){
    		return $res[0]['vcode'];
    	}else{
    		return false ;
    	}
    }
    
    private function verify_vcode($mobile,$vcode){
    	$message = "";
    	$r = $this->get_last_vcode($mobile);
    	if($r !== false){
    		if($r != $vcode){
    			$message = "请输入正确的验证码";
    		}
    	}else{
    		$message = "请输入正确的验证码";
    	}
    	
    	return $message;
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
    
    
    function verify_email_captcha() {
    	$email = IFilter::act(IReq::get('email', 'post'));   
    	$password = IFilter::act(IReq::get('password', 'post'));
    	$repassword = IFilter::act(IReq::get('repassword', 'post'));
    	$message = "";
    	if (IValidate::email($email) == false) {
    		$message = '邮箱格式不正确';
    	} else if (!preg_match('|\S{6,12}|', $password)) {
    		$message = '密码必须是字母，数字，下划线组成的6-12个字符';
    	} else if ($password != $repassword) {
    		$message = '2次密码输入不一致';
    	} else if(($info = $this->verify_hkyz())!=''){
    		$message = $info;
    	}
    
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
    		Probability::ajaxmsg($message,0);
    	} else {
    		Probability::ajaxmsg($message,1);
    	}
    
    	echo json_encode($result);
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
    	$url = IUrl::creatUrl("/wap/reg_email_active/hash/{$hash}");
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
			Util::showMessage("参数不完整");
    	}
    	$hash = IFilter::act($hash, 'string');
    	$tb = new IModel("email_active");
    	$addtime = time() - 3600 * 72;
    	$row = $tb->getObj(" hash ='$hash' AND addtime>$addtime ");
    	if (!$row) {
    		Util::showMessage("邮箱链接已过期，请重新使用邮箱注册");
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
    
    	$this->redirect("/wap/success_reg");
    }
    
    function address_add2() {
    	$accept_name = IFilter::act(IReq::get('accept_name'));
    	$province = IFilter::act(IReq::get('province'), 'int');
    	$city = IFilter::act(IReq::get('city'), 'int');
    	$area = IFilter::act(IReq::get('area'), 'int');
    	$address = IFilter::act(IReq::get('address'));
    	$mobile = IFilter::act(IReq::get('mobile'));
    	$user_id = $this->user['user_id'];
    	$model = new IModel('address');
    	$address_exists = $model->getObj("user_id = {$user_id}");

    	$addressRow = $model->getObj('user_id = ' . $user_id . ' and accept_name = "' . $accept_name . '" and area = ' . $area . ' and address = "' . $address . '"');
    
    	if ($addressRow) {
    		$isError = true;
    		$message = '请不要重复添加同一个收货地址';
    		$data = '';
    	} else {
    		//获取地区text
    		$areaList = area::name($province, $city, $area);
    
    		//执行insert
    		if(empty($address_exists)){
    			$data = array('user_id' => $user_id,'default' => 1 , 'accept_name' => $accept_name, 'province' => $province, 'city' => $city, 'area' => $area, 'address' => $address, 'mobile' => $mobile,);
    		}else{
    			$data = array('user_id' => $user_id, 'accept_name' => $accept_name, 'province' => $province, 'city' => $city, 'area' => $area, 'address' => $address, 'mobile' => $mobile,);
    		}
    		
    		$model->setData($data);
    		$model->add();
    
    		$isError = false;
    		$message = '添加成功';
    
    		$data['province_val'] = $areaList[$province];
    		$data['city_val'] = $areaList[$city];
    		$data['area_val'] = $areaList[$area];
    	}
    	$result = array('isError' => $isError, 'message' => $message, 'data' => $data);
    	echo JSON::encode($result);
    }
    
    public function doPay() {
    	//获得相关参数
    	$order_id = IFilter::act(IReq::get('order_id'), 'int');
    	$recharge = IReq::get('recharge');
    	$payment_id = IFilter::act(IReq::get('payment_id'), 'int');
    
    	if ($order_id) {
    		//获取订单信息
    		$orderDB = new IModel('order');
    		$orderRow = $orderDB->getObj('id = ' . $order_id);
    
    		if (empty($orderRow)) {
    			IError::show(403, '要支付的订单信息不存在');
    		}
    		//防止重复支付
    		if (($orderRow["pay_status"] != 0) || ($orderRow["status"] != 1)) {
    			IError::show(403, '请勿重复支付订单，如果订单支付状态无法确认，请联系客服。' . $orderRow["pay_status"] . $orderRow["status"]);
    		}
    
    		if ($payment_id) {
    			//如果传入新支付方式，按照新支付方式支付
    			$orderDB->setData(array("pay_type" => $payment_id));
    			$orderDB->update("id=" . $order_id);
    		} else {
    			$payment_id = $orderRow['pay_type'];
    		}
    
    		//$orderDB->setData(array("pay_status" => -1));
    		//$orderDB->update("id=" . $order_id);
    	}
    
    	//获取支付方式类库
    	$paymentInstance = Payment::createPaymentInstance($payment_id);
    
    	//在线充值
    	if ($recharge !== null) {
    		$recharge = IFilter::act($recharge, 'float');
    		$paymentRow = Payment::getPaymentById($payment_id);
    
    		//account:充值金额; paymentName:支付方式名字
    		$reData = array('account' => $recharge, 'paymentName' => $paymentRow['name']);
    		$sendData = $paymentInstance->getSendData(Payment::getPaymentInfo($payment_id, 'recharge', $reData));
    	}
    	//订单支付
    	else if ($order_id != 0) {
    		$sendData = $paymentInstance->getSendData(Payment::getPaymentInfo($payment_id, 'order', $order_id));
    	} else {
    		IError::show(403, '发生支付错误');
    	}
    
    	$paymentInstance->doPay($sendData);
    	$this->display(2);
    }  
        
    public function error(){
    	$this->menu_name = '错误页面-美贝壳';
    	$this->redirect('error');
    }
    
    public function yuyue(){
    	$this->menu_name = '预约-美贝壳';
		$this->layout = '';
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
    	$goods['callback'] = '/wap/yuyue';
    	$this->setRenderData($goods);
    	$this->redirect('yuyue');
    }
    
	function yuyue_first(){
	 	$this->menu_name = '云棒介绍-美贝壳';
		$this->layout = '';
    	$this->redirect('yuyue_first');
    }

    public function overview(){
    	$this->menu_name = '云棒详情-美贝壳';
    	$data['callback'] = '/wap/overview';
    	$this->setRenderData($data);
    	$this->redirect('overview');
    }
    
    public function yuyue_success(){
    	$this->menu_name = '预约成功-美贝壳';
		$this->layout = '';
    	$data['callback'] = '/wap/yuyue_success';
    	$this->setRenderData($data);
    	$this->redirect('yuyue_success');
    }
    
    //操作订单状态
    public function order_status() {
    	$op = IReq::get('op');
    	$id = IFilter::act(IReq::get('order_id'), 'int');
    	$model = new IModel('order');
    
    	switch ($op) {
    		//取消
    		case "cancel": {
    			$model->setData(array('status' => 3, "cancel_time" => date('Y-m-d h:i:s')));
    			$res = $model->update("id = " . $id . " and user_id = " . $this->user['user_id'] . " and status = 1 ");
    		}
    		break;
    		//确认收货
    		case "confirm": {
    			$model->setData(array('status' => 5, 'completion_time' => date('Y-m-d h:i:s')));
    			$res = $model->update("id = " . $id . " and user_id = " . $this->user['user_id']);
    		}
    		break;
    		//确认服务订单收货
    		case "service": {
    			$model = new IModel('order_service');
    			$model->setData(array('status' => 7, 'complete_time' => date('Y-m-d h:i:s')));
    			$res = $model->update("order_id = " . $id . " and user_id = " . $this->user['user_id']);
    		}
    		break;
    	}
    	if ($res) {
    		Util::successJump("操作成功！",'/wapcenter/myorder');
    	} else {
    		Util::successJump("操作失败！",'/wapcenter/myorder');
    	}
    }
    
    public function delete_order(){
    	$id = IFilter::act(IReq::get('order_id'), 'int');
    	$where = "id = {$id} and user_id = {$this->user['user_id']} and status = 3";
    	$orderGoodsModel = new IModel('order_goods');
    	$m = $orderGoodsModel->del("order_id = (select id from `mbk_order` where {$where})");
    	$orderModel = new IModel('order');
    	$r = $orderModel->del($where);
    	if($r){
    		Util::successJump("删除成功！",'/wapcenter/myorder');
    	}else{
    		Util::successJump("删除失败！",'/wapcenter/myorder');
    	}
    }
    
    
    public function cart3_result() {
    	//防止页面刷新
    	header("Cache-Control: no-store, no-cache, must-revalidate");
    	header("Cache-Control: post-check=0, pre-check=0", false);
    	$this->menu_name = '支付结果-美贝壳';
    	$order_no = IReq::get('order_no');
    	$user_id = ($this->user['user_id'] == null) ? 0 : $this->user['user_id'];
    	
    	$Order = new IModel("order");
    	$order_info = $Order->query("order_no = '$order_no'");
    	$order_info = $order_info[0];
    	$order_id = $order_info["id"];
  		$data['order_id'] = $order_id;
  		$data['direct_pay'] = "/block/dopay/order_id/".$order_id;
    	$this->setRenderData($data);
    	$this->redirect("cart3_result");
    }
}

?>
