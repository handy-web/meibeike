<?php

/**
 * @brief 公共模块
 * @class Block
 */
class Block extends IController {

    public $layout = '';

    public function init() {
        CheckRights::checkUserRights();
    }

    /**
     * @brief Ajax获取规格值
     */
    function spec_value_list() {
        // 获取POST数据
        $spec_id = IFilter::act(IReq::get('id'), 'int');

        //初始化spec商品模型规格表类对象
        $specObj = new IModel('spec');
        //根据规格编号 获取规格详细信息
        $specData = $specObj->getObj("id = $spec_id");
        if ($specData) 
	{
            echo JSON::encode($specData);
	} 
	else 
	{
            //返回失败标志
            echo '';
        }
    }

    //列出筛选商品
    function goods_list() 
    {
        //商品检索条件
        $show_num = IFilter::act(IReq::get('show_num'), 'int');
        $keywords = IFilter::act(IReq::get('keywords'));
        $cat_id = IFilter::act(IReq::get('category_id'), 'int');
        $min_price = IFilter::act(IReq::get('min_price'), 'float');
        $max_price = IFilter::act(IReq::get('max_price'), 'float');
        $goods_no = IFilter::act(IReq::get('goods_no'));
        $is_products = IFilter::act(IReq::get('is_products'), 'int');
        $seller_id = IFilter::act(IReq::get('seller_id'), 'int');

        //查询条件
        $where = 'go.is_del = 0';
        if ($seller_id) {
            $where .= ' and seller_id = ' . $seller_id;
        }
        $table_name = 'goods as go';
        $fields = 'go.id as goods_id,go.name,go.img,go.store_nums';

        //分类筛选
        if ($cat_id) {
            $table_name .= ' ,category_extend as ca ';
            $where .= " and ca.category_id = {$cat_id} and go.id = ca.goods_id ";
        }

        //货品存在
        if ($is_products) {
            $fields .= ' ,pro.id as product_id,pro.products_no as goods_no,pro.spec_array,pro.sell_price';
            $table_name .= ' ,products as pro ';
            $where .= ' and pro.goods_id = go.id and (go.spec_array != "" or go.spec_array is not null) ';

            $where .= $goods_no ? ' and pro.products_no  = "' . $goods_no . '"' : '';
            $where .= $min_price ? ' and pro.sell_price  >= ' . $min_price : '';
            $where .= $max_price ? ' and pro.sell_price  <= ' . $max_price : '';
        } else {
            $fields .= ' ,go.goods_no,go.sell_price';
            $where .= ' and (go.spec_array = "" or go.spec_array is null) ';
            $where .= $goods_no ? ' and go.goods_no     = "' . $goods_no . '"' : '';
            $where .= $min_price ? ' and go.sell_price  >= ' . $min_price : '';
            $where .= $max_price ? ' and go.sell_price  <= ' . $max_price : '';
        }
        $where.= $keywords ? ' and go.name like "%' . $keywords . '%"' : '';

        $goodsDB = new IModel($table_name);
        $this->data = $goodsDB->query($where, $fields, 'go.id', 'desc', $show_num);
        $this->type = IFilter::act(IReq::get('type')); //页面input的type类型，比如radio，checkbox
        $this->redirect('goods_list');
    }

    /**
     * @brief 获取地区
     */
    public function area_child() {
        $parent_id = intval(IReq::get("aid"));
        $areaDB = new IModel('areas');
        $data = $areaDB->query("parent_id=$parent_id", '*', 'sort', 'asc');
        echo JSON::encode($data);
    }

    /**
     * @brief 获取地区中文
     */
    public function area_name() {
        $province = intval(IReq::get("province"));
        $city = intval(IReq::get("city"));
        $area = intval(IReq::get("area"));
        $data = area::name($province, $city, $area);
        $ret = array("province" => $data[$province],
            "city" => $data[$city],
            "area" => $data[$area]);
        echo JSON::encode($ret);
    }

    //[公共方法]通过解析products表中的spec_array转化为格式：key:规格名称;value:规格值
    public static function show_spec($specJson) {
        $specArray = JSON::decode($specJson);
        $spec = array();

        foreach ($specArray as $val) {
            if ($val['type'] == 1) {
                $spec[$val['name']] = $val['value'];
            } else {
                $spec[$val['name']] = '<img src="' . IUrl::creatUrl() . $val['value'] . '" class="img_border" style="width:15px;height:15px;" />';
            }
        }
        return $spec;
    }

    /**
     * @brief 获得配送方式ajax
     */
    public function order_delivery() {
        $province = IFilter::act(IReq::get("province"), 'int');
        $weight = IFilter::act(IReq::get('total_weight'), 'float');
        $goodsSum = IFilter::act(IReq::get('goodsSum'), 'float');
        $distribution = IFilter::act(IReq::get("distribution"), 'int');

        //调入数据，获得配送方式结果
        $data = Delivery::getDelivery($province, $weight, $goodsSum);

        if ($distribution) {
            echo JSON::encode($data[$distribution]);
        } else {
            echo JSON::encode($data);
        }
    }

    /**
     * @brief 【重要】进行支付支付方法
     */
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

    /**
     * @brief 【重要】支付回调[同步]
     */
    public function callback() {
        //从URL中获取支付方式
        $payment_id = IFilter::act(IReq::get('_id'), 'int');
        $paymentInstance = Payment::createPaymentInstance($payment_id);

        if (!is_object($paymentInstance)) {
            IError::show(403, '支付方式不存在');
        }

        //初始化参数
        $money = '';
        $message = '支付失败';
        $orderNo = '';

        //执行接口回调函数
        $callbackData = array_merge($_POST, $_GET);
        unset($callbackData['controller']);
        unset($callbackData['action']);
        unset($callbackData['_id']);
        $return = $paymentInstance->callback($callbackData, $payment_id, $money, $message, $orderNo);
        $device = IClient::getDevice();
        $device = strtoupper($device);
        //支付成功
        if ($return == 1) {
            //充值方式
            if (stripos($orderNo, 'recharge_') !== false) {
                $tradenoArray = explode('_', $orderNo);
                $recharge_no = isset($tradenoArray[1]) ? $tradenoArray[1] : 0;
                if (payment::updateRecharge($recharge_no)) {
                    $this->redirect('/site/success/message/' . urlencode("充值成功") . '/?callback=/ucenter/account_log');
                    exit;
                }
                IError::show(403, '充值失败');
            } else {
                $order_id = Order_Class::updateOrderStatus($orderNo);
                if ($order_id) {
					if($device == 'PC'){
						$url = "/ucenter/book_result/order_no/$orderNo";
						$this->redirect($url);
						exit;
					}else{
						$url = "/wap/cart3_result?order_no=".$orderNo."&status=1";
						$this->redirect($url);
						exit;
					}
                }
                IError::show(403, '订单修改失败');
            }
        }
        //支付失败
        else {
            $message = $message ? $message : '支付失败';
            if($device == 'PC'){
            	IError::show(403, $message);
            }else{
            	$url = "/wap/cart3_result?order_no=".$orderNo."&status=0";
            	$this->redirect($url);
            	exit;
            }
            
        }
    }

    /**
     * @brief 【重要】支付回调[同步模拟]
     */
    public function callback_fate() {
        $orderNo = IReq::get("orderno");

        $order_id = Order_Class::updateOrderStatus($orderNo);
        if ($order_id) {
            //$url = '/site/success/message/' . urlencode("支付成功");
            //$url .= ISafe::get('user_id') ? '/?callback=/ucenter/order': '';
            $url = "/ucenter/book_result/order_no/$orderNo";
            $this->redirect($url);
            exit;
        }
    }

    /**
     * @brief 【重要】支付回调[异步]
     */
    function server_callback() {
        //从URL中获取支付方式
        $payment_id = IFilter::act(IReq::get('_id'), 'int');
        $paymentInstance = Payment::createPaymentInstance($payment_id);

        if (!is_object($paymentInstance)) {
            die('fail');
        }

        //初始化参数
        $money = '';
        $message = '支付失败';
        $orderNo = '';

        //执行接口回调函数
        $callbackData = array_merge($_POST, $_GET);
        unset($callbackData['controller']);
        unset($callbackData['action']);
        unset($callbackData['_id']);
        $return = $paymentInstance->serverCallback($callbackData, $payment_id, $money, $message, $orderNo);

        //支付成功
        if ($return == 1) {
            //充值方式
            if (stripos($orderNo, 'recharge_') !== false) {
                $tradenoArray = explode('_', $orderNo);
                $recharge_no = isset($tradenoArray[1]) ? $tradenoArray[1] : 0;
                if (payment::updateRecharge($recharge_no)) {
                    $paymentInstance->notifyStop();
                    exit;
                }
            } else {
                $order_id = Order_Class::updateOrderStatus($orderNo);
                if ($order_id) {
                    $paymentInstance->notifyStop();
                    exit;
                }
            }
        }
        //支付失败
        else {
            $paymentInstance->notifyStop();
            exit;
        }
    }

    /**
     * @brief 根据省份名称查询相应的privice
     */
    public function searchPrivice() {
        $province = IFilter::act(IReq::get('province'));

        $tb_areas = new IModel('areas');
        $areas_info = $tb_areas->getObj('parent_id = 0 and area_name like "%' . $province . '%"', 'area_id');
        $result = array('flag' => 'fail', 'area_id' => 0);
        if ($areas_info) {
            $result = array('flag' => 'success', 'area_id' => $areas_info['area_id']);
        }
        echo JSON::encode($result);
    }

    //添加实体代金券
    function add_download_ticket() {
        $isError = true;

        $ticket_num = IFilter::act(IReq::get('ticket_num'));
        $ticket_pwd = IFilter::act(IReq::get('ticket_pwd'));

        $propObj = new IModel('prop');
        $propRow = $propObj->getObj('card_name = "' . $ticket_num . '" and card_pwd = "' . $ticket_pwd . '" and type = 0 and is_userd = 0 and is_send = 1 and is_close = 0 and NOW() between start_time and end_time');

        if (empty($propRow)) {
            $message = '代金券不可用，请确认代金券的卡号密码并且此代金券从未被使用过';
        } else {
            //登录用户
            if ($this->user['user_id']) {
                $memberObj = new IModel('member');
                $memberRow = $memberObj->getObj('user_id = ' . $this->user['user_id'], 'prop');
                if (stripos($memberRow['prop'], ',' . $propRow['id'] . ',') !== false) {
                    $message = '代金券已经存在，不能重复添加';
                } else {
                    $isError = false;
                    $message = '添加成功';

                    if ($memberRow['prop'] == '') {
                        $propUpdate = ',' . $propRow['id'] . ',';
                    } else {
                        $propUpdate = $memberRow['prop'] . $propRow['id'] . ',';
                    }

                    $dataArray = array('prop' => $propUpdate);
                    $memberObj->setData($dataArray);
                    $memberObj->update('user_id = ' . $this->user['user_id']);
                }
            }
            //游客方式
            else {
                $isError = false;
                $message = '添加成功';
                ISafe::set("ticket_" . $propRow['id'], $propRow['id']);
            }
        }

        $result = array(
            'isError' => $isError,
            'data' => $propRow,
            'message' => $message,
        );

        echo JSON::encode($result);
    }

    private function alert($msg) {
        header('Content-type: text/html; charset=UTF-8');
        echo JSON::encode(array('error' => 1, 'message' => $msg));
        exit;
    }

    /**
     * 筛选用户
     */
    public function filter_user() {
        $email = IFilter::act(IReq::get('email'));
        $username = IFilter::act(IReq::get('username'));
        $true_name = IFilter::act(IReq::get('true_name'));
        $group_id = IFilter::act(IReq::get('group_id'));

        $where = '1';
        $userIds = '';

        if ($email) {
            $where .= ' and u.email = "' . $email . '"';
        }

        if ($username) {
            $where .= ' and u.username = "' . $username . '"';
        }

        if ($true_name) {
            $where .= ' and m.true_name = "' . $true_name . '"';
        }

        if ($group_id) {
            $where .= ' and m.group_id = "' . $group_id . '"';
        }

        //有筛选条件
        if ($where != '1') {
            $userDB = new IQuery('user as u');
            $userDB->join = 'left join member as m on u.id = m.user_id';
            $userDB->fields = 'u.id';
            $userDB->where = $where;
            $userData = $userDB->find();
            $tempArray = array();
            foreach ($userData as $key => $item) {
                $tempArray[] = $item['id'];
            }
            $userIds = join(',', $tempArray);
        }

        die('<script type="text/javascript">parent.searchUserCallback("' . $userIds . '");</script>');
    }

    /*
     * @breif ajax添加商品扩展属性
     * */

    function attribute_init() {
        $id = IFilter::act(IReq::get('model_id'), 'int');
        $tb_attribute = new IModel('attribute');
        $attribute_info = $tb_attribute->query('model_id=' . $id);
        echo JSON::encode($attribute_info);
    }

    //获取商品数据
    public function getGoodsData() {
        $id = IFilter::act(IReq::get('id'), 'int');

        $productDB = new IQuery('products as p');
        $productDB->join = 'left join goods as go on go.id = p.goods_id';
        $productDB->where = 'go.id = ' . $id;
        $productDB->fields = 'p.*,go.name';
        $productData = $productDB->find();

        if (!$productData) {
            $goodsDB = new IModel('goods');
            $productData = $goodsDB->query('id = ' . $id);
        }
        echo JSON::encode($productData);
    }

    //获取商品的推荐标签数据
    public function goodsCommend() {
        //商品字符串的逗号间隔
        $id = IFilter::act(IReq::get('id'));

        $goodsDB = new IModel('goods');
        $goodsData = $goodsDB->query("id in ({$id})", "id,name");

        $goodsCommendDB = new IModel('commend_goods');
        foreach ($goodsData as $key => $val) {
            $goodsCommendData = $goodsCommendDB->query("goods_id = " . $val['id']);
            foreach ($goodsCommendData as $k => $v) {
                $goodsData[$key]['commend'][$v['commend_id']] = 1;
            }
        }
        die(JSON::encode($goodsData));
    }

    //获取顺丰快递物流信息
    public function getExpressInfo() {
        //订单ID
        $order_id = IFilter::act(IReq::get('id'));
        $orderDB = new IModel("order");
        $order_info = $orderDB->getObj("id = $order_id");
        //判断状态：发货/服务订单
        //发货
        if ($order_info["distribution_status"] == 2) {
            $deliveryDB = new IModel("delivery_doc");
            $delivery = $deliveryDB->getObj("order_id = $order_id");
            $code = $delivery["delivery_code"];
            $com_id = $delivery["freight_id"];

            $freightDB = new IModel("freight_company");
            $com_info = $freightDB->getObj("id = $com_id");
            $com = $com_info["freight_type"];

            $AppKey = '274067b85fe545c8bf3f31257d6afdc8'; //请将XXXXXX替换成您在http://kuaidi100.com/app/reg.html申请到的KEY
            //$url ='http://api.kuaidi100.com/api?id='.$AppKey.'&com='.$com.'&nu='.$code.'&show=2&muti=1&order=asc';
            $url = 'http://apis.haoservice.com/lifeservice/exp?key=' . $AppKey . '&com=' . $com . '&no=' . $code;

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
            curl_setopt($curl, CURLOPT_TIMEOUT, 5);
            $get_content = curl_exec($curl);
            curl_close($curl);
            $get_content = json_decode($get_content);
            //var_dump ($get_content);
            if ($get_content->error_code == 0) {
                $list = $get_content->result->list;
                $result = "";
                foreach ($list as $l) {
                    $result .= ($l->time . " " . $l->context . "<br />");
                }
                echo $result;
            } else {
                echo $get_content->result;
            }
            exit();
        }
    }

    //cas连通测试函数 登出
    public function testCas_logout() {
        $path = IWeb::$app->getBasePath() . 'plugins/cas/phpCAS.php';
        require_once($path);
        phpCAS::client(CAS_VERSION_2_0, "192.168.2.108", 8443, "/cas");
        phpCAS::setNoCasServerValidation();
        $param = array("service" => IUrl::createUrl("/"));
        phpCAS::logout($param);
    }

    //物流轨迹查询
	public function freight()
	{
		$id = IFilter::act(IReq::get('id'),'int');

		if($id)
		{
			$tb_freight = new IQuery('delivery_doc as d');
			$tb_freight->join  = 'left join freight_company as f on f.id = d.freight_id';
			$tb_freight->where = 'd.id = '.$id;
			$tb_freight->fields= 'd.*,f.freight_type';
			$freightData = $tb_freight->find();

			if($freightData)
			{
				$freightData = current($freightData);
				if($freightData['freight_type'] && $freightData['delivery_code'])
				{
					$result = freight_facade::line($freightData['freight_type'],$freightData['delivery_code']);
					if($result['result'] == 'success')
					{
						$this->data = $result['data'];
						$this->redirect('freight');
						exit;
					}
					else
					{
						die(isset($result['reason']) ? $result['reason'] : '物流接口发生错误');
					}
				}
				else
				{
					die('缺少物流信息');
				}
			}
		}
		die('发货单信息不存在'.$id."!");
	}
	
    public function index() {
        $path = IWeb::$app->getBasePath() . 'plugins/cas/phpCAS.php';
        require_once($path);
        header('P3P:CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');
        phpCAS::client(CAS_VERSION_2_0, "sso.meibeike.com", 8443, "/cas", true);
        //phpCAS::client(CAS_VERSION_2_0, "cas.test.com", 8444, "/cas", true);
        phpCAS::setNoCasServerValidation();
        if (phpCAS::checkAuthentication()) {
            var_dump("已验证！");
            $userRow = phpCAS::getAttributes();
            var_dump($userRow);
            /*
              //获取登陆的用户名
              $username = phpCAS::getUser();
              //用户登陆成功后,采用js进行页面跳转
              echo "$username登录成功";
              $userRow = phpCAS::getAttributes();

              ISafe::set('user_id', $userRow['id']);
              ISafe::set('username', $username);
              ISafe::set('head_ico', $userRow['head_ico']);
              ISafe::set('user_pwd', $userRow['password']);
              ISafe::set('last_login', $userRow['last_login']);
              ISafe::set('email', $userRow['email']);
              ISafe::set('mobile', $userRow['mobile']);


              var_dump($userRow);
              if(CheckRights::isValidUser($username, $userRow['password'])){
              IError::show(403, "$username登录成功");
              }else{
              //TODO 注册
              $userObj = new IModel('user');
              $where = " username = '$username'";
              $res = $userObj->getObj($where);
              if($res){
              IError::show(403, "登入失败:$username在本站已存在，但密码错误！");
              }else{
              $userArray = array(
              'username' => $username,
              'password' => $userRow['password'],
              'email' => $userRow['email'],
              'mobile' => $userRow['mobile'],
              'type' => 3,
              'active' => 1,
              );
              $userObj->setData($userArray);
              $user_id = $userObj->add();
              IError::show(403, "$username登录成功");
              }
              } */
        } else {
            // 访问CAS的验证  
            var_dump("未验证！");
            phpCAS::forceAuthentication();
            //phpCAS::forceAuthentication();
        }
    }

    //cas连通测试函数  登入
    public function testCas_login() {
        var_dump(222222222222);
        $path = IWeb::$app->getBasePath() . 'plugins/cas/phpCAS.php';
        require_once($path);
        header('P3P:CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');
        phpCAS::client(CAS_VERSION_2_0, "sso.meibeike.com", 8443, "/cas", true);
        //phpCAS::client(CAS_VERSION_2_0, "cas.test.com", 8444, "/cas", true);
        phpCAS::setDebug();
        phpCAS::setServerLoginUrl("https://sso.meibeike.com:8443/cas/login?embed=true&service=http://www.meibeike.com/meibeike/index.php?controller=block");
        //phpCAS::setServerLoginUrl("https://cas.test.com:8444/cas/login?embed=true&service=http://www.meibeike.com/meibeike/index.php?controller=block");
        phpCAS::setNoCasServerValidation();
        //这里会检测服务器端的退出的通知，就能实现php和其他语言平台间同步登出了  
        phpCAS::handleLogoutRequests();
        if (phpCAS::checkAuthentication()) {
            //获取登陆的用户名  
            $username = phpCAS::getUser();
            //用户登陆成功后,采用js进行页面跳转  
            echo "$username登录成功";
            $userRow = phpCAS::getAttributes();

            ISafe::set('user_id', $userRow['id']);
            ISafe::set('username', $username);
            ISafe::set('head_ico', $userRow['head_ico']);
            ISafe::set('user_pwd', $userRow['password']);
            ISafe::set('last_login', $userRow['last_login']);
            ISafe::set('email', $userRow['email']);
            ISafe::set('mobile', $userRow['mobile']);


            var_dump($userRow);
            if (CheckRights::isValidUser($username, $userRow['password'])) {
                IError::show(403, "$username登录成功");
            } else {
                //TODO 注册
                $userObj = new IModel('user');
                $where = " username = '$username'";
                $res = $userObj->getObj($where);
                if ($res) {
                    IError::show(403, "登入失败:$username在本站已存在，但密码错误！");
                } else {
                    $userArray = array(
                        'username' => $username,
                        'password' => $userRow['password'],
                        'email' => $userRow['email'],
                        'mobile' => $userRow['mobile'],
                        'type' => 3,
                        'active' => 1,
                    );
                    $userObj->setData($userArray);
                    $user_id = $userObj->add();
                    IError::show(403, "$username登录成功");
                }
            }
        } else {
            // 访问CAS的验证  
            //var_dump(phpCAS::checkAuthentication());
            //var_dump("aaaaaaaaaaaaaaaaaaa");
            phpCAS::forceAuthentication();
        }
        exit;
    }
    
    public function checkemail(){
        $email = IReq::get('email');
        $user = new IModel("user");
        $res = $user->getObj("email = '$email'");
        if($res){
            echo 0;
        }else{
            echo 1;
        }
    }
    
    public function checkmobile(){
        $mobile = IReq::get('mobile');
        $user = new IModel("user");
        $res = $user->getObj("mobile = '$mobile'");
        if($res){
            echo 0;
        }else{
            echo 1;
        }
    }

    //cas 测试入口
    public function login2() {
        $this->redirect("/site/login2");
    }
    
    

}
