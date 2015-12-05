<?php

namespace User\Form;

/**
 * @uses Zend\Form\Form
 */
use Zend\Form\Form;

/**
 * Class RequestVerificationLinkForm
 * @package User\Form
 * @author Dominik Einkemmer
 */
class RequestVerificationLinkForm extends Form
{

    /**
     * Constructor which adds the Form Elements
     * @param string $name
     */
    public function __construct($name = null)
    {
        parent::__construct('Request Verification');

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
            'name' => 'request',
            'type' => '\Zend\Form\Element\Submit',
            'attributes' => array(
                'id' => 'request',
                'class' => 'btn btn-xl',
                'value' => 'Request verification link'
            )
        ));
    }

}
