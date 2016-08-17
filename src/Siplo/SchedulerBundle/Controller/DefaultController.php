<?php

namespace Siplo\SchedulerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{

    public function showScheduleAction(){
        $calendar= $this->render('SiploSchedulerBundle::Calendar/classSchedule.html.twig')->getContent();
        $response = array("code" => 100, "success" => true,"calendar" => $calendar);
        return new Response(json_encode($response));
    }
}
