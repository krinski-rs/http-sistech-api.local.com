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
            $objExprEq = $objQueryBuilder->expr()->isNull('serv.removedAt');
            $objQueryBuilder->andWhere($objExprEq);
            
            if($objRequest->get('name', false)){
                $objExprLike = $objQueryBuilder->expr()->like('serv.name', ':name');
                $objQueryBuilder->andWhere($objExprLike);
                $criteria['name'] = "%{$objRequest->get('name', null)}%";
            }
            
            if($objRequest->get('active', false)){
                $objExprEq = $objQueryBuilder->expr()->eq('serv.active', ':active');
                $objQueryBuilder->andWhere($objExprEq);
                $criteria['active'] = $objRequest->get('active', null);
            }
            
            if($objRequest->get('createdAt', false)){
                $objExprEq = $objQueryBuilder->expr()->eq('serv.createdAt', ':createdAt');
                $objQueryBuilder->andWhere($objExprEq);
                $criteria['createdAt'] = $objRequest->get('createdAt', null);
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
