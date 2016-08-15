<?php
/**
 * User: buddhikajay
 * Date: 8/15/16
 * Time: 7:27 PM
 */

// src/Acme/DemoBundle/EventListener/RegistrationListener.php

namespace UserBundle\EventListener;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use UserBundle\Entity\Student;
use UserBundle\Entity\Teacher;

/**
 * Listener responsible for adding the default user role at registration
 */
class RegistrationListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::REGISTRATION_SUCCESS => 'onRegistrationSuccess',
        );
    }

    public function onRegistrationSuccess(FormEvent $event)
    {
//        $rolesArr = array('ROLE_STUDENT');

        /** @var $user \FOS\UserBundle\Model\UserInterface */
        $user = $event->getForm()->getData();
        if($user instanceof Student){
            $user->addRole('ROLE_STUDENT');
        }
        if($user instanceof Teacher){
            $user->addRole('ROLE_TEACHER');
        }
    }
}