<?php
/**
 * Classe de listagem de Users.
 *
 * Classe responsável por buscar os dados de Users na base de dados.
 */

namespace App\Services\Users;

use Doctrine\ORM\EntityManager;
use Monolog\Logger;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class Listing
 *
 * @package     \App\Services
 * @subpackage  Users
 * @author      reinaldo.freitas@vogeltelecom.com
 */
class Listing
{
    /**
     * Variável que irá guardar a referência do manager do ORM.
     *
     * @access  private
     * @var     \Doctrine\ORM\EntityManager
     */
    private $objEntityManager = NULL;
    
    /**
     * Variável que irá guardar a referência do serviço de log.
     *
     * @access  private
     * @var     \Monolog\Logger
     */
    private $objLogger = NULL;
    
    /**
     * Retorna a instância do objeto.
     * 
     * @param \Doctrine\ORM\EntityManager   $objEntityManager
     * @param \Monolog\Logger               $objLogger
     */
    public function __construct(EntityManager $objEntityManager, Logger $objLogger)
    {
        $this->objEntityManager = $objEntityManager;
        $this->objLogger = $objLogger;
    }

    /**
     * Método que retorna um array com os dados de um ou mais Users
     * 
     * @access  public
     * @param   array $params
     * @throws  \RuntimeException
     * @throws  \Exception
     * @return  array
     */
    public function search(array $params)
    {
        try {
            $objUsersRepository = $this->objEntityManager->getRepository("AppEntity:Authorization\Users");
            $arrayUsers = $objUsersRepository->listUsers($params);
            if(!count($arrayUsers)){
                throw new NotFoundHttpException("Users not found.");
            }
            return $objUsersRepository->listUsers($params);
        } catch (NotFoundHttpException $e){
            throw $e;
        } catch (\RuntimeException $e){
            throw $e;
        } catch (\Exception $e){
            throw $e;
        }
    }
}

