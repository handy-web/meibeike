<?php

/**
 * @brief 用户中心模块
 * @class Ucenter
 * @note  前台
 */
class Ucenter extends IController {

    public $layout = 'site_new';

    public function init() {
        CheckRights::checkUserRights();
        if (!$this->user) {
            $this->redirect('/simple/login');
        }
    }

    public function index() {
        $this->initPayment();
        $this->redirect('index');
    }

    //[用户头像]上传
    function user_ico_upload() {
        $result = array(
            'isError' => true,
        );

        if (isset($_FILES['attach']['name']) && $_FILES['attach']['name'] != '') {
            $photoObj = new PhotoUpload();
            $photoObj->setThumb(100, 100, 'user_ico');
            $photo = $photoObj->run();

            if (!empty($photo['attach']['thumb']['user_ico'])) {
                $user_id = $this->user['user_id'];
                $user_obj = new IModel('user');
                $dataArray = array(
                    'head_ico' => $photo['attach']['thumb']['user_ico'],
                );
                $user_obj->setData($dataArray);
                $where = 'id = ' . $user_id;
                $isSuss = $user_obj->update($where);

                if ($isSuss !== false) {
                    $result['isError'] = false;
                    $result['data'] = IUrl::creatUrl() . $photo['attach']['thumb']['user_ico'];
                    ISafe::set('head_ico', $dataArray['head_ico']);
                } else {
                    $result['message'] = '上传失败';
                }
            } else {
                $result['message'] = '上传失败';
            }
        } else {
            $result['message'] = '请选择图片';
        }
        echo '<script type="text/javascript">parent.callback_user_ico(' . JSON::encode($result) . ');</script>';
    }

    /**
     * @brief 我的订单列表
     */
    public function order() {
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

    /**
     * @brief 订单详情
     * @return String
     */
    public function order_detail() {
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
        $this->redirect('order_detail', false);
    }

    /**
     * 修改收件人信息
     * */
    public function order_accept() {
        $id = IFilter::act(IReq::get('order_id'), 'int');
        $mess = '';

        if ($id) {
            $order_array = array(
                'postcode' => IFilter::act(IReq::get('postcode')),
                'accept_name' => IFilter::act(IReq::get('accept_name')),
                'province' => IFilter::act(IReq::get('province')),
                'city' => IFilter::act(IReq::get('city')),
                'area' => IFilter::act(IReq::get('area')),
                'address' => IFilter::act(IReq::get('address')),
                'telphone' => IFilter::act(IReq::get('telphone')),
                'mobile' => IFilter::act(IReq::get('mobile')),
                'accept_time' => IFilter::act(IReq::get('accept_time')),
            );

            $tb_order = new IModel('order');
            $orderRow = $tb_order->getObj('id = ' . $id);


            $mess = '更新成功！';
            $tb_order->setData($order_array);
            if ($tb_order->update('id = ' . $id . ' and user_id = ' . $this->user['user_id']) === false) {
                $mess = '收件人更新失败!';
            }
        } else {
            $mess = '您的操作失败!';
        }
        
        $this->redirect("order");
        Util::showMessage($mess);
    }
    
    //修改发票
    function order_invoice(){
        $id = IFilter::act(IReq::get('order_id'), 'int');
        $mess = '';

        if ($id) {
            $order_array = array(
                'invoice_type' => IFilter::act(IReq::get('invoice_type')),
                'invoice_title' => IFilter::act(IReq::get('invoice_title')),
            );

            $tb_order = new IModel('order');
            $orderRow = $tb_order->getObj('id = ' . $id);


            $mess = '发票更新成功！';
            $tb_order->setData($order_array);
            if ($tb_order->update('id = ' . $id . ' and user_id = ' . $this->user['user_id']) === false) {
                $mess = '发票更新失败!';
            }
        } else {
            $mess = '您的操作失败!';
        }
        
        $this->redirect("order");
        Util::showMessage($mess);
    }

    //取消订单
    function order_cancel() {
        $id = IFilter::act(IReq::get('order_id'), 'int');
        $user_id = $this->user["user_id"];
        $db = new IModel("order");
        $db->setData(array("status" => 3,
            "cancel_time" => date('Y-m-d h:i:s')));
        $res = $db->update("id = $id and user_id = $user_id and status = 1");
        if ($res) {
            $this->redirect("order");
            Util::showMessage("取消成功！");
        } else {
            Util::showMessage("取消失败！");
        }
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
        $this->redirect("/tool/order");
        if ($res) {
            Util::showMessage("操作成功！");
        } else {
            Util::showMessage("操作失败！");
        }
    }

    /**
     * @brief 我的地址
     */
    public function address() {
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

    //设置默认地址
    public function address_set_default() {
        $address_id = intval(IReq::get('address_id'));
        $user_id = $this->user["user_id"];
        $db = new IModel("address");
        //先取消该用户的其他地址的默认
        $db->setData(array("default" => 0));
        $db->update("user_id = $user_id and id != $address_id");
        $db->setData(array("default" => 1));
        $db->update("id = $address_id");
        echo "success";
    }

    /**
     * @brief 申请服务单
     */
    public function apply_service() {
        $order_id = intval(IReq::get('order_id'));
        $user_id = $this->user["user_id"];
        //取订单信息
        $order = new IModel("order");
        $order_data = $order->getObj(" id = $order_id and user_id = $user_id ");
        if (!$order_data) {
            //订单不存在
            IError::show(403, '订单不存在！');
            exit;
        }
        if ($order_data["status"] != 5) {
            IError::show(403, '订单未完成！');
            exit;
        }
        $this->order_info = $order_data;
        $this->redirect("apply_service");
    }

    /**
     * @brief 申请服务单入库
     * 该系统暂定订单与商品是一对一的关系
     */
    public function do_apply_service() {
        $order_id = intval(IReq::get('order_id'));
        $user_id = $this->user["user_id"];
        $service_no = Order_Class::createServiceNum();
        $status = 1;
        $type = IFilter::act(IReq::get('type'), 'int');
        $reason = IFilter::act(IReq::get('reason'));

        //确认订单状态
        $order = new IModel("order");
        $order_data = $order->getObj(" id = $order_id ");
        if (!$order_data) {
            //订单不存在
            IError::show(403, '订单不存在！');
            exit;
        }
        if ($order_data["status"] < 5) {
            IError::show(403, '订单未完成！');
            exit;
        }
        if ($order_data["status"] > 5) {
            IError::show(403, '订单已申请售后，请勿重复申请！');
            exit;
        }
        //var_dump($order_data);
        //确认商品-订单关联
        $order_goods = new IModel("order_goods");
        $order_goods_data = $order_goods->getObj(" order_id = $order_id ");
        if (!$order_goods_data) {
            IError::show(403, '订单商品信息错误！');
            exit;
        }
        //var_dump($order_goods_data);
        //插入售后服务表
        $dataArray = array("order_id" => $order_id,
            "order_goods_id" => $order_goods_data["id"],
            "service_no" => $service_no,
            "user_id" => $user_id,
            "status" => $status,
            "type" => $type,
            "reason" => $reason,
            "distribution" => $order_data["distribution"],
            "accept_name" => $order_data["accept_name"],
            "postcode" => $order_data["postcode"],
            "telphone" => $order_data["telphone"],
            "country" => $order_data["country"],
            "province" => $order_data["province"],
            "city" => $order_data["city"],
            "area" => $order_data["area"],
            "address" => $order_data["address"],
            "mobile" => $order_data["mobile"],
            "create_time" => ITime::getDateTime(),
        );
        //var_dump($dataArray);
        $service = new IModel("order_service");
        $service->setData($dataArray);
        $this->service_id = $service->add();
        //var_dump($this->service_id);
        //更新订单状态
        $order->setData(array("status" => 7));
        $res = $order->update(" id = $order_id");
        if ($res) {
            $this->redirect('order', true);
            Util::showMessage("售后申请成功！");
        } else {
            $this->redirect('apply_service', true, array("order_id", $order_id));
            Util::showMessage("申请失败！");
        }
    }

    /**
     * @brief 收货地址管理
     */
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

    //添加地址ajax
    function address_add() {
        $accept_name = IFilter::act(IReq::get('accept_name'));
        $province = IFilter::act(IReq::get('province'), 'int');
        $city = IFilter::act(IReq::get('city'), 'int');
        $area = IFilter::act(IReq::get('area'), 'int');
        $address = IFilter::act(IReq::get('address'));
        $zip = IFilter::act(IReq::get('zip'));
        $telphone = IFilter::act(IReq::get('telphone'));
        $mobile = IFilter::act(IReq::get('mobile'));
        $user_id = $this->user['user_id'];

        $model = new IModel('address');
        $addressRow = $model->getObj('user_id = ' . $user_id . ' and accept_name = "' . $accept_name . '" and area = ' . $area . ' and address = "' . $address . '"');

        if ($addressRow) {
            $isError = true;
            $message = '请不要重复添加同一个收货地址';
            $data = '';
        } else {
            //获取地区text
            $areaList = area::name($province, $city, $area);

            //执行insert
            $data = array('user_id' => $user_id, 'accept_name' => $accept_name, 'province' => $province, 'city' => $city, 'area' => $area, 'address' => $address, 'zip' => $zip, 'telphone' => $telphone, 'mobile' => $mobile);
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
    
    
    function address_modify() {
    	$id = IFilter::act(IReq::get('address_id'));
    	$accept_name = IFilter::act(IReq::get('accept_name'));
    	$province = IFilter::act(IReq::get('province'), 'int');
    	$city = IFilter::act(IReq::get('city'), 'int');
    	$area = IFilter::act(IReq::get('area'), 'int');
    	$address = IFilter::act(IReq::get('address'));
    	$mobile = IFilter::act(IReq::get('mobile'));
    	$user_id = $this->user['user_id'];
    	$model = new IModel('address');
    	$addressRow = $model->getObj("id = {$id} and user_id = {$user_id} and accept_name = '{$accept_name}'  and area = '{$area}' and city = '{$city}' and address = '{$address}' and mobile = '{$mobile}'");
    
    	if ($addressRow) {
    		$isError = true;
    		$message = '请不要重复添加同一个收货地址';
    		$data = '';
    	} else {
    		$data = array('accept_name' => $accept_name, 'province' => $province, 'city' => $city, 'area' => $area, 'address' => $address, 'mobile' => $mobile,);
    		$model->setData($data);
    		$r =$model->update("id = {$id} and user_id = {$user_id}");
    		if($r){
    			$isError = false;
    			$message = '修改成功';
    		}else{
    			$isError = true;
    			$message = '修改失败';
    		}
    		$areaList = area::name($province, $city, $area);
    		$data['province_val'] = $areaList[$province];
    		$data['city_val'] = $areaList[$city];
    		$data['area_val'] = $areaList[$area];
    	}
    	$result = array('isError' => $isError, 'message' => $message, 'data' => $data);
    	echo JSON::encode($result);
    }
    
    
    /**
     * @brief 收货地址删除处理
     */
    public function address_del() {
        $id = IFilter::act(IReq::get('id'), 'int');
        $model = new IModel('address');
        $model->del('id = ' . $id . ' and user_id = ' . $this->user['user_id']);
        $this->redirect('address');
    }

    /**
     * @brief 设置默认的收货地址
     */
    public function address_default() {
        $id = IFilter::act(IReq::get('id'), 'int');
        $default = IFilter::string(IReq::get('default'));
        $model = new IModel('address');
        if ($default == 1) {
            $model->setData(array('default' => 0));
            $model->update("user_id = " . $this->user['user_id']);
        }
        $model->setData(array('default' => $default));
        $model->update("id = " . $id . " and user_id = " . $this->user['user_id']);
        $this->redirect('address');
    }

    /**
     * @brief 退款申请页面
     */
    public function refunds_edit() {
        $id = IFilter::act(IReq::get('id'), 'int');
        $order_no = IFilter::act(IReq::get('order_no'));
        $user_id = $this->user['user_id'];
        $content = IFilter::string(IReq::get('content'));

        $order = new IModel('order');
        $orderObj = $order->getObj("order_no = '" . $order_no . "' and user_id = " . $this->user['user_id'] . ' and status=5 and pay_status=1', 'order_no,id,order_amount');

        //订单信息存在
        if ($orderObj) {
            if ($id == 0) {
                $refundment = new IModel('refundment_doc');
                $refundmentObj = $refundment->getObj("order_no = '$order_no'");
                if ($refundmentObj) {
                    switch ($refundmentObj['pay_status']) {
                        case 0: {
                                $this->msg = '此订单已申请退款，请等待处理';
                            }
                            break;

                        case 1: {
                                $this->msg = '此订单不允许退款，如有疑问请联系客服人员';
                            }
                            break;

                        case 2: {
                                $this->msg = '此订单已退款成功，请查看您的账户余额';
                            }
                            break;
                    }
                    $this->info = JSON::encode(array('order_no' => $order_no, 'content' => $content));
                    $this->redirect('refunds', false);
                    exit;
                } else {
                    $model = new IModel('refundment_doc');
                    $model->setData(array('user_id' => $user_id, 'order_no' => $order_no, 'order_id' => $orderObj['id'], 'pay_status' => 0, 'content' => $content, 'time' => date('Y-m-d H:i:s'), 'amount' => $orderObj['order_amount']));
                    $model->add();
                }
            } else {
                $model = new IModel('refundment_doc');
                $model->setData(array('user_id' => $user_id, 'order_no' => $order_no, 'order_id' => $orderObj['id'], 'pay_status' => 0, 'content' => $content, 'time' => date('Y-m-d H:i:s')));
                $model->update("id = " . $id . " and user_id = " . $this->user['user_id']);
            }
            $this->redirect('refunds');
        } else {
            $this->msg = '此订单号不存在或者还未完成交易';
            $this->info = JSON::encode(array('order_no' => $order_no, 'content' => $content));
            $this->redirect('refunds', false);
        }
    }

    /**
     * @brief 退款申请删除
     */
    public function refunds_del() {
        $id = IFilter::act(IReq::get('id'), 'int');
        $model = new IModel("refundment_doc");
        $model->del("id = " . $id . " and user_id = " . $this->user['user_id']);
        $this->redirect('refunds');
    }

    /**
     * @brief 查看退款申请详情
     */
    public function refund_detail() {
        $id = IFilter::act(IReq::get('id'), 'int');
        $model = new IModel("refundment_doc");
        $this->data = $model->getObj("id = " . $id . " and user_id = " . $this->user['user_id']);
        $this->redirect('refund_detail');
    }

    /**
     * @brief 建议中心
     */
    public function complain_edit() {
        $id = IFilter::act(IReq::get('id'), 'int');
        $title = IFilter::act(IReq::get('title'), 'string');
        $content = IFilter::act(IReq::get('content'), 'string');
        $user_id = $this->user['user_id'];
        $model = new IModel('suggestion');
        $model->setData(array('user_id' => $user_id, 'title' => $title, 'content' => $content, 'time' => date('Y-m-d H:i:s')));
        if ($id == '') {
            $model->add();
        } else {
            $model->update('id = ' . $id . ' and user_id = ' . $this->user['user_id']);
        }
        $this->redirect('complain');
    }

    /**
     * @brief 删除消息
     * @param int $id 消息ID
     */
    public function message_del() {
        $id = IFilter::act(IReq::get('id'), 'int');
        $msg = new Mess($this->user['user_id']);
        $msg->delMessage($id);
        $this->redirect('message');
    }

    public function message_read() {
        $id = IFilter::act(IReq::get('id'), 'int');
        $msg = new Mess($this->user['user_id']);
        echo $msg->writeMessage($id, 1);
    }

    //[修改密码]修改动作
    function password_edit() {
        $user_id = $this->user['user_id'];

        $fpassword = IReq::get('fpassword');
        $password = IReq::get('password');
        $repassword = IReq::get('repassword');
        $userObj = new IModel('user');
        $where = 'id = ' . $user_id;
        $userRow = $userObj->getObj($where);

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
            $dataArray = array(
                'password' => $passwordMd5,
            );

//             $userObj->setData($dataArray);
//             $result = $userObj->update($where);
            if ($result) {
                /* 2015-11-11 王要求修改密码后不处于登录状态
                ISafe::set('user_pwd', $passwordMd5);
                $message = '密码修改成功';
                
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
                
                
                $this->redirect('address',false);
                */
                ISafe::clearAll();
                $this->redirect('/site/index');
                //exit(1);
                return;
            } else {
                $message = '修改失败，请重试';
            }
        }

        $this->redirect('modify_password', false);
        Util::showMessage($message);
    }

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
    
    
    
    //[个人资料]展示 单页
    function info() {
        $user_id = $this->user['user_id'];

        $userObj = new IModel('user');
        $where = 'id = ' . $user_id;
        $this->userRow = $userObj->getObj($where);

        $memberObj = new IModel('member');
        $where = 'user_id = ' . $user_id;
        $this->memberRow = $memberObj->getObj($where);

        $this->userGroupRow = array();
        if (isset($this->memberRow['group_id']) && $this->memberRow['group_id']) {
            $userGroupObj = new IModel('user_group');
            $where = 'id = ' . $this->memberRow['group_id'];
            $this->userGroupRow = $userGroupObj->getObj($where);
        }
        $this->redirect('info');
    }

    //[个人资料] 修改 [动作]
    function info_edit_act() {
        $user_id = $this->user['user_id'];

        $memberObj = new IModel('member');
        $where = 'user_id = ' . $user_id;

        //出生年月
        $year = IFilter::act(IReq::get('year', 'post'), 'int');
        $month = IFilter::act(IReq::get('month', 'post'), 'int');
        $day = IFilter::act(IReq::get('day', 'post'), 'int');
        $birthday = $year . '-' . $month . '-' . $day;

        //地区
        $province = IFilter::act(IReq::get('province', 'post'), 'string');
        $city = IFilter::act(IReq::get('city', 'post'), 'string');
        $area = IFilter::act(IReq::get('area', 'post'), 'string');
        $areaStr = ',' . $province . ',' . $city . ',' . $area . ',';

        $dataArray = array(
            'true_name' => IFilter::act(IReq::get('true_name'), 'string'),
            'sex' => IFilter::act(IReq::get('sex'), 'int'),
            'birthday' => $birthday,
            'zip' => IFilter::act(IReq::get('zip'), 'string'),
            'msn' => IFilter::act(IReq::get('msn'), 'string'),
            'qq' => IFilter::act(IReq::get('qq'), 'string'),
            'contact_addr' => IFilter::act(IReq::get('contact_addr'), 'string'),
            'mobile' => IFilter::act(IReq::get('mobile'), 'string'),
            'telephone' => IFilter::act(IReq::get('telephone'), 'string'),
            'area' => $areaStr,
        );

        $memberObj->setData($dataArray);
        $memberObj->update($where);
        $this->info();
    }

    //[账户余额] 展示[单页]
    function withdraw() {
        $user_id = $this->user['user_id'];

        $memberObj = new IModel('member', 'balance');
        $where = 'user_id = ' . $user_id;
        $this->memberRow = $memberObj->getObj($where);
        $this->redirect('withdraw');
    }

    //[账户余额] 提现动作
    function withdraw_act() {
        $user_id = $this->user['user_id'];
        $amount = IFilter::act(IReq::get('amount', 'post'), 'string');
        $message = '';

        $dataArray = array(
            'name' => IFilter::act(IReq::get('name', 'post'), 'string'),
            'note' => IFilter::act(IReq::get('note', 'post'), 'string'),
            'amount' => $amount,
            'user_id' => $user_id,
            'time' => ITime::getDateTime(),
        );

        $mixAmount = 0;
        $memberObj = new IModel('member');
        $where = 'user_id = ' . $user_id;
        $memberRow = $memberObj->getObj($where, 'balance');

        //提现金额范围
        if ($amount <= $mixAmount) {
            $message = '提现的金额必须大于' . $mixAmount . '元';
        } else if ($amount > $memberRow['balance']) {
            $message = '提现的金额不能大于您的帐户余额';
        } else {
            $obj = new IModel('withdraw');
            $obj->setData($dataArray);
            $obj->add();
            $this->redirect('withdraw');
            die();
        }

        if ($message != '') {
            $this->memberRow = array('balance' => $memberRow['balance']);
            $this->withdrawRow = $dataArray;
            $this->redirect('withdraw', false);
            Util::showMessage($message);
        }
    }

    //[账户余额] 提现状态判定
    static function getWithdrawStatus($status) {
        $message = '';

        switch ($status) {
            case "0":
                $message = '未处理';
                break;

            case "-1":
                $message = '失败';
                break;

            case "1":
                $message = '处理中';
                break;

            case "2":
                $message = '成功';
                break;
        }
        return $message;
    }

    //[账户余额] 提现详情
    function withdraw_detail() {
        $user_id = $this->user['user_id'];

        $id = IFilter::act(IReq::get('id'), 'int');
        $obj = new IModel('withdraw');
        $where = 'id = ' . $id . ' and user_id = ' . $user_id;
        $this->withdrawRow = $obj->getObj($where);
        $this->redirect('withdraw_detail');
    }

    //[提现申请] 取消
    function withdraw_del() {
        $id = IFilter::act(IReq::get('id'), 'int');
        if ($id) {
            $dataArray = array('is_del' => 1);
            $withdrawObj = new IModel('withdraw');
            $where = 'id = ' . $id;
            $withdrawObj->setData($dataArray);
            $withdrawObj->update($where);
        }
        $this->redirect('withdraw');
    }

    //[余额交易记录]
    function account_log() {
        $user_id = $this->user['user_id'];

        $memberObj = new IModel('member', 'balance');
        $where = 'user_id = ' . $user_id;
        $this->memberRow = $memberObj->getObj($where);
        $this->redirect('account_log');
    }

    //[收藏夹]备注信息
    function edit_summary() {
        $user_id = $this->user['user_id'];

        $id = IFilter::act(IReq::get('id'), 'int');
        $summary = IFilter::act(IReq::get('summary'), 'string');

        //ajax返回结果
        $result = array(
            'isError' => true,
        );

        if (!$id) {
            $result['message'] = '收藏夹ID值丢失';
        } else if (!$summary) {
            $result['message'] = '请填写正确的备注信息';
        } else {
            $favoriteObj = new IModel('favorite');
            $where = 'id = ' . $id . ' and user_id = ' . $user_id;

            $dataArray = array(
                'summary' => $summary,
            );

            $favoriteObj->setData($dataArray);
            $is_success = $favoriteObj->update($where);

            if ($is_success === false) {
                $result['message'] = '更新信息错误';
            } else {
                $result['isError'] = false;
            }
        }
        echo JSON::encode($result);
    }

    //[收藏夹]获取收藏夹数据
    function get_favorite(&$favoriteObj) {
        //获取收藏夹信息
        $page = (isset($_GET['page']) && (intval($_GET['page']) > 0)) ? intval($_GET['page']) : 1;

        $favoriteObj = new IQuery("favorite");
        $cat_id = intval(IReq::get('cat_id'));
        $where = '';
        if ($cat_id != 0) {
            $where = ' and cat_id = ' . $cat_id;
        }
        $favoriteObj->where = "user_id = " . $this->user['user_id'] . $where;
        $favoriteObj->page = $page;
        $items = $favoriteObj->find();

        $goodsIdArray = array();
        foreach ($items as $val) {
            $goodsIdArray[] = $val['rid'];
        }

        //商品数据
        if (!empty($goodsIdArray)) {
            $goodsIdStr = join(',', $goodsIdArray);
            $goodsObj = new IModel('goods');
            $goodsList = $goodsObj->query('id in (' . $goodsIdStr . ')');
        }

        foreach ($items as $key => $val) {
            foreach ($goodsList as $gkey => $goods) {
                if ($goods['id'] == $val['rid']) {
                    $items[$key]['data'] = $goods;

                    //效率考虑,让goodsList循环次数减少
                    unset($goodsList[$gkey]);
                }
            }

            //如果相应的商品或者货品已经被删除了，
            if (!isset($items[$key]['data'])) {
                $favoriteModel = new IModel('favorite');
                $favoriteModel->del("id={$val['id']}");
                unset($items[$key]);
            }
        }
        return $items;
    }

    //[收藏夹]删除
    function favorite_del() {
        $user_id = $this->user['user_id'];
        $id = IReq::get('id');

        if (!empty($id)) {
            $id = IFilter::act($id, 'int');

            $favoriteObj = new IModel('favorite');

            if (is_array($id)) {
                $idStr = join(',', $id);
                $where = 'user_id = ' . $user_id . ' and id in (' . $idStr . ')';
            } else {
                $where = 'user_id = ' . $user_id . ' and id = ' . $id;
            }

            $favoriteObj->del($where);
            $this->redirect('favorite');
        } else {
            $this->redirect('favorite', false);
            Util::showMessage('请选择要删除的数据');
        }
    }

    //[我的积分] 单页展示
    function integral() {
        /* 获取积分增减的记录日期时间段 */
        $this->historyTime = IFilter::string(IReq::get('history_time', 'post'));
        $defaultMonth = 3; //默认查找最近3个月内的记录

        $lastStamp = ITime::getTime(ITime::getNow('Y-m-d')) - (3600 * 24 * 30 * $defaultMonth);
        $lastTime = ITime::getDateTime('Y-m-d', $lastStamp);

        if ($this->historyTime != null && $this->historyTime != 'default') {
            $historyStamp = ITime::getDateTime('Y-m-d', ($lastStamp - (3600 * 24 * 30 * $this->historyTime)));
            $this->c_datetime = 'datetime >= "' . $historyStamp . '" and datetime < "' . $lastTime . '"';
        } else {
            $this->c_datetime = 'datetime >= "' . $lastTime . '"';
        }

        $memberObj = new IModel('member');
        $where = 'user_id = ' . $this->user['user_id'];
        $this->memberRow = $memberObj->getObj($where, 'point');
        $this->redirect('integral', false);
    }

    //[我的积分]积分兑换代金券 动作
    function trade_ticket() {
        $ticketId = IFilter::act(IReq::get('ticket_id', 'post'), 'int');
        $message = '';
        if (intval($ticketId) == 0) {
            $message = '请选择要兑换的代金券';
        } else {
            $nowTime = ITime::getDateTime();
            $ticketObj = new IModel('ticket');
            $ticketRow = $ticketObj->getObj('id = ' . $ticketId . ' and point > 0 and start_time <= "' . $nowTime . '" and end_time > "' . $nowTime . '"');
            if (empty($ticketRow)) {
                $message = '对不起，此代金券不能兑换';
            } else {
                $memberObj = new IModel('member');
                $where = 'user_id = ' . $this->user['user_id'];
                $memberRow = $memberObj->getObj($where, 'point');

                if ($ticketRow['point'] > $memberRow['point']) {
                    $message = '对不起，您的积分不足，不能兑换此类代金券';
                } else {
                    //生成红包
                    $dataArray = array(
                        'condition' => $ticketRow['id'],
                        'name' => $ticketRow['name'],
                        'card_name' => 'T' . IHash::random(8),
                        'card_pwd' => IHash::random(8),
                        'value' => $ticketRow['value'],
                        'start_time' => $ticketRow['start_time'],
                        'end_time' => $ticketRow['end_time'],
                        'is_send' => 1,
                    );
                    $propObj = new IModel('prop');
                    $propObj->setData($dataArray);
                    $insert_id = $propObj->add();

                    //用户prop字段值null时
                    $memberArray = array('prop' => ',' . $insert_id . ',');
                    $memberObj->setData($memberArray);
                    $result = $memberObj->update('user_id = ' . $this->user["user_id"] . ' and ( prop is NULL or prop = "" )');

                    //用户prop字段值非null时
                    if (!$result) {
                        $memberArray = array(
                            'prop' => 'concat(prop,"' . $insert_id . ',")',
                        );
                        $memberObj->setData($memberArray);
                        $result = $memberObj->update('user_id = ' . $this->user["user_id"], 'prop');
                    }

                    //代金券成功
                    if ($result) {
                        $pointConfig = array(
                            'user_id' => $this->user['user_id'],
                            'point' => '-' . $ticketRow['point'],
                            'log' => '积分兑换代金券，扣除了 -' . $ticketRow['point'] . '积分',
                        );
                        $pointObj = new Point;
                        $pointObj->update($pointConfig);
                    }
                }
            }
        }

        //展示
        if ($message != '') {
            $this->integral();
            Util::showMessage($message);
        } else {
            $this->redirect('redpacket');
        }
    }

    /**
     * 余额付款
     * T:支付失败;
     * F:支付成功;
     */
    function payment_balance() {
        $urlStr = '';
        $user_id = intval($this->user['user_id']);

        $return['attach'] = IReq::get('attach');
        $return['total_fee'] = IReq::get('total_fee');
        $return['order_no'] = IReq::get('order_no');
        $return['return_url'] = IReq::get('return_url');
        $sign = IReq::get('sign');
        if (stripos($return['order_no'], 'recharge_') !== false) {
            IError::show(403, '余额支付方式不能用于在线充值');
            exit;
        }

        if (floatval($return['total_fee']) <= 0 || $return['order_no'] == '' || $return['return_url'] == '') {
            IError::show(403, '支付参数不正确');
        } else {
            $paymentDB = new IModel('payment');
            $paymentRow = $paymentDB->getObj('class_name = "balance" ');
            $pkey = Payment::getConfigParam($paymentRow['id'], 'M_PartnerKey');

            //md5校验
            ksort($return);
            foreach ($return as $key => $val) {
                $urlStr .= $key . '=' . urlencode($val) . '&';
            }

            $urlStr .= $user_id . $pkey;
            if ($sign != md5($urlStr)) {
                IError::show(403, '数据校验不正确');
            } else {
                $memberObj = new IModel('member');
                $memberRow = $memberObj->getObj('user_id = ' . $user_id);

                if (empty($memberRow)) {
                    IError::show(403, '用户信息不存在');
                    exit;
                } else if ($memberRow['balance'] < $return['total_fee']) {
                    IError::show(403, '账户余额不足');
                    exit;
                } else {
                    $orderObj = new IModel('order');
                    $orderRow = $orderObj->getObj('order_no  = "' . IFilter::act($return['order_no']) . '" and pay_status = 0');
                    if (empty($orderRow)) {
                        IError::show(403, '订单已经被处理过，请查看订单状态');
                        exit;
                    }

                    $dataArray = array('balance' => 'balance - ' . IFilter::act($return['total_fee']));
                    $memberObj->setData($dataArray);
                    $is_success = $memberObj->update('user_id = ' . $user_id, 'balance');
                    if ($is_success) {
                        $return['is_success'] = 'T';
                    } else {
                        $return['is_success'] = 'F';
                    }

                    ksort($return);

                    //返还的URL地址
                    $responseUrl = '';
                    foreach ($return as $key => $val) {
                        $responseUrl .= $key . '=' . urlencode($val) . '&';
                    }
                    $nextUrl = urldecode($return['return_url']);
                    if (stripos($nextUrl, '?') === false) {
                        $return_url = $nextUrl . '?' . $responseUrl;
                    } else {
                        $return_url = $nextUrl . '&' . $responseUrl;
                    }

                    //计算要发送的md5校验
                    $urlStrMD5 = md5($responseUrl . $user_id . $pkey);

                    //拼接进返还的URL中
                    $return_url.= 'sign=' . $urlStrMD5;
                    header('location:' . $return_url);
                }
            }
        }
    }

    /*
     * 商品订购页面
     */

    public function book() {
        $user_id = intval($this->user['user_id']);
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

            foreach ($goods_info["spec_array"] as $item) {
                //颜色
                if ($item["name"] == "颜色") {
                    $goods_info["colors"] = explode(',', trim($item['value'], ','));
                } elseif ($item["name"] == "硬盘容量") {
                    $goods_info["volume"] = explode(',', trim($item['value'], ','));
                    //磁盘容量带出价格
                    $productDB = new IModel("products");
                    foreach ($goods_info["volume"] as $k => $v) {
                        $products_info = $productDB->getObj("goods_id = $goods_id and spec_array like '%$v%'");
                        $goods_info["volume"][$k] = array("id" => $item["id"], "name" => $item["name"], "type" => $item["type"], "value" => $v, "price" => $products_info["sell_price"]);
                    }
                }

                //容量
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
        $this->setRenderData($goods_info);
        $this->redirect('book');
    }

    public function book2() {
        $goods_id = IFilter::act(IReq::get('goods_id'), 'int');
        $product_id = IFilter::act(IReq::get('product_id'), 'int');
        $accept_name = IFilter::act(IReq::get('accept_name'));
        $province = IFilter::act(IReq::get('province'), 'int');
        $city = IFilter::act(IReq::get('city'), 'int');
        $area = IFilter::act(IReq::get('area'), 'int');
        $address = IFilter::act(IReq::get('address'));
        $mobile = IFilter::act(IReq::get('mobile'));
        $telphone = IFilter::act(IReq::get('telphone'));
        $postcode = IFilter::act(IReq::get('postcode'));
        $delivery_id = 1;
        $accept_time = IFilter::act(IReq::get('accept_time'));
        $pay_type = IFilter::act(IReq::get('pay_type'), 'int');
        $order_message = IFilter::act(IReq::get('message'));
        $invoice_type = IFilter::act(IReq::get('invoice_type'), 'int');
        $invoice_title = IFilter::act(IReq::get('invoice_title'), 'text');
        $goods_nums = IFilter::act(IReq::get('goods_nums'), 'int');
        $order_no = IFilter::act(IReq::get('order_no'));
        $sel_tz = IReq::get("sel_tz");
        $tz_name = IReq::get("tz_name");
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

        $user_id = ($this->user['user_id'] == null) ? 0 : $this->user['user_id'];

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
            //取图片，以所选套餐尾图为准
            $AttrObj = new IQuery("attribute as at");
            $AttrObj->join = "inner join goods_attribute as ga on at.id = ga.attribute_id inner join pics as pc on ga.attribute_value=pc.id";
            $AttrObj->where = "ga.goods_id = $goods_id and pc.name ='$sel_tz' and at.type in (4,5)";
            $AttrObj->order = "at.name";
            $AttrObj->fields = "at.type, pc.name, pc.content, pc.value";
            $res = $AttrObj->find();
            
            $tmp_pics =  JSON::decode($res[0]["value"]);
            //$products["img"] = end($tmp_pics);
            $products["img"] = $tmp_pics[1]; //第二幅为主图
            //规格
            $spec = block::show_spec($products['spec_array']);
            foreach ($spec as $skey => $svalue) {
                if($skey == "套装"){
                    $specArray['value'] .= $tz_name . ':' . $svalue . ',';
                }else{
                    $specArray['value'] .= $skey . ':' . $svalue . ',';
                }
            }
            $specArray['value'] = IFilter::addSlash(trim($specArray['value'], ','));
        } else {
            $products = $goods;
        }
        
        //如果地址为新地址，则保存地址
        $dataAddress = array("user_id"=> $user_id,
            "accept_name"=>$accept_name,
            "zip"=>$postcode,
            "telphone"=>$telphone,
            "province"=>$province,
            "city"=>$city,
            "area"=>$area,
            "address"=>$address,
            "mobile"=>$mobile,
            "accept_time"=>$accept_time);
        $Addr = new IModel("address");
        $res_addr = $Addr->query(" user_id = $user_id and accept_name='$accept_name' "
                . "and zip='$postcode' and telphone='$telphone' and province='$province' "
                . "and city='$city' and area='$area' and address='$address' and mobile='$mobile' "
                . "and accept_time = '$accept_time'");
        if(!$res_addr){
            $Addr->setData($dataAddress);
            $Addr->add();
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
            "goods_nums"=>$goods_nums,
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
            "post_code"=>$postcode,
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
        $this->redirect("book2");
        //直接支付
        //$this->redirect("/block/dopay/order_id/" . $this->order_id, true);
    }
    
    //保存日志
    private function save_log($data){
            $smsObj = new IModel('log');
            $smsObj->setData($data);           
            $id = $smsObj->add();  
            return $id;
    }
    
    public function book_result() {

		//防止页面刷新
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);

        $order_no = IReq::get('order_no');
			
        $user_id = ($this->user['user_id'] == null) ? 0 : $this->user['user_id'];
        //修改用户优惠券状态 
		$lottery_logDB = new IModel("lottery_log");
		$lottery_logDB->setData(array("status" => 1));
		$lottery_logDB->update("uid = $user_id and activity_type='2016mbkcj'");
        
        $Order = new IModel("order");
        $order_info = $Order->query("order_no = '$order_no'");
        $order_info = $order_info[0];
        $order_id = $order_info["id"];
        
        $Order_goods = new IModel("order_goods");
        $goods_info = $Order_goods->query("order_id = $order_id");
        $goods_info = $goods_info[0];
          
        //获取库存量
		$goodid=$goods_info["goods_id"];
		$goods_select = new IModel("goods");
		$goods_obj = $goods_select->getObj("id =$goodid ");
		$store_nums = $goods_obj["store_nums"];

		

		//库存数量-1
        $store_nums_show="";
		$i=1;

        if($store_nums>5001&&$i==1)
		{
			$data['content'] = "当前库存量【ucenter】：".$store_nums.",序号_".$i;
		    $data['createtime'] = ITime::getDateTime() ;
		    $res = $this->save_log($data);
			
			
			if($i==1){
			$i++;

			//修改用户优惠券状态 
			$goods_DB = new IModel("goods");
			$goods_DB->setData(array("store_nums" => $store_nums-1));
			$goods_DB->update("id = $goodid and store_nums>1");
			
			}

        }
		elseif($store_nums==5001)
		{
		//修改用户优惠券状态 
			$goods_DB = new IModel("goods");
			$goods_DB->setData(array("store_nums" => 1));
			$goods_DB->update("id = $goodid and store_nums>1");
			$store_nums=1;
		}
		else
		{
			$store_nums_show="库存数量不足！";
			$store_nums=1;
		}


        //跳转页
        $data = array("order_id"=>$order_info["id"],
            "accept_name"=>$order_info['accept_name'],
            "province"=>$order_info['province'],
            "city"=>$order_info['city'],
            "area"=>$order_info['area'],
            "address"=>$order_info['address'],
            "mobile"=>$order_info["mobile"],
            "telphone"=>$order_info['telphone'],
            "post_code"=>$order_info['postcode'],
            "pay_type"=>$order_info['pay_type'],
            "accept_time"=>$order_info['accept_time'],
            "invoice_type"=>$order_info['invoice_type'],
            "invoice_title"=>$order_info['invoice_title'],
            "goods_price"=>$goods_info["real_price"],
            "goods_img"=>$goods_info["img"],
            "goods_nums"=>$goods_info["goods_nums"],
            "goods_array"=> $goods_info["goods_array"],
            "order_no"=>$order_no,
            "order_amount" => $order_info["order_amount"],
			"store_nums_show" => $store_nums_show,
			"store_nums" => $store_nums,
            );
        $this->setRenderData($data);
        $this->redirect("book_result");
        //直接支付
        //$this->redirect("/block/dopay/order_id/" . $this->order_id, true);
    }
    
    
    //发送修改邮件
    function modify_email2() {
        $email = IFilter::act(IReq::get('email'));
        $user_id = ISafe::get('user_id');
        //更新邮箱-激活状态
        //2015-9-6 取消更新邮箱，改为验证后再更新
        //$userDB= new IModel("user");
        //$userDB->setData(array("active"=>1, "email"=>$email));
        //$userDB->update("id = $user_id");
        //设置邮箱
        //ISafe::set("email", $email);
        
        $hash = IHash::md5(microtime(true) . mt_rand());
        //记录邮件验证
        $email_active = new IModel("email_active"); 
        $email_active->setData(array('hash' => $hash, 'user_id' => $user_id, 'email'=>$email, 'addtime' => time()));
        $email_id = $email_active->add();
        //发送邮件
        $url = IUrl::creatUrl("/simple/reg_email_active/hash/{$hash}");
        $url = IUrl::getHost() . $url;
        $body = "请您点击下面这个链接激活邮箱：<a href='{$url}'>{$url}</a>。<br />如果不能点击，请您把它复制到地址栏中打开。<br />本链接在3天后将自动失效。";
        $subject = "美贝壳官网邮箱修改激活";
        $smtp = new SendMail();
        $error = $smtp->getError();
        if ($error) {
            $return = array(
                'isError' => true,
                'message' => $error,
            );
            echo JSON::encode($return);
            return ;
        }
        $status = $smtp->send($email, $subject, $body);
        $email_list = explode("@", $email);
        $server = "mail.".$email_list[1];
        $return = array(
                'isError' => false,
                'message' => $status,
                'server'  => "http://".$server,
            );
        //var_dump($return);
        echo JSON::encode($return);
        return ;
    }
    
    
    //手机绑定确认
    public function mobile_setup(){
        $mobile = IFilter::act(IReq::get('mobile'));
        $vcode = IReq::get('vcode');
        if ((!$mobile) || (!$vcode)) {
            $this->redirect("/site/error/msg/" . urlencode("参数不完整！"));
            exit;
        }
        //验证vcode
        if($vcode != ISafe::get("vcode")){
            //throw new IHttpException("用户校验码不匹配！", 0);
            $this->redirect("/site/error/msg/" . urlencode("用户校验码不匹配！"));
            exit;
        }
        //验证mobile
        $userDB= new IModel("user");
        $ifexist = $userDB->getObj("mobile = $mobile");
        if($ifexist){
            $this->redirect("/site/error/msg/" . urlencode("对不起，此手机已被注册！"));
            exit;
        }
        
        $user_id = ISafe::get('user_id');
        $userDB->setData(array("mobile"=>$mobile,"active"=>1));
        $userDB->update("id = $user_id");
        //设置session
        ISafe::set("mobile", $mobile);
        
        $this->redirect("/site/success/message/" . urlencode("手机绑定成功！"));
        
    }
    
    
    //查询订单支付状态
    public function check_order() {
        $orderNo = IReq::get("orderno");

        $Order = new IModel("order");
        $where = "order_no = '$orderNo' and status >= 2 and pay_status = 1 ";
        $order = $Order->getObj($where);
        if($order){
            echo 1;
        }else{
            echo 0;
        }
    }

}
