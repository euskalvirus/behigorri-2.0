<?php
namespace App\Http\Controllers\data;

use Behigorri\Entities\SensitiveData;
use Behigorri\Entities\User;
use Behigorri\Entities\Tag;
use Behigorri\Entities\Group;
use Doctrine\ORM\EntityManager;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use Crypt;
use ParagonIE\Halite\File;
use ParagonIE\Halite\KeyFactory;
use Illuminate\Http\Response;
use ParagonIE\Halite\Symmetric\Crypto as Symmetric;
use Illuminate\Support\Collection as Collection;
use Validator;
use Session;


class SensitiveDataController extends Controller
{
  protected $em;
  protected $path;
  protected $filePath;
  protected $fileName;
  protected $userRepo;
  protected $repository;

  public function __construct(EntityManager $EntityManager, Request $request)
  {
      $this->em = $EntityManager;
      $this->repository = $this->em->getRepository(SensitiveData::class);
  }

  public function compare_objects($a, $b)
  {
      return strcmp(spl_object_hash($a), spl_object_hash($b));
  }

  protected function sensitiveDataEdit($id,$dataToken)
  {
  	$data = $this->em->find("Behigorri\Entities\SensitiveData",$id);
      $loggedUser = Auth::user();
      if ($loggedUser->getSalt() && isset($dataToken) && isset($id) && $data && $data->getUser()->getId()==$loggedUser->getId() && $dataToken == $loggedUser->getDataToken())
      {
          $decrypted;
          if (file_exists(storage_path() . '/' . $dataToken)) {
              $decrypted = file_get_contents(storage_path() . '/' . $dataToken);
          }else{
              return redirect()->back()->withErrors(['error'=>trans('translations.fileOpenError')]);
          }
          unlink(storage_path() . '/' . $dataToken);
          $loggedUser->setDataToken($this->createDataToken()['dataToken']);
          $this->em->persist($loggedUser);
	        $this->em->flush();
          $filteredGroups=[];
          $userGroups =  $loggedUser->getGroups();
          foreach($userGroups as $group)
          {
              if (($data->getGroup() && $data->getGroup()->getId() != $group->getId()) || !$data->getGroup()){
                $filteredGroups[$group->getId()]=[
                    'name' => $group->getName()
                ];
              }
          }

          	return view('data.userDataEdit')->with([
          			'user' => $loggedUser,
          			'title' => 'WELLCOME SIMPLE USER',
          			'data' => $data,
          			'groups' => $filteredGroups,
                'text' => $decrypted,
          			'tags' => $this->getTagsStrings($data->getTags())
  				]);
      } else
      {
        return redirect('/');
      }
  }

  public function newSensitiveData()
  {
      $loggedUser = Auth::user();
      if($loggedUser->getSalt())
      {
      $groups = $loggedUser->getGroups();
      return view('data.userNewData')->with([
          'user' => $loggedUser,
          'title' => 'WELLCOME SIMPLE USER',
          'groups' => $groups,
          ]);
      }else{
          return redirect('/');
      }
  }

  protected function sensitiveDataSave(Request $request)
  {
        $loggedUser = Auth::user();
        if($loggedUser->getSalt())
        {
            $data = new SensitiveData();
            $data->setName($this->repository->avoidSqlInjection($request->input('name')));
            $data->setUser($loggedUser);
            $data->setCreatedAt($mysqltime = date("Y-m-d H:i:s"));
            $data->setUpdatedAt($mysqltime = date("Y-m-d H:i:s"));
            $data->setHasFile(false);
            $group = null;
            $encryptionKey;
            if($request->input('group') !== null){
              $group= $this->em->find("Behigorri\Entities\Group", $request->input('group'));
              if(!password_verify($request->input('password'), $group->getDecryptPassword()))
              {
                return redirect()->back()->withErrors(array('error' => trans('translations.groupDecryptPasswordError')));
              }
              $salt = $group->getSalt();
              $encryptionKey = KeyFactory::deriveEncryptionKey($request->input('password'), $salt);
            }else{

              if(!password_verify($request->input('password'), $loggedUser->getDecryptPassword()))
              {
                return redirect()->back()->withErrors(array('error' => trans('translations.userDecryptPasswordError')));
              }
              $salt = $loggedUser->getSalt();
              $encryptionKey = KeyFactory::deriveEncryptionKey($request->input('password'), $salt);
            }

            $data->setGroup($group);
            $this->em->persist($data);
            $this->em->flush();
            $this->repository->encryptSensitiveData($data, $encryptionKey, $encryptionKey,$request->input('text'),$request->file('dataFile'));
            $dataWithTags = $this->splitAndCreateTags($request->input('tags'),$data);
            $this->em->persist($dataWithTags);
            $this->em->flush();
        }
        return redirect('/');
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

  protected function sensitiveDataUpdate(Request $request)
  {
    $loggedUser = Auth::user();
    $data = $this->em->find("Behigorri\Entities\SensitiveData",$request->input('id'));
    if ($loggedUser->getSalt() && $data && $data->getUser()->getId()==$loggedUser->getId() && $loggedUser->getDataToken()==$request->input('dataToken'))
    {
      $oldPassword=$request->input('oldPassword');
      $oldSalt;
      if($data->getGroup()!== null)
      {
        if(!password_verify($oldPassword, $data->getGroup()->getDecryptPassword()))
        {
          //return redirect()->back()->withErrors(array('error' => 'incorrect file decryption password'));
          return redirect('/')->withErrors(array('error' => trans('translations.groupDecryptPasswordError')));
        }
        $oldSalt = $data->getGroup()->getSalt();
      }else{
        if(!password_verify($oldPassword, $data->getUser()->getDecryptPassword()))
        {
            return redirect('/')->withErrors(array('error' => trans('translations.userDecryptPasswordError')));
        }
        $oldSalt = $data->getUser()->getSalt();
      }
      $loggedUser->setDataToken($this->createDataToken()['dataToken']);
      $data->setName($this->repository->avoidSqlInjection($request->input('name')));
      $data->setUpdatedAt($mysqltime = date("Y-m-d H:i:s"));

      $newSalt;
      $newGroup;
      $newPassword;
      if($request->input('newPassword') !== ''){
        $newPassword = $request->input('newPassword');
        if($request->input('group') === "null")
        {
          if(!password_verify($request->input('newPassword'), $data->getUser()->getDecryptPassword()))
          {
            return redirect('/')->withErrors(array('error' => trans('translations.newUserDecryptPasswordError')));
          }
          $data->setGroup(null);
          $newSalt = $data->getUser()->getSalt();
        }else{
          $newGroup = $this->em->find("Behigorri\Entities\Group",$request->input('group'));
          if(!password_verify($request->input('newPassword'), $newGroup->getDecryptPassword()))
          {
            return redirect('/')->withErrors(array('error' => trans('translations.newGroupDecryptPasswordError')));
          }
          $data->setGroup($newGroup);
          $newSalt = $newGroup->getSalt();
        }
      }else{
        $newPassword = $request->input('oldPassword');
        if($data->getGroup() !== null)
        {
          $newSalt = $data->getGroup()->getSalt();
        }else{
          $newSalt = $data->getUser()->getSalt();
        }
      }
      $newEncryptionKey = KeyFactory::deriveEncryptionKey($newPassword, $newSalt);
      $oldEncryptionKey = KeyFactory::deriveEncryptionKey($oldPassword, $oldSalt);
      $data = $this->updateTags($request->input('tags'),$data);
      $this->repository->changeEncryption($data,$oldPassword,$oldSalt,$newPassword,$newSalt,$request->file('text'), $request->file('dataFile'));
      $this->em->persist($data);
      $this->em->flush();
    }
    return redirect('/');
}

  private function sensitiveDataDelete($id)
  {
      $loggedUser = Auth::user();
      $data = $this->em->find("Behigorri\Entities\SensitiveData",$id);
      if ($loggedUser->getSalt() && $data && $loggedUser->canBeViewSenstiveData($id) && $data->getUser()->getId()==$loggedUser->getId())
      {
      $this->setPaths($id);
      if (file_exists($this->filePath)) {
         unlink($this->filePath);
        }
        if($data->getHasFIle() && file_exists($this->filePath .'.0'))
         {
            unlink($this->filePath .'.0');
          }
        $this->em->remove($data);
        $this->em->flush();
      return 'ok';
    }
    return 'not ok';

  }

  public function newSensitiveDataFile()
  {
  	$loggedUser = Auth::user();
  	$groups = $loggedUser->getGroups();
  	return view('data.userNewDataFile')->with([
  			'user' => $loggedUser,
  			'title' => 'WELLCOME SIMPLE USER',
  			'groups' => $groups,
  	]);
  }

  protected function sensitiveDataView($id,$dataToken)
  {
  $loggedUser = Auth::user();
  $data = $this->em->find("Behigorri\Entities\SensitiveData",$id);
  if ($loggedUser->getSalt() && isset($dataToken) && isset($id) && $data && $loggedUser->canBeViewSenstiveData($id) && $dataToken == $loggedUser->getDataToken())
  {
      $decrypted;
      if (file_exists(storage_path() . '/' . $dataToken)) {
          $decrypted = file_get_contents(storage_path() . '/' . $dataToken);
      }else{
          return redirect()->back()->withErrors(['error'=>trans('translations.fileOpenError')]);
      }
      unlink(storage_path() . '/' . $dataToken);
      $loggedUser->setDataToken($this->createDataToken()['dataToken']);
      $this->em->persist($loggedUser);
      $this->em->flush();
      $owner = $data->getUser();
      return view('data.userDataView')->with([
            'user' => $loggedUser,
            'title' => 'WELLCOME SIMPLE USER',
            'data' => $data,
            'text'=> $decrypted,
            'tags' => $this->getTagsStrings($data->getTags())
      ]);
  }
  return redirect('/');
}


  private function splitAndCreateTags($tagsString, $data)
  {
    if($tagsString!='')
    {
      $tags = explode(',', $tagsString);
    	return $this->filterTags($tags,$data);
    }
  	else{
      return $data;
    }
  }

  private function filterTags($tags,$data)
  {
    foreach ($tags as $key => $tag) {
      if($tag ==''){
        unset($tags[$key]);
      }
    }
  	$tagRep = $this->em->getRepository('Behigorri\Entities\Tag');
    //dd($tags);
  	foreach ($tags as $tag){
  		if(!$this->tagExist($tag, $tagRep)){
  			$newTag = new Tag();
  			$newTag->setName($tag);
        $newTag->setUpdatedAt($mysqltime = date("Y-m-d H:i:s"));
        $newTag->setUpdatedAt($mysqltime = date("Y-m-d H:i:s"));
  			$this->em->persist($newTag);
  			$this->em->flush();
        //dd($newTag->getId());
  			$data->addTag($newTag);
  		}else {
  			$tag = $tagRep->findBy(['name' => $tag]);
  			$data->addTag($tag[0]);
  		}
  	}
  	return $data;
  }

  private function tagExist($tag, $tagRep)
  {
  	$search = $tagRep->findBy(['name' => $tag]);
  	if(empty($search)){
  		return false;
  	} else {
  		return true;
  	}
  }

  private function getTagsStrings($tags){
  	$tagsString = "";
  	foreach ($tags as $tag){
  		$tagsString = $tagsString . $tag->getName() . ',';
  	}
  	return $tagsString;
  }

  private function updateTags($tagsString, $data)
  {
  	$splitedTags = explode(',', $tagsString);
  	foreach ($data->getTags() as $tag)
  	{
  		if(!in_array($tag->getName(),$splitedTags)){
  			$data->removeTag($tag);
  		}
  		unset($splitedTags[array_search($tag->getName(), $splitedTags)]);

  	}
  	return $this->filterTags($splitedTags,$data);

  }

  protected function sensitiveDataSearch(Request $request)
  {
  	$loggedUser = Auth::user();
    if($loggedUser->getSalt())
    {
        $datas = $loggedUser->getUniqueSensitiveData();
    	if($request->input('search')!='')
    	{
    		$splitedWords = explode(' ', $request->input('search'));
    		foreach ($splitedWords as $id =>$word) {
    			$splitedWords[$id]=(filter_var(stripslashes(trim($word)), FILTER_SANITIZE_STRING));
    		}
    		$searchDatas=[];
    		foreach ($datas as $id =>$data)
    		{
    			if(!$this->similarInArray($data,$splitedWords))
    			{
    				unset($datas[$id]);
    			}
    		}
    	}
      $searchDatas = $this->repository->paginate($datas);
    	return $this->returnSearchView($searchDatas);
    }
  	return redirect('/');
  }

  private function similarInArray($data, $array)
  {
  	foreach ($array as $word)
  	{
     		if(strpos($data->getName(), $word)!== false)
  		{
  			return true;
  		}
  	}
  	return false;
  }

 public function sensitiveDataSearchByTag($tag)
 {
 		$loggedUser = Auth::user();
        if($loggedUser->getSalt())
        {
            $datas = $loggedUser->getSensitiveDataByTag($tag);
 		$searchDatas = $this->repository->paginate($datas,15);
 		return $this->returnSearchView($searchDatas);
        }
 		return redirect('/');
 }

 private function returnSearchView($searchDatas)
 {
 	$loggedUser =Auth::user();
 	$sensitivedatas = $loggedUser->getUniqueSensitiveData();
 	$dataTags = $this->repository->getTags($sensitivedatas);
  $datas = $this->repository->paginate($sensitivedatas);
  if($loggedUser->getGod() || $loggedUser->getUserActive())
  {
    return view(trans('index.index'))->with([
        'user' => $loggedUser,
        'title' => 'BEHIGORRI PASSWORD MANAGER',
        'datas' => $searchDatas,
        'tags' => $dataTags
    ]);
  } else{
    Auth::logout();
    $error='not activated';
    return view('auth.login')->with(['error' => $error]);
  }
 }

 private function sensitiveDataDownload($id,$password)
 {
   $loggedUser = Auth::user();
   $data = $this->em->find("Behigorri\Entities\SensitiveData",$id);
   if ($data && $loggedUser->canBeViewSenstiveData($id))
   {
     $loggedUser->setDataToken($this->createDataToken()['dataToken']);
     $this->em->persist($loggedUser);
     $this->em->flush();
     $owner = $data->getUser();
     $this->setPaths($id);
     if (file_exists($this->path))
     {
        $salt;
       if($data->getGroup()!=null){
         $salt = $data->getGroup()->getSalt();
       }else{
         $salt = $data->getUser()->getSalt();
       }
       $encryptionKey = KeyFactory::deriveEncryptionKey($password, $salt);
       $fileName= $data->getFileName() .'.'. $data->getFileExtension();
       $this->repository->decryptFileWithId($id,$encryptionKey,$fileName);
       $outputFile  = $this->repository->openFile(storage_path() . '/' . $fileName);
       $this->repository->decryptFile($encryptionKey,$fileName);
       $this->repository->closeFile($outputFile);
       return response()->download(storage_path() . '/' . $fileName)->deleteFileAfterSend(true);
      }
   }else{
     return redirect('/');
   }

 }

 public function confirmPassword(Request $request)
 {
   //dd($request);
   $loggedUser = Auth::user();

   if($loggedUser->getSalt() && $loggedUser->canBeViewSenstiveData($request->input('id')))
   {
     $data = $this->em->find("Behigorri\Entities\SensitiveData",$request->input('id'));
      $verifycation = $this->verifyPassword($request->all());
      if(isset($verifycation['error'])){
        return redirect()->back()->withErrors($verifycation);
      }
       $dataToken = $this->createDataToken();
       $loggedUser->setDataToken($dataToken['dataToken']);
       $request->request->set('dataToken', $dataToken['dataToken']);
       $this->em->persist($loggedUser);
       $this->em->flush();
       if($request->input('action')=='edit')
       {
           $this->decryptDataTextandCreateFile($request->input('id'),$request->input('password'),$dataToken['dataToken']);
         return redirect()->route('DataEdit', ['id' => $request->input('id'), 'dataToken' => $dataToken['dataToken']]);
       }elseif ($request->input('action')=='view')
       {
           $this->decryptDataTextandCreateFile($request->input('id'),$request->input('password'),$dataToken['dataToken']);
          return redirect()->route('DataView', ['id' => $request->input('id'), 'dataToken' => $dataToken['dataToken']]);
       }elseif ($request->input('action')=='delete')
      {
          $this->sensitiveDataDelete($request->input('id'));
          return redirect()->back();
      }elseif($request->input('action')=='downloadFile')
      {
          return $this->sensitiveDataDownload($request->input('id'),$request['password']);
      }else{
         return redirect()->back();
       }

   }else{
     return redirect()->back();
   }

 }

//IN PROGRESS!!!!!!!!!!!!!!!
 private function decryptDataTextandCreateFile($id,$password, $dataToken)
 {
   $loggedUser = Auth::user();
     $salt;
     $encryptionKey;
     $data = $this->em->find("Behigorri\Entities\SensitiveData",$id);
     if($data->getGroup()!==null){
       $salt = $data->getGroup()->getSalt();
       $encryptionKey = KeyFactory::deriveEncryptionKey($password, $salt);
     }else{
       $salt = $loggedUser->getSalt();
       $encryptionKey = KeyFactory::deriveEncryptionKey($password, $salt);
     }
     $name = $dataToken;
     $this->setPaths($id);
     $decrypted;
     if (file_exists($this->path)) {
         $sensitiveDataText = file_get_contents($this->filePath);
         $decrypted = Symmetric::decrypt($sensitiveDataText, $encryptionKey);
     }
     $newDecryptedFile  = $this->repository->openFile(storage_path() . '/' . $dataToken);
     fwrite($newDecryptedFile , $decrypted);
     $this->repository->closeFile($newDecryptedFile);
     return 'ok';
 }

 private function verifyPassword(Array $request)
 {

   $loggedUser = Auth::user();
    $data = $this->em->find("Behigorri\Entities\SensitiveData",$request['id']);

    if($data->getGroup()){
      if(!password_verify( $request['password'],$data->getGroup()->getDecryptPassword()))
      {
        return array('error' => trans('translations.groupDecryptPasswordError'));
      }
    }else{
      if(!password_verify( $request['password'],$loggedUser->getDecryptPassword()))
      {
        return array('error' => trans('translations.userDecryptPasswordError'));
      }
    }
 }

 private function tokenValidator(array $data)
 {
     return Validator::make($data, [
         'dataToken' => 'required|max:355|unique:User',
     ], $this->repository->getValitationMessages());
 }

 private function createDataToken()
 {
     $dataToken = ['dataToken' => '' . md5(microtime().rand()) . ''];
     $validator = $this->tokenValidator($dataToken);

     while ($validator->fails()) {
        $dataToken = md5(microtime().rand());
        $validator = $this->tokeValidator($dataToken);
     }
    return  $dataToken;
 }

 private function generateArray($request)
 {
   $datas=[];
   foreach($request->all() as $id => $field)
   {
     $datas[$id] = $field;
   }
   return $datas;
 }
}
