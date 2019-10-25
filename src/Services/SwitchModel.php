<?php
namespace App\Services;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Network\Service as EntityService;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Services\SwitchModel\Create;
use App\Services\SwitchModel\Listing;
use App\DBAL\Type\Enum\Network\MarcaSwitchType;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Psr\Log\LoggerInterface;

class SwitchModel
{
    private $objEntityManager   = NULL;
    
    public function __construct(RegistryInterface $objRegistry, LoggerInterface $objLogger)
    {
        $this->objEntityManager = $objRegistry->getManager('default');
        $this->objLogger = $objLogger;
    }
    
    private function getDefaultContext()
    {
        return [
            AbstractNormalizer::CALLBACKS => [
                'recordingDate' => function ($dateTime) {
                    return $dateTime instanceof \DateTime ? $dateTime->format(\DateTime::ISO8601) : '';
                },
                'removalDate' => function ($dateTime) {
                    return $dateTime instanceof \DateTime ? $dateTime->format(\DateTime::ISO8601) : '';
                }
            ],
        ];
    }
    
    public function create(Request $objRequest)
    {
        try {
            $objCreate = new Create($this->objEntityManager, $this->objLogger);
            $objTemplate = $objCreate
                ->create($objRequest)
                ->save();
            
            $objGetSetMethodNormalizer = new GetSetMethodNormalizer(NULL, NULL, NULL, NULL, NULL, $this->getDefaultContext());
            $objSerializer = new Serializer([$objGetSetMethodNormalizer]);
            return $objSerializer->normalize($objTemplate);
        } catch (\RuntimeException $e){
            throw $e;
        } catch (\Exception $e){
            throw $e;
        }
    }
    
    public function get(int $idService)
    {
        try {
            $objListing = new Listing($this->objEntityManager);
            $objService = $objListing->get($idService);
            
            if(!($objService instanceof EntityService)){
                throw new NotFoundHttpException("Not Found");
            }
            
            $objGetSetMethodNormalizer = new GetSetMethodNormalizer(NULL, NULL, NULL, NULL, NULL, $this->getDefaultContext());
            $objSerializer = new Serializer([$objGetSetMethodNormalizer]);
            return $objSerializer->normalize($objService);
        } catch (\RuntimeException $e){
            throw $e;
        } catch (\Exception $e){
            throw $e;
        }
    }
    
    public function list(Request $objRequest)
    {
        try {
            $objListing = new Listing($this->objEntityManager);
            $arrayTemplate = $objListing->list($objRequest);
            if(!count($arrayTemplate)){
                throw new NotFoundHttpException("Not Found");
            }
            
            $objGetSetMethodNormalizer = new GetSetMethodNormalizer(NULL, NULL, NULL, NULL, NULL, $this->getDefaultContext());
            $objSerializer = new Serializer([$objGetSetMethodNormalizer]);
            return $objSerializer->normalize($arrayTemplate);
        } catch (\RuntimeException $e){
            throw $e;
        } catch (\Exception $e){
            throw $e;
        }
    }
    
    public function getBrand(Request $objRequest)
    {
        try {            
            $objGetSetMethodNormalizer = new GetSetMethodNormalizer(NULL, NULL, NULL, NULL, NULL, []);
            $objSerializer = new Serializer([$objGetSetMethodNormalizer]);
            return $objSerializer->normalize(array_flip(MarcaSwitchType::getChoices()));
        } catch (\RuntimeException $e){
            throw $e;
        } catch (\Exception $e){
            throw $e;
        }
    }
}