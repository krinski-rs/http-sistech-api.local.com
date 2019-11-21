<?php
namespace App\Controller\Pop;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Services\Pop;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Psr\Log\LoggerInterface;
class PopController extends AbstractController
{    
    private $objPop = NULL;
    private $objLogger  = NULL;
    
    public function __construct(Pop $objPop, LoggerInterface $objLogger){
        $this->objPop = $objPop;
        $this->objLogger = $objLogger;
    }
        
    public function postPop(Request $objRequest)
    {
        try {
            if(!$this->objPop instanceof Pop){
                return new JsonResponse(['message'=> 'Class "App\Services\Pop not found."'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
            $retorno = $this->objPop->create($objRequest);
            return new JsonResponse($retorno, Response::HTTP_OK);
        } catch (\RuntimeException $e) {
            return new JsonResponse(['message'=>$e->getMessage()], Response::HTTP_PRECONDITION_FAILED);
        } catch (\Exception $e) {
            return new JsonResponse(['message'=>$e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function getPop(Request $objRequest, int $idPop)
    {
        try {
            if(!$this->objPop instanceof Pop){
                return new JsonResponse(['message'=> 'Class "App\Services\Pop not found."'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
            $objPop = $this->objPop->get($idPop);
            return new JsonResponse($objPop, Response::HTTP_OK);
        } catch (NotFoundHttpException $e) {
            return new JsonResponse(NULL, Response::HTTP_NOT_FOUND);
        } catch (\RuntimeException $e) {
            return new JsonResponse(['message'=>$e->getMessage()], Response::HTTP_PRECONDITION_FAILED);
        } catch (\Exception $e) {
            return new JsonResponse(['message'=>$e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function getPops(Request $objRequest)
    {        
        try {
            if(!$this->objPop instanceof Pop){
                return new JsonResponse(['message'=> 'Class "App\Services\Pop not found."'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
            $arrayPop = $this->objPop->list($objRequest);
            return new JsonResponse($arrayPop, Response::HTTP_OK);
        } catch (NotFoundHttpException $e) {
            return new JsonResponse(NULL, Response::HTTP_NOT_FOUND);
        } catch (\RuntimeException $e) {
            return new JsonResponse(['message'=>$e->getMessage()], Response::HTTP_PRECONDITION_FAILED);
        } catch (\Exception $e) {
            return new JsonResponse(['message'=>$e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function deletePop(int $idPop)
    {
        return new JsonResponse(['id'=>['deletePop']], Response::HTTP_OK);
    }
    
    public function putPop(int $idPop)
    {
        return new JsonResponse(['id'=>['putPop']], Response::HTTP_OK);
    }
    
    public function patchPop(int $idPop)
    {
        return new JsonResponse(['id'=>['patchPop']], Response::HTTP_OK);
    }
}