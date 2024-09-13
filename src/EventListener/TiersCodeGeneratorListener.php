<?php

namespace App\EventListener;

use Psr\Log\LoggerInterface;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use App\Entity\Tiers;
use App\Service\GenerateCodeService;

class TiersCodeGeneratorListener
{
    private $numberService;
    private $logger;

    public function __construct(GenerateCodeService $numberService, LoggerInterface $logger)
    {
        $this->numberService = $numberService;
        $this->logger = $logger;
        $this->logger->info('TiersCodeGeneratorListener instantiated');
    }

    public function prePersist(Tiers $tiers, LifecycleEventArgs $event): void
    {
        $this->logger->info('prePersist called for Tiers: ' . $tiers->getIntitule());
    $code = $this->numberService->generateCode('client');
    $tiers->setCode($code);
    $this->logger->info('Generated code for Tiers: ' . $code);
    }
}
