<?php

namespace App\Controller;


use App\Event\TestEvent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/',name:'homepage')]
    public function homepage(EventDispatcherInterface $dispatcher): Response
    {
        return $this->render('base.html.twig');
    }
}
