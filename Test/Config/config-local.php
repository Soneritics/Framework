<?php
/* Example config file. This config file is NOT loaded but is just an example of how to configure an application.
 * All the files that start with `config-` are loaded. You can use `config-local.php` and `config-global.php` for
 * example, where config-local.php will be excluded from the VCS.
 */
return array(
	// Select the Logger. When not using a framework's logger, use the full namespace to define the logger.
	'Logging' => array(
		'Logger' => 'Logger\Screen',
		'Config' => array()
	),

	// Set the modules and their routes. You can use multiple Route objects. When a route is found,
	// no more checking will be done.
	'Routing' => array(
		'/' => array(
			'Module' => 'Public',
			'Controller' => 'Page'
		),
		'/faq'  => array(
			'Module' => 'Public',
			'Controller' => 'Page',
			'Function' => 'faq'
		),
		'/page/*'  => array(
			'Module' => 'Public',
			'Controller' => 'Page',
			'Function' => 'show'
		),
		'Test\Router'
	),

	// Error handling. Name of the error handler within the Modules namespace.
	'Error' => 'Error\Error'
);