<?php
namespace App\Controller\Users;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Services\Users;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UsersController extends AbstractController
{
    private $objUsers = NULL;
    
    public function __construct(Users $objUsers)
    {
        $this->objUsers = $objUsers;
    }
    
    public function create(Request $objRequest)
    {
        try {
            if(!$this->objUsers instanceof Users){
                return new JsonResponse(['message'=> 'Class "App\Services\Users" not found.'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
            $user = $this->objUsers->create($objRequest);
            return new JsonResponse($user, Response::HTTP_OK);
        } catch (\RuntimeException $e) {
            return new JsonResponse(['message'=>$e->getMessage()], $e->getCode());
        } catch (\Exception $e) {
            return new JsonResponse(['message'=>$e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function list(Request $objRequest)
    {
        try {
            if(!$this->objUsers instanceof Users){
                return new JsonResponse(['message'=> 'Class "App\Services\Users" not found.'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
            $users = $this->objUsers->list($objRequest);
            return new JsonResponse($users, Response::HTTP_OK);
        } catch (NotFoundHttpException $e) {
            return new JsonResponse(['message'=>$e->getMessage()], Response::HTTP_NOT_FOUND);
        } catch (\RuntimeException $e) {
            return new JsonResponse(['message'=>$e->getMessage()], $e->getCode());
        } catch (\Exception $e) {
            return new JsonResponse(['message'=>$e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
