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

class Lottery extends IController {

    function init() {
        CheckRights::checkUserRights();
    }

    function index() {
       $res = IReq::get('app','get');
       $len = strlen($res);
       if($len=='10' || $len=='12'){
           $res = empty($res) ? 0 : $res;
           $data['apk'] = $res ;
           $userInfo = $this->getUserInfo();
           $data['username'] = $userInfo['username'];
           $this->setRenderData($data);
           $this->redirect('index');
       }else{
           echo "<script>alert('非法请求')</script>";
           echo "<script>window.location.href='/'</script>";
       }
    }
    
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
                $userInfo['username'] = $user['username'] ;
                return $userInfo ;
            }else{
                return "账号不存在";
            }
        }
    }
    
    function netDiskVerify(){
        $userInfo = $this->getUserInfo();
        //var_dump($userInfo);
        if(!is_array($userInfo)){
                $result['code'] = 0;
                $result['message'] = $userInfo;
                echo json_encode($result);
                exit;
        }else{
            $ps = DB_Meicloud::appointment_query($userInfo['id']);
            if($ps == 0){                
                $account_type =  IFilter::act(IReq::get('account_type','post'));
                if($account_type == '0'){
                    $account_name = "华为网盘";
                }else if($account_type == '1'){
                    $account_name = "新浪微盘";
                }else if($account_type == '2'){
                    $account_name = "金山快盘";
                }
                $account =  IFilter::act(IReq::get('account','post'));
                $appointment_no = IFilter::act(IReq::get('apk','post'));
                $data['netdisk_name'] = $account_name ;
                $data['netdisk_account'] = $account;
                $appObj = new IModel('appointment a');
                $appObj->setData($data);
                $where = "a.mei_id = {$userInfo['meiid']}";
                $res = $appObj->update($where);
                if($res){
                        $result['code'] = 1;
                        $result['message'] = "您的资料已提交,相关人员会在24小时内对您提交的资料进行审核";
                        echo json_encode($result);
                        exit;
                }else{
                        $result['code'] = 0;
                        $result['message'] = "网盘验证失败";
                        echo json_encode($result);
                        exit;
                }
            }else if($ps == 1){
                    $result['code'] = 0;
                    $result['message'] = "您还没有预约";
                    echo json_encode($result);
                    exit;
            }else{
                    $result['code'] = 0;
                    $result['message'] = "预约记录查询失败";
                    echo json_encode($result);
                    exit;
            }
        }
    }
    
    function vip_buy(){
        $data['callback'] = '/lottery/vip_buy';
        $data['end_time'] = strtotime('2016-07-30 14:00:00');
        $data['current_time'] = time();
        $this->setRenderData($data);
        $this->redirect('vip_buy');
    }
    
}

?>
