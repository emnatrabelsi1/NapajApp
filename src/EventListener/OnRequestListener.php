<?php
namespace App\EventListener;

use App\Helper\UserHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class OnRequestListener
{
    protected $em;
    protected $tokenStorage;

    public function __construct(EntityManagerInterface $em, TokenStorageInterface $tokenStorage, AuthorizationCheckerInterface $authorizationChecker, UserHelper $userHelper, \Twig\Environment $twig)
    {
        $this->em = $em;
        $this->tokenStorage = $tokenStorage;
        $this->authorizationChecker = $authorizationChecker;
        $this->twig = $twig;
        $this->userHelper = $userHelper;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        if($this->tokenStorage->getToken()) {
            $user = $this->tokenStorage->getToken()->getUser();
            $this->twig->addGlobal("alerts", $this->userHelper->getAlerts());
            if($this->authorizationChecker->isGranted('ROLE_ADMIN')){
            }else{
                $companyFilter = $this->em->getFilters()->enable('company');
                $companyFilter->setParameter('isNapaj', $user->getCompany()->isNapaj());
                $companyFilter->setParameter('companyId', $user->getCompany() ? $user->getCompany()->getId() : null);
            }
        }
    }
}
?>