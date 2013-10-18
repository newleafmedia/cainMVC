<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);


defined('BASE_PATH')
    || define('BASE_PATH', realpath(dirname(__FILE__) ));

define('BASE_URL','http://framework.localhost:8888/');


set_include_path(implode(PATH_SEPARATOR, array(
    realpath(BASE_PATH . '/library'),
    realpath(BASE_PATH . '/../library'),
    get_include_path(),
)));

include BASE_PATH.'/library/Cain/Application.php';

try{

	$application = new Cain\Application(
		BASE_PATH . DIRECTORY_SEPARATOR . 'config.php',
		'development'
	);


	$application->run();

} catch (Exception $e) {

	//echo $e->getMessage().'<pre>'. $e->getTraceAsString().'</pre>';

}


function __autoload($class_name) 
{
    $filename = str_replace('\\', DIRECTORY_SEPARATOR, $class_name).'.php';

    include $filename;
}