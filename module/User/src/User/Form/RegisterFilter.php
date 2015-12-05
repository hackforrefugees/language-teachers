<?php

namespace User\Form;

/**
 * @uses Zend\InputFilter\Inputfilter
 */
use Zend\InputFilter\InputFilter;

/**
 * Class RegisterFilter
 * @package User\Form
 * @author Dominik Einkemmer
 */
class RegisterFilter extends InputFilter
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

        $this->add(array(
            'name' => 'gender',
            'required' => false,
            'validators' => array(
                array(
                    'name' => 'InArray',
                    'options' => array(
                        'haystack' => array('m', 'w')
                    )
                )
            )
        ));

        $this->add(array(
            'name' => 'birthDate',
            'required' => false
        ));

        $this->add(array(
            'name' => 'firstName',
            'required' => true,
            'filters' => array(
                array(
                    'name' => 'StripTags'
                )
            ),
            'validators' => array(
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min' => 2,
                        'max' => 100
                    )
                )
            )
        ));

        $this->add(array(
            'name' => 'lastName',
            'required' => true,
            'filters' => array(
                array(
                    'name' => 'StripTags'
                )
            ),
            'validators' => array(
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min' => 2,
                        'max' => 100
                    )
                )
            )
        ));

        $this->add(array(
            'name' => 'passwordRegister',
            'required' => true
        ));

        $this->add(array(
            'name' => 'areaUnitId',
            'required' => false
        ));

        $this->add(array(
            'name' => 'currencyId',
            'required' => false
        ));

        $this->add(array(
            'name' => 'minBudget',
            'required' => false
        ));

        $this->add(array(
            'name' => 'maxBudget',
            'required' => false
        ));

        $this->add(array(
            'name' => 'minRent',
            'required' => false
        ));

        $this->add(array(
            'name' => 'maxRent',
            'required' => false
        ));

        $this->add(array(
            'name' => 'userGroupId',
            'required' => false
        ));

        $this->add(array(
            'name' => 'passwordStrengthScore',
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'GreaterThan',
                    'options' => array(
                        'min' => '38',
                        'message' => 'Your password is too week, please follow the guidelines.'
                    )
                )
            )
        ));

        $this->add(array(
            'name' => 'passwordStrength',
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'Identical',
                    'options' => array(
                        'token' => 'strong',
                        'message' => 'Your password is too week, please follow the guidelines.'
                    ),
                ),
            ),
        ));

        $this->add(array(
            'name' => 'confirmPassword',
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'Identical',
                    'options' => array(
                        'token' => 'passwordRegister',
                    ),
                ),
            ),
        ));

        $this->add(array(
            'name' => 'profilePicture',
            'required' => false,
            'type' => 'Zend\InputFilter\FileInput',
            'filters' => array(
                array(
                    'name' => 'FileRenameUpload',
                    'options' => array(
                        'target' => __DIR__ . '/../../../../../public/img/profilePictures/',
                        'use_upload_extension' => true,
                        'use_upload_name' => true,
                        'randomize' => true
                    ),
                ),
            ),
            'validators' => array(
                array(
                    'name' => 'Zend\Validator\File\Size',
                    'options' => array(
                        'max' => 3145728,
                    ),
                ),
                array(
                    'name' => 'Zend\Validator\File\Extension',
                    'options' => array(
                        'jpeg',
                        'jpg',
                        'png',
                        'bmp',
                        'gif',
                    ),
                ),
            )
        ));

        $this->add(array(
            'name' => 'securityQuestionId',
            'required' => true
        ));

        $this->add(array(
            'name' => 'securityQuestionAnswer',
            'required' => true
        ));
    }

}
