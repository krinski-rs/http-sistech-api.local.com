<?php
/**
 * Classe SSOAuthEventListener
 * 
 * Listener executando quando e feita qualquer requisição ao framework
 */

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
// use Symfony\Component\HttpKernel\HttpKernel;
// use Symfony\Component\HttpFoundation\Request;
// use Symfony\Component\HttpFoundation\JsonResponse;
// use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Bridge\Monolog\Logger;
// use App\Service\SSO\AuthExceptions;
// use App\Service\SSO\SSOClient;
use Symfony\Component\HttpKernel\HttpKernelInterface;
// use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class SSOAuthEventListener
 * 
 * @package     \App\EventListener
 * @author      reinaldo.freitas@vogeltelecom.com
 */
class SSOAuthEventListener
{
    /**
     * Variável que irá guardar a referência do serviço de log.
     *
     * @access  private
     * @var     \Symfony\Bridge\Monolog\Logger
     */
    private $objLogger                  = NULL;
//     private $objAuthExceptions          = NULL;
//     private $objSSoClient               = NULL;
//     private $objParameterBagInterface   = NULL;

    /**
     * Variável que irá guardar os parâmetros do serviço.
     *
     * @access  private
     * @var     array
     */
    private $corsParameters             = NULL;
    
    /**
     * Retorna a instância do objeto.
     * 
     * @access  public
     * @param   array $cors
     * @param   \Symfony\Bridge\Monolog\Logger $objLogger
     */
    public function __construct(array $cors, Logger $objLogger)
    {
        $this->corsParameters           = $cors;
        $this->objLogger                = $objLogger;
//         $this->objAuthExceptions        = $objAuthExceptions;
//         $this->objSSoClient             = $objSSoClient;
//         $this->objParameterBagInterface = $objParameterBagInterface;
    }
    
    /**
     * Método que trata o request do framwork.
     * 
     * @access  public
     * @param \Symfony\Component\HttpKernel\Event\GetResponseEvent $objGetResponseEvent
     * @return \Symfony\Component\HttpFoundation\Response|void
     */
    public function onKernelRequest(GetResponseEvent $objGetResponseEvent)
    {
        /*
         * Não faça nada se não for o MASTER_REQUEST
         */
        if (HttpKernelInterface::MASTER_REQUEST !== $objGetResponseEvent->getRequestType()) {
            return;
        }
        $objRequest = $objGetResponseEvent->getRequest();
        $method  = $objRequest->getRealMethod();
        $allowed_origin = array_search($objRequest->headers->get('origin'), $this->corsParameters['allowed_origin']);
        $allowed_origin = (($allowed_origin === FALSE) ? array_search($objRequest->getClientIp(), $this->corsParameters['allowed_origin']) : $allowed_origin);
        if($allowed_origin === FALSE){
            $objResponse = new Response();
            $objResponse->headers->set('status', 403);
            $objGetResponseEvent->setResponse($objResponse);
            return $objGetResponseEvent;
        }
        
        if ('OPTIONS' === strtoupper($method)) {
            $objResponse = new Response();
            $objResponse->headers->set('Access-Control-Allow-Origin', trim($this->corsParameters['allowed_origin'][$allowed_origin]));
            $objResponse->headers->set('Access-Control-Allow-Credentials', 'true');
            $objResponse->headers->set('Access-Control-Allow-Methods', 'POST,GET,PUT,DELETE,PATCH,OPTIONS');
            $objResponse->headers->set('Access-Control-Allow-Headers', implode(",", $this->corsParameters['allowed_headers']));
            $objResponse->headers->set('Access-Control-Max-Age', 3600);
            $objGetResponseEvent->setResponse($objResponse);
            return $objResponse;
        }
        
        if ($objRequest->headers->get('content-type') == 'application/json') {
            $data = json_decode($objGetResponseEvent->getRequest()->getContent(), true);
            $total = 0;
            if($data && count($data)){
                reset($data);
                while($total < count($data)){
                    $dado = current($data);
                    $objRequest->attributes->set(key($data), $dado);
                    $total++;
                    next($data);
                }
            }
        }
    }

    /**
     * Método que trata o response do framwork
     * 
     * @access  public
     * @param   \Symfony\Component\HttpKernel\Event\FilterResponseEvent $objFilterResponseEvent
     * @return  void
     */
    public function onKernelResponse(FilterResponseEvent $objFilterResponseEvent)
    {
        $objRequest = $objFilterResponseEvent->getRequest();
        /*
         * Execute o CORS aqui para garantir que o domínio esteja no sistema
         */
        if (HttpKernelInterface::MASTER_REQUEST !== $objFilterResponseEvent->getRequestType()) {
            return;
        }
        $allowed_origin = array_search($objRequest->headers->get('origin'), $this->corsParameters['allowed_origin']);
        $allowed_origin = (($allowed_origin === FALSE) ? array_search($objRequest->getClientIp(), $this->corsParameters['allowed_origin']) : $allowed_origin);
        if($allowed_origin === FALSE){
            $objResponse = $objFilterResponseEvent->getResponse();
            return $objResponse->headers->set('status', 403);
        }
        
        $objResponse = $objFilterResponseEvent->getResponse();
        $objResponse->headers->set('Access-Control-Allow-Origin', trim($this->corsParameters['allowed_origin'][$allowed_origin]));
        $objResponse->headers->set('Access-Control-Allow-Credentials', 'true');
        $objResponse->headers->set('Access-Control-Allow-Methods', 'POST,GET,PUT,DELETE,PATCH,OPTIONS');
        $objResponse->headers->set('Access-Control-Allow-Headers', implode(",", $this->corsParameters['allowed_headers']));
    }
}

