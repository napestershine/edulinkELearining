<?php
/**
 * Created by PhpStorm.
 * User: buddhikajay
 * Date: 11/13/15
 * Time: 11:20 PM
 */

namespace UserBundle\Services;

use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

class LogoutSuccessHandler
{
    protected $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function onLogoutSuccess(Request $request)
    {
        // redirect the user to where they were before the login process begun.
        $referer_url = $request->headers->get('referer');

        $response = new RedirectResponse($referer_url);
        return $response;
    }
}