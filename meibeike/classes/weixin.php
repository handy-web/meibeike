<?php
/**
 * @copyright (c) 2015 aircheng.com
 * @file wechat.php
 * @brief wechat 的oauth协议登录接口
 * @author nswe
 * @date 2015/12/3 18:24:18
 * @version 4.3
 */

/**
 * @class wechat
 * @brief wechat的oauth协议接口
 */
class WeiXin extends Exception 
{
	private $AppID     = '';
	private $AppSecret = '';

	public function __construct($config)
	{
		$this->AppID     = isset($config['appid']) ? $config['appid'] : "";
		$this->AppSecret = isset($config['appsecrect']) ? $config['appsecrect'] : "";
		$this->Redirect_Uri = isset($config['redirect_uri']) ? $config['redirect_uri'] : "";
	}

	public function getFields()
	{
		return array(
			'AppID' => array(
				'label' => 'AppID',
				'type'  => 'string',
			),
			'AppSecret'=>array(
				'label' => 'AppSecret',
				'type'  => 'string',
			),
		);
	}

	//获取登录url地址
	public function getLoginUrl()
	{		
		$urlparam = array(
			"appid=".$this->AppID,
			"redirect_uri=".urlencode($this->Redirect_Uri),
			"response_type=code",
			"scope=snsapi_login",
			"state=".rand(100,999).substr(time(),-4,4)
		);
		$url = "https://open.weixin.qq.com/connect/qrconnect?".join("&",$urlparam)."#wechat_redirect";
		return $url;
	}	

	//获取进入令牌
	public function getAccessToken($parms)
	{
		$urlparam = array(
			"appid=".$this->AppID,
			"secret=".$this->AppSecret,
			"code=".$parms['code'],
			"grant_type=authorization_code",
		);
		$url = "https://api.weixin.qq.com/sns/oauth2/access_token?".join("&",$urlparam);

		//模拟post提交
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		$result = curl_exec($ch);
		$tokenInfo = JSON::decode($result);
		if(!$tokenInfo)
		{
			die(var_export($result));
		}

		if(!isset($tokenInfo['access_token']))
		{
			die(var_export($tokenInfo));
		}

		//获取用户信息
		$unid = $tokenInfo['openid'];
		$name = substr($unid,-8);
		if(strpos($tokenInfo['scope'],"snsapi_userinfo") === false)
		{
			
		}
		else
		{
			//$unid       = isset($tokenInfo['unionid']) ? $tokenInfo['unionid'] : $tokenInfo['openid'];
			$wechatName = trim(preg_replace('/[\x{10000}-\x{10FFFF}]/u',"",$tokenInfo['nickname']));
			$name       = $wechatName ? $wechatName : substr($unid,-8);
		}
		ISession::set('wechat_user_nick',$name);
		ISession::set('wechat_user_id',$unid);
		return $tokenInfo;
	}

	//获取用户数据
	public function getUserInfo($parms)
	{			
		$urlparam = array(
				"access_token=".$parms['access_token'],
				"openid=".$parms['openid'],
		);
		$url = "https://api.weixin.qq.com/sns/userinfo?".join("&",$urlparam);		
		//模拟post提交
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		$result = curl_exec($ch);
		$userInfo = JSON::decode($result);
		return $userInfo;
	}
// *******************************微信公众平台*****************************
	//微信公众平台
	public function getMpAccessToken($parms='')
	{
		$urlparam = array(
			"grant_type=client_credential",
			"appid=wx6ce563c72363109f",
			"secret=5096ee488115d9dd9e4e76ac020dbeb9"
		);
		$url = "https://api.weixin.qq.com/cgi-bin/token?".join("&",$urlparam);
		//模拟post提交
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		$result = curl_exec($ch);
		$res = JSON::decode($result);
		return $res;
	}
	
	public function getJsapiTicket($parms){
		$urlparam = array(
			"access_token=".$parms['access_token'],
			"type=jsapi"
		);
		$url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?".join('&',$urlparam);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		$result = curl_exec($ch);
		$res = JSON::decode($result);
		return $res;
	}
	
	public function createSign($parms){
		$urlstr = array(
		"jsapi_ticket=".$parms['jsapi_ticket'],
		"noncestr=".$parms['noncestr'],
		"timestamp=".$parms['timestamp'],
		"url=".$parms['url']
		);
		$signature = sha1(join('&',$urlstr));
		return $signature;
	}
	
	public function checkStatus($parms)
	{
		if(isset($parms['error']) || !isset($parms['code']))
		{
			return false;
		}
		else
		{
			return true;
		}
	}
	
}