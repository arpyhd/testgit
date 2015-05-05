<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
Yii::setPathOfAlias('bootstrap', dirname(__FILE__).'/../extensions/bootstrap');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	
	'name'=>'Nirbuy',
	'sourceLanguage'=>'en',

	// theme
	'theme' => 'bootstrap',
	
	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool		
		'admin',
	),

	'behaviors'=>array(
		'onBeginRequest'=>array(
			'class'=>'application.components.BeginRequest',
		),
	),

	// application components
	'components'=>array(
		'user'=>array(
			//enable cookie-based authentication
			'loginUrl'=>array('site/login'),
			'allowAutoLogin'=>true,
		),
		
        'filterWord'=>array(
                'class'=>'application.extensions.FilterWord',
        ),

		'messages'=>array(
                'class'=>'CDbMessageSource',
            	// additional parameters for CDbMessageSource here
                'sourceMessageTable' => 'sourcemessage',
                'translatedMessageTable' => 'message',
        ),

		'request'=>array(
            'enableCookieValidation'=>true,
			'enableCsrfValidation'=>false,
        ),
		
		'mobileDetect' => array(
            'class' => 'ext.MobileDetect.MobileDetect'
        ),
        
        // uncomment the following to enable URLs in path-format
		'urlManager'=>array(
			'urlFormat' => 'path',
			'showScriptName' => false,
			'rules'=>array(
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
                '<controller:\w+>/<action:\w+>/catalogue/<catalogue:\d+>'=>'<controller>/<action>',
                '<controller:\w+>/<action:\w+>/category/<category:\d+>'=>'<controller>/<action>',
                '<controller:\w+>/<action:\w+>/category/<category:\w+>/<modality:\w+>'=>'<controller>/<action>',                            
			),
		),

		'bootstrap'=>array(
            'class'=>'bootstrap.components.Bootstrap',
        ),
		
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=nirbuydb',
			'emulatePrepare' => true,
			'username' => 'nirbuy',
                        'password' => 'n1rBuY',
                    //'username' => 'root',
                    //    'password' => '',
			'charset' => 'utf8',
		),

		'session' => array(
			'sessionName' => 'catalogue',
			'class'=>'CHttpSession',
			'cookieMode' => 'only',
		),
		
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// uncomment the following to show log messages on web pages
				
				array(
					'class'=>'CWebLogRoute',
				),
				
			),
		),
            
        'Cookies' => array('class' => 'application.components.CookiesHelper'),
	),

    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params' => array(
        // this is used in contact page
        'adminEmail' => 'info@nirbuy.co.uk',
        'euros' => '&#8364;',
        'csvMimetypes' => array(
            'text/csv',
            'text/plain',
            'application/csv',
            'text/comma-separated-values',
            'application/excel',
            'application/vnd.ms-excel',
            'application/vnd.msexcel',
            'text/anytext',
            'application/octet-stream',
            'application/txt',
        ),
        'maxChars' => array(
            'category' => 127,
            'product' => 80,
        )
    ),
);