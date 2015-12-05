<?php

namespace User\Form;

/**
 * @uses Zend\InputFilter\Inputfilter
 */
use Zend\InputFilter\InputFilter;

/**
 * Class ResetPasswordFilter
 * @package User\Form
 * @author Dominik Einkemmer
 */
class ResetPasswordFilter extends InputFilter
{

    /**
     * Constructor which adds the Filters
     */
    public function __construct()
    {
        $this->add(array(
            'name' => 'password',
            'required' => true
        ));

        $this->add(array(
            'name' => 'passwordStrengthScore',
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'GreaterThan',
                    'options' => array(
                        'min' => '38',
                        'message' => 'Your password is too weak. Please follow the instructions'
                    )
                )
            )
        ));

        $this->add(array(
            'name' => 'passwordStrength',
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'Identical',
                    'options' => array(
                        'token' => 'strong',
                        'message' => 'Your password is too weak. Please follow the instructions'
                    ),
                ),
            ),
        ));

        $this->add(array(
            'name' => 'confirmPassword',
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'Identical',
                    'options' => array(
                        'token' => 'password',
                    ),
                ),
            ),
        ));
    }

}
