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


class SensitiveDataController extends Controller
{
    protected $em;
    protected $path;
    protected $filePath;
    protected $fileName;
    protected $userRepo;

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
        if (isset($dataToken) && isset($id) && $data && $data->getUser()->getId()==$loggedUser->getId() && $dataToken == $loggedUser->getDataToken())
        {
            //$loggedUser->setDataToken($dataToken['dataToken']);
            $loggedUser->setDataToken($this->createDataToken()['dataToken']);
            $this->em->persist($loggedUser);
  	        $this->em->flush();
            $filteredGroups=[];
            if($data->getUser()->getId() == $loggedUser->getId())
            {
                $groups = $data->getGroups();
                $userGroups =  $loggedUser->getGroups();
                /*foreach($groups as $group)
                {
                    $filteredGroups[$group->getId()]=[
                        'name' => $group->getName(),
                        'active' => true
                    ];
                }

                foreach($userGroups as $group)
                {
                    if (!isset($filteredGroups[$group->getId()])){
                        $filteredGroups[$group->getId()]=[
                            'name' => $group->getName(),
                            'active' => false
                        ];
                    }
                }*/
                foreach($userGroups as $group)
                {
                    if ($data->getGroup() && $data->getGroup()->getId() == $group->getId()){

                    }
                    $filteredGroups[$group->getId()]=[
                        'name' => $group->getName()
                    ];
                }
            }
            $sensitiveDataText = '';
            $this->setPaths($id);
            if (file_exists($this->path)) {
                $sensitiveDataText = file_get_contents($this->filePath);
                $keyFactoryFile  = fopen(storage_path() . '/' . 'encryption.key', "w") or die("Unable to open file!");
                fwrite($keyFactoryFile  , $loggedUser->getSalt());
                fclose($keyFactoryFile );
                $encryptionKey = KeyFactory::loadEncryptionKey(storage_path() . '/' . 'encryption.key');
                $decrypted = Symmetric::decrypt($sensitiveDataText, $encryptionKey);
                unlink(storage_path() . '/' . 'encryption.key');
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
        $groups = $loggedUser->getGroups();
        return view('data.userNewData')->with([
            'user' => $loggedUser,
            'title' => 'WELLCOME SIMPLE USER',
            'groups' => $groups,
            ]);
    }

    protected function sensitiveDataSave(Request $request)
    {
	        $loggedUser = Auth::user();
	        $data = new SensitiveData();
	        $data->setName($this->repository->avoidSqlInjection($request->input('name')));
	        $data->setUser($loggedUser);
          $data->setCreatedAt($mysqltime = date("Y-m-d H:i:s"));
          $data->setUpdatedAt($mysqltime = date("Y-m-d H:i:s"));
	        $data->setHasFile(false);
	        $groups=$request->input('groups');
	        /*if($groups == null){
	            $groups = [];
	        }
	        foreach ($groups as $groupId)
	        {
	            $group= $this->em->find("Behigorri\Entities\Group", $groupId);
	            $data->addGroup($group);
	        }*/

          $group= $this->em->find("Behigorri\Entities\Group", $request->input('group'));
	        $data->setGroup($group);
	        $this->em->persist($data);
	        $this->em->flush();

	        $this->setPaths($data->getId());
	        if (!file_exists($this->path)) {
	            mkdir($this->path, 0777, true);
	        }
	        //var_dump($this->filePath);exit;
	        $sensitiveDataText  = fopen($this->filePath , "w") or die("Unable to open file!");
	        $keyFactoryFile  = fopen(storage_path() . '/' . 'encryption.key', "w") or die("Unable to open file!");
	        fwrite($keyFactoryFile  , $loggedUser->getSalt());
	        fclose($keyFactoryFile );
	        $encryptionKey = KeyFactory::loadEncryptionKey(storage_path() . '/' . 'encryption.key');
	        $ciphertext = Symmetric::encrypt($request->input('text'), $encryptionKey);
	        fwrite($sensitiveDataText , $ciphertext);
	        fclose($sensitiveDataText );


          if($request->file('dataFile')){

            $file = $request->file('dataFile');
      		  $fileName= $file->getClientOriginalName();
            $file->move($this->path, $fileName);
            $outputFile  = fopen($this->filePath . '.0' , "w") or die("Unable to open file!");
            //dd($outputFile);
            $keyFactoryFile  = fopen(storage_path() . '/' . 'encryption.key', "w") or die("Unable to open file!");
            fwrite($keyFactoryFile  , $loggedUser->getSalt());
            fclose($keyFactoryFile );
            $encryptionKey = KeyFactory::loadEncryptionKey(storage_path() . '/' . 'encryption.key');
            File::encrypt($this->path . '/' . $fileName, $outputFile, $encryptionKey);
            fclose($outputFile);
            unlink($this->path . '/' . $fileName);
            $data->setHasFile(true);
            $data->setFileName(pathinfo($fileName, PATHINFO_FILENAME));
            $data->setFileExtension(pathinfo($fileName, PATHINFO_EXTENSION));
            $this->em->persist($data);
  	        $this->em->flush();

          }


	        unlink(storage_path() . '/' . 'encryption.key');
	        $dataWithTags = $this->splitAndCreateTags($request->input('tags'),$data);
	        $this->em->persist($dataWithTags);
	        $this->em->flush();

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
        if ($data && $data->getUser()->getId()==$loggedUser->getId() && $loggedUser->getDataToken()==$request->input('dataToken'))
        {
          $loggedUser->setDataToken($this->createDataToken()['dataToken']);
        	$data->setName($this->repository->avoidSqlInjection($request->input('name')));
            $data->setUpdatedAt($mysqltime = date("Y-m-d H:i:s"));
	        /*$newGroups=$request->input('group');
	        foreach($data->getGroups() as $group)
	        {
	            if(in_array($group->getId(),$newGroups)){
	                unset($newGroups[array_search($group->getId(), $newGroups)]);
	            } else {
	                $data->removeGroup($group);
	            }
	        }

	        foreach($newGroups as $groupId)
	        {
	            $group= $this->em->find("Behigorri\Entities\Group", $groupId);
	            $data->addGroup($group);
	        }*/
          $group= $this->em->find("Behigorri\Entities\Group", $request->input('group'));
          $data->setGroup($group);
	        $data = $this->updateTags($request->input('tags'),$data);
	        $this->em->persist($data);
	        $this->em->flush();
	        $this->setPaths($request->input('id'));
	        if (!file_exists($this->path)) {
	            mkdir($this->path, 0777, true);
		      }
		      $sensitiveDataText  = fopen($this->filePath , "w") or die("Unable to open file!");
		      $keyFactoryFile  = fopen(storage_path() . '/' . 'encryption.key', "w") or die("Unable to open file!");
	        fwrite($keyFactoryFile  , $loggedUser->getSalt());
	        fclose($keyFactoryFile );
	        $encryptionKey = KeyFactory::loadEncryptionKey(storage_path() . '/' . 'encryption.key');
	        $ciphertext = Symmetric::encrypt($request->input('text'), $encryptionKey);
		      fwrite($sensitiveDataText , $ciphertext);
	        fclose($sensitiveDataText );

          if($request->file('dataFile')){

            $file = $request->file('dataFile');
            $fileName= $file->getClientOriginalName();
            $file->move($this->path, $fileName);
            $outputFile  = fopen($this->filePath . '.0' , "w") or die("Unable to open file!");
            //dd($outputFile);
            $keyFactoryFile  = fopen(storage_path() . '/' . 'encryption.key', "w") or die("Unable to open file!");
            fwrite($keyFactoryFile  , $loggedUser->getSalt());
            fclose($keyFactoryFile );
            $encryptionKey = KeyFactory::loadEncryptionKey(storage_path() . '/' . 'encryption.key');
            File::encrypt($this->path . '/' . $fileName, $outputFile, $encryptionKey);
            fclose($outputFile);
            unlink($this->path . '/' . $fileName);
            $data->setHasFile(true);
            $data->setFileName(pathinfo($fileName, PATHINFO_FILENAME));
            $data->setFileExtension(pathinfo($fileName, PATHINFO_EXTENSION));
            $this->em->persist($data);
            $this->em->flush();

          }
      }
        return redirect('/');
    }

    protected function sensitiveDataDelete($id,$dataToken)
    {
    	$data = $this->em->find("Behigorri\Entities\SensitiveData",$id);
        $loggedUser = Auth::user();
        if (isset($dataToken) && isset($id) && $data->getUser()->getId()==$loggedUser->getId() && $dataToken == $loggedUser->getDataToken())
        {
            $loggedUser->setDataToken($this->createDataToken()['dataToken']);
            $this->em->persist($loggedUser);
            $this->em->flush();

            /*$groups = $data->getGroups();
            foreach ($groups as $group){
                $data->removeGroup($group);
            }*/
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
        }
        return redirect()->back();

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
    if (isset($dataToken) && isset($id) && $data && $loggedUser->canBeViewSenstiveData($id) && $dataToken == $loggedUser->getDataToken())
    {
        $loggedUser->setDataToken($this->createDataToken()['dataToken']);
        $this->em->persist($loggedUser);
        $this->em->flush();
            $owner = $data->getUser();
            $this->setPaths($id);
            if (file_exists($this->path))
            {
                    $file=null;
                    if ($data->gethasFile())
                    {
                        $file = $data->getFileName() .'.'. $data->getFileExtension();
                    }
                    $sensitiveDataText = file_get_contents($this->filePath);
                    $keyFactoryFile  = fopen(storage_path() . '/' . 'encryption.key', "w") or die("Unable to open file!");
                    fwrite($keyFactoryFile  , $owner->getSalt());
                    fclose($keyFactoryFile );
                    $encryptionKey = KeyFactory::loadEncryptionKey(storage_path() . '/' . 'encryption.key');
                    $decrypted = Symmetric::decrypt($sensitiveDataText, $encryptionKey);
                    return view('data.userDataView')->with([
                          'user' => $loggedUser,
                          'title' => 'WELLCOME SIMPLE USER',
                          'data' => $data,
                          'text'=> $decrypted,
                          'tags' => $this->getTagsStrings($data->getTags())
                    ]);
            }

    }else{
            return redirect('/');
    }
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
   		$datas = $loggedUser->getSensitiveDataByTag($tag);
   		$searchDatas = $this->repository->paginate($datas,15);
   		return $this->returnSearchView($searchDatas);
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

   public function sensitiveDataDownload($id)
   {
     $loggedUser = Auth::user();
     $data = $this->em->find("Behigorri\Entities\SensitiveData",$id);
     if ($data && $loggedUser->canBeViewSenstiveData($id))
     {
       $owner = $data->getUser();
       $this->setPaths($id);
       if (file_exists($this->path))
       {


         $keyFactoryFile  = fopen(storage_path() . '/' . 'encryption.key', "w") or die("Unable to open file!");
         fwrite($keyFactoryFile  , $owner->getSalt());
         fclose($keyFactoryFile );
         $encryptionKey = KeyFactory::loadEncryptionKey(storage_path() . '/' . 'encryption.key');
         $fileName= $data->getFileName() .'.'. $data->getFileExtension();

         $outputFile  = fopen(storage_path() . '/' . $fileName, "w+") or die("Unable to open file!");
         File::decrypt($this->path . '/' . $this->fileName .'.0', storage_path() . '/' . $fileName, $encryptionKey);
         return response()->download(storage_path() . '/' . $fileName)->deleteFileAfterSend(true);
         fclose($outputFile);
         unlink(storage_path() . '/' . 'encryption.key');
        }

     }else{
       return redirect('/');
     }

   }

   public function confirmPassword(Request $request)
   {
     //dd($request);
     $loggedUser = Auth::user();
     if($loggedUser->canBeViewSenstiveData($request->input('id')))
     {
         if(!password_verify( $request->input('password'),$loggedUser->getDecryptPassword()))
         {
           return redirect()->back()->withErrors(array('error' => 'incorrect password'));
         }
         $dataToken = $this->createDataToken();
         $loggedUser->setDataToken($dataToken['dataToken']);
         //$request->all()['dataToken'] = $dataToken['dataToken'];
         $request->request->set('dataToken', $dataToken['dataToken']);
         $this->em->persist($loggedUser);
         $this->em->flush();
         if($request->input('action')=='edit')
         {
           //return redirect('/data/edit/' . $dataToken['dataToken'])->withInput(Input::except('password','action'));
           return redirect()->route('DataEdit', ['id' => $request->input('id'), 'dataToken' => $dataToken['dataToken']]);
           //return redirect()->route('/data/edit/{token?}')->with($request->all());
           //return redirect('/data/edit/' . $dataToken['dataToken'])->with('id',1);
         }elseif ($request->input('action')=='view')
         {
            // return redirect('/data/view/' . $dataToken['dataToken'])->withInput(Input::except('password','action'));
            return redirect()->route('DataView', ['id' => $request->input('id'), 'dataToken' => $dataToken['dataToken']]);
        }elseif ($request->input('action')=='delete')
        {
            return redirect()->route('DataDelete', ['id' => $request->input('id'), 'dataToken' => $dataToken['dataToken']]);
        }else{
           return redirect()->back();
         }

     }else{
       return redirect()->back();
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
