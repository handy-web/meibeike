<?php
/**
 * @copyright (c) 2011 [group]
 * @file sendmail.php
 * @brief 积分日志记录处理类
 * @author chendeshan
 * @date 2011-6-15 14:58:39
 * @version 0.6
 */
class SendMail
{
	private $config = array();//邮件配置信息
	private $smtp   = null;   //邮件发送对象
	private $error  = '';     //错误信息

	//构造函数
	public function __construct($site_config = null)
	{
		if($site_config == null)
		{
			$siteConfigObj = new Config("site_config");
			$site_config   = $siteConfigObj->getInfo();
			$this->config  = $site_config;
		}
		else
		{
			$this->config  = $site_config;
		}

		if($this->checkEmailConf($site_config))
		{
			$phpMailerDir = IWEB_PATH.'core/util/phpmailer/PHPMailerAutoload.php';
			include_once($phpMailerDir);

			//创建实例
			$this->smtp = new PHPMailer();
			$this->smtp->Timeout = 60;
			$this->smtp->SMTPSecure = $site_config['email_safe'];
			$this->smtp->isHTML();

			//使用系统mail函数发送
			if(isset($site_config['email_type']) && $site_config['email_type']=='2')
			{
				$this->smtp->isMail();
			}
			//使用外部SMTP服务器发送
			else
			{
				$this->smtp->isSMTP();
				$this->smtp->SMTPAuth = true;
				$this->smtp->Host     = $site_config['smtp'];
				$this->smtp->Port     = $site_config['smtp_port'];
				$this->smtp->Username = $site_config['smtp_user'];
				$this->smtp->Password = $site_config['smtp_pwd'];

				echo $this->smtp->Host    ."_____,$this->smtp->Host    <br/>";
				echo $this->smtp->Port    ."_____,$this->smtp->Port    <br/>";
				echo $this->smtp->Username."_____,$this->smtp->Username<br/>";
				echo $this->smtp->Password."_____,$this->smtp->Password<br/>";
			}
		}
		else
		{
			$this->error = '配置参数填写不完整';
		}
	}

	//获取错误信息
	public function getError()
	{
		echo $this->error    ."_____,$this->error    <br/>";
		return $this->error;
	}

	/**
	 * @brief 检查邮件配置信息的合法性
	 * @parms $site_config array 配置信息
	 * @return bool true:成功;false:失败;
	 */
	public function checkEmailConf($site_config)
	{
		if(isset($site_config['email_type']) && isset($site_config['mail_address']))
		{
			echo $site_config['email_type']     ."_____,是否到这里email_type<br/>";
			if($site_config['email_type'] == 1)
			{
				$mustConfig = array('smtp','smtp_user','smtp_pwd','smtp_port');
				foreach($mustConfig as $val)
				{
					if(!isset($site_config[$val]) || $site_config[$val] == '')
					{
						return false;
					}
				}
			echo $site_config['email_type']     ."_____,是否到这里2email_type<br/>";
				
				return true;
			}
			else
			{
				return true;
			}
		}
		else
		{
			return false;
		}
	}

	public function send_mail($tomail, $subject, $body){
    require('class.phpmailer.php');
    $mail = new PHPMailer();
    $mail->IsSMTP(); 
    //$mail->SMTPDebug = 2;						//显示调试信息
    $mail->SMTPAuth = true;						
    //$mail->SMTPSecure = "ssl";				//启用ssl加密
    $mail->Host = "smtp.qiye.163.com";				//邮箱服务器名称
    $mail->Port = 25;							//邮箱服务端口
    $mail->Username = "service@meibeike.com";			//发件人邮箱地址
    $mail->Password = "woaiMBK666";					//发件人邮箱密码
    $mail->CharSet = "UTF-8";
    $mail->SetFrom('service@meibeike.com', '张三');		//发件人信息 邮箱地址，姓名
    $mail->Subject = $subject;
    $mail->MsgHTML($body);
    $mail->AddAddress($tomail, "");
    if (!$mail->Send())
    {
        $stat = 0;
    }
    else
    {
        $stat = 1;
    }
    return $stat;
}

	/**
	 * @brief 邮件发送
	 * @parms  $to      string 收件人
	 * @parms  $title   string 标题
	 * @parms  $content string 内容
	 * @parms  $bcc     string 抄送收件人以";"分隔开
	 * @return bool true:成功;false:失败;
	 */
	public function send($to,$title,$content,$bcc = '')
	{
		echo $to."_____,to<br/><br/>";
    
 
		if(is_object($this->smtp))
		{
			$this->smtp->FromName= isset($this->config['name']) ? $this->config['name'] : 'iWebShop';
			$this->smtp->From    = $this->config['mail_address'];
			$this->smtp->Subject = $title;
			$this->smtp->Body    = $content;
		echo $this->smtp->FromName."_____,$this->smtp->FromName<br/>";
echo $this->smtp->From."_____,$this->smtp->From<br/>";

			//收件人
			$tempToEmail = explode(';',$to);
			foreach($tempToEmail as $key => $val)
			{
				$this->smtp->addAddress($val);
			}

			//抄送人
			if($bcc)
			{
				$tempBccEmail = explode(';',$bcc);
				foreach($tempBccEmail as $key => $val)
				{
					$this->smtp->addBCC($val);
				}
			}

echo $this->smtp->send()."_____,$this->smtp->send()<br/>";

			return $this->smtp->send();
		}
		else
		{
			return false;
		}
	}

	//获取配置信息
	public function getConfigItem($key)
	{
		return isset($this->config[$key]) ? $this->config[$key] : null;
	}
}