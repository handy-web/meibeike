<?php

class APIOrder {
    /*
     *  页面订单函数文件
     */

    /*
     * 取我的订单数量
     */

    public function getMyOrderCnt($user_id) {
        $dbObj = IDBFactory::getDB();
        $tableName = IWeb::$app->config['DB']['tablePre'] . 'order';
        $ret = $dbObj->doSql('SELECT count(1) cnt FROM ' . $tableName . ' WHERE user_id in (' . $user_id . ') AND if_del = 0 ');

        return $ret[0]['cnt'];
    }

    /*
     * 取我的订单列表
     */

    public function getMyOrderList($user_id) {
        $fields = 'count(1)';
        $dbObj = IDBFactory::getDB();
        $table1 = IWeb::$app->config['DB']['tablePre'] . 'order';
        $table2 = IWeb::$app->config['DB']['tablePre'] . 'order_goods';
        $ret = $dbObj->doSql('SELECT a.*, b.goods_id, b.real_price, b.goods_nums,b.goods_price, b.img, b.goods_array FROM ' . $table1 . ' a inner join ' . $table2 . ' b on a.id = b.order_id WHERE a.user_id in (' . $user_id . ') AND a.if_del = 0 group by a.id order by a.create_time desc');
        //var_dump($ret);
        return $ret;
    }

    /*
     * 取发票类型
     */

    public function getInvoiceType($type) {
        switch ($type) {
            case 2: return "单位";
            default: return "个人";
        }
    }

    /*
     * 根据订单号取服务订单号
     */

    public function getServiceOrder($order_id) {
        $dbObj = IDBFactory::getDB();
        $tableName = IWeb::$app->config['DB']['tablePre'] . 'order_service';
        $ret = $dbObj->doSql('SELECT * FROM ' . $tableName . ' WHERE order_id in (' . $order_id . ') ');
        //var_dump($ret);
        return $ret[0];
    }

    /**
     * 取服务单类型文字
     */
    public function getServiceType($type) {
        switch ($type) {
            case 1: return "申请退货";
            case 2: return "申请换货";
            default: return "申请维修";
        }
    }

    //获取服务单状态文字
    public function getServiceStatusText($status) {
        switch ($status) {
            case 1: {
                    return '提交服务单';
                }
                break;
            case 2: {
                    return '已确认';
                }
                break;
            case 3: {
                    return '已受理';
                }
                break;
            case 4: {
                    return '办理售后';
                }
                break;
            case 5: {
                    return '配货';
                }
                break;
            case 6: {
                    return '出货';
                }
                break;
            default: {
                    return '已完成';
                }
                break;
        }
    }

}
