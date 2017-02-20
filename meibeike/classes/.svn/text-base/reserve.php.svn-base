<?php

/**
 * @copyright (c) 2011 jooyea.cn
 * @file Order_Class.php
 * @brief 订单中相关的
 * @author relay
 * @date 2011-02-24
 * @version 0.6
 */
class Reserve {

    /**
     * 获取来源网站
     */
    public static function getFromSite(){
       //session_start();
       $fr = IReq::get('fr','get');
//        if (!empty($fr))
//        {
//           ISession::set("from_site",$fr);
           
            
//        }else{
//            if (empty(ISession::get("from_site"))){
//               $fr =  IClient::getSiteRefUrl();
//               ISession::set("from_site",$fr); 
//             }else{
//               $fr = ISession::get("from_site");
//             }
//        }
		
       if (empty($fr))
       {
       		$fr =  IClient::getSiteRefUrl();   
       }
       
       if (empty($fr))
       {   	
       		$fr = ISession::get("from_site");
       }
       else{    
       		ISession::set("from_site",$fr);
       }
           
       return  $fr;
       
    }

}
