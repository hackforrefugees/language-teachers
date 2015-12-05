<?php

namespace User\Form;

/**
 * @uses Zend\InputFilter\Inputfilter
 */
use Zend\InputFilter\InputFilter;

/**
 * Class UpdateProfilePictureFilter
 * @package User\Form
 * @author Dominik Einkemmer
 */
class UpdateProfilePictureFilter extends InputFilter
{

    /**
     * Constructor which adds the Filters
     */
    public function __construct()
    {
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
    }

}
