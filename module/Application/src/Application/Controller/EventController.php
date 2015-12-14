<?php

namespace Application\Controller;


use Application\Entity\LtEvent;
use Application\Form\CreateEventFilter;
use Application\Form\CreateEventForm;
use Application\HelperClasses\AuthenticationHelper;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

/**
 * Class EventController
 * @package Application\Controller
 * @author Dominik Einkemmer
 */
class EventController extends AbstractRestfulController
{

    /**
     * Action that returns all the events in a given distance around a given address
     * @return JsonModel
     */
    public function indexAction()
    {
        if($this->request->isOptions()){
            return new JsonModel();
        }

        $controllerName = $this->params('controller');
        $actionName = $this->params('action');
        $authenticationHelper = new AuthenticationHelper($this->getServiceLocator());
        $headers = $this->request->getHeaders();
        $authTokenObject = $headers->get('authToken');
        $hasPermission = $authenticationHelper->checkPermissions($controllerName, $actionName, $authTokenObject);
        if (!$hasPermission) {
            $this->response->setStatusCode(401);
            return new JsonModel(array('error' => 1, 'message' => 'You don\'t have the necessary permissions to view this resource.'));
        }

        if ($this->request->isPost()) {
            $post = get_object_vars(json_decode($this->request->getContent()));
            $address = $post['address'];

            $url = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . str_replace(' ', '+', $address) . '&sensor=true';
            $googleData = file_get_contents($url);
            $googleDataArray = json_decode($googleData, true);
            $latitude = $googleDataArray['results'][0]['geometry']['location']['lat'];
            $longitude = $googleDataArray['results'][0]['geometry']['location']['lng'];

            $distance = $post['distance'];

            $adapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
            $statement = $adapter->createStatement();
            $statement->setSql("CALL spatialEventSearch($latitude, $longitude, $distance)");
            $results = $adapter->query(
                $statement->getSql(), $adapter::QUERY_MODE_EXECUTE
            );

            $eventList = array();

            foreach ($results->toArray() as $eventFromDb) {
                $eventList[] = $eventFromDb;
            }
            return new JsonModel($eventList);
        } else {
            $this->response->setStatusCode(405);
            return new JsonModel(array(
                'error' => 1,
                'message' => 'Request-Method not allowed'
            ));
        }
    }

    /**
     * Action that returns the data for a single event
     * @return JsonModel
     */
    public function getSingleEventAction()
    {
        if($this->request->isOptions()){
            return new JsonModel();
        }
        $controllerName = $this->params('controller');
        $actionName = $this->params('action');
        $authenticationHelper = new AuthenticationHelper($this->getServiceLocator());
        $headers = $this->request->getHeaders();
        $authTokenObject = $headers->get('authToken');
        $hasPermission = $authenticationHelper->checkPermissions($controllerName, $actionName, $authTokenObject);
        if (!$hasPermission) {
            $this->response->setStatusCode(401);
            return new JsonModel(array('error' => 1, 'message' => 'You don\'t have the necessary permissions to view this resource.'));
        }

        $eventId = (int)$this->params()->fromRoute('eventId', 0);
        if (!$eventId) {
            $this->response->setStatusCode(404);
            return new JsonModel(array('error' => 1, 'message' => 'No Event-Id defined'));
        }

        $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $event = $objectManager->find('Application\Entity\LtEvent', $eventId);

        $eventArray = array(
            'eventId' => $event->getEventid(),
            'eventDate' => $event->getEventtime()->format('Y-m-d'),
            'eventTime' => $event->getEventtime()->format('H:i'),
            'maxStudents' => $event->getMaxstudents(),
            'street' => $event->getStreet(),
            'streetNumber' => $event->getStreetNumber(),
            'zipCode' => $event->getZipcode(),
            'city' => $event->getCity(),
            'country' => $event->getCountry(),
            'latitude' => $event->getLatitude(),
            'longitude' => $event->getLongitude(),
            'eventLanguage' => array(
                'langCode' => $event->getEventlanguage()->getLangcode(),
                'languageName' => $event->getEventlanguage()->getLanguagename()
            ),
            'title' => $event->getEventtitle(),
        );

        if ($event->getMaxteachers() !== 0) {
            $eventArray['maxTeachers'] = $event->getMaxteachers();
        } else {
            $eventArray['maxTeachers'] = null;
        }

        $creator = $event->getCreatoruserid();

        if ($creator->getUsergroup() === 'organisation') {
            $eventArray['creator']['name'] = $creator->getContactName();

            if ($creator->getProfilepicturepath() !== '') {
                $eventArray['creator']['profilePicture'] = $creator->getProfilepicturepath();
            } else {
                $eventArray['creator']['profilePicture'] = null;
            }

            $organisation = $objectManager->find('Application\Entity\LtOrganisation', $creator->getUserId());

            $eventArray['creator']['contactPerson'] = array(
                'name' => $organisation->getContactpersonname(),
                'email' => $organisation->getContactpersonemail()
            );

            if ($organisation->getContactpersonphone() !== '') {
                $eventArray['creator']['contactPerson']['phone'] = $organisation->getContactpersonphone();
            } else {
                $eventArray['creator']['contactPerson']['phone'] = null;
            }
        } else {
            $eventArray['creator'] = array(
                'name' => $creator->getContactName(),
                'email' => $creator->getEmail(),
            );

            if ($creator->getPhone() !== '') {
                $eventArray['creator']['phone'] = $creator->getPhone();
            } else {
                $eventArray['creator']['phone'] = null;
            }

            if ($creator->getProfilepicturepath() !== '') {
                $eventArray['creator']['profilePicture'] = $creator->getProfilepicturepath();
            } else {
                $eventArray['creator']['profilePicture'] = null;
            }
        }

        $students = $event->getStudentid();

        $eventArray['availableStudentSpaces'] = $event->getMaxstudents() - $students->count();

        $volunteers = $event->getVolunteerid();
        if ($event->getMaxteachers() !== 0) {
            $eventArray['availableVolunteerSpaces'] = $event->getMaxteachers() - $volunteers->count();
        } else {
            $eventArray['availableVolunteerSpaces'] = null;
        }

        $authService = new AuthenticationService();
        $session = $authService->getStorage()->read();
        $userId = (int)$session['userId'];
        $creatorId = (int)$creator->getUserId();
        if ($userId == $creatorId) {
            if ($students->count() > 0) {
                foreach ($students as $student) {
                    $tempArray = array();
                    $user = $objectManager->find('Application\Entity\LtUser', $student->getStudentid());
                    $tempArray['name'] = $user->getContactName();
                    $tempArray['email'] = $user->getEmail();

                    if ($user->getPhone() !== '') {
                        $tempArray['phone'] = $user->getPhone();
                    } else {
                        $tempArray['phone'] = null;
                    }

                    $eventArray['students'][] = $tempArray;
                }
            } else {
                $eventArray['students'] = null;
            }

            if ($volunteers->count() > 0) {
                foreach ($volunteers as $volunteer) {
                    $tempArray = array();
                    $user = $objectManager->find('Application\Entity\LtUser', $volunteer->getVolunteerid());
                    $tempArray['name'] = $user->getContactName();
                    $tempArray['email'] = $user->getEmail();
                    if ($user->getPhone() !== '') {
                        $tempArray['phone'] = $user->getPhone();
                    } else {
                        $tempArray['phone'] = null;
                    }

                    $eventArray['volunteers'][] = $tempArray;
                }
            } else {
                $eventArray['volunteers'] = null;
            }
        }

        return new JsonModel($eventArray);
    }

    /**
     * Action for creating an Event
     * @return JsonModel
     */
    public function createEventAction()
    {
        if($this->request->isOptions()){
            return new JsonModel();
        }

        $controllerName = $this->params('controller');
        $actionName = $this->params('action');
        $authenticationHelper = new AuthenticationHelper($this->getServiceLocator());
        $headers = $this->request->getHeaders();
        $authTokenObject = $headers->get('authToken');
        $hasPermission = $authenticationHelper->checkPermissions($controllerName, $actionName, $authTokenObject);
        if (!$hasPermission) {
            $this->response->setStatusCode(401);
            return new JsonModel(array('error' => 1, 'message' => 'You don\'t have the necessary permissions to view this resource.'));
        }

        if ($this->request->isPost()) {
            $createEventForm = new CreateEventForm();
            $createEventFilter = new CreateEventFilter();
            $createEventForm->setInputFilter($createEventFilter);

            $post = get_object_vars(json_decode($this->request->getContent()));
            $createEventForm->setData($post);
            if (!$createEventForm->isValid()) {
                $errorMessages = array();
                foreach ($createEventForm->getMessages() as $elementName => $messages) {
                    foreach ($messages as $message) {
                        $errorMessages[$elementName] = $message;
                    }
                }

                return new JsonModel(array('error' => 1, 'message' => 'You have an error in your form. Please try again.', 'formErrors' => $errorMessages));
            }

            $formData = $createEventForm->getData();

            if (array_key_exists('address', $formData) && $formData['address'] !== '') {
                $address = $formData['address'];
            } else {
                $address = $formData['street'] . ' ' . $formData['streetNumber'] . ', ' . $formData['zipCode'] . ' ' . $formData['city'] . ', ' . $formData['country'];
            }

            $url = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . str_replace(' ', '+', $address) . '&sensor=true';
            $googleData = file_get_contents($url);
            $googleDataArray = json_decode($googleData, true);
            $latitude = $googleDataArray['results'][0]['geometry']['location']['lat'];
            $longitude = $googleDataArray['results'][0]['geometry']['location']['lng'];

            $authService = new AuthenticationService();
            $session = $authService->getStorage()->read();
            $userId = $session['userId'];

            $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

            $user = $objectManager->find('Application\Entity\LtUser', $userId);
            $language = $objectManager->find('Application\Entity\LtLanguage', $formData['eventLanguage']);
            $eventTime = new \DateTime($formData['eventTime']);

            unset($formData['eventLanguage'], $formData['eventTime']);

            $hydrator = new DoctrineObject($objectManager);
            $event = new LtEvent();
            $event = $hydrator->hydrate($formData, $event);
            $event->setCreatoruserid($user);
            $event->setEventlanguage($language);
            $event->setEventtime($eventTime);
            $event->setLatitude($latitude);
            $event->setLongitude($longitude);
            $objectManager->persist($event);
            $objectManager->flush();
            return new JsonModel(array(
                'error' => 0,
                'message' => 'Event created successfully'
            ));
        } else {
            $this->response->setStatusCode(405);
            return new JsonModel(array(
                'error' => 1,
                'message' => 'Request-Method not allowed'
            ));
        }
    }

    /**
     * Action for assigning the logged in student or volunteer to an event and therefore registering him as a participant
     * @return JsonModel
     */
    public function addParticipantToEventAction()
    {
        if($this->request->isOptions()){
            return new JsonModel();
        }
        $controllerName = $this->params('controller');
        $actionName = $this->params('action');
        $authenticationHelper = new AuthenticationHelper($this->getServiceLocator());
        $headers = $this->request->getHeaders();
        $authTokenObject = $headers->get('authToken');
        $hasPermission = $authenticationHelper->checkPermissions($controllerName, $actionName, $authTokenObject);
        if (!$hasPermission) {
            $this->response->setStatusCode(401);
            return new JsonModel(array('error' => 1, 'message' => 'You don\'t have the necessary permissions to view this resource.'));
        }

        if ($this->request->isPut()) {
            $eventId = $this->params()->fromRoute('eventId', 0);
            if (!$eventId) {
                $this->response->setStatusCode(404);
                return new JsonModel(array('error' => 1, 'message' => 'No Event-Id defined'));
            }
            $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
            $event = $objectManager->find('Application\Entity\LtEvent', $eventId);
            $authService = new AuthenticationService();
            $session = $authService->getStorage()->read();
            $userId = $session['userId'];

            $user = $objectManager->find('Application\Entity\LtUser', $userId);

            if ($user->getUsergroup() === 'student') {
                $student = $objectManager->find('Application\Entity\LtStudent', $userId);

                if ($event->getStudentid()->contains($student)) {
                    return new JsonModel(array(
                        'error' => 1,
                        'message' => 'You have already been registered for this event!'
                    ));
                }

                $event->addStudentid($student);
                $objectManager->persist($event);
                $objectManager->flush();
            } else {
                $volunteer = $objectManager->find('Application\Entity\LtVolunteer', $userId);
                if ($event->getVolunteerid()->contains($volunteer)) {
                    return new JsonModel(array(
                        'error' => 1,
                        'message' => 'You have already been registered for this event!'
                    ));
                }
                $event->addVolunteerid($volunteer);
                $objectManager->persist($event);
                $objectManager->flush();
            }

            $this->response->setStatusCode(201);
            return new JsonModel(array(
                'error' => 0,
                'message' => 'Successfully Registered for event'
            ));
        } else {
            $this->response->setStatusCode(405);
            return new JsonModel(array(
                'error' => 1,
                'message' => 'Request-Type not allowed'
            ));
        }
    }

    /**
     * Action for assigning the logged in student or volunteer to an event and therefore registering him as a participant
     * @return JsonModel
     */
    public function removeParticipantFromEventAction()
    {
        if($this->request->isOptions()){
            return new JsonModel();
        }
        $controllerName = $this->params('controller');
        $actionName = $this->params('action');
        $authenticationHelper = new AuthenticationHelper($this->getServiceLocator());
        $headers = $this->request->getHeaders();
        $authTokenObject = $headers->get('authToken');
        $hasPermission = $authenticationHelper->checkPermissions($controllerName, $actionName, $authTokenObject);
        if (!$hasPermission) {
            $this->response->setStatusCode(401);
            return new JsonModel(array('error' => 1, 'message' => 'You don\'t have the necessary permissions to view this resource.'));
        }

        if ($this->request->isDelete()) {
            $eventId = $this->params()->fromRoute('eventId', 0);
            if (!$eventId) {
                $this->response->setStatusCode(404);
                return new JsonModel(array('error' => 1, 'message' => 'No Event-Id defined'));
            }
            $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
            $event = $objectManager->find('Application\Entity\LtEvent', $eventId);
            $authService = new AuthenticationService();
            $session = $authService->getStorage()->read();
            $userId = $session['userId'];
            $user = $objectManager->find('Application\Entity\LtUser', $userId);

            if ($user->getUsergroup() === 'student') {
                $student = $objectManager->find('Application\Entity\LtStudent', $userId);

                if (!$event->getStudentid()->contains($student)) {
                    return new JsonModel(array(
                        'error' => 1,
                        'message' => 'You are not registered for this event so you can\'t delete your participation!'
                    ));
                }

                $event->removeStudentid($student);
                $objectManager->persist($event);
                $objectManager->flush();
            } else {
                $volunteer = $objectManager->find('Application\Entity\LtVolunteer', $userId);
                if (!$event->getVolunteerid()->contains($volunteer)) {
                    return new JsonModel(array(
                        'error' => 1,
                        'message' => 'You are not registered for this event so you can\'t delete your participation!'
                    ));
                }
                $event->removeVolunteerid($volunteer);
                $objectManager->persist($event);
                $objectManager->flush();
            }

            return new JsonModel(array(
                'error' => 0,
                'message' => 'Successfully deleted participation for event'
            ));
        } else {
            $this->response->setStatusCode(405);
            return new JsonModel(array(
                'error' => 1,
                'message' => 'Request-Type not allowed'
            ));
        }
    }

}