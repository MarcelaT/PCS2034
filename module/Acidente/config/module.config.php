<?php

return array(
    'controllers' => array(
        'invokables' => array(
            'Acidente\Controller\Acidente' => 'Acidente\Controller\AcidenteController',
        ),
    ),

    // The following section is new and should be added to your file
    'router' => array(
        'routes' => array(
            'acidente' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/acidente[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Acidente\Controller\Acidente',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            'acidente' => __DIR__ . '/../view',
        ),
    ),
);