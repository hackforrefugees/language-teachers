<?php

namespace User\Form;

/**
 * @uses Zend\InputFilter\Inputfilter
 */
use Zend\InputFilter\InputFilter;

/**
 * Class RequestVerificationLinkFilter
 * @package User\Form
 * @author Dominik Einkemmer
 */
class RequestVerificationLinkFilter extends InputFilter
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
    }

}
