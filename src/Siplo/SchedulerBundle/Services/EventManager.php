<?php
namespace Siplo\SchedulerBundle\Services;
use Doctrine\ORM\EntityManager;
use Siplo\SchedulerBundle\Entity\Event;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * Created by PhpStorm.
 * User: aseladarshan
 * Date: 1/31/16
 * Time: 4:39 PM
 */
class EventManager
{
    protected $entityManager;

    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }
    public function insertEvent($logger,$em,$userId,$startDateTime,$endDateTime){
//        $logger = $this->get('logger');
//        $em= $this->entityManager;

        $now = new \DateTime();
        if($now->getTimestamp()>($startDateTime->getTimestamp()-5*60)){
            //if start time has passed(with 5 mins)
            //change start and end time to this week
            $weekDiff = ceil(($now->getTimestamp()-$startDateTime->getTimestamp())/(7*24*60*60));
            //shift weeks
            $startDateTime->setTimeStamp($startDateTime->getTimestamp()+($weekDiff*7*24*60*60));
            $endDateTime->setTimeStamp($endDateTime->getTimestamp()+($weekDiff*7*24*60*60));

        }



        $user= $em->getRepository('SiploUserBundle:Teacher')->find($userId );
        $startTime = $startDateTime->format('H:i');
        $endTime = $endDateTime->format('H:i');
        $dayOfWeek = date('w', ($startDateTime->getTimestamp()));
        $freeSlots = $user->getEvents();
        foreach($freeSlots as $freeSlot){
            if($freeSlot->getTitle() != "Class") {
                //remove free slots which overlaps new class events
                $freeSlotEndTime = $freeSlot->getEndDateTime()->format('H:i');
                $freeSlotStartTime = $freeSlot->getStartDateTime()->format('H:i');
                $logger->debug("eventInsert :" . $freeSlot->getId() . " d:" . $freeSlot->getStartDateTime()->format('w') . " w: " . $dayOfWeek);
//

                if ($freeSlot->getStartDateTime()->format('w') == $dayOfWeek) {

                    $logger->debug("eventInsert:day".$dayOfWeek);
                    if (($freeSlotStartTime < $startTime && $freeSlotEndTime > $startTime)
                        || ($freeSlotStartTime < $endTime && $freeSlotEndTime > $endTime)
                        || ($freeSlotStartTime == $startTime && $freeSlotEndTime == $endTime)
                    ) {
                        $user->removeEvent($freeSlot);
                        $em->remove($freeSlot);
                        $logger->debug("eventInsert :remove");
                    }


                }
            }

        }
        $event = new Event("Class",$startDateTime,$endDateTime);
        $event->setBgColor("Red");
//        $em->persist($event);

        $em->merge($user);
        $em->flush();
        return $event;
//        $dayOfWeek += ($dayOfWeek == 6 ? -6:1);

//        foreach($freeSlots as $freeSlot){
//            if($freeSlot->getTitle() == "Free Slot") {
//
//                $freeSlotEndTime = $freeSlot->getEndDateTime()->format('H:i');
//                $freeSlotStartTime = $freeSlot->getStartDateTime()->format('H:i');
//                $logger->debug("eventInsert :" . $freeSlot->getId() . " d:" . $freeSlot->getStartDateTime()->format('w') . " w: " . $dayOfWeek);
////
//
//                if ($freeSlot->getStartDateTime()->format('w') == $dayOfWeek) {
//
//                $logger->debug("eventInsert:day".$dayOfWeek);
//                    if (($freeSlotStartTime < $startTime && $freeSlotEndTime >= $endTime)
//                        || ($freeSlotStartTime <= $startTime && $freeSlotEndTime > $endTime)
//                    ) {
////                        $teacher = $classRequest->getTeacher();
//
//                        //if class slot is between free slot
//
//                        $logger->debug("eventInsert :true");
//                        if ($endTime != $freeSlotEndTime) {
//
//                            $updatedEnd = new \DateTime(($endDateTime->format('Y-m-d')) . 'T' . $freeSlotEndTime);
//
//                            $newFreeSlot = new Event("Free Slot", $endDateTime, $updatedEnd);
//                            $newFreeSlot->setBgColor("Green");
//
//                            $em->persist($newFreeSlot);
//
//                            $user->addEvent($newFreeSlot);
//                        }
//                        if ($freeSlotStartTime != $startTime) {
//                            $updatedStart = new \DateTime(($startDateTime->format('Y-m-d')) . 'T' . $freeSlotStartTime);
//
//                            $newFreeSlot = new Event("Free Slot", $updatedStart, $startDateTime);
//                            $newFreeSlot->setBgColor("Green");
//
//                            $em->persist($newFreeSlot);
//
//
//                            $user->addEvent($newFreeSlot);
//                        }
//
//                        $user->removeEvent($freeSlot);
//                        $em->remove($freeSlot);
//                        $logger->debug("eventInsert :remove");
//
//                        break;
//
//                    }
//
//
//                }
//            }
//
//        }



    }

}