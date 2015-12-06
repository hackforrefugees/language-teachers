<?php

/**
 * Dejan Cabrilo <dcabrilo@bitspan.rs>
 *
 * @link      https://github.com/cabrilo/BitDbBcryptAuthAdapter for the canonical source repository
 * @copyright Dejan Cabrilo
 * @license   https://github.com/cabrilo/BitDbBcryptAuthAdapter/blob/master/LICENSE New BSD License
 */

namespace BitDbBcryptAuthAdapter;

class Module
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
}