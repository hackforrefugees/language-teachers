<?php
namespace User\Form;

/**
 * @uses Zend\InputFilter\InputFilter
 */
use Zend\InputFilter\InputFilter;

/**
 * Class LoginFilter
 * @package Application\Form
 * @author Dominik Einkmmer
 */
class LoginFilter extends InputFilter
{

    /**
     * Constructor adding the Filters for the Form fields
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
            'name' => 'password',
            'required' => true,
        ));

    }

}
