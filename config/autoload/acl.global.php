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
                'Application\Controller\Language' => array(
                    'index' => 'anonymous',
                ),
                'Application\Controller\Event' => array(
                    'index' => 'anonymous',
                    'getSingleEvent' => 'anonymous',
                    'createEvent' => 'volunteer',
                    'addParticipantToEvent' => 'student',
                    'removeParticipantFromEvent' => 'student',
                ),
                'User\Controller\Index' => array(
                    'index' => 'student',
                    'register' => 'anonymous',
                    'login' => 'anonymous',
                    'logout' => 'student',
                    'notAllowed' => 'anonymous',
                ),
                'User\Controller\Data' => array(
                    'getSecurityQuestions' => 'anonymous'
                )
            ),
            'deny' => array(
                'Application\Controller\Event' => array(
                    'addParticipantToEvent' => 'organisation',
                    'removeParticipantFromEvent' => 'organisation',
                ),
            )
        )
    )
);
?>