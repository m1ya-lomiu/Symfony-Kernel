<?php

namespace AppBundle\EventListener;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class UserAgentSubscriber
 * @package AppBundle\EventListener
 */
class UserAgentSubscriber implements EventSubscriberInterface
{
    /** @var LoggerInterface  */
    private $logger;

    /**
     * UserAgentSubscriber constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $this->logger->info('RRRRRAWWWWWRRR');
        $request = $event->getRequest();
        $userAgenet = $request->headers->get('User-Agent');
        $this->logger->info('The user agenet is ' . $userAgenet);

        if (rand(0, 100) > 50) {
            /*$response = new Response('Come back later');
            $event->setResponse($response);*/
        }

        $isMac = stripos($userAgenet, 'mac') !== false;
        if ($request->query->get('notMac')) {
            $isMac = false;
        }
        $request->attributes->set('isMac', $isMac);

        /*$request->attributes->set('_controller', function ($id) {
            return new Response('Hello world' . $id);
        });*/
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            // kernel.request
            KernelEvents::REQUEST => 'onKernelRequest'
        ];
    }
}