<?php
namespace App\Controller\Service;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Services\Service;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
class ServiceController extends AbstractController
{    
    private $objService = NULL;
    
    public function __construct(Service $objService){
        $this->objService = $objService;
    }
    
    public function postService(Request $objRequest)
    {
        try {
            if(!$this->objService instanceof Service){
                return new JsonResponse(['message'=> 'Class "App\Service\Service not found."'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
            $retorno = $this->objService->create($objRequest);
            return new JsonResponse($retorno, Response::HTTP_OK);
        } catch (\RuntimeException $e) {
            return new JsonResponse(['mensagem'=>$e->getMessage()], Response::HTTP_PRECONDITION_FAILED);
        } catch (\Exception $e) {
            return new JsonResponse(['mensagem'=>$e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function getService(Request $objRequest, int $idService)
    {
        try {
            if(!$this->objService instanceof Service){
                return new JsonResponse(['message'=> 'Class "App\Service\Service not found."'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
            $objService = $this->objService->get($idService);
            return new JsonResponse($objService, Response::HTTP_OK);
        } catch (NotFoundHttpException $e) {
            return new JsonResponse(NULL, Response::HTTP_NOT_FOUND);
        } catch (\RuntimeException $e) {
            return new JsonResponse(['mensagem'=>$e->getMessage()], Response::HTTP_PRECONDITION_FAILED);
        } catch (\Exception $e) {
            return new JsonResponse(['mensagem'=>$e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function getServices(Request $objRequest)
    {        
        try {
            if(!$this->objService instanceof Service){
                return new JsonResponse(['message'=> 'Class "App\Service\Service not found."'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
            $arrayService = $this->objService->list($objRequest);
            return new JsonResponse($arrayService, Response::HTTP_OK);
        } catch (NotFoundHttpException $e) {
            return new JsonResponse(NULL, Response::HTTP_NOT_FOUND);
        } catch (\RuntimeException $e) {
            return new JsonResponse(['mensagem'=>$e->getMessage()], Response::HTTP_PRECONDITION_FAILED);
        } catch (\Exception $e) {
            return new JsonResponse(['mensagem'=>$e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function deleteService(int $idService)
    {
        return new JsonResponse(['id'=>['deleteService']], Response::HTTP_OK);
    }
    
    public function putService(int $idService)
    {
        return new JsonResponse(['id'=>['putService']], Response::HTTP_OK);
    }
    
    public function patchService(int $idService)
    {
        return new JsonResponse(['id'=>['patchService']], Response::HTTP_OK);
    }
}