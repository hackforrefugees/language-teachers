<?php

return array(
    'controllers' => array(
        'invokables' => array(
            'User\Controller\Index' => User\Controller\IndexController::class,
            'User\Controller\Verification' => User\Controller\VerificationController::class,
            'User\Controller\Account' => User\Controller\AccountController::class,
            'User\Controller\AccountRest' => User\Controller\AccountRestController::class
        ),
        'map' => array(
            'userAuthentication' => User\Controller\Plugin\UserAuthentication::class
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
                        'type' => 'Segment',
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
                        'type' => 'Segment',
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
                    'profile' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/profile',
                            'constraints' => array(),
                            'defaults' => array(
                                '__NAMESPACE__' => 'User\Controller',
                                'controller' => 'Account',
                                'action' => 'index',
                            )
                        )
                    ),
                    'logout' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/logout',
                            'constraints' => array(),
                            'defaults' => array(
                                '__NAMESPACE__' => 'User\Controller',
                                'controller' => 'Index',
                                'action' => 'logout',
                            )
                        )
                    ),
                    'verify' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/verify[/:token]',
                            'constraints' => array(
                                'token' => '[a-z0-9]*',
                            ),
                            'defaults' => array(
                                '__NAMESPACE__' => 'User\Controller',
                                'controller' => 'Verification',
                                'action' => 'index',
                                'token' => 0
                            )
                        )
                    ),
                    'requestLink' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/requestLink',
                            'defaults' => array(
                                '__NAMESPACE__' => 'User\Controller',
                                'controller' => 'Verification',
                                'action' => 'requestLink',
                            )
                        )
                    ),
                    'forgotPassword' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/forgotPassword',
                            'defaults' => array(
                                '__NAMESPACE__' => 'User\Controller',
                                'controller' => 'Verification',
                                'action' => 'forgotPassword',
                            )
                        )
                    ),
                    'resetPassword' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/resetPassword[/:hash]',
                            'constraints' => array(
                                'hash' => '[a-z0-9]*',
                            ),
                            'defaults' => array(
                                '__NAMESPACE__' => 'User\Controller',
                                'controller' => 'Verification',
                                'action' => 'resetPassword',
                            )
                        )
                    ),
                    'downloadContract' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/downloadContract',
                            'defaults' => array(
                                '__NAMESPACE__' => 'User\Controller',
                                'controller' => 'Account',
                                'action' => 'downloadContract',
                            )
                        )
                    ),
                    'delete' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/delete',
                            'defaults' => array(
                                '__NAMESPACE__' => 'User\Controller',
                                'controller' => 'Account',
                                'action' => 'delete',
                            )
                        )
                    ),
                    'successFullyDeleted' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/successFullyDeleted',
                            'defaults' => array(
                                '__NAMESPACE__' => 'User\Controller',
                                'controller' => 'Account',
                                'action' => 'successFullyDeleted',
                            )
                        )
                    ),
                    'edit' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/edit',
                            'defaults' => array(
                                '__NAMESPACE__' => 'User\Controller',
                                'controller' => 'Account',
                                'action' => 'edit',
                            )
                        )
                    ),
                    'addTranslation' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/addTranslation[/:language]',
                            'constraints' => array(
                                'language' => '[a-zA-Z-_]{5}'
                            ),
                            'defaults' => array(
                                '__NAMESPACE__' => 'User\Controller',
                                'controller' => 'Account',
                                'action' => 'addTranslation',
                            )
                        )
                    ),
                    'deleteTranslation' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/deleteTranslation[/:language]',
                            'constraints' => array(
                                'language' => '[a-zA-Z-_]{5}'
                            ),
                            'defaults' => array(
                                '__NAMESPACE__' => 'User\Controller',
                                'controller' => 'Account',
                                'action' => 'deleteTranslation',
                            )
                        )
                    ),
                    'editTranslation' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/editTranslation[/:language]',
                            'constraints' => array(
                                'language' => '[a-zA-Z-_]{5}'
                            ),
                            'defaults' => array(
                                '__NAMESPACE__' => 'User\Controller',
                                'controller' => 'Account',
                                'action' => 'editTranslation',
                            )
                        )
                    ),
                )
            ),
            'userRest' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/userRest',
                    'defaults' => array(
                        '__NAMESPACE__' => 'User\Controller',
                        'controller' => 'AccountRest',
                        'action' => 'index',
                    )
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'inlineEdit' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/inlineEdit',
                            'constraints' => array(),
                            'defaults' => array(
                                '__NAMESPACE__' => 'User\Controller',
                                'controller' => 'AccountRest',
                                'action' => 'inlineEdit',
                            )
                        )
                    ),
                    'currencies' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/currencies',
                            'constraints' => array(),
                            'defaults' => array(
                                '__NAMESPACE__' => 'User\Controller',
                                'controller' => 'AccountRest',
                                'action' => 'currencies',
                            )
                        )
                    ),
                    'areaUnits' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/areaUnits',
                            'constraints' => array(),
                            'defaults' => array(
                                '__NAMESPACE__' => 'User\Controller',
                                'controller' => 'AccountRest',
                                'action' => 'areaUnits',
                            )
                        )
                    ),
                )
            )
        )
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'user' => __DIR__ . '/../view'
        )
    )
);
