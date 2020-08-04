<?php
namespace App\EventListener;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Psr\Log\LoggerInterface;

class ControllerListener
{
    protected $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;

    }

    public function onKernelRequest(RequestEvent $event)
    {
           // var_dump($event->getRequest()->attributes);
        if(in_array($event->getRequest()->attributes->get('_controller'),
                ['App\Controller\SecurityStaffController::login',
                    'App\Controller\SecurityMemberController::login'])
          ){

             $username = $event->getRequest()->request->get('username');
             $this->logger->alert($username);
             
         }
    }
}