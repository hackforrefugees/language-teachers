<?php

namespace Application\Controller;


use Application\Entity\LtEvent;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

/**
 * Class EventController
 * @package Application\Controller
 * @author Dominik Einkemmer
 */
class EventController extends AbstractRestfulController{

    public function indexAction(){
        if($this->request->isPost()){
            $post = $this->request->getPost();
            $address = $post['address'];

            $url = 'https://maps.googleapis.com/maps/api/geocode/json?address='.str_replace(' ', '+', $address). '&sensor=true';
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

            foreach($results->toArray() as $eventFromDb){
                $eventList[]= $eventFromDb;
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

    public function getSingleEventAction(){
        $eventId = $this->params()->fromRoute('eventId', 0);
        if(!$eventId){
            $this->response->setStatusCode(404);
            return new JsonModel(array('error' => 1, 'message' => 'No Event-Id defined'));
        }

        $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $event = $objectManager->find('Application\Entity\LtEvent', $eventId);
        return new JsonModel(array('event' => $event));
    }

}