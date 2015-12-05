<?php

namespace User\Form;

/**
 * @uses Zend\InputFilter\Inputfilter
 */
use Zend\InputFilter\InputFilter;

/**
 * Class ForgotPasswordFilter
 * @package User\Form
 * @author Dominik Einkemmer
 */
class ForgotPasswordFilter extends InputFilter
{

    /**
     * Constructor which adds the Filters
     */
    public function __construct()
    {
        $this->add(array(
            'name' => 'email',
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'EmailAddress',
                    'options' => array(
                        'domain' => true,
                    )
                )
            )
        ));

        $this->add(array(
            'name' => 'securityQuestionId',
            'required' => true
        ));

        $this->add(array(
            'name' => 'securityQuestionAnswer',
            'required' => true
        ));
    }

}
