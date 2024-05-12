<?php

namespace App\Helper;

use App\Entity\SupplyOrder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class UserHelper
{
    private $em;

    public function __construct(EntityManagerInterface $em, AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->em = $em;
        $this->authorizationChecker = $authorizationChecker;
    }

    public function getAlerts(): ?array
    {
        $soRepository = $this->em->getRepository(SupplyOrder::class);
        if($this->authorizationChecker->isGranted('ROLE_ADMIN')){
            return ['PendingSupplyOrder' => $soRepository->findAllPending()];
        }

        return [];
    }
}
