<?php
namespace Application\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AuthenticationEventServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $userLoginFailureMessageService =
            $serviceLocator
                ->get('authentication_event_max_logins_message');

        $userLoginFailureCountService =
            $serviceLocator
                ->get('authentication_event_max_logins_count');

        $userLoginFailure =
            $serviceLocator
                ->get('user_login_fail_event');

        $userLoginFailure->setAccountDeactivatedMessage($userLoginFailureMessageService->getValue());
        $userLoginFailure->setMaxLoginAttempts($userLoginFailureCountService->getValue());

        $userLoginSuccess =
            $serviceLocator
                ->get('user_login_success_event');

        $setUserOffice = $serviceLocator->get('user_login_set_office_event');

        $userAuthenticationService =
            $serviceLocator
                ->get('user_login_event');

        // Tracks login failures
        $userAuthenticationService->attach($userLoginFailure);
        // Handles successful login functionality (like reset of failed login attempts)
        $userAuthenticationService->attach($userLoginSuccess);
        // Handles setting session data (office) when user logs in
        $userAuthenticationService->attach($setUserOffice);

        return $userAuthenticationService;
    }
}
