<?php
namespace Behigorri\Repositories;

use Doctrine\ORM\EntityRepository;
use Illuminate\Pagination\LengthAwarePaginator as LengthAwarePaginator;
use Illuminate\Pagination\Paginator as Paginator;
use Validator;

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
                $filteredWord = $this->avoidSqlInjection($word);
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

      public function avoidSqlInjection ($string)
      {
          return preg_replace("/[^a-zA-Z0-9\s]/", "", $string);
      }

      public function getValitationMessages()
      {
          return array(
              'email.required'=> trans('trasnalations.emailRequired'),
              'email.email' => trans('translations.emailEmail'),
              'email.max' => trans('trasnlations.emailMax'),
              'email.unique' => trans('translations.emailUnique'),
              'name.required'=> trans('translations.nameRequired'),
              'name.max' => trans('translations.nameMax'),
              'name.unique' => trans('translations.nameUnique'),
              'password.required' => trans('translations.passwordRequired'),
              'password.confirmed' => trans('translations.passwordConfirmed'),
              'password.min' => trans('translations.passwordMin'),
              'password_Confirmation.required' => trans('translations.passwordConfirmationRequired'),
              'password_Confirmation.same' => trans('translations.passwordConfirmationSame'),
              'decryptpassword.required' => trans('translations.decryptPasswordRequired'),
              'decryptpassword.confirmed' => trans('translations.decryptPasswordConfirmed'),
              'decryptpassword.min' => trans('translations.decryptPasswordMin'),
              'decryptpassword_Confirmation.required' => trans('translations.decryptPasswordConfirmationRequired'),
              'decryptpassword_Confirmation.same' => trans('translations.decryptPasswordConfirmationSame'),
              'salt.unique' => trans('translations.saltlUnique'),
         );
      }

      public function paginate($items)
      {
        $perPage =20;
        $pageStart = \Request::get('page', 1);
       // Start displaying items from this number;
        $offSet = ($pageStart * $perPage) - $perPage;

       // Get only the items you need using array_slice
        $itemsForCurrentPage = array_slice($items, $offSet, $perPage, true);

        return new LengthAwarePaginator($itemsForCurrentPage, count($items), $perPage,Paginator::resolveCurrentPage(), array('path' => Paginator::resolveCurrentPath()));
      }

      private function saltValidator(array $salt)
      {
          return Validator::make($salt, [
              'salt' => 'required|max:355|unique:User',
          ], $this->getValitationMessages());
      }


      public function saltGenerator()
      {
        $salt = ['salt' => '' . random_bytes(32) . ''];
        $validator = $this->saltValidator($salt);
        while ($validator->fails()) {
          $salt = ['salt' => '' . random_bytes(32) . ''];
          $validator = $this->saltValidator($salt);
        }
  	  	return $salt['salt'];
      }


}
