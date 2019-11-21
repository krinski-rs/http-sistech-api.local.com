<?php
namespace App\Services\Service;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
class Listing
{
    private $objEntityManager = NULL;
    
    public function __construct(EntityManager $objEntityManager)
    {
        $this->objEntityManager = $objEntityManager;
    }
    
    public function get(int $idService)
    {
        try {
            $objRepositoryService = $this->objEntityManager->getRepository('AppEntity:Network\Service');
            $objService = $objRepositoryService->find($idService);
            return $objService;
        } catch (\RuntimeException $e){
            throw $e;
        } catch (\Exception $e){
            throw $e;
        }
    }
    
    public function list(Request $objRequest)
    {
        try {
            $objRepositoryService = $this->objEntityManager->getRepository('AppEntity:Network\Service');
            $criteria = [];
            $arrayService = [];
            
            $objQueryBuilder = $objRepositoryService->createQueryBuilder('serv');
            $objExprEq = $objQueryBuilder->expr()->isNull('serv.removalDate');
            $objQueryBuilder->andWhere($objExprEq);
            
            if($objRequest->get('name', false)){
                $objExprLike = $objQueryBuilder->expr()->like('serv.name', ':name');
                $objQueryBuilder->andWhere($objExprLike);
                $criteria['name'] = "%{$objRequest->get('name', null)}%";
            }
            
            if($objRequest->get('nickname', false)){
                $objExprLike = $objQueryBuilder->expr()->like('serv.nickname', ':nickname');
                $objQueryBuilder->andWhere($objExprLike);
                $criteria['nickname'] = "%{$objRequest->get('nickname', null)}%";
            }
            
            if($objRequest->get('isActive', false)){
                $objExprEq = $objQueryBuilder->expr()->eq('serv.isActive', ':isActive');
                $objQueryBuilder->andWhere($objExprEq);
                $criteria['isActive'] = $objRequest->get('isActive', null);
            }
            
            if($objRequest->get('recordingDate', false)){
                $objExprEq = $objQueryBuilder->expr()->eq('serv.recordingDate', ':recordingDate');
                $objQueryBuilder->andWhere($objExprEq);
                $criteria['removalDate'] = $objRequest->get('recordingDate', null);
            }
            
            if(count($criteria)){
                $objQueryBuilder->setParameters($criteria);
            }
            
            $limit = (integer)$objRequest->get('limit',15);
            $offset = ((integer)$objRequest->get('offset',0) * $limit);
            
            $objQueryBuilder->setFirstResult($offset);
            $objQueryBuilder->setMaxResults($limit);
            $objQueryBuilder->addOrderBy('serv.id', 'ASC');
            
            $arrayService['data'] = $objQueryBuilder->getQuery()->getResult();
            $objQueryBuilder->resetDQLPart('orderBy');
            $objQueryBuilder->select('count(serv.id) AS total');
            $objQueryBuilder->setFirstResult(0);
            $objQueryBuilder->setMaxResults(1);
            $resultSet = $objQueryBuilder->getQuery()->getResult();
            $arrayService['total'] = $resultSet[0]['total'];
            $arrayService['offset'] = (integer)$objRequest->get('offset',0);
            $arrayService['limit'] = (integer)$objRequest->get('limit',15);
            return $arrayService;
        } catch (\RuntimeException $e){
            throw $e;
        } catch (\Exception $e){
            throw $e;
        }
    }
}
