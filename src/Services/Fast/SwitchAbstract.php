<?php
namespace App\Services\Fast;
use Monolog\Logger;
abstract class SwitchAbstract implements SwitchInterface
{
    protected $objSNMP      = NULL;
    protected $addressIpv4  = NULL;
    protected $community    = NULL;
    protected $objLogger    = NULL;
    
    const IF_ALIAS          = '1.3.6.1.2.1.31.1.1.1.18';
    const IF_ADMIN_STATUS   = '1.3.6.1.2.1.2.2.1.7';
    const IF_OPER_STATUS    = '1.3.6.1.2.1.2.2.1.8';
    const IF_DUPLEX_STATUS  = '1.3.6.1.2.1.10.7.2.1.19';
    const IF_SPEED          = '1.3.6.1.2.1.2.2.1.5';
    
    public function __construct(Logger $objLogger, string $addressIpv4, string $community)
    {
        try {
            $this->addressIpv4  = $addressIpv4;
            $this->community    = $community;
            $this->objLogger    = $objLogger;
            $this->objSNMP = new \SNMP(\SNMP::VERSION_1, $this->addressIpv4, $this->community);
        } catch (\Exception $e) {
            throw $e;
        }
    }
    
    public function getIfAlias()
    {
        try {
            $this->objSNMP->valueretrieval = SNMP_VALUE_OBJECT;
            $arrayIfAlias = $this->objSNMP->walk(self::IF_ALIAS);
            return $this->parse($arrayIfAlias);
        } catch (\Exception $e) {
            throw $e;
        }
    }
    
    public function getIfAdminStatus()
    {
        try {
            $arrayIfAdminStatus = $this->objSNMP->walk(self::IF_ADMIN_STATUS);
            return $this->parse($arrayIfAdminStatus);
        } catch (\Exception $e) {
            throw $e;
        }
    }
    
    public function getIfOperStatus()
    {
        try {
            $arrayIfOperStatus = $this->objSNMP->walk(self::IF_OPER_STATUS);
            return $this->parse($arrayIfOperStatus);
        } catch (\Exception $e) {
            throw $e;
        }
    }
    
    public function getIfDuplexStatus()
    {
        try {
            $arrayIfDuplexStatus = $this->objSNMP->walk(self::IF_DUPLEX_STATUS);
            return $this->parse($arrayIfDuplexStatus);
        } catch (\Exception $e) {
            throw $e;
        }
    }
    
    public function getIfSpeed()
    {
        try {
            $arrayIfSpeed = $this->objSNMP->walk(self::IF_SPEED);
            return $this->parse($arrayIfSpeed);
        } catch (\Exception $e) {
            throw $e;
        }
    }
    
    public function __destruct()
    {
        if($this->objSNMP instanceof \SNMP){
            $this->objSNMP->close();
        }
    }
    
    private function parse(array $arrayParse)
    {
        $arrayRetorno = [];
        if(count($arrayParse)){
            reset($arrayParse);
            $index = 0;
            while($objParse = current($arrayParse)){
                if($objParse->type === SNMP_OCTET_STR){
                    $objParse->value = str_replace(['STRING: ', '"'], ['', ''], $objParse->value);
                    $arrayRetorno[$index] = $objParse;
                }elseif($objParse->type === SNMP_INTEGER){
                    $objParse->value = (integer)str_replace(['INTEGER: ', '"'], ['', ''], $objParse->value);
                    $arrayRetorno[$index] = $objParse;
                }elseif($objParse->type === SNMP_COUNTER){
                    $objParse->value = (integer)str_replace(['Gauge32: ', '"'], ['', ''], $objParse->value);
                    $arrayRetorno[$index] = $objParse;
                }else{
//                     print_r([$objParse->type, SNMP_COUNTER64, SNMP_INTEGER, SNMP_UINTEGER, SNMP_TIMETICKS, SNMP_UNSIGNED, SNMP_COUNTER, SNMP_IPADDRESS, SNMP_OBJECT_ID, SNMP_NULL, SNMP_OPAQUE, SNMP_OCTET_STR, SNMP_BIT_STR]);
                    $arrayRetorno[$index] = $objParse;
                }
                $index++;
                next($arrayParse);
            }
        }
        return $arrayRetorno;
    }
}
