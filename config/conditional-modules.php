<?php
/**
 * register uri to stop module loading
 */
return [

	'redux' => [
		'/login' => ['POST'],
	],

	'wp-filebase' => [
		'!/' => ['GET'],
		'!/institucional/' => ['GET'],
	],

	'backwpup' => [
		'/' => ['GET'],
		'/login' => ['POST'],
		'/fale-conosco' => ['GET', 'POST'],
	],

	'sucuri' => [
		'/' => ['GET'],
		'/login' => ['POST'],
		'/fale-conosco' => ['GET', 'POST'],
	],
];