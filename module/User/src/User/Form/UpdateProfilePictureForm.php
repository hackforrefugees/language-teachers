<?php

namespace User\Form;

/**
 * @uses Zend\Form\Form
 */
use Zend\Form\Form;

/**
 * Class UpdateProfilePictureForm
 * @package User\Form
 * @author Dominik Einkemmer
 */
class UpdateProfilePictureForm extends Form
{

    /**
     * Constructor which adds the Form Elements
     * @param string $name
     */
    public function __construct($name = null)
    {
        parent::__construct('Register');

        $this->setAttribute("method", "post");
        $this->setAttribute("enctype", "multipart/form-data");


        $this->add(array(
            'name' => 'profilePicture',
            'type' => '\Zend\Form\Element\File',
            'attributes' => array(
                'class' => 'avatar-input',
                'id' => 'profilePicture',
                'accept' => 'image/*',
            ),
            'options' => array(
                'label' => 'Profile-Picture',
                'label_attributes' => array(
                    'class' => 'col-md-2 col-xs-12 control-label'
                )
            )
        ));

        $this->add(array(
            'name' => 'profilePictureSrc',
            'type' => '\Zend\Form\Element\Hidden',
            'attributes' => array(
                'class' => 'avatar-src',
                'id' => 'avatar-src',
            ),
        ));

        $this->add(array(
            'name' => 'profilePictureData',
            'type' => '\Zend\Form\Element\Hidden',
            'attributes' => array(
                'class' => 'avatar-data',
                'id' => 'avatar-data',
            ),
        ));

        $this->add(array(
            'name' => 'submit',
            'type' => '\Zend\Form\Element\Submit',
            'attributes' => array(
                'id' => 'register',
                'class' => 'btn btn-xl',
                'value' => 'Update Profile-Picture'
            )
        ));
    }

}
