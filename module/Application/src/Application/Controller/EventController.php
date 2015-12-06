<?php

namespace Application\Controller;


use Application\Entity\LtEvent;
use Application\Form\CreateEventForm;
use Application\Form\CreateEventFilter;
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

    public function indexAction()
    {
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

    public function getSingleEventAction()
    {
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

        $volunteers = $event->getVolunteerid();
        if ($event->getMaxteachers() !== 0) {
            $eventArray['availableVolunteerSpaces'] = $event->getMaxteachers() - $volunteers->count();
        } else {
            $eventArray['availableVolunteerSpaces'] = null;
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

        return new JsonModel($eventArray);
    }

    public function createEventAction()
    {
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
            $authService = new AuthenticationService();
            $session = $authService->getStorage()->read();
            $userId = $session['userId'];

            $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

            $user = $objectManager->find('Application\Entity\LtUser', $userId);
            $language = $objectManager->find('Application\Entity\LtLanguage', $formData['eventLanguage']);

            unset($formData['eventLanguage']);

            $hydrator = new DoctrineObject($objectManager);
            $event = new LtEvent();
            $event = $hydrator->hydrate($formData, $event);
            die(var_dump($event));
        } else {
            $this->response->setStatusCode(405);
            return new JsonModel(array(
                'error' => 1,
                'message' => 'Request-Method not allowed'
            ));
        }
    }
}