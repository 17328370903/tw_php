<?php

namespace core;

class Bootstrap
{

    public static function run()
    {

        self::setConstant();
        self::loadConfig();
        self::setErrorDeal();
        self::dealRoute();
    }

    //設置常量
    private static function setConstant()
    {
        defined('ROOT_PATH') or define('ROOT_PATH', dirname(__DIR__));
        defined('DS') or define('DS', DIRECTORY_SEPARATOR);
    }

    //加載配置文件
    private static function loadConfig()
    {
        $appConfig = include_once ROOT_PATH.DS.'config/app.php';
        date_default_timezone_set($appConfig['time_zone']);
        $GLOBALS['appConfig'] = $appConfig;
    }


    //處理路由
    private static function dealRoute()
    {

        $path = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : "";
        $__m  = $GLOBALS['appConfig']['model'];
        $__c  = $GLOBALS['appConfig']['controller'];
        $__a  = $GLOBALS['appConfig']['action'];


        if (empty($path)) {
            $path = "/";
        }

        if (array_key_exists($path, $GLOBALS['appConfig']['route_rewriting'])) {
            $path = $GLOBALS['appConfig']['route_rewriting'][$path];
        }

        if ( ! empty($path) && $path != "/") {
            $routes = explode("/", $path);
            $__m    = $routes[1];
            $__c    = empty($routes[2]) ? $__c : $routes[2];
            $__a    = empty($routes[3]) ? $__a : $routes[3];
        }

        $className = "app\\".$__m."\\controller\\".$__c."Controller";

        defined('M') or define('M', $__m);
        defined('C') or define('C', $__c);
        defined('A') or define('A', $__a);
        $controller = new $className();
        $controller->$__a();

    }

    /**
     * 係統錯誤處理
     *
     * @return void
     */
    private static function setErrorDeal()
    {

        error_reporting(0);
        set_error_handler(
            function ($error_no, $error_message, $file, $line) {
                $debug     = $GLOBALS['appConfig']['debug'] ?? false;
                $errorData = [
                    'error_no'  => $error_no,
                    'error_msg' => $error_message,
                    'file'      => $file,
                    'line'      => $line,
                ];
                if ($debug) {
                    echo json_encode($errorData);
                } else {
                    jump("/404.html");
                }
                self::writeErrorLog($errorData);
                die;
            }, E_ALL | E_STRICT
        );

        //捕獲致命錯誤
        register_shutdown_function(function () {
            $e = error_get_last();
            if ($e) {
                $debug     = $GLOBALS['appConfig']['debug'] ?? false;
                $errorData = [
                    'error_no'  => 500,
                    'error_msg' => $e['message'],
                    'file'      => $e['file'],
                    'line'      => $e['line'],
                ];
                if ($debug) {
                    echo json_encode($errorData);
                } else {
                    jump("/404.html");
                }
                self::writeErrorLog($errorData);
                die;
            }
        });
    }

    /**
     * 寫入日誌
     *
     * @param array $data
     *
     * @return void
     */
    private static function writeErrorLog(array $data)
    {
        $path = ROOT_PATH.DS."logs".DS.date("Y/m");
        if ( ! file_exists($path)) {
            mkdir($path, 0777, true);
        }
        $file             = date("d").".log";
        $data['datetime'] = date('Y-m-d H:i:s');
        file_put_contents(
            $path.DS.$file, var_export($data, true)."\n", FILE_APPEND
        );
    }

}