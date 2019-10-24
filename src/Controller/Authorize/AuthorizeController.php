<?php
namespace App\Controller\Authorize;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AuthorizeController extends AbstractController
{

    public function index()
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/Authorize/AuthorizeController.php',
        ]);
    }
    public function login()
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/Authorize/AuthorizeController.php',
        ]);
    }
}
