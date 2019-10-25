<?php
namespace App\Controller\SwitchModel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Services\SwitchModel;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Monolog\Logger;
class SwitchModelController extends AbstractController
{    
    private $objSwitchModel = NULL;
    
    public function __construct(SwitchModel $objSwitchModel){
        $this->objSwitchModel = $objSwitchModel;
    }
    
    public function getBrand(Request $objRequest)
    {
        try {
            if(!$this->objSwitchModel instanceof SwitchModel){
                return new JsonResponse(['message'=> 'Class "App\Services\SwitchModel not found."'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
            $retorno = $this->objSwitchModel->getBrand($objRequest);
            return new JsonResponse($retorno, Response::HTTP_OK);
        } catch (\RuntimeException $e) {
            return new JsonResponse(['mensagem'=>$e->getMessage()], Response::HTTP_PRECONDITION_FAILED);
        } catch (\Exception $e) {
            return new JsonResponse(['mensagem'=>$e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function postSwitchModel(Request $objRequest)
    {
        try {
            if(!$this->objSwitchModel instanceof SwitchModel){
                return new JsonResponse(['message'=> 'Class "App\Services\SwitchModel not found."'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
            $retorno = $this->objSwitchModel->create($objRequest);
            return new JsonResponse($retorno, Response::HTTP_OK);
        } catch (\RuntimeException $e) {
            return new JsonResponse(['mensagem'=>$e->getMessage()], Response::HTTP_PRECONDITION_FAILED);
        } catch (\Exception $e) {
            return new JsonResponse(['mensagem'=>$e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function getSwitchModel(Request $objRequest, int $idSwitchModel)
    {
        try {
            if(!$this->objSwitchModel instanceof SwitchModel){
                return new JsonResponse(['message'=> 'Class "App\Services\SwitchModel not found."'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
            $objSwitchModel = $this->objSwitchModel->get($idSwitchModel);
            return new JsonResponse($objSwitchModel, Response::HTTP_OK);
        } catch (NotFoundHttpException $e) {
            return new JsonResponse(NULL, Response::HTTP_NOT_FOUND);
        } catch (\RuntimeException $e) {
            return new JsonResponse(['mensagem'=>$e->getMessage()], Response::HTTP_PRECONDITION_FAILED);
        } catch (\Exception $e) {
            return new JsonResponse(['mensagem'=>$e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function getSwitchModels(Request $objRequest)
    {        
        try {
            if(!$this->objSwitchModel instanceof SwitchModel){
                return new JsonResponse(['message'=> 'Class "App\Services\SwitchModel not found."'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
            $arraySwitchModel = $this->objSwitchModel->list($objRequest);
            return new JsonResponse($arraySwitchModel, Response::HTTP_OK);
        } catch (NotFoundHttpException $e) {
            return new JsonResponse(NULL, Response::HTTP_NOT_FOUND);
        } catch (\RuntimeException $e) {
            return new JsonResponse(['mensagem'=>$e->getMessage()], Response::HTTP_PRECONDITION_FAILED);
        } catch (\Exception $e) {
            return new JsonResponse(['mensagem'=>$e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function deleteSwitchModel(int $idSwitchModel)
    {
        return new JsonResponse(['id'=>['deleteSwitchModel']], Response::HTTP_OK);
    }
    
    public function putSwitchModel(int $idSwitchModel)
    {
        return new JsonResponse(['id'=>['putSwitchModel']], Response::HTTP_OK);
    }
    
    public function patchSwitchModel(int $idSwitchModel)
    {
        return new JsonResponse(['id'=>['patchSwitchModel']], Response::HTTP_OK);
    }
}