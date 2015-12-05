<?php

namespace User\Form;

/**
 * @uses Zend\Form\Form
 */
use Zend\Form\Form;

/**
 * Class AddDescriptionForm
 *
 * @category User
 * @package User_Form
 * @author Dominik Einkemmer
 */
class AddDescriptionForm extends Form
{

    /**
     * Constructor which adds the Form Elements
     * @param string $name
     */
    public function __construct($name = null)
    {
        parent::__construct('Add Translation');
        $this->setAttribute("method", "post");
        $this->setAttribute("enctype", "multipart/form-data");

        $this->add(array(
            'name' => 'shortDescription',
            'type' => '\Zend\Form\Element\Textarea',
            'attributes' => array(
                'id' => 'shortDescription',
                'class' => 'form-control',
                'required' => 'required',
                'placeholder' => 'Short-Description',
            ),
            'options' => array(
                'label' => 'Short-Description',
                'label_attributes' => array(
                    'class' => 'col-md-12 control-label'
                )
            )
        ));

        $this->add(array(
            'name' => 'description',
            'type' => '\Zend\Form\Element\Textarea',
            'attributes' => array(
                'id' => 'description',
                'class' => 'form-control',
                'required' => 'required',
                'placeholder' => 'Description',
            ),
            'options' => array(
                'label' => 'Description',
                'label_attributes' => array(
                    'class' => 'col-md-12 control-label'
                )
            )
        ));

        $this->add(array(
            'name' => 'submit',
            'type' => '\Zend\Form\Element\Submit',
            'attributes' => array(
                'id' => 'submit',
                'class' => 'btn btn-xl',
                'value' => 'Save description'
            ),
        ));
    }

}
