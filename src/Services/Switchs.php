<?php
namespace App\Services;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Network\Switchs as EntitySwitchs;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Services\Switchs\Create;
use App\Services\Switchs\Listing;
use App\Entity\Network\Vlan;
use App\Services\Switchs\SwitchUpdate;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Psr\Log\LoggerInterface;

class Switchs
{
    private $objEntityManager   = NULL;
    private $objLogger          = NULL;
    
    public function __construct(RegistryInterface $objRegistry, LoggerInterface $objLogger)
    {
        $this->objEntityManager = $objRegistry->getManager('default');
        $this->objLogger = $objLogger;
    }
    
    public function create(Request $objRequest)
    {
        try {
            $objCreate = new Create($this->objEntityManager, $this->objLogger);
            $objTemplate = $objCreate
                ->create($objRequest)
                ->save();
            $defaultContext = [
                AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
                    if($object instanceof Vlan){
                        return $object->getTagId();
                    }else{
                        return $object->getName();
                    }
                },
                AbstractNormalizer::CALLBACKS => [
                    'createdAt' => function ($dateTime) {
                        return $dateTime instanceof \DateTime ? $dateTime->format(\DateTime::ISO8601) : '';
                    },
                    'removedAt' => function ($dateTime) {
                        return $dateTime instanceof \DateTime ? $dateTime->format(\DateTime::ISO8601) : '';
                    }
                ],
            ];
            
            $objGetSetMethodNormalizer = new GetSetMethodNormalizer(NULL, NULL, NULL, NULL, NULL, $defaultContext);
            $objSerializer = new Serializer([$objGetSetMethodNormalizer]);
            return $objSerializer->normalize($objTemplate);
        } catch (\RuntimeException $e){
            throw $e;
        } catch (\Exception $e){
            throw $e;
        }
    }
    
    public function get(int $idSwitch)
    {
        try {
            $objListing = new Listing($this->objEntityManager);
            $objSwitch = $objListing->get($idSwitch);
            
            if(!($objSwitch instanceof EntitySwitchs)){
                throw new NotFoundHttpException("Not Found");
            }
            
            $defaultContext = [
                AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
                    if($object instanceof Vlan){
                        return $object->getTagId();
                    }else{
                        return $object->getName();
                    }
                },
                AbstractNormalizer::CALLBACKS => [
                    'createdAt' => function ($dateTime) {
                        return $dateTime instanceof \DateTime ? $dateTime->format(\DateTime::ISO8601) : NULL;
                    },
                    'removedAt' => function ($dateTime) {
                        return $dateTime instanceof \DateTime ? $dateTime->format(\DateTime::ISO8601) : NULL;
                    },
                ],
            ];
            
            $objGetSetMethodNormalizer = new GetSetMethodNormalizer(NULL, NULL, NULL, NULL, NULL, $defaultContext);
            $objSerializer = new Serializer([$objGetSetMethodNormalizer]);
            return $objSerializer->normalize($objSwitch);
        } catch (\RuntimeException $e){
            throw $e;
        } catch (\Exception $e){
            throw $e;
        }
    }
    
    public function list(Request $objRequest, bool $returnObj = FALSE)
    {
        try {
            $objListing = new Listing($this->objEntityManager);
            $arraySwitch = $objListing->list($objRequest);
            if(!count($arraySwitch)){
                throw new NotFoundHttpException("Not Found");
            }
            
            if($returnObj){
                return $arraySwitch;
            }
            
            $defaultContext = [
                AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
                    if($object instanceof Vlan){
                        return $object->getTagId();
                    }else{
                        return $object->getName();
                    }
                },
                AbstractNormalizer::CALLBACKS => [
                    'createdAt' => function ($dateTime) {
                        return $dateTime instanceof \DateTime ? $dateTime->format(\DateTime::ISO8601) : NULL;
                    },
                    'removedAt' => function ($dateTime) {
                        return $dateTime instanceof \DateTime ? $dateTime->format(\DateTime::ISO8601) : NULL;
                    },
                ],
            ];
            $objGetSetMethodNormalizer = new GetSetMethodNormalizer(NULL, NULL, NULL, NULL, NULL, $defaultContext);
            $objSerializer = new Serializer([$objGetSetMethodNormalizer]);
            return $objSerializer->normalize($arraySwitch);
        } catch (\RuntimeException $e){
            throw $e;
        } catch (\Exception $e){
            throw $e;
        }
    }
    
    public function updatePorts(EntitySwitchs $objSwitch)
    {
        try {
            $objSwitchUpdate = new SwitchUpdate($this->objEntityManager, $this->objLogger, $objSwitch->getAddressIpv4(), $objSwitch->getCommunity());
            $objSwitch = $objSwitchUpdate->updatePorts($objSwitch);
        } catch (\RuntimeException $e){
            throw $e;
        } catch (\Exception $e){
            throw $e;
        }
    }
}