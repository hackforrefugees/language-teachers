<?php
namespace Application\Form;

/**
 * @uses Zend\Form\Form
 */
use Zend\Form\Form;

/**
 * Class CreateEventForm
 * @package User\Form
 * @author Dominik Einkemmer
 */
class CreateEventForm extends Form
{
    /**
     * Constructor
     * @param null $name
     */
    public function __construct($name = null)
    {
        parent::__construct('Create Event');
        $this->setAttribute("method", "post");
        $this->setAttribute("id", "createEvent");
        $this->setAttribute("enctype", "multipart/form-data");

        $this->add(array(
            'name' => 'eventTitle',
            'type' => '\Zend\Form\Element\Text',
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'eventTitle',
                'placeholder' => 'Event-Title',
                'required' => 'required'
            ),
            'options' => array(
                'label' => 'Event-Title',
                'label_attributes' => array(
                    'class' => 'col-md-12 control-label'
                )
            )
        ));

        $this->add(array(
            'name' => 'eventTime',
            'type' => 'Zend\Form\Element\DateTime',
            'attributes' => array(
                'required' => 'required',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Event-Data & Time:',
                'label_attributes' => array(
                    'class' => 'control-label'
                )
            )
        ));

        $this->add(array(
            'name' => 'maxTeachers',
            'type' => '\Zend\Form\Element\Text',
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'maxTeachers',
                'placeholder' => 'Maximum volunteers',
                'required' => 'required'
            ),
            'options' => array(
                'label' => 'Maximum volunteers',
                'label_attributes' => array(
                    'class' => 'col-md-12 control-label'
                )
            )
        ));

        $this->add(array(
            'name' => 'maxStudents',
            'type' => '\Zend\Form\Element\Text',
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'maxStudents',
                'placeholder' => 'Maximum students',
            ),
            'options' => array(
                'label' => 'Maximum students',
                'label_attributes' => array(
                    'class' => 'col-md-12 control-label'
                )
            )
        ));

        $this->add(array(
            'name' => 'street',
            'type' => '\Zend\Form\Element\Text',
            'attributes' => array(
                'id' => 'street',
                'class' => 'form-control',
                'required' => 'required',
                'placeholder' => 'Street',
            ),
            'options' => array(
                'label' => 'Street',
                'label_attributes' => array(
                    'class' => 'col-md-12 control-label'
                )
            )
        ));

        $this->add(array(
            'name' => 'streetNumber',
            'type' => '\Zend\Form\Element\Text',
            'attributes' => array(
                'id' => 'streetNumber',
                'class' => 'form-control',
                'required' => 'required',
                'placeholder' => 'Street-Number',
            ),
            'options' => array(
                'label' => 'Street-Number',
                'label_attributes' => array(
                    'class' => 'col-md-12 control-label'
                )
            )
        ));

        $this->add(array(
            'name' => 'zipCode',
            'type' => '\Zend\Form\Element\Text',
            'attributes' => array(
                'id' => 'zipCode',
                'class' => 'form-control',
                'required' => 'required',
                'placeholder' => 'Zip-Code',
            ),
            'options' => array(
                'label' => 'Zip-Code',
                'label_attributes' => array(
                    'class' => 'col-md-12 control-label'
                )
            )
        ));

        $this->add(array(
            'name' => 'city',
            'type' => '\Zend\Form\Element\Text',
            'attributes' => array(
                'id' => 'city',
                'class' => 'form-control',
                'required' => 'required',
                'placeholder' => 'City',
            ),
            'options' => array(
                'label' => 'City',
                'label_attributes' => array(
                    'class' => 'col-md-12 control-label'
                )
            )
        ));

        $this->add(array(
            'name' => 'country',
            'type' => '\Zend\Form\Element\Text',
            'attributes' => array(
                'id' => 'country',
                'class' => 'form-control',
                'required' => 'required',
                'placeholder' => 'Country',
            ),
            'options' => array(
                'label' => 'Country',
                'label_attributes' => array(
                    'class' => 'col-md-12 control-label'
                )
            )
        ));

        $this->add(array(
            'name' => 'eventLanguage',
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Event-Language:',
                'label_attributes' => array(
                    'class' => 'control-label'
                ),
                'disable_inarray_validator' => true,
                'empty_option' => '-- Please select --'
            )
        ));
    }

}
