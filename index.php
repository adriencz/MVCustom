<?php

/*
* constant WEBROOT      (Path from parent folder of this file)
* constant ROOTFILE     (Absolute root Path)
* constant SYSTEM       (system folder Path)
* constant ASSETS       (assets folder Path)
*/

define('WEBROOT', str_replace("index.php", '', $_SERVER['SCRIPT_NAME']));
define('ROOTFILE', str_replace("index.php", '', $_SERVER['SCRIPT_FILENAME']));
define('APP', WEBROOT.'application');
define('SYSTEM', ROOTFILE.'system');
define('ASSETS', WEBROOT.'assets');

/* ---------- Core & Config files ---------- */

require_once(SYSTEM.'/core/Controller.php');
require_once(SYSTEM.'/core/Model.php');
require_once(SYSTEM.'/config/config.php');

define('BASEURL', $config['baseurl']);

/*
* string $url ($_GET['url'] from .htaccess)
*/

if (!isset($_GET['url'])) {
  exit('No direct script access allowed');
}

$url = htmlspecialchars(trim($_GET['url']), ENT_QUOTES);

$url = explode('/', $url);

if (!isset($url[0]) || $url[0] == '' && isset($config['default_controller']))
{
  $url[0] = $config['default_controller'];
}

if (!isset($url[1]) || $url[1] == '' && isset($config['default_controller_method']))
{
  $url[1] = $config['default_controller_method'];
}

/*
* string $controller         (Contains 1st url param)
* string $method             (Contains 2nd url param)
*/

$controller = $url[0];
$method = $url[1];

// if URL ends with '/'

foreach ($url as $key => $value) {
  if ($value == '') { unset($url[$key]); }
}


// if 1st char of ($controller) is a lowercase

$upperController = str_split($controller);

if (!ctype_upper($upperController[0]))
{

  $upperController[0] = strtoupper($upperController[0]);
  $controller = implode("", $upperController);

}


// if $controller file exists

if (file_exists(ROOTFILE.'application/controllers/'.$controller.'.php'))
{
  require(ROOTFILE.'application/controllers/'.$controller.'.php');

  /*
  * object $controller (Controller enter with url)
  */

  $controller = new $controller();

  if (method_exists($controller, $method))
  {

    // unset controller & method from $url to $url = method params

    unset($url[0]);
    unset($url[1]);

    // check number of $method parameters

    $reflection = new ReflectionMethod($controller, $method);
    $methodParams = $reflection->getParameters();


    // Verif $url parameters & $method(parameters)

    if (count($url) < count($methodParams))
    {

      $controller->mvcError("Missing URL /controller/method/[<u>param(s)</u>]");

    }elseif (count($url) > count($methodParams))
    {

      $controller->mvcError("Unknown URL /controller/method/[<u>param(s)</u>]");

    }else {

      call_user_func_array(array($controller, $method), $url);

    }

  }else {
    $controller->mvcError("404 not found");
  }
}else {
  $error = new Global_Controller();
  $error->mvcError("404 not found");
}
