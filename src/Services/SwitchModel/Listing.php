<?php
namespace App\Services\SwitchModel;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
class Listing
{
    private $objEntityManager = NULL;
    
    public function __construct(EntityManager $objEntityManager)
    {
        $this->objEntityManager = $objEntityManager;
    }
    
    public function get(int $idSwitchModel)
    {
        try {
            $objRepositorySwitchModel = $this->objEntityManager->getRepository('AppEntity:Network\SwitchModel');
            $objSwitchModel = $objRepositorySwitchModel->find($idSwitchModel);
            return $objSwitchModel;
        } catch (\RuntimeException $e){
            throw $e;
        } catch (\Exception $e){
            throw $e;
        }
    }
    
    public function list(Request $objRequest)
    {
        try {
            $objRepositorySwitchModel = $this->objEntityManager->getRepository('AppEntity:Network\SwitchModel');
            $criteria = [];
            $arraySwitchModel = [];
            
            $objQueryBuilder = $objRepositorySwitchModel->createQueryBuilder('switmode');
            $objExprEq = $objQueryBuilder->expr()->isNull('switmode.removalDate');
            $objQueryBuilder->andWhere($objExprEq);
            
            if($objRequest->get('name', false)){
                $objExprLike = $objQueryBuilder->expr()->like('switmode.name', ':name');
                $objQueryBuilder->andWhere($objExprLike);
                $criteria['name'] = "%{$objRequest->get('name', null)}%";
            }
            
            if($objRequest->get('brand', false)){
                $objExprEq = $objQueryBuilder->expr()->eq('switmode.brand', ':brand');
                $objQueryBuilder->andWhere($objExprEq);
                $criteria['brand'] = $objRequest->get('brand', null);
            }
            
            if($objRequest->get('isActive', false)){
                $objExprEq = $objQueryBuilder->expr()->eq('switmode.isActive', ':isActive');
                $objQueryBuilder->andWhere($objExprEq);
                $criteria['isActive'] = $objRequest->get('isActive', null);
            }
            
            if($objRequest->get('createdAt', false)){
                $objExprEq = $objQueryBuilder->expr()->eq('switmode.createdAt', ':createdAt');
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
            $objQueryBuilder->addOrderBy('switmode.id', 'ASC');
            
            
            $arraySwitchModel['data'] = $objQueryBuilder->getQuery()->getResult();
            $objQueryBuilder->resetDQLPart('orderBy');
            $objQueryBuilder->select('count(switmode.id) AS total');
            $objQueryBuilder->setFirstResult(0);
            $objQueryBuilder->setMaxResults(1);
            $resultSet = $objQueryBuilder->getQuery()->getResult();
            $arraySwitchModel['total'] = $resultSet[0]['total'];
            $arraySwitchModel['offset'] = (integer)$objRequest->get('offset',0);
            $arraySwitchModel['limit'] = (integer)$objRequest->get('limit',15);
            
            return $arraySwitchModel;
        } catch (\RuntimeException $e){
            throw $e;
        } catch (\Exception $e){
            throw $e;
        }
    }
}
