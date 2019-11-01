<?php
namespace App\Services\Switchs;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validation;
use Doctrine\ORM\EntityManager;
use Monolog\Logger;
use App\Entity\Network\Switchs;
// use App\Entity\Network\SwitchModel;
use App\Entity\Network\SwitchModelPort;
use App\Entity\Network\SwitchPort;
use App\DBAL\Type\Enum\Network\PortTypeType;
use App\DBAL\Type\Enum\Network\PortModeType;
class Create
{
    private $objEntityManager   = NULL;
    private $objSwitchs         = NULL;
    private $objLogger          = NULL;
    private $objPop             = NULL;
    private $objVlan            = NULL;
    private $objSwitchModel     = NULL;
    
    public function __construct(EntityManager $objEntityManager, Logger $objLogger)
    {
        $this->objEntityManager = $objEntityManager;
        $this->objLogger = $objLogger;
    }
    
    private function addPort(){
        try {
            $arraySwitchModelPort = $this->objSwitchModel->getSwitchModelPort();
            if($arraySwitchModelPort->count()){
                reset($arraySwitchModelPort);
                while($objSwitchModelPort = $arraySwitchModelPort->current()){
                    if($objSwitchModelPort instanceof SwitchModelPort){
                        $quantities = $objSwitchModelPort->getQuantities();
                        $ini = 1;
                        while($ini <= $quantities){
                            $objSwitchPort = new SwitchPort();
                            $objSwitchPort->setAutoNeg(TRUE);
                            $objSwitchPort->setFlowCtrl(FALSE);
                            $objSwitchPort->setMode(PortModeType::ACCESS);
                            $objSwitchPort->setNumbering($ini);
                            $objSwitchPort->setType($objSwitchModelPort->getPortType());
                            $this->objSwitchs->addSwitchPort($objSwitchPort);
                            $ini++;
                        }
                    }
                    $arraySwitchModelPort->next();
                }
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }
    
    public function create(Request $objRequest)
    {
        try {
            $this->validate($objRequest);
            $this->objSwitchs = new Switchs();
            $this->objSwitchs->setIsActive($objRequest->get('isActive'));
            
            if(trim($objRequest->get('address_ipv4', NULL))){
                $this->objSwitchs->setAddressIpv4(trim($objRequest->get('address_ipv4', NULL)));
            }
            
            if(trim($objRequest->get('address_ipv6', NULL))){
                $this->objSwitchs->setAddressIpv6(trim($objRequest->get('address_ipv6', NULL)));
            }
            
            $this->objSwitchs->setRecordingDate(new \DateTime());
            
            if(trim($objRequest->get('name', NULL))){
                $this->objSwitchs->setName(trim($objRequest->get('name', NULL)));
            }
            
            if(trim($objRequest->get('password', NULL))){
                $this->objSwitchs->setPassword(trim($objRequest->get('password', NULL)));
            }
            $this->objSwitchs->setPop($this->objPop);
            $this->objSwitchs->setRemovalDate(NULL);
            $this->objSwitchs->setSwitchModel($this->objSwitchModel);
                        
            if(trim($objRequest->get('username', NULL))){
                $this->objSwitchs->setUsername(trim($objRequest->get('username', NULL)));
            }
                        
            if(trim($objRequest->get('community', NULL))){
                $this->objSwitchs->setCommunity(trim($objRequest->get('community', NULL)));
            }
            
            $this->objSwitchs->setVlan($this->objVlan);
            $this->addPort();
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
        $objLength = new Assert\Length([ 'min' => 2, 'max' => 255 ] );
        $objInt = new Assert\Type([ 'type' => 'numeric' ]);
        $objIpv4 = new Assert\Ip([ 'version' => 4 ]);
        $objIpv6 = new Assert\Ip([ 'version' => 6 ]);
        
        $objRecursiveValidator = Validation::createValidatorBuilder()->getValidator();
        
        $objCollection = new Assert\Collection(
            [
                'fields' => [
                    'name' => new Assert\Required(
                        [
                            $objNotNull,
                            $objNotBlank,
                            $objLength
                        ]
                    ),
                    'switchModel' => new Assert\Required(
                        [
                            $objNotNull,
                            $objNotBlank,
                            $objInt
                        ]
                    ),
                    'isActive' => new Assert\Optional(
                        [
                            $objTypeBool
                        ]
                    ),
                    'vlan' => new Assert\Optional(
                        [
                            $objInt
                        ]
                    ),
                    'pop' => new Assert\Optional(
                        [
                            $objNotNull,
                            $objNotBlank,
                            $objInt
                        ]
                    ),
                    'addressIpv4' =>   new Assert\Optional(
                        [
                            $objIpv4
                        ]
                    ),
                    'addressIpv6' =>  new Assert\Optional(
                        [
                            $objIpv6
                        ]
                    ),
                    'password' =>   new Assert\Optional(
                        [
                            $objLength
                        ]
                    ),
                    'username' => new Assert\Optional(
                        [
                            $objLength
                        ]
                    )
                ]
            ]
        );
        $data = [
            'addressIpv4'  => $objRequest->get('addressIpv4'),
            'addressIpv6'  => $objRequest->get('addressIpv6'),
            'name'  => trim($objRequest->get('name', NULL)),
            'password'  => trim($objRequest->get('password', NULL)),
            'pop'  => trim($objRequest->get('pop', NULL)),
            'switchModel'  => trim($objRequest->get('switchModel', NULL)),
            'username'  => trim($objRequest->get('username', NULL)),
            'vlan'  => $objRequest->get('vlan')
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
        
        if($objRequest->get('vlan', NULL)){
            $this->objVlan = $this->objEntityManager->getRepository('AppEntity:Network\Vlan')->find($objRequest->get('vlan', NULL));
        }
        
        if($objRequest->get('pop', NULL)){
            $this->objPop = $this->objEntityManager->getRepository('AppEntity:Network\Pop')->find($objRequest->get('pop', NULL));
        }
        
        $this->objSwitchModel = $this->objEntityManager->getRepository('AppEntity:Network\SwitchModel')->find($objRequest->get('switchModel', NULL));
    }
    
    public function save()
    {
        $this->objEntityManager->persist($this->objSwitchs);
        $this->objEntityManager->flush();
        return $this->objSwitchs;
    }
}