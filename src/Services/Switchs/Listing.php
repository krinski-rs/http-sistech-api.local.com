<?php
namespace App\Services\Switchs;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\Collections\Criteria;
class Listing
{
    private $objEntityManager = NULL;
    
    public function __construct(EntityManager $objEntityManager)
    {
        $this->objEntityManager = $objEntityManager;
    }
    
    public function get(int $idSwitchs)
    {
        try {
            $objRepositorySwitchs = $this->objEntityManager->getRepository('AppEntity:Network\Switchs');
            $objSwitchs = $objRepositorySwitchs->find($idSwitchs);
            return $objSwitchs;
        } catch (\RuntimeException $e){
            throw $e;
        } catch (\Exception $e){
            throw $e;
        }
    }
    
    public function list(Request $objRequest)
    {
        try {
            $objRepositorySwitchs = $this->objEntityManager->getRepository('AppEntity:Network\Switchs');
            $criteria = [];
            $arraySwitchs = [];
            
            $objQueryBuilder = $objRepositorySwitchs->createQueryBuilder('swit');
            $objQueryBuilder->select('DISTINCT swit');
            $objQueryBuilder->innerJoin('swit.switchPort', 'port');
            $objExprEq = $objQueryBuilder->expr()->isNull('swit.removalDate');
            $objQueryBuilder->andWhere($objExprEq);
            
            if($objRequest->get('name', false)){
                $objExprLike = $objQueryBuilder->expr()->like('swit.name', ':name');
                $objQueryBuilder->andWhere($objExprLike);
                $criteria['name'] = "%{$objRequest->get('name', null)}%";
            }
            
            if($objRequest->get('isActive', false)){
                $objExprEq = $objQueryBuilder->expr()->eq('swit.isActive', ':isActive');
                $objQueryBuilder->andWhere($objExprEq);
                $criteria['isActive'] = $objRequest->get('isActive', null);
            }
            
            if($objRequest->get('recordingDate', false)){
                $objExprEq = $objQueryBuilder->expr()->eq('swit.recordingDate', ':recordingDate');
                $objQueryBuilder->andWhere($objExprEq);
                $criteria['recordingDate'] = $objRequest->get('recordingDate', null);
            }
            if($objRequest->get('id', false)){
                $objExprEq = $objQueryBuilder->expr()->eq('swit.id', ':id');
                $objQueryBuilder->andWhere($objExprEq);
                $criteria['id'] = $objRequest->get('id', null);
            }
            
            if(count($criteria)){
                $objQueryBuilder->setParameters($criteria);
            }
            
            $limit = (integer)$objRequest->get('limit',15);
            $offset = ((integer)$objRequest->get('offset',0) * $limit);
            
            $objQueryBuilder->setFirstResult($offset);
            $objQueryBuilder->setMaxResults($limit);
            
//             $objQueryBuilder->orderBy('swit.id', 'ASC');
            $objCriteriaOrderBy = Criteria::create()->orderBy(['swit.id'=>'ASC']);
            $objQueryBuilder->addCriteria($objCriteriaOrderBy);
            $objQueryBuilder->groupBy('swit.id'); 
            
            
                        
            $arraySwitchs['data'] = $objQueryBuilder->getQuery()->getResult();
            
            $objQueryBuilder->resetDQLParts(['groupBy', 'orderBy']);
            $objQueryBuilder->select('count(DISTINCT swit.id) AS total');
            $objQueryBuilder->setFirstResult(0);
            $objQueryBuilder->setMaxResults(1);
            $resultSet = $objQueryBuilder->getQuery()->getResult();
            $arraySwitchs['total'] = $resultSet[0]['total'];
            $arraySwitchs['offset'] = (integer)$objRequest->get('offset',0);
            $arraySwitchs['limit'] = (integer)$objRequest->get('limit',15);
            return $arraySwitchs;
        } catch (\RuntimeException $e){
            throw $e;
        } catch (\Exception $e){
            throw $e;
        }
    }
}
