<?php
namespace App\Services\Pop;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
class Listing
{
    private $objEntityManager = NULL;
    
    public function __construct(EntityManager $objEntityManager)
    {
        $this->objEntityManager = $objEntityManager;
    }
    
    public function get(int $idPop)
    {
        try {
            $objRepositoryPop = $this->objEntityManager->getRepository('AppEntity:Network\Pop');
            $objPop = $objRepositoryPop->find($idPop);
            return $objPop;
        } catch (\RuntimeException $e){
            throw $e;
        } catch (\Exception $e){
            throw $e;
        }
    }
    
    public function list(Request $objRequest)
    {
        try {
            $objRepositoryPop = $this->objEntityManager->getRepository('AppEntity:Network\Pop');
            $criteria = [];
            $arrayPop = [];
            
            $objQueryBuilder = $objRepositoryPop->createQueryBuilder('pop');
            $objExprEq = $objQueryBuilder->expr()->isNull('pop.removalDate');
            $objQueryBuilder->andWhere($objExprEq);
            
            if($objRequest->get('name', false)){
                $objExprLike = $objQueryBuilder->expr()->like('pop.name', ':name');
                $objQueryBuilder->andWhere($objExprLike);
                $criteria['name'] = "%{$objRequest->get('name', null)}%";
            }
            
            if($objRequest->get('isActive', false)){
                $objExprEq = $objQueryBuilder->expr()->eq('pop.isActive', ':isActive');
                $objQueryBuilder->andWhere($objExprEq);
                $criteria['isActive'] = $objRequest->get('isActive', null);
            }
            
            if($objRequest->get('recordingDate', false)){
                $objExprEq = $objQueryBuilder->expr()->eq('pop.recordingDate', ':recordingDate');
                $objQueryBuilder->andWhere($objExprEq);
                $criteria['recordingDate'] = $objRequest->get('recordingDate', null);
            }
            if(count($criteria)){
                $objQueryBuilder->setParameters($criteria);
            }
            
            
            $limit = (integer)$objRequest->get('limit',15);
            $offset = ((integer)$objRequest->get('offset',0) * $limit);
            
            $objQueryBuilder->setFirstResult($offset);
            $objQueryBuilder->setMaxResults($limit);
            $objQueryBuilder->addOrderBy('pop.id', 'ASC');
            
            $arrayPop['data'] = $objQueryBuilder->getQuery()->getResult();
            $objQueryBuilder->resetDQLPart('orderBy');
            $objQueryBuilder->select('count(pop.id) AS total');
            $objQueryBuilder->setFirstResult(0);
            $objQueryBuilder->setMaxResults(1);
            $resultSet = $objQueryBuilder->getQuery()->getResult();
            $arrayPop['total'] = $resultSet[0]['total'];
            $arrayPop['offset'] = (integer)$objRequest->get('offset',0);
            $arrayPop['limit'] = (integer)$objRequest->get('limit',15);
            return $arrayPop;
        } catch (\RuntimeException $e){
            throw $e;
        } catch (\Exception $e){
            throw $e;
        }
    }
}
