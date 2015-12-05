<?php

return array(
    'controllers' => array(
        'invokables' => array(
            'User\Controller\Index' => 'User\Controller\IndexController',
            'User\Controller\Data' => 'User\Controller\DataController',
        ),
        'map' => array(
            'userAuthentication' => 'User\Controller\Plugin\UserAuthentication'
        )
    ),
    'router' => array(
        'routes' => array(
            'user' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/user',
                    'defaults' => array(
                        'controller' => 'User\Controller',
                        'action' => 'index',
                    )
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'login' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/login',
                            'constraints' => array(),
                            'defaults' => array(
                                '__NAMESPACE__' => 'User\Controller',
                                'controller' => 'Index',
                                'action' => 'login',
                            )
                        )
                    ),
                    'register' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/register',
                            'constraints' => array(),
                            'defaults' => array(
                                '__NAMESPACE__' => 'User\Controller',
                                'controller' => 'Index',
                                'action' => 'register',
                            )
                        )
                    ),
                    'securityQuestions' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/securityQuestions',
                            'constraints' => array(),
                            'defaults' => array(
                                '__NAMESPACE__' => 'User\Controller',
                                'controller' => 'Data',
                                'action' => 'getSecurityQuestions',
                            )
                        )
                    ),
                )
            ),
        )
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'user' => __DIR__ . '/../view'
        )
    )
);
