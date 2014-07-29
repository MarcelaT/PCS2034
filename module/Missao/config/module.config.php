<?php

return array(
    'controllers' => array(
        'invokables' => array(
            'Missao\Controller\Missao' => 'Missao\Controller\MissaoController',
        ),
    ),
	'controller_plugins' => array(
		'invokables' => array(
			'commonsPlugin' => 'SanAuth\Controller\Plugin\CommonsPlugin',
		)
	),
	
    'router' => array(
        'routes' => array(
            'missao' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/missao[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Missao\Controller\Missao',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            'missao' => __DIR__ . '/../view',
        ),
    ),
);