<?php

return array(
    'controllers' => array(
        'invokables' => array(
            'TipoRecurso\Controller\TipoRecurso' => 'TipoRecurso\Controller\TipoRecursoController',
        ),
    ),
	'controller_plugins' => array(
		'invokables' => array(
			'commonsPlugin' => 'SanAuth\Controller\Plugin\CommonsPlugin',
		)
	),
	
    'router' => array(
        'routes' => array(
            'tiporecurso' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/tiporecurso[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'TipoRecurso\Controller\TipoRecurso',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            'tipoRecurso' => __DIR__ . '/../view',
        ),
    ),
);