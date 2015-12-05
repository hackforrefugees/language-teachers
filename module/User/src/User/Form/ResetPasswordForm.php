<?php

namespace User\Form;

/**
 * @uses Zend\Form\Form
 */
use Zend\Form\Form;

/**
 * Class ResetPasswordForm
 * @package User\Form
 * @author Dominik Einkemmer
 */
class ResetPasswordForm extends Form
{

    /**
     * Constructor which adds the Form Elements
     * @param string $name
     */
    public function __construct($name = null)
    {
        parent::__construct('Reset Password');

        $this->setAttribute("method", "post");
        $this->setAttribute("enctype", "multipart/form-data");

        $this->add(array(
            'name' => 'password',
            'type' => '\Zend\Form\Element\Password',
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'password',
                'placeholder' => 'New password',
                'required' => 'required'
            ),
            'options' => array(
                'label' => 'New password',
                'label_attributes' => array(
                    'class' => 'control-label'
                )
            )
        ));

        $this->add(array(
            'name' => 'confirmPassword',
            'type' => '\Zend\Form\Element\Password',
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'confirmPassword',
                'placeholder' => 'Repeat password',
                'required' => 'required'
            ),
            'options' => array(
                'label' => 'Repeat password',
                'label_attributes' => array(
                    'class' => 'control-label'
                )
            )
        ));

        $this->add(array(
            'name' => 'passwordStrength',
            'type' => 'Zend\Form\Element\Hidden',
            'attributes' => array(
                'id' => 'passwordStrength',
                'required' => 'required'
            ),
        ));

        $this->add(array(
            'name' => 'passwordStrengthScore',
            'type' => 'Zend\Form\Element\Hidden',
            'attributes' => array(
                'id' => 'passwordStrengthScore',
                'required' => 'required'
            ),
        ));

        $this->add(array(
            'name' => 'save',
            'type' => '\Zend\Form\Element\Submit',
            'attributes' => array(
                'id' => 'save',
                'class' => 'btn btn-xl',
                'value' => 'Save'
            )
        ));
    }

}
