<?php
namespace Behigorri\Repositories;

use Doctrine\ORM\EntityRepository;
use Illuminate\Pagination\LengthAwarePaginator as LengthAwarePaginator;
use Illuminate\Pagination\Paginator as Paginator;
use Validator;
use ParagonIE\Halite\File;
use ParagonIE\Halite\KeyFactory;
use ParagonIE\Halite\Symmetric\Crypto as Symmetric;

class BehigorriRepository extends EntityRepository
{
  protected $path;
  protected $filePath;
  protected $fileName;

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

      private function setPaths($id){
          $this->path = storage_path() . '/' . $this->idToPath($id);
          $this->filePath = $this->path . '/' . substr($id,-1);
      }

      private function idToPath($id) {
          if ($id < 10) {
          	$this->fileName = $id;
              return "0/" . $id;
          }
          $idArray = str_split((string)$id);
          $this->fileName = end($idArray);
          array_pop($idArray);
          return implode('/', $idArray);
      }

      public function getEncryptionKey($data, $password)
      {
        $encryptionKey;
        if($data->getGroup()!==null){
          $salt = $data->getGroup()->getSalt();
          $encryptionKey = KeyFactory::deriveEncryptionKey($password, $salt);
        }else{
          $salt = $loggedUser->getSalt();
          $encryptionKey = KeyFactory::deriveEncryptionKey($password, $salt);
        }
        return $encryptionKey;
      }

      public function decryptText($encryptionKey)
      {
        $sensitiveDataText = file_get_contents($this->filePath);
        $decrypted = Symmetric::decrypt($sensitiveDataText, $encryptionKey);
        return $decrypted;
      }

      public function decryptFile($encryptionKey,$fileName)
      {
        File::decrypt($this->path . '/' . $this->fileName .'.0', storage_path() . '/' . $fileName, $encryptionKey);
      }

      public function decryptFileWithId($id,$encryptionKey,$fileName)
      {
          $this->setPaths($id);
          $this->decryptFile($encryptionKey,$fileName);
      }

      public function encryptFile($data,$oldEncryptionKey, $newEncryptionKey,$file)
      {
        if($file!== null)
        {
          $realFilePath = ''.$this->filePath . '.0';
          $fileName= $file->getClientOriginalName();
          $file->move($this->path, $fileName);
          $outputFile = $this->openFile($realFilePath);
          File::encrypt($this->path . '/' . $fileName, $outputFile, $newEncryptionKey);
          $this->closeFile($outputFile);
          unlink($this->path . '/' . $fileName);
          $data->setHasFile(true);
          $data->setFileName(pathinfo($fileName, PATHINFO_FILENAME));
          $data->setFileExtension(pathinfo($fileName, PATHINFO_EXTENSION));
        }else if($data->getHasFile()){
          $realFilePath = $this->filePath . '.0';
          $fileName= $data->getFileName() .'.'. $data->getFileExtension();
          $this->decryptFile($oldEncryptionKey,$fileName);
          $realFilePath = $this->filePath . '.0';
          $outputFile = $this->openFile($realFilePath);
          File::encrypt(storage_path() . '/' . $fileName, $outputFile, $newEncryptionKey);
          fclose($outputFile);
          unlink(storage_path() . '/' . $fileName);
        }
      }

      public function encrypText($encryptionKey, $text)
      {
        if (!file_exists($this->path)) {
            mkdir($this->path, 0777, true);
        }
        $sensitiveDataText  = $this->openFile($this->filePath);
        $ciphertext = Symmetric::encrypt($text, $encryptionKey);
        fwrite($sensitiveDataText , $ciphertext);
        $this->closeFile($sensitiveDataText);
      }

      public function openFile($filepath)
      {
        $openedFile = fopen($filepath , "w+") or die(trans('translations.fileOpenError'));

        return $openedFile;
      }

      public function closeFile($openedFile)
      {
        return fclose($openedFile);
      }

      public function encryptSensitiveData($data, $oldEncryptionKey, $newEncryptionKey,$text,$file)
      {
        $this->setPaths($data->getId());
        if (!file_exists($this->path)) {
          mkdir($this->path, 0777, true);
        }
        if($text === null){
          $text=$this->decryptText($oldEncryptionKey);
        }
        $this->encrypText($newEncryptionKey, $text);
        $this->encryptFile($data,$oldEncryptionKey, $newEncryptionKey,$file);
      }

      public function changeEncryption($data,$oldPassword,$oldSalt,$newPassword,$newSalt,$newText, $newFile)
      {
        $this->setPaths($data->getId());
        $oldEncryptionKey = KeyFactory::deriveEncryptionKey($oldPassword,$oldSalt);
        $newEncryptionKey = KeyFactory::deriveEncryptionKey($newPassword,$newSalt);
        if($newText === null)
        {
          $newText = $this->decryptText($oldEncryptionKey);
        }

        $this->encryptSensitiveData($data,$oldEncryptionKey, $newEncryptionKey,$newText,$newFile);
      }


}
