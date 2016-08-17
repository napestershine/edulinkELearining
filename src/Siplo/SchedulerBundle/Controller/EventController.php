<?php

namespace Siplo\SchedulerBundle\Controller;

use DateTime;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Siplo\SchedulerBundle\Entity\Event;
use Siplo\SchedulerBundle\Form\EventType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Event controller.
 *
 * @Route("/events")
 */
class EventController extends Controller
{

    /**
     * Lists all Event entities.
     *
     * @Route("/", name="events")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('SiploSchedulerBundle:Event')->findAll();

        return array(
            'entities' => $entities,
        );
    }
//function to select events in date time range
//http://stackoverflow.com/questions/11553183/select-entries-between-dates-in-doctrine-2
    public function getEventsInRange(DateTime $start, DateTime $end){
        $repository = $this->getDoctrine()->getRepository('SiploSchedulerBundle:Event');
        $query = $repository->createQueryBuilder('event')
            ->where('event.startDatetime BETWEEN :start AND :end WHERE event.id IN ()')
            ->setParameter('start', $start->format('Y-m-d'))
            ->setParameter('end', $end->format('Y-m-d'))
            ->orderBy('event.startDatetime', 'ASC')
            ->getQuery();
        $events = $query->getResult();
    }

    /**
     * Creates a new Event entity.
     *
     * @Route("/t", name="events_create")
     * @Method("POST")
     * @Template("SiploSchedulerBundle:Event:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Event("Free", new \DateTime(), new \DateTime(), true);
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('events_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }


    /**
     * Creates a new Event entity.
     *
     * @Route("/", name="event_create_ajax", condition="request.isXmlHttpRequest()")
     * condition: "context.getMethod() in ['GET', 'HEAD'] and request.headers.get('Content-Type') matches '/firefox/i'"
     * @Method("POST")
     * @Template("SiploSchedulerBundle:Event:new.html.twig")
     */
    public function createAjaxAction(Request $request)
    {


        $logger = $this->get('logger');
        $em = $this->getDoctrine()->getManager();

        $logger->debug("SchedulerBundle dateTime ", [date_create()]);
        $data = $request->request->get('data');
        $logger->debug("SchedulerBundle row data",[$data]);
        if($data!=null){
            $logger->debug("SchedulerBundle: Create Event : success :data:",[$data]);
            $jsonArray = json_decode($data);
            $logger->debug("SchedulerBundle array", [$jsonArray]);
            foreach($jsonArray as $phpArray){
                $entity = new Event($phpArray->title, new \DateTime($phpArray->start), new \DateTime($phpArray->end), $phpArray->allDay);
                $logger->debug("SchedulerBundle accessing elements",[$phpArray->title, new \DateTime($phpArray->start), new \DateTime($phpArray->end), $phpArray->allDay]);
                $em->persist($entity);
                $em->flush();
                $logger->debug("SchedulerBundle id of persisted event", [$entity->getId()]);
                $user = $this->getUser();
                $logger->debug("SchedulerBundle id of current user", [$user->getId()]);
                $user->addEvent($entity);
                $em->flush();
            }
            return new Response(json_encode(array("code"=>201, "success"=>true)));
        }
        else{
            $logger->debug("SchedulerBundle: Create Event : err : Null Data");
            return new Response(json_encode(array("code"=>400, "success"=>false)));
        }


    }

    /**
     * Creates a new Event entity.
     *
     * @Route("/update", name="event_update_ajax", condition="request.isXmlHttpRequest()")
     * condition: "context.getMethod() in ['GET', 'HEAD'] and request.headers.get('Content-Type') matches '/firefox/i'"
     * @Method("POST")
     * @Template("SiploSchedulerBundle:Event:new.html.twig")
     */
    public function updateAjaxAction(Request $request)
    {


        $logger = $this->get('logger');
//        $em = $this->getDoctrine()->getManager();

        $logger->debug("SchedulerBundle dateTime ", [date_create()]);
        $data = $request->request->get('data');
//        $id = $data.id;
        $logger->debug("SchedulerBundle row data",[$data]);
        if($data!=null){
            $logger->debug("SchedulerBundle: Update Event : success :data:",[$data]);
            $jsonArray = json_decode($data);
            $logger->debug("SchedulerBundle array", [$jsonArray]);
            foreach($jsonArray as $phpArray){
                $id= $phpArray ->id;
//                $entity = new Event($phpArray->title, new \DateTime($phpArray->start), new \DateTime($phpArray->end), $phpArray->allDay);
//                $logger->debug("SchedulerBundle accessing elements",[$phpArray->title, new \DateTime($phpArray->start), new \DateTime($phpArray->end), $phpArray->allDay]);
//                $em->persist($entity);
//                $em->flush();


                $em = $this->getDoctrine()->getManager();
                $entity =  $em->getRepository('SiploSchedulerBundle:Event')->find($id);

                if (!$entity) {
                    throw $this->createNotFoundException(
                        'No entity found for id '.$id
                    );
                }

                $entity-> setStartDatetime(new \DateTime($phpArray->start));
                $entity-> setEndDatetime(new \DateTime($phpArray->end));
                $em->flush();
                $logger->debug("SchedulerBundle id of persisted event", [$entity->getId()]);
//                $user = $this->getUser();
//                $logger->debug("SchedulerBundle id of current user", [$user->getId()]);
//                $user->addEvent($entity);
//                $em->flush();
            }
            return new Response(json_encode(array("code"=>201, "success"=>true)));
        }
        else{
            $logger->debug("SchedulerBundle: Update Event : err : Null Data");
            return new Response(json_encode(array("code"=>400, "success"=>false)));
        }


    }

    /**
     * Creates a form to create a Event entity.
     *
     * @param Event $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Event $entity)
    {
        $form = $this->createForm(new EventType(), $entity, array(
            'action' => $this->generateUrl('events_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Event entity.
     *
     * @Route("/new", name="events_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Event();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Event entity.
     *
     * @Route("/{id}", name="events_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SiploSchedulerBundle:Event')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Event entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Finds and displays a Event entity.
     *
     * @Route("/", name="event_show_ajax", condition="request.isXmlHttpRequest()")
     * @Method("GET")
     */
    public function showAjaxAction(){

    }

    /**
     * @Route("/load/test", name="load_events_ajax")
     */
    public function redirectToShowFreeSlots($start, $end, $u_id){
        return $this->redirect("/events/free");
    }

    /**
     * Finds and displays a Free time slots of the an user.
     *
     * @Route("/free", name="event_show_free_slots_ajax")
     * @Method("POST")
     */
    public function showFreeSlotsOfTeacherAction(){
        $request = $this->container->get('request');
        $userId = $request->request->get('userId');
        $logger = $this->get('logger');
        $logger->debug("SchedulerBundle: teacherID", [$userId]);
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('SiploUserBundle:User')->findOneBy(
            array('id' => $userId)
        );
        if($user!=null){
            $allEvents =  $user->getEvents();
            $freeEvent = null;
            $freeEventsArr = null;
            foreach($allEvents as $eachEvent){
                if($eachEvent->getTitle() == "Free Slot"){

                    $freeEvent = array(
                        "id" => $eachEvent->getId(),
                        "title"=>"",
                        "start"=>$eachEvent->getStartDatetime()->format(DateTime::ISO8601),
                        "end"=>$eachEvent->getEndDatetime()->format(DateTime::ISO8601),
                        "allDay"=>$eachEvent->getAllDay(),
                        "color"=>$eachEvent->getBgColor(),
                    );
                    $freeEventsArr[] = $freeEvent;
                }

            }
            $logger->debug("SchedulerBundle : showFreeSlotsOfTeacherAction",[$freeEventsArr]);
            return new Response(json_encode($freeEventsArr));
//            $logger->debug("SchedulerBundle : showFreeSlotsOfTeacherAction",[$allEvents]);
//            return new Response(json_encode($allEvents[0]));
        }
        throw new AccessDeniedException();
    }

    /**
     * Finds and displays all time slots of the an user.
     *
     * @Route("/all", name="event_show_all_slots_ajax")
     * @Method("POST")
     */
    public function showAllSlotsOfUserAction(){
        $request = $this->container->get('request');
        $userId = $request->request->get('userId');
        $logger = $this->get('logger');
        $logger->debug("SchedulerBundle: userID", [$userId]);
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('SiploUserBundle:User')->findOneBy(
            array('id' => $userId)
        );
        if($user!=null){
            $allEvents =  $user->getEvents();
            $freeEvent = null;
            $freeEventsArr = null;
            foreach($allEvents as $eachEvent){
//                if($eachEvent->getTitle() == "Free Slot"){

                    $freeEvent = array(
                        "id" => $eachEvent->getId(),
                        "title"=>$eachEvent->getTitle(),
                        "start"=>$eachEvent->getStartDatetime()->format(DateTime::ISO8601),
                        "end"=>$eachEvent->getEndDatetime()->format(DateTime::ISO8601),
                        "allDay"=>$eachEvent->getAllDay(),
                        "color"=>$eachEvent->getBgColor(),
                        "url"=>$eachEvent->getUrl(),
                    );
//                }
                $freeEventsArr[] = $freeEvent;
            }
            $logger->debug("SchedulerBundle : showAllSlotsOfUserAction",[$freeEventsArr]);
            return new Response(json_encode($freeEventsArr));
//            $logger->debug("SchedulerBundle : showFreeSlotsOfTeacherAction",[$allEvents]);
//            return new Response(json_encode($allEvents[0]));
        }
        throw new AccessDeniedException();
    }

    /**
     * Deletes a Event entity.
     *
     * @Route("/delete", name="events_delete_ajax")
     */
    public function deleteAjaxAction(Request $request)
    {
        $events = $request->get('data');


        $em = $this->getDoctrine()->getManager();
        foreach ($events as $eventId){
            $entity = $em->getRepository('SiploSchedulerBundle:Event')->find($eventId);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Event entity.');
            }

            $em->remove($entity);
        }
        $em->flush();


        return new Response(json_encode(array("code"=>201, "success"=>true)));
    }
    /**
     * Displays a form to edit an existing Event entity.
     *
     * @Route("/{id}/edit", name="events_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SiploSchedulerBundle:Event')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Event entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Event entity.
    *
    * @param Event $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Event $entity)
    {
        $form = $this->createForm(new EventType(), $entity, array(
            'action' => $this->generateUrl('events_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Event entity.
     *
     * @Route("/{id}", name="events_update")
     * @Method("PUT")
     * @Template("SiploSchedulerBundle:Event:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SiploSchedulerBundle:Event')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Event entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('events_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Event entity.
     *
     * @Route("/{id}", name="events_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SiploSchedulerBundle:Event')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Event entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('events'));
    }

    /**
     * Creates a form to delete a Event entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('events_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }

    /**
     * Finds and displays a Free time slots of the an user.
     *
     * @Route("/classEvents", name="event_show_classes_ajax")
     * @Method("POST")
     */
    public function showClassesOfUserAction(){
        $request = $this->container->get('request');
        $userId = $request->request->get('userId');
        $logger = $this->get('logger');
        $logger->debug("SchedulerBundle:showClassesOfUserAction: userID", [$userId]);
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('SiploUserBundle:User')->findOneBy(
            array('id' => $userId)
        );
        if($user!=null){
            $allEvents =  $user->getEvents();
            $freeEvent = null;
            $freeEventsArr = null;
            foreach($allEvents as $eachEvent){
                if($eachEvent->getTitle() == "Class"){

                    $freeEvent = array(
                        "id" => $eachEvent->getId(),
                        "title"=>"You have a class",
                        "start"=>$eachEvent->getStartDatetime()->format(DateTime::ISO8601),
                        "end"=>$eachEvent->getEndDatetime()->format(DateTime::ISO8601),
                        "allDay"=>$eachEvent->getAllDay(),
                        "color"=>"orange",
                    );
                    $freeEventsArr[] = $freeEvent;
                }

            }
            $logger->debug("SchedulerBundle : showClassesOfUserAction",[$freeEventsArr]);
            return new Response(json_encode($freeEventsArr));
//            $logger->debug("SchedulerBundle : showFreeSlotsOfTeacherAction",[$allEvents]);
//            return new Response(json_encode($allEvents[0]));
        }
        throw new AccessDeniedException();
    }
}
