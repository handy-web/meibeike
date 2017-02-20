<?php
/**
 * @copyright Copyright(c) 2010 jooyea.cn
 * @file webapplication_class.php
 * @brief web应用类
 * @author webning
 * @date 2010-12-10
 * @version 0.6
 * @note
 */
/**
 * @brief IWebApplication 应用类
 * @class IWebApplication
 * @note
 */
class IWebApplication extends IApplication
{
	public $clientType;               //客户端类型, pc电脑, mobile手机
	public $defaultController = 'site';
    public $controller        = null;

    /**
     * @brief 构造函数
     * @param array or string $config 配置数组或者配置文件名称
     */
    public function __construct($config)
    {
    	parent::__construct($config);
    	$this->clientType = IClient::getDevice();       //客户端类型，pc或mobile
    }
    /**
     * @brief 请求执行方法，是application执行的入口方法
     */
    public function execRequest()
    {
        IUrl::beginUrl();
        $this->controller = $this->createController();
        IInterceptor::run("onCreateController",$this->controller);
        $this->controller->run();
		IInterceptor::run("onFinishController");
    }
    /**
     * @brief 创建当前的Controller对象
     * @return object Controller对象
     */
    public function createController()
    {
        $ctrlId = IUrl::getInfo("controller");

        if($ctrlId == '')
        {
        	$ctrlId = $this->defaultController;
        }

        if(class_exists($ctrlId))
        {
        	$ctrlObject = new $ctrlId($this,$ctrlId);
        }
        else
        {
        	$ctrlObject = new IController($this,$ctrlId);
        }
        $this->controller = $ctrlObject;
        return $this->controller;
    }
    /**
     * @brief 取得当前的Controller
     * @return object Controller对象
     */
    public function getController()
    {
        return $this->controller;
    }
}