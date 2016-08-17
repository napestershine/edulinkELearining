<?php

namespace AppBundle\Controller;

use DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class EventController extends Controller
{
    /**
     * @Route("/event/get", name="get_class_timeslots")
     */
    public function getTimeSlotsAction(Request $request)
    {
//        $request = $this->container->get('request');
        $user = $this->getUser();
        $logger = $this->get('logger');
        $logger->debug("SchedulerBundle: teacherID", [$user]);
        $em = $this->getDoctrine()->getManager();
//        $user = $em->getRepository('UserBundle:Student')->findOneBy(
//            array('id' => $userId)
//        );
        if($user!=null){
            $allSessions=$em->getRepository('AppBundle:Session')->findByBatchId($user->getBatch()->getId());
//            $allSessions = $user->getBatch()->getSessions();
//            $allEvents =  $user->getEvents();
            $freeEvent = null;
            $freeEventsArr = null;
            foreach($allSessions as $eachSession){
                $eachEvent = $eachSession->getEvent();
                //if($eachEvent->getTitle() == "Free Slot"){

                    $freeEvent = array(
                        "id" => $eachEvent->getId(),
                        "title"=>"",
                        "start"=>$eachEvent->getStartDatetime()->format(DateTime::ISO8601),
                        "end"=>$eachEvent->getEndDatetime()->format(DateTime::ISO8601),
                        "allDay"=>$eachEvent->getAllDay(),
                        "color"=>$eachEvent->getBgColor(),
                    );
                    $freeEventsArr[] = $freeEvent;
                //}

            }
            $logger->debug("SchedulerBundle : showFreeSlotsOfTeacherAction",[$freeEventsArr]);
            return new Response(json_encode($freeEventsArr));
//            $logger->debug("SchedulerBundle : showFreeSlotsOfTeacherAction",[$allEvents]);
//            return new Response(json_encode($allEvents[0]));
        }
        throw new AccessDeniedException();
    }
}