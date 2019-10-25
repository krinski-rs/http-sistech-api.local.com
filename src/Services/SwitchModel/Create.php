<?php
namespace App\Services\SwitchModel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validation;
use Doctrine\ORM\EntityManager;
use Monolog\Logger;
use App\Entity\Network\SwitchModel;
use App\DBAL\Type\Enum\Network\MarcaSwitchType;
use App\Entity\Network\SwitchModelPort;

class Create
{
    private $objEntityManager   = NULL;
    private $objSwitchModel         = NULL;
    private $objLogger          = NULL;
    
    public function __construct(EntityManager $objEntityManager, Logger $objLogger)
    {
        $this->objEntityManager = $objEntityManager;
        $this->objLogger = $objLogger;
    }
    
    public function addSwitchModelPort(Request $objRequest)
    {
        try {            
            $this->validate($objRequest);
            
            $quantities = (integer)$objRequest->get('port10Ge');
            if($quantities > 0){
                $objSwitchModelPort = new SwitchModelPort();
                $objSwitchModelPort->setPortType(3);
                $objSwitchModelPort->setQuantities($quantities);
                $this->objSwitchModel->addSwitchModelPort($objSwitchModelPort);
            }
            
            $quantities = (integer)$objRequest->get('portGe');
            if($quantities > 0){
                $objSwitchModelPort = new SwitchModelPort();
                $objSwitchModelPort->setPortType(2);
                $objSwitchModelPort->setQuantities($quantities);
                $this->objSwitchModel->addSwitchModelPort($objSwitchModelPort);
            }
            
            $quantities = (integer)$objRequest->get('portFe');
            if($quantities > 0){
                $objSwitchModelPort = new SwitchModelPort();
                $objSwitchModelPort->setPortType(1);
                $objSwitchModelPort->setQuantities($quantities);
                $this->objSwitchModel->addSwitchModelPort($objSwitchModelPort);
            }
        } catch (\RuntimeException $e){
            throw $e;
        } catch (\Exception $e){
            throw $e;
        }
        
    }
    
    public function create(Request $objRequest)
    {
        try {
            $choice = MarcaSwitchType::getChoices();
            $this->validate($objRequest);
            $this->objSwitchModel = new SwitchModel();
            $this->objSwitchModel->setActive(TRUE);
            $this->objSwitchModel->setName($objRequest->get('name'));
            $this->objSwitchModel->setBrand($choice[$objRequest->get('brand')]);
            $this->objSwitchModel->setRecordingDate(new \DateTime());
            $this->objSwitchModel->setRemovalDate(NULL);
            $this->addSwitchModelPort($objRequest);
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
        $objNotNull->message = 'Esse valor não deve ser nulo.';
        $objNotBlank = new Assert\NotBlank();
        $objNotBlank->message = 'Esse valor não deve estar em branco.';
        
        $objLength = new Assert\Length(
            [
                'min' => 2,
                'max' => 255,
                'minMessage' => 'O campo deve ter pelo menos {{ limit }} caracteres.',
                'maxMessage' => 'O campo não pode ser maior do que {{ limit }} caracteres.'
            ]
        );
        
        $objChoice = new Assert\Choice(
            [
                'min' => 1,
                'max' => 1,
                'choices' => array_keys(MarcaSwitchType::getChoices()),
                'message' => 'O valor selecionado não é uma opção válida.',
                'minMessage' => 'Você deve selecionar pelo menos {{l imit }} opção.',
                'maxMessage' => 'Você deve selecionar no máximo {{ limit }} opção.'
            ]
        );
        
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
                    'brand' => new Assert\Required( [
                            $objNotNull,
                            $objNotBlank,
                            $objChoice
                        ]
                    )
                ]
            ]
        );
        $data = [
            'name'  => trim($objRequest->get('name', NULL)),
            'brand' => trim($objRequest->get('brand', NULL))
        ];
                
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
        
        if(!((integer)$objRequest->get('portGe') > 0) && !((integer)$objRequest->get('portFe') > 0) && !((integer)$objRequest->get('port10Ge') > 0)){
            $mensagem = "Informe pelo menos um tipo de porta";
            throw new \RuntimeException($mensagem);
        }
    }
    
    public function save()
    {
        $this->objEntityManager->persist($this->objSwitchModel);
        $this->objEntityManager->flush();
        return $this->objSwitchModel;
    }
}