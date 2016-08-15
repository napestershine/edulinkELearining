<?php
/**
 * Created by PhpStorm.
 * User: aseladarshan
 * Date: 8/15/16
 * Time: 6:19 PM
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class ClassroomController extends Controller
{
    /**
     * @Route("/classroom/student", name="studentClassroom")
     */
    public function getStudentClassroomAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('@App/classroom/studentClassroom.html.twig');
    }

    /**
     * @Route("/classroom/teacher/video", name="teacherVideo")
     */
    public function getTeacherVideoAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('@App/classroom/teacherVideo.html.twig');
    }
    /**
     * @Route("/classroom/teacher/whiteboard", name="teacherWhiteboard")
     */
    public function getTeacherWhiteboardAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('@App/classroom/teacherWhiteboard.html.twig');
    }
}