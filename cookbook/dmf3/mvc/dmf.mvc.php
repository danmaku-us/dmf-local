<?php if (!defined('PmWiki')) exit();
//加载&注册MVC相关内容
define("DMF_MVC__PATH", dirname(__FILE__));

spl_autoload_register(function ($class) {
    $p = DMF_MVC__PATH."/controllers/{$class}.php";
    if (file_exists($p)) 
        include($p);
});
include(DMF_MVC__PATH."/core/class.Controller.php");
include(DMF_MVC__PATH."/core/class.Router.php");
include(DMF_MVC__PATH."/core/class.Input.php");
include(DMF_MVC__PATH."/config/routes.php");

$HandleActions['mvc'] = 'HandleMVCURL';
$HandleAuth['mvc'] = 'read';
function HandleMVCURL($pn, $auth = 'read') {
    ob_start();
    $input = new K_Input();
    $router = Router::getInstance();
    //$router->addRule("{^static/(.*)}i", "pub/$1");
    $router->init($input);
    //var_dump($router);exit;
    if ((!class_exists($router->controller, true)) || empty($router->action)) {
        $controller = "defaultController";
        $action     = "try_getFile";
    } else {
        $controller = $router->controller;
        $action     = $router->action;
    }

    global $MVC_Input, $MVC_Router;
    $MVC_Input = $input;
    $MVC_Router = $router;
    
    $controller = create_object($controller);
    $arr = array($controller, $action);
    if (is_callable($arr, TRUE)) {
        die(call_user_func_array($arr, $router->params));
    } else {
        Abort("Unknown Method");
    }
}

function create_object($name) { return new $name(); }