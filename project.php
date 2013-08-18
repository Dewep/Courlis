<?php


require_once(dirname(__FILE__) . '/Shape/Shape.php');

$courlis = new Shape(dirname(__FILE__));


$routages = array();

$routages[] = array(
		'name'			=> 'home',
		'url'			=> '',
		'module'		=> 'default',
		'controller'	=> 'default',
		'action'		=> 'index',
		'param'			=> array()
	);

$routages[] = array(
		'name'			=> 'module',
		'url'			=> '/([a-z]+)',
		'module'		=> '{1}',
		'controller'	=> 'default',
		'action'		=> 'index',
		'param'			=> array()
	);

$routages[] = array(
		'name'			=> 'module/controller',
		'url'			=> '/([a-z]+)/([a-z]+)',
		'module'		=> '{1}',
		'controller'	=> '{2}',
		'action'		=> 'index',
		'param'			=> array()
	);

$routages[] = array(
		'name'			=> 'module/controller/action',
		'url'			=> '/([a-z]+)/([a-z]+)/([a-z]+)',
		'module'		=> '{1}',
		'controller'	=> '{2}',
		'action'		=> '{3}',
		'param'			=> array()
	);

$routages[] = array(
		'name'			=> 'module/controller/action',
		'url'			=> '/([a-z]+)/([a-z]+)/([a-z]+)/([a-z]+)',
		'module'		=> '{1}',
		'controller'	=> '{2}',
		'action'		=> '{3}',
		'param'			=> array('test' => '{4}')
	);


$courlis->load($routages);


?>