<?php

namespace User\Form;

/**
 * @uses Zend\Form\Form
 */
use Zend\Form\Form;

/**
 * Class ForgotPasswordForm
 *
 * @category User
 * @package User_Form
 * @author Dominik Einkemmer
 */
class ForgotPasswordForm extends Form
{

    /**
     * Constructor which adds the Form Elements
     * @param string $name
     */
    public function __construct($name = null)
    {
        parent::__construct('Forgot Password');

        $this->setAttribute("method", "post");
        $this->setAttribute("enctype", "multipart/form-data");

        $this->add(array(
            'name' => 'email',
            'type' => '\Zend\Form\Element\Email',
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'email',
                'placeholder' => 'Email',
                'required' => 'required'
            ),
            'options' => array(
                'label' => 'Email',
                'label_attributes' => array(
                    'class' => 'control-label'
                )
            )
        ));

        $this->add(array(
            'name' => 'securityQuestionId',
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'required' => 'required',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Select a Security-Question',
                'empty_option' => '-- Please select --',
                'label_attributes' => array(
                    'class' => 'control-label'
                ),
                'disable_inarray_validator' => true,
            )
        ));

        $this->add(array(
            'name' => 'securityQuestionAnswer',
            'attributes' => array(
                'type' => 'text',
                'required' => 'required',
                'class' => 'form-control',
                'placeholder' => 'Answer',
            ),
            'options' => array(
                'label' => 'Answer',
                'label_attributes' => array(
                    'class' => 'control-label'
                ),
            )
        ));

        $this->add(array(
            'name' => 'request',
            'type' => '\Zend\Form\Element\Submit',
            'attributes' => array(
                'id' => 'request',
                'class' => 'btn btn-xl',
                'value' => 'Request password reset'
            )
        ));
    }

}
