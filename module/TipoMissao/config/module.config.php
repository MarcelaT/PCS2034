<?php

return array(
    'controllers' => array(
        'invokables' => array(
            'TipoMissao\Controller\TipoMissao' => 'TipoMissao\Controller\TipoMissaoController',
        ),
    ),
	'controller_plugins' => array(
		'invokables' => array(
			'commonsPlugin' => 'SanAuth\Controller\Plugin\CommonsPlugin',
		)
	),
	
    'router' => array(
        'routes' => array(
            'tipomissao' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/tipomissao[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'TipoMissao\Controller\TipoMissao',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            'tipomissao' => __DIR__ . '/../view',
        ),
    ),
);