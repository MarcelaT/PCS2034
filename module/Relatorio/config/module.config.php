<?php

return array(
    'controllers' => array(
        'invokables' => array(
            'Relatorio\Controller\Relatorio' => 'Relatorio\Controller\RelatorioController',
        ),
    ),
	'controller_plugins' => array(
		'invokables' => array(
			'commonsPlugin' => 'SanAuth\Controller\Plugin\CommonsPlugin',
		)
	),
    // The following section is new and should be added to your file
    'router' => array(
        'routes' => array(
            'relatorio' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/relatorio[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Relatorio\Controller\Relatorio',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            'relatorio' => __DIR__ . '/../view',
        ),
    ),
);