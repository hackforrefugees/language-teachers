<?php
namespace User\Form;

/**
 * @uses Zend\Form\Form
 */
use Zend\Form\Form;

/**
 * Class LoginForm
 * @package User\Form
 * @author Dominik Einkemmer
 */
class LoginForm extends Form
{
    /**
     * Constructor
     * @param null $name
     */
    public function __construct($name = null)
    {
        parent::__construct('Login');
        $this->setAttribute("method", "post");
        $this->setAttribute("id", "loginForm");
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
                    'class' => 'col-md-12 control-label'
                )
            )
        ));

        $this->add(array(
            'name' => 'password',
            'type' => '\Zend\Form\Element\Password',
            'attributes' => array(
                'id' => 'password',
                'class' => 'form-control',
                'required' => 'required',
                'placeholder' => 'Password',
            ),
            'options' => array(
                'label' => 'Password',
                'label_attributes' => array(
                    'class' => 'col-md-12 control-label'
                )
            )
        ));
    }

}
