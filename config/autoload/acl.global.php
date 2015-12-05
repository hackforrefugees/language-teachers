<?php

return array(
    'acl' => array(
        'roles' => array(
            'anonymous' => null,
            'student' => 'anonymous',
            'volunteer' => 'student',
            'organisation' => 'volunteer',
            'admin' => 'organisation',
        ),
        'resources' => array(
            'allow' => array(
                'Application\Controller\Index' => array(
                    'index' => 'anonymous',
                ),
                'User\Controller\Index' => array(
                    'index' => 'anonymous',
                    'register' => 'anonymous',
                    'login' => 'anonymous',
                    'logout' => 'student',
                )
            )
        )
    )
);
?>