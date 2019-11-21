<?php
namespace App\Services\Service;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validation;
use Doctrine\ORM\EntityManager;
use Monolog\Logger;
use App\Entity\Network\Service;
class Create
{
    private $objEntityManager   = NULL;
    private $objService         = NULL;
    private $objLogger          = NULL;
    
    public function __construct(EntityManager $objEntityManager, Logger $objLogger)
    {
        $this->objEntityManager = $objEntityManager;
        $this->objLogger = $objLogger;
    }
    
    public function create(Request $objRequest)
    {
        try {
            $this->validate($objRequest);
                       
            $this->objService = new Service();
            $this->objService->setIsActive(TRUE);
            $this->objService->setName($objRequest->get('name'));
            $this->objService->setNickname($objRequest->get('nickname', NULL));
            $this->objService->setRecordingDate(new \DateTime());
            $this->objService->setRemovalDate(NULL);
        } catch (\RuntimeException $e){
            throw $e;
        } catch (\Exception $e){
            throw $e;
        }
        return $this;
    }
    
    private function validate(Request $objRequest)
    {
        $objNotNull = new Assert\NotNull();
        $objNotBlank = new Assert\NotBlank();
        $objTypeBool = new Assert\Type(['type'=>'bool']);
        
        $objLength = new Assert\Length( [ 'min' => 2, 'max' => 255 ] );
        $objLengthNickname = new Assert\Length( [ 'min' => 3, 'max' => 15 ] );
        
        $objRecursiveValidator = Validation::createValidatorBuilder()->getValidator();
        
        $objCollection = new Assert\Collection(
            [
                'fields' => [
                    'name' => new Assert\Required( [
                            $objNotNull,
                            $objNotBlank,
                            $objLength
                        ]
                    ),
                    'nickname' => new Assert\Optional( [
                            $objLengthNickname
                        ]
                    ),
                    'isActive' => new Assert\Required(
                        [
                            $objNotNull,
                            $objTypeBool
                        ]
                    )
                ]
            ]
        );
        $data = [
            'name' => trim($objRequest->get('name', NULL)),
            'isActive' => $objRequest->get('isActive'),
            'nickname' => trim($objRequest->get('nickname'))
        ];
        $this->objLogger->error("Create Service", $data);
        $objConstraintViolationList = $objRecursiveValidator->validate($data, $objCollection);
        
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
            throw new \RuntimeException($mensagem);
        }
    }
    
    public function save()
    {
        $this->objEntityManager->persist($this->objService);
        $this->objEntityManager->flush();
        return $this->objService;
    }
}