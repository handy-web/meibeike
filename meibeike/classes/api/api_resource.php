<?php
return array(
	'getGoodsList'    => array('file' => 'goods.php','class'=> 'APIGoods',),
	'getArtList'      => array('query' => array('name' => 'article','where' => 'visibility = 1 and top = 1','order' => 'sort ASC,id DESC','fields'=> 'title,id,style,color','limit' => '10')),
	'getRegimentList' => array('query' => array('name' => 'regiment','where' => 'is_close = 0 and NOW() between start_time and end_time','fields' => 'id,title,regiment_price,img','order' => 'id desc','limit' => '10')),
	'getPromotionList'=> array('query' => array('name' => 'promotion as p','join' => 'left join goods as go on go.id = p.condition','fields'=>'p.end_time,go.img as img,p.name as name,p.award_value as award_value,go.id as goods_id,p.id as p_id,end_time','where'=>'p.type = 1 and p.is_close = 0 and go.is_del = 0 and NOW() between start_time and end_time AND go.id is not null','order'=>'p_id desc','limit'=>'10')),
	'getCommendNew'   => array('query' => array('name' => 'commend_goods as co','join' => 'left join goods as go on co.goods_id = go.id','where' => 'co.commend_id = 1 and go.is_del = 0 AND go.id is not null','fields' => 'go.img,go.sell_price,go.name,go.id','limit'=>'10','order'=>'sort asc,id desc')),
	'getCommendPrice' => array('query' => array('name' => 'commend_goods as co','join' => 'left join goods as go on co.goods_id = go.id','where' => 'co.commend_id = 2 and go.is_del = 0 AND go.id is not null','fields' => 'go.img,go.sell_price,go.name,go.id','limit'=>'10','order'=>'sort asc,id desc')),
	'getCommendHot'   => array('query' => array('name' => 'commend_goods as co','join' => 'left join goods as go on co.goods_id = go.id','where' => 'co.commend_id = 3 and go.is_del = 0 AND go.id is not null','fields' => 'go.img,go.sell_price,go.name,go.id','limit'=>'10','order'=>'sort asc,id desc')),
	'getCommendRecom' => array('query' => array('name' => 'commend_goods as co','join' => 'left join goods as go on co.goods_id = go.id','where' => 'co.commend_id = 4 and go.is_del = 0 AND go.id is not null','fields' => 'go.img,go.sell_price,go.name,go.id','limit'=>'10','order'=>'sort asc,id desc')),
	'getOrderDistributed' => array('query' => array('name' => 'order','where' => 'distribution_status = 1 and if_del = 0','limit' => '10','order' => 'id desc')),
        'getDeliveryList'=>array('query'=>array("name"=>"delivery_doc as c", "join"=>"left join freight_company as f on c.freight_id = f.id", 'where'=>'c.order_id = #oid# and c.if_del=0 ',"fields"=>"c.id,c.delivery_code,c.order_type,f.freight_name","order"=>"c.time asc")),
        'getUserInfo' => array('query'=>array('name'=>"user", 'where'=>'id = #uid#')),
    
        'getMyOrderCnt' => array('file' => 'order.php', 'class' => 'APIOrder'),
        'getMyOrderList' => array('file' => 'order.php', 'class' => 'APIOrder'),
        'getInvoiceType' => array('file' => 'order.php', 'class' => 'APIOrder'),
        'getServiceOrder' => array('file' => 'order.php', 'class' => 'APIOrder'),
        'getServiceType' => array('file' => 'order.php', 'class' => 'APIOrder'),
);