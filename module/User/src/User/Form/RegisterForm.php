<?php
namespace User\Form;

/**
 * @uses Zend\Form\Form
 */
use Zend\Form\Form;

/**
 * Class RegisterForm
 * @package User\Form
 * @author Dominik Einkemmer
 */
class RegisterForm extends Form
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
            'name' => 'userType',
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'required' => 'required',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Register as:',
                'label_attributes' => array(
                    'class' => 'control-label'
                ),
                'empty_option' => '-- Please select --',
                'value_options' => array(
                    'student' => 'Student',
                    'volunteer' => 'Volunteer',
                    'organisation' => 'Organisation',
                ),
            )
        ));

        $this->add(array(
            'name' => 'contactName',
            'type' => '\Zend\Form\Element\Text',
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'contactName',
                'placeholder' => 'Name',
                'required' => 'required'
            ),
            'options' => array(
                'label' => 'Name',
                'label_attributes' => array(
                    'class' => 'col-md-12 control-label'
                )
            )
        ));

        $this->add(array(
            'name' => 'phone',
            'type' => '\Zend\Form\Element\Text',
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'phone',
                'placeholder' => 'Phone',
            ),
            'options' => array(
                'label' => 'Phone',
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

        $this->add(array(
            'name' => 'confirmPassword',
            'type' => '\Zend\Form\Element\Password',
            'attributes' => array(
                'id' => 'confirmPassword',
                'class' => 'form-control',
                'required' => 'required',
                'placeholder' => 'Repeat-Password',
            ),
            'options' => array(
                'label' => 'Repeat-Password',
                'label_attributes' => array(
                    'class' => 'col-md-12 control-label'
                )
            )
        ));

        $this->add(array(
            'name' => 'profilePicturePath',
            'type' => '\Zend\Form\Element\File',
            'attributes' => array(
                'id' => 'profilePicturePath',
                'class' => 'form-control',
                'required' => 'required',
                'accept' => 'image/*'
            ),
            'options' => array(
                'label' => 'Profile-Picture',
                'label_attributes' => array(
                    'class' => 'col-md-12 control-label'
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
                'label_attributes' => array(
                    'class' => 'control-label'
                ),
                'disable_inarray_validator' => true,
                'empty_option' => '-- Please select --'
            )
        ));

        $this->add(array(
            'name' => 'securityQuestionAnswer',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
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

        //Organisation specific fields
        $this->add(array(
            'name' => 'contactPersonName',
            'type' => '\Zend\Form\Element\Text',
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'phone',
                'placeholder' => 'Name of Contact-Person',
            ),
            'options' => array(
                'label' => 'Name of Contact-Person',
                'label_attributes' => array(
                    'class' => 'col-md-12 control-label'
                )
            )
        ));

        $this->add(array(
            'name' => 'contactPersonEmail',
            'type' => '\Zend\Form\Element\Text',
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'contactPersonEmail',
                'placeholder' => 'Email of Contact-Person',
            ),
            'options' => array(
                'label' => 'Email of Contact-Person',
                'label_attributes' => array(
                    'class' => 'col-md-12 control-label'
                )
            )
        ));

        $this->add(array(
            'name' => 'contactPersonPhone',
            'type' => '\Zend\Form\Element\Text',
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'contactPersonPhone',
                'placeholder' => 'Phone of Contact-Person',
            ),
            'options' => array(
                'label' => 'Phone of Contact-Person',
                'label_attributes' => array(
                    'class' => 'col-md-12 control-label'
                )
            )
        ));

        $this->add(array(
            'name' => 'organisationDescription',
            'type' => '\Zend\Form\Element\Textarea',
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'organisationDescription',
                'placeholder' => 'Description of Organisation',
            ),
            'options' => array(
                'label' => 'Description of Organisation',
                'label_attributes' => array(
                    'class' => 'col-md-12 control-label'
                )
            )
        ));

        $this->add(array(
            'name' => 'organisationWebsite',
            'type' => '\Zend\Form\Element\Text',
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'organisationWebsite',
                'placeholder' => 'Website of Organisation',
            ),
            'options' => array(
                'label' => 'Website of Organisation',
                'label_attributes' => array(
                    'class' => 'col-md-12 control-label'
                )
            )
        ));

        //Volunteer specific fields
        $this->add(array(
            'name' => 'region',
            'type' => '\Zend\Form\Element\Text',
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'region',
                'placeholder' => 'Region you help in',
            ),
            'options' => array(
                'label' => 'Region you help in',
                'label_attributes' => array(
                    'class' => 'col-md-12 control-label'
                )
            )
        ));

        //Volunteer & Student specific fields
        $this->add(array(
            'name' => 'nativeLanguage',
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Native-Language:',
                'label_attributes' => array(
                    'class' => 'control-label'
                ),
                'disable_inarray_validator' => true,
                'empty_option' => '-- Please select --'
            )
        ));
    }

}
