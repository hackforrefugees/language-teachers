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
     * Constructor which adds the Form Elements
     * @param string $name
     */
    public function __construct($name = null)
    {
        parent::__construct('Register');

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
            'name' => 'passwordRegister',
            'type' => '\Zend\Form\Element\Password',
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'passwordRegister',
                'placeholder' => 'Password',
                'required' => 'required'
            ),
            'options' => array(
                'label' => 'Password',
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
            'name' => 'userGroupId',
            'type' => '\Zend\Form\Element\Select',
            'attributes' => array(
                'id' => 'userGroupId',
                'class' => 'form-control',
                'required' => 'required'
            ),
            'options' => array(
                'label' => 'User-Type',
                'label_attributes' => array(
                    'class' => 'control-label',
                ),
                'disable_in_array_validator' => true,
                'empty_option' => '-- Please select --'
            )
        ));

        $this->add(array(
            'name' => 'firstName',
            'type' => '\Zend\Form\Element\Text',
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'firstName',
                'placeholder' => 'Firstname',
                'required' => 'required'
            ),
            'options' => array(
                'label' => 'Firstname',
                'label_attributes' => array(
                    'class' => 'control-label'
                )
            )
        ));

        $this->add(array(
            'name' => 'lastName',
            'type' => '\Zend\Form\Element\Text',
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'lastName',
                'placeholder' => 'Lastname',
                'required' => 'required'
            ),
            'options' => array(
                'label' => 'Lastname',
                'label_attributes' => array(
                    'class' => 'control-label'
                )
            )
        ));

        $this->add(array(
            'name' => 'birthDate',
            'type' => '\Zend\Form\Element\Date',
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'birthDate',
                'placeholder' => 'Birthday',
            ),
            'options' => array(
                'label' => 'Birthday',
                'label_attributes' => array(
                    'class' => 'control-label'
                )
            )
        ));

        $this->add(array(
            'name' => 'gender',
            'type' => '\Zend\Form\Element\Radio',
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'gender',
            ),
            'options' => array(
                'label' => 'Gender',
                'label_attributes' => array(
                    'class' => 'control-label'
                ),
                'value_options' => array(
                    'm' => 'Male',
                    'w' => 'Female'
                )
            )
        ));

        $this->add(array(
            'name' => 'minBudget',
            'type' => '\Zend\Form\Element\Text',
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'minBudget',
                'placeholder' => 'Minimum-Budget'
            ),
            'options' => array(
                'label' => 'Minimum-Budget',
                'label_attributes' => array(
                    'class' => 'control-label'
                )
            )
        ));

        $this->add(array(
            'name' => 'maxBudget',
            'type' => '\Zend\Form\Element\Text',
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'maxBudget',
                'placeholder' => 'Maximum-Budget'
            ),
            'options' => array(
                'label' => 'Maximum-Budget',
                'label_attributes' => array(
                    'class' => 'control-label'
                )
            )
        ));

        $this->add(array(
            'name' => 'minRent',
            'type' => '\Zend\Form\Element\Text',
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'minRent',
                'placeholder' => 'Minimum-Rent'
            ),
            'options' => array(
                'label' => 'Minimum-Rent',
                'label_attributes' => array(
                    'class' => 'control-label'
                )
            )
        ));

        $this->add(array(
            'name' => 'maxRent',
            'type' => '\Zend\Form\Element\Text',
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'maxRent',
                'placeholder' => 'Maximum-Rent'
            ),
            'options' => array(
                'label' => 'Maximum-Rent',
                'label_attributes' => array(
                    'class' => 'control-label'
                )
            )
        ));

        $this->add(array(
            'name' => 'currencyId',
            'type' => '\Zend\Form\Element\Select',
            'attributes' => array(
                'id' => 'currencyId',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Preferred Currency',
                'label_attributes' => array(
                    'class' => 'control-label',
                ),
                'disable_in_array_validator' => true,
                'empty_option' => '-- Please select --'
            )
        ));

        $this->add(array(
            'name' => 'areaUnitId',
            'type' => '\Zend\Form\Element\Select',
            'attributes' => array(
                'id' => 'areaUnitId',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Preferred Area-Unit',
                'label_attributes' => array(
                    'class' => 'control-label',
                ),
                'disable_in_array_validator' => true,
                'empty_option' => '-- Please select --'
            )
        ));

        $this->add(array(
            'name' => 'shortDescription',
            'type' => '\Zend\Form\Element\Textarea',
            'attributes' => array(
                'id' => 'shortDescription',
                'class' => 'form-control',
                'placeholder' => 'Short-Description',
            ),
            'options' => array(
                'label' => 'Short-Description',
                'label_attributes' => array(
                    'class' => 'control-label'
                )
            )
        ));

        $this->add(array(
            'name' => 'description',
            'type' => '\Zend\Form\Element\Textarea',
            'attributes' => array(
                'id' => 'description',
                'class' => 'form-control',
                'placeholder' => 'Description',
            ),
            'options' => array(
                'label' => 'Description',
                'label_attributes' => array(
                    'class' => 'control-label'
                )
            )
        ));

        $this->add(array(
            'name' => 'street',
            'type' => '\Zend\Form\Element\Text',
            'attributes' => array(
                'id' => 'street',
                'class' => 'form-control',
                'placeholder' => 'Street'
            ),
            'options' => array(
                'label' => 'Street',
                'label_attributes' => array(
                    'class' => 'control-label'
                )
            )
        ));

        $this->add(array(
            'name' => 'streetNumber',
            'type' => '\Zend\Form\Element\Text',
            'attributes' => array(
                'id' => 'streetNumber',
                'class' => 'form-control',
                'placeholder' => 'Street-Number'
            ),
            'options' => array(
                'label' => 'Street-Number',
                'label_attributes' => array(
                    'class' => 'control-label'
                )
            )
        ));

        $this->add(array(
            'name' => 'zipCode',
            'type' => '\Zend\Form\Element\Text',
            'attributes' => array(
                'id' => 'zipCode',
                'class' => 'form-control',
                'placeholder' => 'Zip-Code'
            ),
            'options' => array(
                'label' => 'Zip-Code',
                'label_attributes' => array(
                    'class' => 'control-label'
                )
            )
        ));

        $this->add(array(
            'name' => 'city',
            'type' => '\Zend\Form\Element\Text',
            'attributes' => array(
                'id' => 'city',
                'class' => 'form-control',
                'placeholder' => 'City'
            ),
            'options' => array(
                'label' => 'City',
                'label_attributes' => array(
                    'class' => 'control-label'
                )
            )
        ));

        $this->add(array(
            'name' => 'state',
            'type' => '\Zend\Form\Element\Text',
            'attributes' => array(
                'id' => 'state',
                'class' => 'form-control',
                'placeholder' => 'State'
            ),
            'options' => array(
                'label' => 'State',
                'label_attributes' => array(
                    'class' => 'control-label'
                )
            )
        ));

        $this->add(array(
            'name' => 'country',
            'type' => '\Zend\Form\Element\Text',
            'attributes' => array(
                'id' => 'country',
                'class' => 'form-control',
                'placeholder' => 'Country'
            ),
            'options' => array(
                'label' => 'Country',
                'label_attributes' => array(
                    'class' => 'control-label'
                )
            )
        ));

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

        $this->add(array(
            'name' => 'register',
            'type' => '\Zend\Form\Element\Submit',
            'attributes' => array(
                'id' => 'register',
                'class' => 'btn btn-xl',
                'value' => 'Register'
            )
        ));
    }

}
