<!DOCTYPE html>
<html lang="zh"><head><meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=Edge">
        <title>请升级您的浏览器 - 美贝壳</title>
        <style type="text/css">
            html {
                background:#999 repeat-x left top;
                width: 100%;
                height: 100%;
            }
            body {
                font-family: "Microsoft YaHei", 微软雅黑, "STHei", 华文黑体, "Helvetica Neue", Helvetica, Arial, sans-serif;         
            }
            body,div,dl,dt,dd,ul,ol,li,h1,h2,h3,h4,h5,h6,p {
                margin:0;padding:0;
            }
            h1,h2,h3,h4,h5,h6,b {
                font-size:100%;
                font-weight:500;
            }
            ol,ul {
                list-style:none;
            }
            a {
                text-decoration:none;
                cursor: pointer;
            }
            .browser-update {
                display: block;
                width: 718px;
                height: 496px;
                clear: both;
                background: #ffffff no-repeat left top;
                position: absolute;
                left: 50%;
                top: 50%;
                margin-left: -359px ;
                margin-top: -248px;
            } 
            .browser-update h1,
            .browser-update h2,
            .browser-update h3 {
                text-align: center;
            }
            .browser-update h1 {
                padding: 67px 0 0 0;
                font-size: 30px;
                line-height: 30px;
                color: #333;
                letter-spacing: 1px;
            }
            .browser-update h2 {
                font-size: 14px;
                line-height: 1.8;
                color: #999;
                padding: 32px 55px 15px;
                letter-spacing: 0.1px;
            }
            .browser-update h3 {
                font-size: 16px;
                line-height: 16px;
                color: #666;
                padding: 20px 0 40px 0;

            }
            .browser-update ul {
                clear: both;
            }

            .browser-update  li {
                float: left;
                background: no-repeat left top;
                width: 140px;
                height: 170px;
                position: relative;
                text-align: center;
                *display: inline;
                *zoom: 1;
            }

            .browser-update ul a {
                display: block;
                width: 100%;
                padding: 0 0 0 0;
            }

            .browser-update h2 a.link {
                color: #6b95ea;
            }

            .browser-update h4 {
                color: #999;
                font-size: 14px;
                line-height: 20px;
            }

            .browser-update .chrome {
                background-position: 10px 0;
                margin: 0 0 0 89px;
            }
            .browser-update .firefox {
                background-position: -140px 0 ;
                margin: 0 60px;
            }
            .browser-update .ie {
                background-position: -300px 0;
            }

            #update {
                display: none;
            }

            .browser-update p {
                font-size: 10px;
                line-height: 30px;
            }

            .browser-update p a {
                width: 48px;
                color: #000;
                color: #6b95ea;
            }</style>
    </head>
    <body><div class="browser-update" id="update" style="display:block;">
            <h1>请升级您的浏览器</h1>
            <h2>尊敬的用户，您现在使用的浏览器版本过低，请升级后继续使用美贝壳的服务。</h2>
            <h3>您可以选择：</h3>
            <ul>
                <li class="chrome">
                    <a href="http://www.google.cn/intl/zh-CN/chrome/browser/" target="_blank"><img src="<?php echo IUrl::creatUrl("")."views/".$this->theme."/skin/".$this->skin."/images/front/chrome.png";?>"></img><h4>Google Chrome</h4></a>
                </li>
                <li class="firefox">
                    <a href="http://www.mozilla.org/zh-CN/firefox/new/" target="_blank"><img src="<?php echo IUrl::creatUrl("")."views/".$this->theme."/skin/".$this->skin."/images/front/ff.png";?>"></img><h4>Mozilla Firefox</h4></a>
                </li>
                <li class="ie">
                    <a href="http://www.microsoft.com/china/windows/IE/upgrade/index.aspx" target="_blank"><img src="<?php echo IUrl::creatUrl("")."views/".$this->theme."/skin/".$this->skin."/images/front/IE.png";?>"></img><h4>Internet Explorer 8+</h4>
                    </a>
                </li>
            </ul>
        </div>
    </body>
</html>