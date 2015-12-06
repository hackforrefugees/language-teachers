<?php
namespace User\Form;

/**
 * @uses Zend\InputFilter\InputFilter
 */
use Zend\Filter\StripTags;
use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;
use Zend\Validator\Callback;
use Zend\Validator\EmailAddress;
use Zend\Validator\StringLength;

/**
 * Class RegisterFilter
 * @package User\Form
 * @author Dominik Einkmmer
 */
class RegisterFilter extends InputFilter
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
                        'domain' => true
                    )
                )
            )
        ));

        $this->add(array(
            'name' => 'contactName',
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
                        'max' => 200
                    )
                )
            )
        ));

        $this->add(array(
            'name' => 'password',
            'required' => true
        ));

        $this->add(array(
            'name' => 'confirmPassword',
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'Identical',
                    'options' => array(
                        'token' => 'password',
                    ),
                ),
            )
        ));

        $this->add(array(
            'name' => 'phone',
            'required' => false
        ));

//        $this->add(array(
//            'name' => 'profilePicturePath',
//            'required' => false,
//            'type' => 'Zend\InputFilter\FileInput',
//            'filters' => array(
//                array(
//                    'name' => 'FileRenameUpload',
//                    'options' => array(
//                        'target' => __DIR__ . '/../../../../../public/img/profilePictures/',
//                        'use_upload_extension' => true,
//                        'use_upload_name' => true,
//                        'randomize' => true
//                    )
//                )
//            ),
//            'validators' => array(
//                array(
//                    'name' => 'Zend\Validator\File\Size',
//                    'options' => array(
//                        'max' => 3145728,
//                    )
//                ),
//                array(
//                    'name' => 'Zend\Validator\File\Extension',
//                    'options' => array(
//                        'jpeg',
//                        'jpg',
//                        'png',
//                        'bmp',
//                        'gif'
//                    )
//                )
//            )
//        ));

        $this->add(array(
            'name' => 'securityQuestionId',
            'required' => true
        ));

        $this->add(array(
            'name' => 'securityQuestionAnswer',
            'required' => true
        ));

        //Organisation specific fields

        $callBackOrganisationFields = new Callback(function ($value, $context) {
            return ($context['userType'] === "organisation" && trim($value) !== "");
        });

        $stripTags = new StripTags();
        $stringLengthPersonName = new StringLength(array('encoding' => 'UTF-8', 'min' => 2, 'max' => 150));

        $contactPersonNameFilter = new Input('contactPersonName');
        $contactPersonNameFilter->setRequired(false);
        $contactPersonNameFilter->getValidatorChain()
            ->attach($stringLengthPersonName)
            ->attach($callBackOrganisationFields);
        $contactPersonNameFilter->getFilterChain()->attach($stripTags);

        $this->add(array(
            'name' => 'contactPersonEmail',
            'required' => false,
            'validators' => array(
                array(
                    'name' => 'EmailAddress',
                    'options' => array(
                        'domain' => true
                    )
                )
            )
        ));
        $emailAddress = new EmailAddress(array('domain' => true));
        $contactEmailFilter = new Input('contactPersonEmail');
        $contactEmailFilter->getValidatorChain()
            ->attach($emailAddress)
            ->attach($callBackOrganisationFields);

        $this->add(array(
            'name' => 'contactPersonPhone',
            'required' => false
        ));

        $this->add(array(
            'name' => 'organisationDescription',
            'required' => false,
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
                        'max' => 200
                    )
                )
            )
        ));

        $this->add(array(
            'name' => 'organisationWebsite',
            'required' => false,
            'validators' => array(
                array(
                    'name' => 'Uri'
                )
            )
        ));

        //Volunteer specific fields
        $this->add(array(
            'name' => 'languages',
            'required' => false
        ));

        $this->add(array(
            'name' => 'region',
            'required' => false
        ));

        //Volunteer & Student specific fields
        $this->add(array(
            'name' => 'nativeLanguage',
            'required' => false
        ));
    }

}
