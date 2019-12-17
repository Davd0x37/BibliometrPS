<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AppController extends AbstractController
{
    public function index()
    {
        $number = random_int(0, 100);

        return $this->render('home.html.twig', [
            'title' => "Bilbliometr"
        ]);
    }
}