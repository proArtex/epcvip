<?php declare(strict_types=1);

namespace App\EventSubscriber;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\SecurityBundle\Security\FirewallMap;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\TerminateEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ApiRequestResponseLogEventSubscriber implements EventSubscriberInterface
{
    /**
     * @var FirewallMap
     */
    private $firewallMap;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(FirewallMap $firewallMap, LoggerInterface $logger)
    {
        $this->firewallMap = $firewallMap;
        $this->logger = $logger;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::TERMINATE => 'logRequestAndResponse'
        ];
    }

    public function logRequestAndResponse(TerminateEvent $event): void
    {
        $request = $event->getRequest();
        $response = $event->getResponse();
        $firewallConfig = $this->firewallMap->getFirewallConfig($request);
        $firewallName = $firewallConfig->getName();

        if ($firewallName !== 'api') {
            return;
        }

        $this->logger->info('Processed request', [
            'method' => $request->server->get('REQUEST_METHOD'),
            'url' => $request->server->get('REQUEST_URI'),
            'ip' => $request->server->get('REMOTE_ADDR'),
            'referrer' => $request->server->get('HTTP_REFERER'),
            'headers' => $request->headers->all(),
            'body' => $request->getContent()
        ]);

        $this->logger->info('Sent response', [
            'code' => $response->getStatusCode(),
            'headers' => $response->headers->all(),
            'body' => $response->getContent()
        ]);
    }
}
