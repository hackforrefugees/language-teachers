<?php

namespace User\Form;

/**
 * @uses Zend\InputFilter\Inputfilter
 */
use Zend\InputFilter\InputFilter;

/**
 * Class AddDescriptionFilter
 * @package User\Form
 * @author Dominik Einkemmer
 */
class AddDescriptionFilter extends InputFilter
{

    /**
     * Constructor which adds the Filters
     */
    public function __construct()
    {
        $this->add(array(
            'name' => 'shortDescription',
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
                        'min' => 15,
                        'max' => 100
                    )
                )
            )
        ));

        $this->add(array(
            'name' => 'description',
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
                        'min' => 30,
                    )
                )
            )
        ));
    }

}
