<?php
return array(
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'controllers' => array(
        'Event\Controller\Index' => 'Event\Controller\IndexController'
    ),
    'router' => array(
        'routes' => array(
            'event' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/event',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Event\Controller',
                        'controller' => 'Index',
                        'action' => 'index',
                    )
                ),
                'may_terminate' => true,
                'child_routes' => array(
                )
            )
        ),
    )
);