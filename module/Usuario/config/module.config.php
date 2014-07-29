<?php

return array(
    'controllers' => array(
        'invokables' => array(
            'Usuario\Controller\Usuario' => 'Usuario\Controller\UsuarioController',
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
            'usuario' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/usuarios[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Usuario\Controller\Usuario',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            'usuario' => __DIR__ . '/../view',
        ),
    ),
);