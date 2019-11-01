<?php
namespace App\Controller\Switchs;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Services\Switchs;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
class SwitchsController extends AbstractController
{    
    private $objSwitchs = NULL;
    
    public function __construct(Switchs $objSwitchs){
        $this->objSwitchs = $objSwitchs;
    }
        
    public function postSwitchs(Request $objRequest)
    {
        try {
            if(!$this->objSwitchs instanceof Switchs){
                return new JsonResponse(['message'=> 'Class "App\Services\Switchs not found."'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
            $retorno = $this->objSwitchs->create($objRequest);
            return new JsonResponse($retorno, Response::HTTP_OK);
        } catch (\RuntimeException $e) {
            return new JsonResponse(['message'=>$e->getMessage()], Response::HTTP_PRECONDITION_FAILED);
        } catch (\Exception $e) {
            return new JsonResponse(['message'=>$e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function getSwitchs(Request $objRequest, int $idSwitchs)
    {
        try {
            if(!$this->objSwitchs instanceof Switchs){
                return new JsonResponse(['message'=> 'Class "App\Services\Switchs not found."'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
            $objSwitchs = $this->objSwitchs->get($idSwitchs);
            return new JsonResponse($objSwitchs, Response::HTTP_OK);
        } catch (NotFoundHttpException $e) {
            return new JsonResponse(NULL, Response::HTTP_NOT_FOUND);
        } catch (\RuntimeException $e) {
            return new JsonResponse(['message'=>$e->getMessage()], Response::HTTP_PRECONDITION_FAILED);
        } catch (\Exception $e) {
            return new JsonResponse(['message'=>$e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
        
    public function getSwitchss(Request $objRequest)
    {        
        try {
            if(!$this->objSwitchs instanceof Switchs){
                return new JsonResponse(['message'=> 'Class "App\Services\Switchs not found."'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
            $arraySwitchs = $this->objSwitchs->list($objRequest);
            return new JsonResponse($arraySwitchs, Response::HTTP_OK);
        } catch (NotFoundHttpException $e) {
            return new JsonResponse(NULL, Response::HTTP_NOT_FOUND);
        } catch (\RuntimeException $e) {
            return new JsonResponse(['message'=>$e->getMessage()], Response::HTTP_PRECONDITION_FAILED);
        } catch (\Exception $e) {
            return new JsonResponse(['message'=>$e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function deleteSwitchs(int $idSwitchs)
    {
        return new JsonResponse(['id'=>['deleteSwitchs']], Response::HTTP_OK);
    }
    
    public function putSwitchs(int $idSwitchs)
    {
        return new JsonResponse(['id'=>['putSwitchs']], Response::HTTP_OK);
    }
    
    public function patchSwitchs(int $idSwitchs)
    {
        return new JsonResponse(['id'=>['patchSwitchs']], Response::HTTP_OK);
    }
}
