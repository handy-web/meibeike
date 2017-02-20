<?php
/**
 * @brief 营销模块
 * @class Market
 * @note  后台
 */
class Market extends IController
{
	public $checkRight  = 'all';
	public $layout = 'admin';
	private $ticketDir = 'backup/ticket';

	function init()
	{
		IInterceptor::reg('CheckRights@onCreateAction');
	}

	//修改代金券状态is_close和is_send
	function ticket_status()
	{
		$status    = IReq::get('status');
		$id        = IReq::get('id');
		$ticket_id = IReq::get('ticket_id');

		if(!empty($id) && $status != null && $ticket_id != null)
		{
			$ticketObj = new IModel('prop');
			if(is_array($id))
			{
				foreach($id as $val)
				{
					$where = 'id = '.$val;
					$ticketRow = $ticketObj->getObj($where,$status);
					if($ticketRow[$status]==1)
					{
						$ticketObj->setData(array($status => 0));
					}
					else
					{
						$ticketObj->setData(array($status => 1));
					}
					$ticketObj->update($where);
				}
			}
			else
			{
				$where = 'id = '.$id;
				$ticketRow = $ticketObj->getObj($where,$status);
				if($ticketRow[$status]==1)
				{
					$ticketObj->setData(array($status => 0));
				}
				else
				{
					$ticketObj->setData(array($status => 1));
				}
				$ticketObj->update($where);
			}
			$this->redirect('ticket_more_list/ticket_id/'.$ticket_id);
		}
		else
		{
			$this->ticket_id = $ticket_id;
			$this->redirect('ticket_more_list',false);
			Util::showMessage('请选择要修改的id值');
		}
	}

	//[代金券]获取代金券数量
	function getTicketCount($propObj,$id)
	{
		$where     = '`condition` = "'.$id.'"';
		$propCount = $propObj->getObj($where,'count(*) as count');
		return $propCount['count'];
	}

	//[代金券]添加,修改[单页]
	function ticket_edit()
	{
		$id = IReq::get('id');
		if($id)
		{
			$ticketObj       = new IModel('ticket');
			$where           = 'id = '.$id;
			$this->ticketRow = $ticketObj->getObj($where);
		}
		$this->redirect('ticket_edit');
	}

	//[代金券]添加,修改[动作]
	function ticket_edit_act()
	{
		$id        = IReq::get('id');
		$ticketObj = new IModel('ticket');

		$dataArray = array(
			'name'      => IReq::get('name','post'),
			'value'     => IReq::get('value','post'),
			'start_time'=> IReq::get('start_time','post'),
			'end_time'  => IReq::get('end_time','post'),
			'point'  => IReq::get('point','post'),
		);

		$ticketObj->setData($dataArray);
		if($id)
		{
			$where = 'id = '.$id;
			$ticketObj->update($where);
		}
		else
		{
			$ticketObj->add();
		}
		$this->redirect('ticket_list');
	}

	//[代金券]生成[动作]
	function ticket_create()
	{
		$propObj   = new IModel('prop');
		$prop_num  = intval(IReq::get('num'));
		$ticket_id = intval(IReq::get('ticket_id'));

		if($prop_num && $ticket_id)
		{
			$prop_num  = ($prop_num > 5000) ? 5000 : $prop_num;
			$ticketObj = new IModel('ticket');
			$where     = 'id = '.$ticket_id;
			$ticketRow = $ticketObj->getObj($where);

			for($item = 0; $item < intval($prop_num); $item++)
			{
				$dataArray = array(
					'condition' => $ticket_id,
					'name'      => $ticketRow['name'],
					'card_name' => 'T'.IHash::random(8),
					'card_pwd'  => IHash::random(8),
					'value'     => $ticketRow['value'],
					'start_time'=> $ticketRow['start_time'],
					'end_time'  => $ticketRow['end_time'],
				);

				//判断code码唯一性
				$where = 'card_name = \''.$dataArray['card_name'].'\'';
				$isSet = $propObj->getObj($where);
				if(!empty($isSet))
				{
					$item--;
					continue;
				}
				$propObj->setData($dataArray);
				$propObj->add();
			}
			$logObj = new Log('db');
			$logObj->write('operation',array("管理员:".$this->admin['admin_name'],"生成了代金券","面值：".$ticketRow['value']."元，数量：".$prop_num."张"));
		}
		$this->redirect('ticket_list');
	}

	//[代金券]删除
	function ticket_del()
	{
		$id = IFilter::act(IReq::get('id'),'int');
		if(!empty($id))
		{
			$ticketObj = new IModel('ticket');
			$propObj   = new IModel('prop');
			$propRow   = $propObj->getObj(" `type` = 0 and `condition` = {$id} and (is_close = 2 or (is_userd = 0 and is_send = 1)) ");

			if($propRow)
			{
				$this->redirect('ticket_list',false);
				Util::showMessage('无法删除代金券，其下还有正在使用的代金券');
				exit;
			}

			$where = "id = {$id} ";
			$ticketRow = $ticketObj->getObj($where);
			if($ticketObj->del($where))
			{
				$where = " `type` = 0 and `condition` = {$id} ";
				$propObj->del($where);

				$logObj = new Log('db');
				$logObj->write('operation',array("管理员:".$this->admin['admin_name'],"删除了一种代金券","代金券名称：".$ticketRow['name']));
			}
			$this->redirect('ticket_list');
		}
		else
		{
			$this->redirect('ticket_list',false);
			Util::showMessage('请选择要删除的id值');
		}
	}

	//[代金券详细]删除
	function ticket_more_del()
	{
		$id        = IReq::get('id');
		$ticket_id = IReq::get('ticket_id');
		if(!empty($id))
		{
			$ticketObj = new IModel('ticket');
			$ticketRow = $ticketObj->getObj('id = '.$ticket_id);
			$logObj    = new Log('db');
			$propObj   = new IModel('prop');
			if(is_array($id))
			{
				$idStr = join(',',$id);
				$where = ' id in ('.$idStr.')';
				$logObj->write('operation',array("管理员:".$this->admin['admin_name'],"批量删除了实体代金券","代金券名称：".$ticketRow['name']."，数量：".count($id)));
			}
			else
			{
				$where = 'id = '.$id;
				$logObj->write('operation',array("管理员:".$this->admin['admin_name'],"删除了1张实体代金券","代金券名称：".$ticketRow['name']));
			}
			$propObj->del($where);
			$this->redirect('ticket_more_list/ticket_id/'.$ticket_id);
		}
		else
		{
			$this->ticket_id = $ticket_id;
			$this->redirect('ticket_more_list',false);
			Util::showMessage('请选择要删除的id值');
		}
	}

	//[代金券详细] 列表
	function ticket_more_list()
	{
		$this->ticket_id = IReq::get('ticket_id');
		$this->redirect('ticket_more_list');
	}

	//[代金券] 输出excel表格
	function ticket_excel()
	{
		//代金券excel表存放地址
		$fileName = $this->ticketDir.'/t'.date('YmdHis').IHash::random(8).'.xls';
		$ticket_id = IReq::get('id');
		$ticket_id_array = array();

		if(!empty($ticket_id))
		{
			$excelStr = '<table><tr><th>名称</th><th>卡号</th><th>密码</th><th>面值</th>
			<th>已被使用</th><th>是否关闭</th><th>是否发送</th><th>开始时间</th><th>结束时间</th></tr>';

			$propObj = new IModel('prop');
			$where   = 'type = 0';
			if(is_array($ticket_id))
			{
				$ticket_id_array = $ticket_id;
			}
			else
			{
				$ticket_id_array[] = $ticket_id;
			}

			//当代金券数量没有时不允许备份excel
			foreach($ticket_id_array as $key => $tid)
			{
				if($this->getTicketCount($propObj,$tid) == 0)
				{
					unset($ticket_id_array[$key]);
				}
			}

			if($ticket_id_array)
			{
				$id_num_str = join('","',$ticket_id_array);
			}
			else
			{
				$this->redirect('ticket_list',false);
				Util::showMessage('实体代金券数量为0张，无法备份');
				exit;
			}

			$where.= ' and `condition` in("'.$id_num_str.'")';

			$propList = $propObj->query($where,'*','`condition`','asc','10000');
			foreach($propList as $key => $val)
			{
				$is_userd = ($val['is_userd']=='1') ? '是':'否';
				$is_close = ($val['is_close']=='1') ? '是':'否';
				$is_send  = ($val['is_send']=='1') ? '是':'否';

				$excelStr.='<tr>';
				$excelStr.='<td>'.$val['name'].'</td>';
				$excelStr.='<td>'.$val['card_name'].'</td>';
				$excelStr.='<td>'.$val['card_pwd'].'</td>';
				$excelStr.='<td>'.$val['value'].' 元</td>';
				$excelStr.='<td>'.$is_userd.'</td>';
				$excelStr.='<td>'.$is_close.'</td>';
				$excelStr.='<td>'.$is_send.'</td>';
				$excelStr.='<td>'.$val['start_time'].'</td>';
				$excelStr.='<td>'.$val['end_time'].'</td>';
				$excelStr.='</tr>';
			}
			$excelStr.='</table>';

			$fileObj = new IFile($fileName,'w+');
			$fileObj->write($excelStr);
			$this->ticket_excel_list();
		}
		else
		{
			$this->redirect('ticket_list',false);
			Util::showMessage('请选择要操作的文件');
		}
	}

	//[代金券] 展示excel文件
	function ticket_excel_list()
	{
		IFile::mkdir($this->ticketDir);

		$dirArray = array();
		$dirRes   = opendir($this->ticketDir);
		while($fileName = readdir($dirRes))
		{
			if(!in_array($fileName,IFile::$except))
			{
				$dirArray[$fileName]['name'] = $fileName;
				$dirArray[$fileName]['size'] = filesize($this->ticketDir.'/'.$fileName);
				$dirArray[$fileName]['time'] = date('Y-m-d',fileatime($this->ticketDir.'/'.$fileName));
			}
		}
		$this->dirArray = $dirArray;
		$this->redirect('ticket_excel_list',false);
	}

	//[代金券] excel文件删除
	function ticket_excel_del()
	{
		$id = IReq::get('id');
		if(!empty($id))
		{
			if(is_array($id))
			{
				foreach($id as $val)
				{
					IFile::unlink($this->ticketDir.'/'.$val);
				}
			}
			else
			{
				IFile::unlink($this->ticketDir.'/'.$id);
			}
			$this->ticket_excel_list();
		}
		else
		{
			$this->ticket_excel_list();
			Util::showMessage('请选择要删除的文件');
		}
	}

	//[代金券] excel文件下载
	function ticket_excel_download($fileName = null)
	{
		if($fileName==null)
		{
			$file = IFilter::act(IReq::get('file'),'filename');
		}
		else
		{
			$file = $fileName;
		}

		if($file != null)
		{
			header('Content-Description: File Transfer');
			header('Content-Length: '.filesize($this->ticketDir.'/'.$file));
			header('Content-Disposition: attachment; filename='.basename($file));
			readfile($this->ticketDir.'/'.$file);
		}
	}

	//[代金券] excel打包
	function ticket_excel_pack()
	{
		if(class_exists('ZipArchive'))
		{
			//获取要打包的文件数组
			$fileArray = IReq::get('id');
			if(!empty($fileArray))
			{
				$fileName = 'T_'.date('YmdHis').IHash::random(8).'.zip';
				$zip = new ZipArchive();
				$zip->open($this->ticketDir.'/'.$fileName,ZIPARCHIVE::CREATE);
				foreach($fileArray as $file)
				{
					$attachfile = $this->ticketDir.'/'.$file;
					$zip->addFile($attachfile,basename($attachfile));
				}
				$zip->close();
				$this->ticket_excel_download($fileName);
				@unlink($this->ticketDir.'/'.$fileName);
			}
			else
			{
				$this->ticket_excel_list();
				Util::showMessage('请选择要打包的文件');
			}
		}
		else
		{
			$this->ticket_excel_list();
			Util::showMessage('您的php环境没有打包工具类库');
		}
	}

	//[代金券]获取代金券数据
	function getTicketList()
	{
		$ticketObj  = new IModel('ticket');
		$ticketList = $ticketObj->query();
		echo JSON::encode($ticketList);
	}

	//[促销活动] 添加修改 [单页]
	function pro_rule_edit()
	{
		$id = IReq::get('id');
		if($id)
		{
			$promotionObj = new IModel('promotion');
			$where = 'id = '.$id;
			$this->promotionRow = $promotionObj->getObj($where);
		}
		$this->redirect('pro_rule_edit');
	}

	//[促销活动] 添加修改 [动作]
	function pro_rule_edit_act()
	{
		$id = IReq::get('id');
		$promotionObj = new IModel('promotion');

		$group_all    = IReq::get('group_all','post');
		if($group_all == 'all')
		{
			$user_group_str = 'all';
		}
		else
		{
			$user_group = IReq::get('user_group','post');
			$user_group_str = '';
			if(!empty($user_group))
			{
				$user_group_str = join(',',$user_group);
				$user_group_str = ','.$user_group_str.',';
			}
		}

		$dataArray = array(
			'name'       => IReq::get('name','post'),
			'condition'  => IReq::get('condition','post'),
			'is_close'   => IReq::get('is_close','post'),
			'start_time' => IReq::get('start_time','post'),
			'end_time'   => IReq::get('end_time','post'),
			'intro'      => IFilter::act(IReq::get('intro','post'),'text'),
			'award_type' => IReq::get('award_type','post'),
			'type'       => 0,
			'user_group' => $user_group_str,
			'award_value'=> IReq::get('award_value','post'),
		);

		$promotionObj->setData($dataArray);

		if($id)
		{
			$where = 'id = '.$id;
			$promotionObj->update($where);
		}
		else
		{
			$promotionObj->add();
		}
		$this->redirect('pro_rule_list');
	}

	//[促销活动] 删除
	function pro_rule_del()
	{
		$id = IReq::get('id');
		if(!empty($id))
		{
			$promotionObj = new IModel('promotion');
			if(is_array($id))
			{
				$idStr = join(',',$id);
				$where = ' id in ('.$idStr.')';
			}
			else
			{
				$where = 'id = '.$id;
			}
			$promotionObj->del($where);
			$this->redirect('pro_rule_list');
		}
		else
		{
			$this->redirect('pro_rule_list',false);
			Util::showMessage('请选择要删除的促销活动');
		}
	}

	//[限时抢购]添加,修改[单页]
	function pro_speed_edit()
	{
		$id = IFilter::act(IReq::get('id'),'int');
		if($id)
		{
			$promotionObj = new IModel('promotion');
			$where = 'id = '.$id;
			$promotionRow = $promotionObj->getObj($where);
			if(empty($promotionRow))
			{
				$this->redirect('pro_speed_list');
			}

			//促销商品
			$goodsObj = new IModel('goods');
			$goodsRow = $goodsObj->getObj('id = '.$promotionRow['condition'],'id,name,sell_price,img');
			if($goodsRow)
			{
				$result = array(
					'isError' => false,
					'data'    => $goodsRow,
				);
			}
			else
			{
				$result = array(
					'isError' => true,
					'message' => '关联商品被删除，请重新选择要抢购的商品',
				);
			}

			$promotionRow['goodsRow'] = JSON::encode($result);
			$this->promotionRow = $promotionRow;
		}
		$this->redirect('pro_speed_edit');
	}

	//[限时抢购]添加,修改[动作]
	function pro_speed_edit_act()
	{
		$id = IReq::get('id');

		$condition    = IReq::get('condition','post');
		$award_value  = IReq::get('award_value','post');
		$group_all    = IReq::get('group_all','post');
		if($group_all == 'all')
		{
			$user_group_str = 'all';
		}
		else
		{
			$user_group = IReq::get('user_group','post');
			$user_group_str = '';
			if(!empty($user_group))
			{
				$user_group_str = join(',',$user_group);
				$user_group_str = ','.$user_group_str.',';
			}
		}

		$dataArray = array(
			'id'         => $id,
			'name'       => IReq::get('name','post'),
			'condition'  => $condition,
			'award_value'=> $award_value,
			'is_close'   => IReq::get('is_close','post'),
			'start_time' => IReq::get('start_time','post'),
			'end_time'   => IReq::get('end_time','post'),
			'intro'      => IReq::get('intro','post'),
			'type'       => 1,
			'award_type' => 0,
			'user_group' => $user_group_str,
		);

		if(!$condition || !$award_value)
		{
			$this->promotionRow = $dataArray;
			$this->redirect('pro_speed_edit',false);
			Util::showMessage('请添加促销的商品，并为商品填写价格');
		}

		$proObj = new IModel('promotion');
		$proObj->setData($dataArray);
		if($id)
		{
			$where = 'id = '.$id;
			$proObj->update($where);
		}
		else
		{
			$proObj->add();
		}
		$this->redirect('pro_speed_list');
	}

	//[限时抢购]删除
	function pro_speed_del()
	{
		$id = IReq::get('id');
		if(!empty($id))
		{
			$propObj = new IModel('promotion');
			if(is_array($id))
			{
				$idStr = join(',',$id);
				$where = ' id in ('.$idStr.')';
			}
			else
			{
				$where = 'id = '.$id;
			}
			$propObj->del($where);
			$this->redirect('pro_speed_list');
		}
		else
		{
			$this->redirect('pro_speed_list',false);
			Util::showMessage('请选择要删除的id值');
		}
	}

	//[团购]添加修改[单页]
	function regiment_edit()
	{
		$id = IFilter::act(IReq::get('id'),'int');

		if($id)
		{
			$regimentObj = new IModel('regiment');
			$where       = 'id = '.$id;
			$regimentRow = $regimentObj->getObj($where);
			if(!$regimentRow)
			{
				$this->redirect('regiment_list');
			}

			//促销商品
			$goodsObj = new IModel('goods');
			$goodsRow = $goodsObj->getObj('id = '.$regimentRow['goods_id']);

			$result = array(
				'isError' => false,
				'data'    => $goodsRow,
			);
			$regimentRow['goodsRow'] = JSON::encode($result);
			$this->regimentRow = $regimentRow;
		}
		$this->redirect('regiment_edit');
	}

	//[团购]添加修改[动作]
	function regiment_edit_act()
	{
		$id      = IFilter::act(IReq::get('id'),'int');
		$goodsId = IFilter::act(IReq::get('goods_id'),'int');

		$dataArray = array(
			'id'        	=> $id,
			'title'     	=> IFilter::act(IReq::get('title','post')),
			'start_time'	=> IFilter::act(IReq::get('start_time','post')),
			'end_time'  	=> IFilter::act(IReq::get('end_time','post')),
			'is_close'      => IFilter::act(IReq::get('is_close','post')),
			'intro'     	=> IFilter::act(IReq::get('intro','post')),
			'goods_id'      => $goodsId,
			'store_nums'    => IFilter::act(IReq::get('store_nums','post')),
			'least_count'   => IFilter::act(IReq::get('least_count','post')),
			'regiment_price'=> IFilter::act(IReq::get('regiment_price','post')),
			'sort'          => IFilter::act(IReq::get('sort','post')),
		);

		if($goodsId)
		{
			$goodsObj = new IModel('goods');
			$where    = 'id = '.$goodsId;
			$goodsRow = $goodsObj->getObj($where);

			//处理上传图片
			if(isset($_FILES['img']['name']) && $_FILES['img']['name'] != '')
			{
				$uploadObj = new PhotoUpload();
				$photoInfo = $uploadObj->run();
				$dataArray['img'] = $photoInfo['img']['img'];
			}
			else
			{
				$dataArray['img'] = $goodsRow['img'];
			}

			$dataArray['sell_price'] = $goodsRow['sell_price'];
		}
		else
		{
			$this->regimentRow = $dataArray;
			$this->redirect('regiment_edit',false);
			Util::showMessage('请选择要关联的商品');
		}

		$regimentObj = new IModel('regiment');
		$regimentObj->setData($dataArray);

		if($id)
		{
			$where = 'id = '.$id;
			$regimentObj->update($where);
		}
		else
		{
			$regimentObj->add();
		}
		$this->redirect('regiment_list');
	}

	//[团购]删除
	function regiment_del()
	{
		$id = IReq::get('id');
		if(!empty($id))
		{
			$regObj     = new IModel('regiment');
			$regUserObj = new IModel('regiment_user_relation');

			if(is_array($id))
			{
				$idStr = join(',',$id);
				$where = ' id in ('.$idStr.')';
				$uwhere= ' regiment_id in ('.$idStr.')';
			}
			else
			{
				$where  = 'id = '.$id;
				$uwhere = 'regiment_id = '.$id;
			}
			$regObj->del($where);
			$regUserObj->del($uwhere);
			$this->redirect('regiment_list');
		}
		else
		{
			$this->redirect('regiment_list',false);
			Util::showMessage('请选择要删除的id值');
		}
	}

	//账户余额记录
	function account_list()
	{
		$page       = intval(IReq::get('page')) ? IReq::get('page') : 1;
		$event      = intval(IReq::get('event'));
		$startDate  = IFilter::act(IReq::get('startDate'));
		$endDate    = IFilter::act(IReq::get('endDate'));

		$where      = "event != 3";
		if($startDate)
		{
			$where .= " and time >= '{$startDate}' ";
		}

		if($endDate)
		{
			$temp   = $endDate.' 23:59:59';
			$where .= " and time <= '{$temp}' ";
		}

		if($event)
		{
			$where .= " and event = $event ";
		}

		$accountObj = new IQuery('account_log');
		$accountObj->where = $where;
		$accountObj->order = 'id desc';
		$accountObj->page  = $page;

		$this->accountObj  = $accountObj;
		$this->event       = $event;
		$this->startDate   = $startDate;
		$this->endDate     = $endDate;
		$this->accountList = $accountObj->find();

		$this->redirect('account_list');
	}

	//后台操作记录
	function operation_list()
	{
		$page       = intval(IReq::get('page')) ? IReq::get('page') : 1;
		$startDate  = IFilter::act(IReq::get('startDate'));
		$endDate    = IFilter::act(IReq::get('endDate'));

		$where      = "1";
		if($startDate)
		{
			$where .= " and datetime >= '{$startDate}' ";
		}

		if($endDate)
		{
			$temp   = $endDate.' 23:59:59';
			$where .= " and datetime <= '{$temp}' ";
		}

		$operationObj = new IQuery('log_operation');
		$operationObj->where = $where;
		$operationObj->order = 'id desc';
		$operationObj->page  = $page;

		$this->operationObj  = $operationObj;
		$this->startDate     = $startDate;
		$this->endDate       = $endDate;
		$this->operationList = $operationObj->find();

		$this->redirect('operation_list');
	}

	//清理后台管理员操作日志
	function clear_log()
	{
		$type  = IReq::get('type');
		$month = intval(IReq::get('month'));
		if(!$month)
		{
			die('请填写要清理日志的月份');
		}

		$diffSec = 3600*24*30*$month;
		$lastTime= strtotime(date('Y-m')) - $diffSec;
		$dateStr = date('Y-m',$lastTime);

		switch($type)
		{
			case "account":
			{
				$logObj = new IModel('account_log');
				$logObj->del("time <= '{$dateStr}'");
				$this->redirect('account_list');
				break;
			}
			case "operation":
			{
				$logObj = new IModel('log_operation');
				$logObj->del("datetime <= '{$dateStr}'");
				$this->redirect('operation_list');
				break;
			}
			default:
				die('缺少类别参数');
		}
	}

	//修改结算账单
	public function bill_edit()
	{
		$id = IFilter::act(IReq::get('id'),'int');
		$billDB = new IModel('bill');
		$this->billRow = $billDB->getObj('id = '.$id);
		$this->redirect('bill_edit');
	}

	//结算单修改
	public function bill_update()
	{
		$id = IFilter::act(IReq::get('id'),'int');
		$pay_content = IFilter::act(IReq::get('pay_content'));
		$is_pay = IFilter::act(IReq::get('is_pay'),'int');

		if($id)
		{
			$data = array(
				'admin_id' => $this->admin['admin_id'],
				'pay_content' => $pay_content,
				'is_pay' => $is_pay,
			);

			$billDB = new IModel('bill');

			$data['pay_time'] = ($is_pay == 1) ? date('Y-m-d H:i:s') : "";

			$billRow= $billDB->getObj('id = '.$id);
			if(isset($billRow['order_goods_ids']) && $billRow['order_goods_ids'])
			{
				//更新订单商品关系表中的结算字段
				$orderGoodsDB = new IModel('order_goods');
				$orderGoodsIdArray = explode(',',$billRow['order_goods_ids']);
				foreach($orderGoodsIdArray as $key => $val)
				{
					$orderGoodsDB->setData(array('is_checkout' => $is_pay));
					$orderGoodsDB->update('id = '.$val);
				}
			}

			$billDB->setData($data);
			$billDB->update('id = '.$id);
		}
		$this->redirect('bill_list');
	}

	//结算单删除
	public function bill_del()
	{
		$id = IFilter::act(IReq::get('id'),'int');

		if($id)
		{
			$billDB = new IModel('bill');
			$billDB->del('id = '.$id.' and is_pay = 0');
		}

		$this->redirect('bill_list');
	}
	
	/***********************************F码代码 zhaopeijun *********************************/
	public function add_fm_activity(){
		$this->redirect('add_fm_activity');
	}
	
	public function fm_activity_edit(){
		$data['activity_name'] = IFilter::act(IReq::get('activity_name','post'));
		$data['min_code'] = IFilter::act(IReq::get('min_code','post'));
		$data['max_code'] = IFilter::act(IReq::get('max_code','post'));
		$data['fm_count'] = IFilter::act(IReq::get('fm_count','post'));
		$data['start_time'] = IFilter::act(IReq::get('start_time','post'));
		$data['end_time'] = IFilter::act(IReq::get('end_time','post'));
		$data['prize_type'] = IFilter::act(IReq::get('prize_type','post'));
		$data['prize_limit'] = IFilter::act(IReq::get('prize_limit','post'));
		$data['prize_comment'] = IFilter::act(IReq::get('prize_comment','post'));
		$data['exchange_prize_type'] = IFilter::act(IReq::get('exchange_prize_type','post'));
		$data['exchange_prize_limit'] = IFilter::act(IReq::get('exchange_prize_limit','post'));
		$data['exchange_prize_comment'] = IFilter::act(IReq::get('exchange_prize_comment','post'));
		$data['exchange_count'] = IFilter::act(IReq::get('exchange_count','post'));
		$data['coupon_start_time'] = IFilter::act(IReq::get('coupon_start_time','post'));
		$data['coupon_end_time'] = IFilter::act(IReq::get('coupon_end_time','post'));
		$data['add_time'] = ITime::getDateTime();
		$fid = IFilter::act(IReq::get('fid','get'));
		$fm_model = new IModel('fm_activity');
		if(empty($fid)){
			$fm_model->setData($data);
			$res = $fm_model->add();
				if($res){
					echo '<script>alert("添加成功")</script>';
					echo '<script>window.location.href="/market/fm_activity_list"</script>';
				}else{
					echo '<script>alert("添加失败")</script>';
					echo '<script>window.history.go(-1)</script>';
				}
		}else{
			$fm_model->setData($data);
			$r = $fm_model->update("id={$fid}");
			if($r){
				echo '<script>alert("保存成功")</script>';
				echo '<script>window.history.go(-1)</script>';
			}else{
				echo '<script>alert("保存失败")</script>';
				echo '<script>window.history.go(-1)</script>';
			}
		}
		
	}
	
	public function fm_activity_list(){
		$out_status = IFilter::act(IReq::get('out_status'));
		$review_status = IFilter::act(IReq::get('review_status'));
		$current_time = time();
		$where = "";
		if($out_status == '0'){		
			$where = "unix_timestamp(start_time)<{$current_time} and unix_timestamp(end_time)>{$current_time}";
		}else if($out_status == '1'){
			$where = "unix_timestamp(start_time)>{$current_time} or unix_timestamp(end_time)<{$current_time}";
		}
		
		if($review_status == '0'){
			if(!empty($where)) $where.= " and status = {$review_status}";
			else $where.= "status = {$review_status}";
		}else if($review_status == '1'){
			if(!empty($where)) $where.= " and status = {$review_status}";
			else $where.= "status = {$review_status}";
		}else if($review_status == '2'){
			if(!empty($where)) $where.= " and status = {$review_status}";
			else $where.= "status = {$review_status}";
		}
		$fObj = new IQuery('fm_activity f');
		$fObj->fields = 'f.id,f.activity_name,f.min_code,f.max_code,f.fm_count,f.start_time,f.end_time,f.status';
		if(!empty($where) && is_string($where)){
			$fObj->where = $where ;
		}
		$res = $fObj->find();
		if(!empty($res) && is_array($res)){
			foreach($res as &$val){
				if($current_time > strtotime($val['start_time']) && $current_time < strtotime($val['end_time'])){
					$val['out_status'] = 0;//活动未过期
				}else{
					$val['out_status'] = 1;//活动已过期
				}
			}
		}
		$fm['fm_activity_list'] = $res ;
		$fm['out_status'] = $out_status;
		$fm['review_status'] = $review_status;
		$this->setRenderData($fm);
		$this->redirect('fm_activity_list');
	}
	
	public function fm_activity_detail(){
		$id = IFilter::act(IReq::get('id'));
		$aModel = new IModel('fm_activity');
		$where = "id = {$id}";
		$data['detail'] = $aModel->getObj($where);
		$this->setRenderData($data);
		$this->redirect('fm_activity_detail');
	}
	
	
	public function fm_activity_review_list(){
		$page = IReq::get('page');
		if (! $page) {
			$page = 1;
		}
		$out_status = IFilter::act(IReq::get('out_status'));
		$review_status = IFilter::act(IReq::get('review_status'));
		$current_time = time();
		$where = "";
		if($out_status == '0'){		
			$where = "unix_timestamp(start_time)<{$current_time} and unix_timestamp(end_time)>{$current_time}";
		}else if($out_status == '1'){
			$where = "unix_timestamp(start_time)>{$current_time} or unix_timestamp(end_time)<{$current_time}";
		}
		
		if($review_status == '0'){
			if(!empty($where)) $where.= " and status = {$review_status}";
			else $where.= "status = {$review_status}";
		}else if($review_status == '1'){
			if(!empty($where)) $where.= " and status = {$review_status}";
			else $where.= "status = {$review_status}";
		}else if($review_status == '2'){
			if(!empty($where)) $where.= " and status = {$review_status}";
			else $where.= "status = {$review_status}";
		}
		$fObj = new IQuery('fm_activity f');
		if(!empty($where) && is_string($where)){
			$fObj->where = $where ;
		}
		$fObj->fields = "count(*) total";
		$count = $fObj->find();
		$total = $count[0]['total'];
		
		$fObj->fields = 'f.id,f.activity_name,f.min_code,f.max_code,f.fm_count,f.start_time,f.end_time,f.status';
		$fObj->page = $page;
		$fObj->pagesize = 20;
		$fObj->pagelength = total;		
		$res = $fObj->find();
		$page = $fObj->getPageBar();
		if(!empty($res) && is_array($res)){
			foreach($res as &$val){
				if($current_time > strtotime($val['start_time']) && $current_time < strtotime($val['end_time'])){
					$val['out_status'] = 0;//活动未过期
				}else{
					$val['out_status'] = 1;//活动已过期
				}
			}
		}
		$fm['fm_activity_list'] = $res ;
		$fm['page'] = $page;
		$fm['out_status'] = $out_status;
		$fm['review_status'] = $review_status;
		$this->setRenderData($fm);
		$this->redirect('fm_activity_review_list');
	}
	
	
	public function fm_activity_review(){
		$id = IFilter::act(IReq::get('id'));
		$status = IFilter::act(IReq::get('status'));
		if(!empty($id)){		
			$data['status'] = $status;
			$data['admin_id'] = $this->admin['admin_id'];
			$obj = new IModel('fm_activity');
			$obj->setData($data);
			$r = $obj->update("id = {$id}");
			if($r){
				if($status == '1'){
					//审核通过生成f码
					$fQuery = new IQuery('fm f');
					$fQuery->fields = "f.f_code";
					$res = $fQuery->find();
					if(empty($res)){
						$fArr = array();
					}else{
						foreach($res as $val){
							$fArr[] = $val['f_code'];
						}
					}
					
					$fModel = new IModel('fm_activity');
					$where = "id = {$id}";
					$data = $fModel->getObj($where);
					$randModel = new probability();
					$type = 'F';
					$randArr = $randModel->createRand($type,$data['min_code'],$data['max_code'],$data['fm_count'],$fArr);
					foreach($randArr as $key=>$val){
						$fmArr[$key]['f_code'] = $val;
						$fmArr[$key]['aid'] = $data['id'];
					}
					$fModel = new IModel('fm');
					$fModel->setData($fmArr);
					$insertRes = $fModel->addAll();
					if($insertRes){
						echo '<script>alert("审核成功")</script>';
						echo '<script>window.history.go(-1)</script>';
					}else{
						echo '<script>alert("审核失败")</script>';
						echo '<script>window.history.go(-1)</script>';
					}
				}else if($status == '2'){
					echo '<script>alert("审核成功")</script>';
					echo '<script>window.history.go(-1)</script>';
				}

			}else{
				echo '<script>alert("审核失败")</script>';
				echo '<script>window.history.go(-1)</script>';
			}
		}
	}
	
	public function fm_list(){
		$aid = IFilter::act(IReq::get('aid'));
		$activity_name = IFilter::act(IReq::get('activity_name'));
		$send_status = IFilter::act(IReq::get('send_status'));
		$exchange_status = IFilter::act(IReq::get('exchange_status'));
		$use_status = IFilter::act(IReq::get('use_status'));
		$fQuery = new IQuery('fm f'); 
		$fQuery->join = "inner join mbk_fm_activity fa on fa.id = f.aid left join mbk_user u on f.owner_meiid = u.id";
		$fQuery->fields = 'f.*,u.email,u.mobile,fa.start_time,fa.end_time';
		$where = "aid = {$aid}";
		if($use_status == '0'){
			$where.=" and order_number is  null or order_number = ''";
		}else if($use_status=='1'){
			$where.= " and order_number is not null and order_number <> ''";
		}
		
		if($send_status != ''){
			$where.= " and send_status = {$send_status}";
		}

		if($exchange_status != ''){
			$where.= " and exchange_status = {$exchange_status}";
		}
		$fQuery->where = $where;
		$res = $fQuery->find();
		$data['fm_list'] = $res;
		$data['activity_name'] = $activity_name;
		$data['aid'] = $aid;
		$data['send_status'] = $send_status;
		$data['exchange_status'] = $exchange_status;
		$data['use_status'] = $use_status;
		$this->setRenderData($data);	
		$this->redirect('fm_list');
	}
	
	public function send_fm(){
		$fcode= IFilter::act(IReq::get('fcode'));
		$aid= IFilter::act(IReq::get('aid'));
		$search = IFilter::act(IReq::get('search'));
		if($search != '1'){
			if(empty($fcode)){
				echo '<script>alert("请选择至少一个F码")</script>';
				echo '<script>parent.location.reload()</script>';
			}
		}
		$this->layout = '';
		$where = "";
		$charge_status = IFilter::act(IReq::get('charge_status'));
		$send_status = IFilter::act(IReq::get('send_status'));
		$start_time = IFilter::act(IReq::get('start_time'));
		$data['start_time'] = $start_time;
		$start_time = strtotime($start_time);
		$end_time = IFilter::act(IReq::get('end_time'));
		$data['end_time'] = $end_time;
		$end_time = strtotime($end_time);
		$meiid = IFilter::act(IReq::get('meiid'));
		if($charge_status != ''){
			$where .= " and o.pay_status = {$charge_status}";
		}
		
		if($send_status != ''){
			$where .= " and f.send_status = {$send_status}";
		}
		
		if($meiid != ''){
			$patt = '/\d+/';
			if(preg_match($patt,$meiid)){
				$where .= " and u.mobile = {$meiid}";
			}else{
				$where .= " and u.email = {$meiid}";
			}
		}
		
		if($start_time != ''){
			$where .= " and unix_timestamp(o.pay_time) >= {$start_time}";
		}
		
		if($end_time != ''){
			$where .= " and unix_timestamp(o.pay_time) <= {$end_time}";
		}
		$data['charge_status'] = $charge_status;
		$data['send_status'] = $send_status;
		$data['meiid'] = $meiid;
		$data['fcode'] = $fcode;
		$data['aid'] = $aid;
		$data['where'] = $where;
		$this->setRenderData($data);
		$this->redirect('send_fm');
		
	}
	
	public function do_send_fm(){
		$fid =  IFilter::act(IReq::get('fcode','post'));
		$aid = IFilter::act(IReq::get('aid','post'));
		$uid = IFilter::act(IReq::get('uid','post'));
		$uModel = new IModel('fm');
		if(stripos($fid,',')===false){				
			$where = "id = {$fid} and aid = {$aid}";
		}else{
			$fstr = '('.$fid.')';
			$where = "aid = {$aid} and id in {$fstr}";
		}
		$data['owner_meiid'] = $uid;
		$data['send_time'] = date('Y-m-d H:i:s',time());
		$data['send_status'] = 1;
		$uModel->setData($data);
		$r = $uModel->update($where);
		if($r){
			echo '<script>alert("发放成功")</script>';
			echo '<script>parent.location.reload()</script>';
		}else{
			echo '<script>alert("发放失败")</script>';
			echo '<script>parent.location.reload()</script>';
		}
		
	}
	
	public function apply_fm_list(){
		$deal_status = IFilter::act(IReq::get('deal_status'));
		$where = "";
		if($deal_status != ''){
			$where .= " and apply_status = {$deal_status}";
		}
		$data['deal_status'] = $deal_status;
		$data['where'] = $where;
		$this->setRenderData($data);
		$this->redirect('apply_fm_list');
	}
	
	public function do_apply_fm_review(){
		$id = IFilter::act(IReq::get('id'));
		$apply_status = IFilter::act(IReq::get('apply_status'));
		$admin_id = $this->admin['admin_id'];
		if(!empty($id)){
			$fModel = new IModel('fm_apply');
			$data['apply_status'] = $apply_status;
			$data['admin_id'] = $admin_id ;
			$data['review_time'] = ITime::getDateTime();
			$where = "id = {$id}";
			$fModel->setData($data);
			$r = $fModel->update($where);
			if($r){
				echo '<script>alert("审核成功")</script>';
				echo '<script>window.location.href="/market/apply_fm_list"</script>';
			}else{
				echo '<script>alert("审核失败")</script>';
				echo '<script>window.location.href="/market/apply_fm_list"</script>';
			}
		}
	}
	
	public function apply_fm_review(){
		$apply_status = IFilter::act(IReq::get('apply_status'));
		$where = "";
		if($apply_status != ''){
			$where .= " and fm.apply_status = {$apply_status}";
		}
		$data['where'] = $where;
		$this->setRenderData($data);
		$this->redirect('apply_fm_review');
	}
	
	public function apply_fm_detail(){
		$id = IFilter::act(IReq::get('id'));
		if(!empty($id)){
			$fQuery = new IQuery('fm_apply fa');
			$fQuery->join = "left join mbk_user u on u.id = fa.apply_meiid";
			$fQuery->fields = "fa.apply_time,fa.apply_comment,u.username,u.email,u.mobile";
			$fQuery->where = "fa.id = {$id}";
			$res = $fQuery->find();
			$detail = $res[0];
		}
		
		$data['detail'] = $detail;
		$this->setRenderData($data);
		$this->redirect('apply_fm_detail');
	}
}
