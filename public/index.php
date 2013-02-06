<?php

ini_set('display_errors', 'on');
ini_set('display_startup_errors', 'on');
ini_set('upload_max_filesize', '25M');


define('ROOT_DIR', dirname(dirname(__FILE__)));

$paths = array(
    ROOT_DIR . '/application',
    ROOT_DIR . '/application/modules',
    ROOT_DIR . '/application/models',
    ROOT_DIR . '/library'
);

set_include_path(implode(PATH_SEPARATOR, $paths));

require 'Zend/Loader/Autoloader.php';

try {
	Zend_Loader_Autoloader::getInstance()->setFallbackAutoloader(true);

date_default_timezone_set('UTC');
$config = new Zend_Config_Ini(ROOT_DIR . '/application/config.ini');
// set cache manager
$cacheFrontend = array(
     'caching' => $config->cache->enable,
     'lifetime' => $config->cache->lifetime,
     'write_control' => false,
     'automatic_serialization' => true,
     'automatic_cleaning_factor' => 0,
     'ignore_user_abort' => true
);

$cacheManager = new Zend_Cache_Manager();

$cacheManager->setCacheTemplate('file', array(
     'frontend' => array(
          'name' => 'Output',
          'options' => $cacheFrontend
     ),
     'backend' => array(
          'name' => 'file',
          'options' => array(
               'cache_dir' => $config->cache->file->cache_dir,
               'file_locking' => true,
               'read_control' => true,
               'read_control_type' => 'adler32',
               'hashed_directory_level' => 1,
               'file_name_prefix' => '_kai_template'
          )
     )
));

Zend_Registry::set('cache_manager', $cacheManager);

$db = new Zend_Db_Adapter_Pdo_Mysql($config->database->params);
Zend_Db_Table_Abstract::setDefaultAdapter($db);
Zend_Db_Table_Abstract::setDefaultMetadataCache($cacheManager->getCache('file'));

// Get front controller instance
$front = Zend_Controller_Front::getInstance();
// Set modular structure directories
$front->addModuleDirectory(ROOT_DIR . '/application/modules');

$dispatcher = $front->getDispatcher();
$request = $front->getRequest();

// Init View Renderer
$view = new Zend_View();
$view->headTitle('Служба заказчика');
$view->headTitle()->setSeparator(' - ');
$view->addScriptPath(ROOT_DIR . '/application/views/scripts');
$tplFormat = 'html';

$navConf = new Zend_Config_Ini(ROOT_DIR . '/application/configs/navigation.ini', 'nav');
$container = new Zend_Navigation($navConf);
$view->getHelper('navigation')->setContainer($container);
$view->addHelperPath(ROOT_DIR . '/library/Kaipack/View/Helper', 'Kaipack_View_Helper');

$renderer = new Zend_Controller_Action_Helper_ViewRenderer();
$renderer->setView($view);
$renderer->setViewSuffix($tplFormat);
// Set view renderer helper
Zend_Controller_Action_HelperBroker::addHelper($renderer);

// Init Zend_Layout
$layout = Zend_Layout::startMvc();
$layout->setLayoutPath(ROOT_DIR . '/application/views/scripts');
$layout->setViewSuffix($tplFormat);

// init router
$router = new Zend_Controller_Router_Rewrite();

// home route
$router->addRoute('home', new Zend_Controller_Router_Route('/', array(
     'module' => 'default',
     'controller' => 'index',
     'action' => 'index'
)));

// photos route
$router->addRoute('photos', new Zend_Controller_Router_Route('/photos', array(
    'module' => 'default',
    'controller' => 'photos',
    'action' => 'index'
)));

$router->addRoute('get-photo', new Zend_Controller_Router_Route_Regex('photo(\d+)', array(
    'module' => 'default',
    'controller' => 'photos',
    'action' => 'get'
), array(
    1 => 'photo_id'
)));

// service route
$router->addRoute('service', new Zend_Controller_Router_Route('/service', array(
     'module' => 'default',
     'controller' => 'index',
     'action' => 'service'
)));

// advice route
$router->addRoute('advice', new Zend_Controller_Router_Route('/advice', array(
     'module' => 'default',
     'controller' => 'advice',
     'action' => 'index'
)));

// article route
$router->addRoute('article', new Zend_Controller_Router_Route('/article', array(
     'module' => 'default',
     'controller' => 'article',
     'action' => 'index'
)));

$router->addRoute('articleshow', new Zend_Controller_Router_Route_Regex('article-(\d+)', array(
	'module' => 'default',
	'controller' => 'article',
	'action' => 'show'
), array(
	1 => 'article_id'
)));


// faq route
$router->addRoute('faq', new Zend_Controller_Router_Route('/faq', array(
     'module' => 'default',
     'controller' => 'faq',
     'action' => 'index'
)));

// rating contractors route
$router->addRoute('rating-contractors', new Zend_Controller_Router_Route('/rating-contractors', array(
     'module' => 'default',
     'controller' => 'contractors',
     'action' => 'index'
)));

// about route
$router->addRoute('about', new Zend_Controller_Router_Route('/about', array(
     'module' => 'default',
     'controller' => 'index',
     'action' => 'tender'
)));

// cost of services route
$router->addRoute('cost-of-services', new Zend_Controller_Router_Route('/cost-of-services', array(
     'module' => 'default',
     'controller' => 'index',
     'action' => 'cost-of-Services'
)));

// contacts route
$router->addRoute('contacts', new Zend_Controller_Router_Route('/contacts', array(
     'module' => 'default',
     'controller' => 'index',
     'action' => 'contacts'
)));

// admin auth
$router->addRoute('auth', new Zend_Controller_Router_Route('/admin', array(
     'module' => 'admin',
     'controller' => 'auth',
     'action' => 'login'
)));

$translator = new Zend_Translate(
	array(
		'adapter' => 'array',
		'content' => '../resources/languages',
		'locale'  => 'ru',
		'scan' => Zend_Translate::LOCALE_DIRECTORY
	)
);
Zend_Validate_Abstract::setDefaultTranslator($translator);

$front->setRouter($router);

// mail
$config = Config::getInstance();

$tr = new Zend_Mail_Transport_Smtp($config->getOption('smtp-server'), array(
	'ssl' => 'tls',
    'port' => 587,
	'auth' => 'login',
	'username' => $config->getOption('smtp-login'),
	'password' => $config->getOption('smtp-password')
));
Zend_Mail::setDefaultTransport($tr);

// Switch off error handler plugin
$front->setParam('noErrorHandler', true);
// Throw exceptions in dispatch loop
$front->throwExceptions(true);

// Set return response flag
$front->returnResponse(true);

// Dispatch request
$response = $front->dispatch($request);
// Output response
$response->sendResponse();

} catch (Exception $e) {
	echo $e->getMessage();
	echo '<pre>', $e->getTraceAsString(), '</pre>';
}