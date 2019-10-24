<?php
/**
 * Classe de Integração
 *
 * Classe responsável pelas funcionalidades de usuário.
 */

namespace App\Services;

use Psr\Log\LoggerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\HttpFoundation\Request;
use App\Services\Users\Create;
use App\Services\Users\Listing;
use Doctrine\Bundle\DoctrineBundle\Registry;

/**
 * Class User
 *
 * @package \App\Services
 * @author  reinaldo.freitas@vogeltelecom.com
 */
class Users
{
    /**
     * Variável que irá guardar a referência do manager do ORM.
     *
     * @access  private
     * @var     RegistryInterface
     */
    private $objEntityManager   = NULL;

    /**
     * Variável que irá guardar a referência do serviço de log.
     *
     * @access  private
     * @var     LoggerInterface
     */
    private $objLogger  = NULL;
        
    /**
     * Retorna a instância do objeto.
     * 
     * @access  public
     * @param   Registry $objRegistry
     * @param   \Monolog\Logger   $objLogger
     */
    public function __construct(LoggerInterface $objLogger, RegistryInterface $objRegistry)
    {
        $this->objEntityManager = $objRegistry->getManager('authorize');
        $this->objLogger        = $objLogger;
    }
    
    private function getDefaultContext()
    {
        return [
            AbstractNormalizer::CALLBACKS => [
                'recordingDate' => function ($dateTime) {
                    return $dateTime instanceof \DateTime ? $dateTime->format(\DateTime::ISO8601) : NULL;
                },
                'removalDate' => function ($dateTime) {
                    return $dateTime instanceof \DateTime ? $dateTime->format(\DateTime::ISO8601) : NULL;
                },
                'expirationDate' => function ($dateTime) {
                    return $dateTime instanceof \DateTime ? $dateTime->format(\DateTime::ISO8601) : NULL;
                }
            ]
        ];
    }
    /**
     * Cria um User.
     *
     * @access  public
     * @param   Request $objRequest
     * @throws  \RuntimeException
     * @throws  \Exception
     * @return  array
     */
    
    public function create(Request $objRequest)
    {
        try {
            $objCreate = new Create($this->objEntityManager, $this->objLogger);
            $objUsers = $objCreate->insert([
                'expirationDate' => $objRequest->get('expirationDate'),
                'isActive' => $objRequest->get('isActive'),
                'name' => $objRequest->get('name'),
                'username' => $objRequest->get('email'),
                'password' =>$objRequest->get('password'),
                'confirm' =>$objRequest->get('confirm')
            ]);
            
            $objGetSetMethodNormalizer = new GetSetMethodNormalizer(null, null, null, null, null, $this->getDefaultContext());
            $objSerializer = new Serializer([$objGetSetMethodNormalizer]);
            $normalize = $objSerializer->normalize($objUsers);
            return $normalize;
        } catch (NotFoundHttpException $e){
            throw $e;
        } catch (\RuntimeException $e){
            throw $e;
        } catch (\Exception $e){
            throw $e;
        }
    }

    public function list(Request $objRequest)
    {
        try {
            $objListing = new Listing($this->objEntityManager, $this->objLogger);
            $arrayUsers = $objListing->search([
                'name'      => $objRequest->get('name', null),
                'isActive'  => $objRequest->get('isActive'),
                'username'  => $objRequest->get('username')
            ]);

            $objGetSetMethodNormalizer = new GetSetMethodNormalizer(null, null, null, null, null, $this->getDefaultContext());
            $objSerializer = new Serializer([$objGetSetMethodNormalizer]);
            $normalize = $objSerializer->normalize($arrayUsers);
            return $normalize;
        } catch (NotFoundHttpException $e){
            throw $e;
        } catch (\RuntimeException $e){
            throw $e;
        } catch (\Exception $e){
            throw $e;
        }
    }
}

