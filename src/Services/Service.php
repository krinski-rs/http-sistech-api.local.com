<?php
namespace App\Services;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Services\Service\Listing;
use App\Entity\Network\Service as EntityService;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Services\Service\Create;
use Psr\Log\LoggerInterface;
class Service
{
    private $objEntityManager   = NULL;
    private $objLogger          = NULL;
    
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
            $objService = $objCreate
                ->create($objRequest)
                ->save();
            
            $objGetSetMethodNormalizer = new GetSetMethodNormalizer(null, null, null, null, null, $this->getDefaultContext());
            $objSerializer = new Serializer([$objGetSetMethodNormalizer]);
            return $objSerializer->normalize($objService);
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
            
            $objGetSetMethodNormalizer = new GetSetMethodNormalizer(null, null, null, null, null, $this->getDefaultContext());
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
            $arrayService = $objListing->list($objRequest);
            if(!count($arrayService)){
                throw new NotFoundHttpException("Not Found");
            }
            
            $objGetSetMethodNormalizer = new GetSetMethodNormalizer(null, null, null, null, null, $this->getDefaultContext());
            $objSerializer = new Serializer([$objGetSetMethodNormalizer]);
            return $objSerializer->normalize($arrayService);
        } catch (\RuntimeException $e){
            throw $e;
        } catch (\Exception $e){
            throw $e;
        }
    }
}