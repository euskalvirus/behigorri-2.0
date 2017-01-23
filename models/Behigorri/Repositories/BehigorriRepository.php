<?php
namespace Behigorri\Repositories;

use Doctrine\ORM\EntityRepository;

class BehigorriRepository extends EntityRepository
{

      public function search($criteria)
      {
        if($criteria){
          $sqb = $this->createQueryBuilder('c');
          foreach ($criteria as $id => $word) {
          	//var_dump($word ."  ". $id);exit;
            $splitedWords = explode(' ', $criteria[$id]);
            foreach ($splitedWords as $word) {
            	$filteredWord=(filter_var(stripslashes(trim($word)), FILTER_SANITIZE_STRING));
            	$ors[] = $sqb->expr()->orx('c.name like ' .  $sqb->expr()->literal('%' . $filteredWord . '%'));
            }
            $sqb->andWhere(join(' OR ', $ors));
            
          }

        }
        //var_dump($where);exit;
        //$result = $sqb->getQuery()->getResult();
        //var_dump($sqb->getQuery());exit;
        return $sqb->getQuery()->getResult();
        

      }
      
      public function getTags($datas)
      {
      	$dataTags =[];
      	foreach ($datas as $data)
      	{
      		foreach ($data->getTags() as $tag)
      			if(!isset($dataTags[$tag->getId()]))
      			{
      				$dataTags[$tag->getId()] = $tag;
      			}
      	}
      	return $dataTags;
      }



}
