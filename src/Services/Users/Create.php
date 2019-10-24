<?php
/**
 * Classe de criação de Users.
 *
 * Classe responsável por persistir os dados de um Users na Base de dados.
 */

namespace App\Services\Users;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\ConstraintViolationList;
use Doctrine\ORM\EntityManager;
use Monolog\Logger;
use App\Entity\Authorization\Users;
use Symfony\Component\Security\Core\Encoder\NativePasswordEncoder;

/**
 * Class Create
 *
 * @package     \App\Services
 * @subpackage  Users
 * @author      reinaldo.freitas@vogeltelecom.com
 */
class Create
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
     * @access  public
     * @param   \Doctrine\ORM\EntityManager $objEntityManager
     * @param   \Monolog\Logger $objLogger
     */
    public function __construct(EntityManager $objEntityManager, Logger $objLogger)
    {
        $this->objEntityManager = $objEntityManager;
        $this->objLogger = $objLogger;
    }
    
    /**
     * Insere os dados do Users na base de dados.
     * 
     * @access  public
     * @param   array $params
     * @throws  \RuntimeException
     * @throws  \Exception
     * @return  Users
     */
    public function insert(array $params)
    {
        try {
            $this->validate($params);
            $objUsers = new Users();
            $expirationDate = $params['expirationDate'];
            if($expirationDate){
                $expirationDate = new \DateTime($expirationDate);
            }
            
            $objUsers->setExpirationDate($expirationDate);
            $objUsers->setIsActive($params['isActive']);
            $objUsers->setIsDeleted(FALSE);
            $objUsers->setName(trim($params['name']));
            $objUsers->setRecordingDate(new \DateTime());
            $objUsers->setRemovalDate(NULL);
            $objUsers->setSalt(uniqid(mt_rand()));
            $objUsers->setUsername(trim($params['username']));
            
            $objNativePasswordEncoder = new NativePasswordEncoder(NULL, NULL, 12);
            $password = $objNativePasswordEncoder->encodePassword(trim($params['password']), $objUsers->getSalt());
            $objUsers->setPassword($password);
            $this->objEntityManager->persist($objUsers);
            $this->objEntityManager->flush();
            
            return $objUsers;
        } catch (\RuntimeException $e){
            throw $e;
        } catch (\Exception $e){
            throw $e;
        }
    }
    
    /**
     * Valida os dado que serão inseridos na base de dados.
     * 
     * @access  public
     * @param   array $params
     * @throws  \RuntimeException
     * @throws  \Exception
     * @return  void
     */
    public function validate(array $params)
    {
        try {
            $objUsersRepository = $this->objEntityManager->getRepository("AppEntity:Authorization\Users");
            $objUsers = $objUsersRepository->findOneBy(['username' => trim($params['username'])]);
            $objNotNull = new Assert\NotNull();
            $objNotBlank = new Assert\NotBlank();
            $objDateTime = new Assert\DateTime();
            $objTypeBool = new Assert\Type(['type'=>'bool']);
            $objEmail = new Assert\Email(['mode'=>'strict']);
            $objIdenticalTo = new Assert\IdenticalTo(['value'=>$params['confirm']]);
            $objNotEqualTo = new Assert\NotEqualTo(['value'=>($objUsers?$objUsers->getUsername():"")]);
            $objLengthName = new Assert\Length([
                'max' => 255,
            ]);
            
            $objValidation = Validation::createValidator();
            $objCollection = new Assert\Collection(
                [
                    'fields' => [
                        'confirm' => new Assert\Required(
                            [
                                $objNotNull,
                                $objNotBlank
                            ]
                        ),
                        'password' => new Assert\Required(
                            [
                                $objNotNull,
                                $objNotBlank,
                                $objIdenticalTo
                            ]
                        ),
                        'username' => new Assert\Required(
                            [
                                $objNotNull,
                                $objNotBlank,
                                $objEmail,
                                $objNotEqualTo
                            ]
                        ),
                        'name' => new Assert\Required(
                            [
                                $objNotNull,
                                $objNotBlank,
                                $objLengthName
                            ]
                        ),
                        'isActive' => new Assert\Required(
                            [
                                $objNotNull,
                                $objTypeBool
                            ]
                        ),
                        'expirationDate' => new Assert\Optional(
                            [
                                $objDateTime
                            ]
                        )
                    ]
                ]
                );
            $objConstraintViolationList = $objValidation->validate($params, $objCollection);
            $this->getErrors($objConstraintViolationList);
            
        } catch (\RuntimeException $e){
            throw $e;
        } catch (\Exception $e){
            throw $e;
        }
    }
    
    /**
     * Verifica se existe algum erro e formata a mensagem se existir e lança uma exception.
     * 
     * @access  private
     * @param   \Symfony\Component\Validator\ConstraintViolationList $objConstraintViolationList
     * @throws  \RuntimeException
     * @return  void
     */
    private function getErrors(ConstraintViolationList $objConstraintViolationList){
        if($objConstraintViolationList->count()){
            $objArrayIterator = $objConstraintViolationList->getIterator();
            $objArrayIterator->rewind();
            $mensagem = '';
            while($objArrayIterator->valid()){
                if($objArrayIterator->key()){
                    $mensagem.= "\n";
                }
                $mensagem.= $objArrayIterator->current()->getPropertyPath().': '.$objArrayIterator->current()->getMessage();
                $objArrayIterator->next();
            }
            throw new \RuntimeException($mensagem, Response::HTTP_PRECONDITION_FAILED);
        }
    }
}

