DMF
================

从PmWiki滥改而来的用于辅助弹幕保存以及弹幕设计的工具。并借助PmWiki本体提供一定的版本控制能力。

"DMF"取自最早的域名缩写。并无实际意义。

所需环境
---------

* PHP 5.3以上
* 任意支持rewrite的HTTP服务端

安装方法
-------

其他配置照常，然后实现以下rewrite功能

    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ index.php?n=$1  [QSA,L]

使用方法找用过的人。若干年后可能会出现在Wiki页面。

警告
----

1. 二次开发请慎重。
2. 大量**糟糕**代码可能导致身体不适甚至精神失常。
    * 本人没有系统学习过编程方法及模式，代码非常不科学。热烈欢迎各种意见及建议

开源协议
-------

GNU GPLv2及以后版本
