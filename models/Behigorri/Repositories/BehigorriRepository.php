<?php
namespace Behigorri\Repositories;

use Doctrine\ORM\EntityRepository;

class BehigorriRepository
{

      public function findBySearch($criteria)
      {
        if($criteria){
          $sqb = $this->createQueryBuilder('c');
          foreach ($criteria as $word) {
            $splitedWords = explode(' ', $criteria[$word]);
            foreach ($splitedWords as $word) {
                $filteredWord=(filter_var(stripslashes(trim($word)), FILTER_SANITIZE_STRING));
                $where= $where . 'name like :word' . $id . ' or ';
                $ors[] = $sqb->expr()->orx('c. = ' .
                $sqb->expr()->literal(mysql_real_escape_string($filteredWord)));
            }
             $sqb->andWhere(join(' OR ', $ors));
          }

        }
        return $sqb;

      }



}
