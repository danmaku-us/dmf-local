<?php if (!defined('PmWiki')) exit();
//加载&注册MVC相关内容
define("DMF_MVC__PATH", dirname(__FILE__));

spl_autoload_register(function ($class) {
    $p = DMF_MVC__PATH."/controllers/{$class}.php";
    if (file_exists($p)) {
        include($p);
    } else {
        FB::info("MVC::Autoloader failed to load {$p}");
    }
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
        $controllerName = $router->controller;
        $action     = $router->action;
    }

    global $MVC_Input, $MVC_Router;
    $MVC_Input = $input;
    $MVC_Router = $router;
    
    $controller = create_object($controllerName);
    $arr = array($controller, $action);
    if (method_exists($controller, $action) && is_callable($arr, TRUE)) {
        die(call_user_func_array($arr, $router->params));
    } else {
        FB::Error("Unknown controller {$controllerName}::{$action}");
    }
}

function create_object($name) { return new $name(); }