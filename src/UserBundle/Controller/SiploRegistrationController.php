<?php
/**
 * Created by PhpStorm.
 * User: buddhikajay
 * Date: 8/11/16
 * Time: 11:15 PM
 */

namespace UserBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SiploRegistrationController extends Controller
{
    /**
     * @Route(name="register_teacher" ,path="/register/teacher")
     */
    public function registerTeacherAction()
    {
        return $this->container
            ->get('pugx_multi_user.registration_manager')
            ->register('UserBundle\Entity\Teacher');
    }

    /**
     * @Route(name="register_student", path="/register/student")
     */
    public function registerStudentAction()
    {
        return  $this->container
            ->get('pugx_multi_user.registration_manager')
            ->register('UserBundle\Entity\Student');
    }
}