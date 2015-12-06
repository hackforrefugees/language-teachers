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
 * Class CreateEventFilter
 * @package User\Form
 * @author Dominik Einkmmer
 */
class CreateEventFilter extends InputFilter
{

    /**
     * Constructor adding the Filters for the Form fields
     */
    public function __construct()
    {
        $this->add(array(
            'name' => 'eventTitle',
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
            'name' => 'eventTime',
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'Date',
                    'options' => array(
                        'format' => 'Y-m-d H:i',
                    )
                )
            )
        ));

        $this->add(array(
            'name' => 'maxTeachers',
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'IsInt',
                ),
                array(
                    'name' => 'GreaterThan',
                    'options' => array(
                        'min' => 0,
                        'inclusive' => true
                    )
                )
            )
        ));
        $this->add(array(
            'name' => 'maxStudents',
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'IsInt',
                ),
                array(
                    'name' => 'GreaterThan',
                    'options' => array(
                        'min' => 0,
                        'inclusive' => true
                    )
                )
            )
        ));

        $this->add(array(
            'name' => 'street',
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
                        'max' => 150
                    )
                )
            )
        ));

        $this->add(array(
            'name' => 'streetNumber',
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
                        'min' => 1,
                        'max' => 5
                    )
                )
            )
        ));

        $this->add(array(
            'name' => 'zipCode',
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
                        'min' => 4,
                        'max' => 10
                    )
                )
            )
        ));

        $this->add(array(
            'name' => 'city',
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
                        'max' => 150
                    )
                )
            )
        ));

        $this->add(array(
            'name' => 'country',
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
                        'max' => 150
                    )
                )
            )
        ));

        $this->add(array(
            'name' => 'eventLanguage',
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
                        'max' => 5
                    )
                )
            )
        ));
    }

}
