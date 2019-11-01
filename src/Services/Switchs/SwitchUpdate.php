<?php
namespace App\Services\Switchs;
use Monolog\Logger;
use App\Entity\Network\Switchs;
use App\Services\Fast\SwitchAbstract;
use App\Entity\Network\SwitchPort;
use Doctrine\ORM\EntityManager;
class SwitchUpdate extends SwitchAbstract
{
    private $objEntityManager   = NULL;
    
    public function __construct(EntityManager $objEntityManager, Logger $objLogger, string $addressIpv4, string $community)
    {
        $this->objEntityManager = $objEntityManager;
        parent::__construct($objLogger, $addressIpv4, $community);
    }
    
    public function updatePorts(Switchs $objSwitch)
    {
        try {
            $arrayIfAlias           = $this->getIfAlias();
            $arrayIfAdminStatus     = $this->getIfAdminStatus();
            $arrayIfOperStatus      = $this->getIfOperStatus();
            $arrayIfDuplexStatus    = $this->getIfDuplexStatus();
//             $arrayIfSpeed           = $this->getIfSpeed();
            
            $arrayPort = $objSwitch->getPort();
            
            if(count($arrayIfAlias) && $arrayPort->count()){
                reset($arrayIfAlias);
                while($ifAlias = current($arrayIfAlias)){
                    $key = key($arrayIfAlias);
                    $objPort = $arrayPort->offsetGet($key);
                    if($objPort instanceof SwitchPort){
                        $ifAdminStatus = $arrayIfAdminStatus[$key];
                        $ifOperStatus = $arrayIfOperStatus[$key];
                        $ifDuplexStatus = $arrayIfDuplexStatus[$key];
//                         $ifSpeed = $arrayIfSpeed[$key];
                        $destiny = (trim($ifAlias->value) ? trim($ifAlias->value) : NULL);
                        $adminStatus = (((integer)$ifAdminStatus->value === 1) ? "UP" : (((integer)$ifAdminStatus->value === 2) ? 'DOWN' : NULL));
                        $operStatus = (((integer)$ifOperStatus->value === 1) ? "UP" : (((integer)$ifOperStatus->value === 2) ? 'DOWN' : NULL));
                        $duplex = (((integer)$ifDuplexStatus->value === 1) ? "UNKNOWN" : (((integer)$ifDuplexStatus->value === 2) ? "HALF" : (((integer)$ifDuplexStatus->value === 3) ? "FULL" : NULL)));
//                         $speed = (((integer)$ifSpeed->value > 0) ? round((($ifSpeed->value/1024)/1024), 2). ' Mbps' : NULL);
                        $objPort->setDestiny($destiny);
                        $objPort->setAdminStatus($adminStatus);
                        $objPort->setOperStatus($operStatus);
                        $objPort->setDuplex($duplex);
                        $objPort->setSpeed(NULL);
                        $objPort->setModifiedAt(new \DateTime());
                    }
                    
                    next($arrayIfAlias);
                }
            }
            $this->objEntityManager->merge($objSwitch);
            $this->objEntityManager->flush();
        } catch (\RuntimeException $e){
            throw $e;
        } catch (\Exception $e){
            throw $e;
        }
        return $this;
    }
}
