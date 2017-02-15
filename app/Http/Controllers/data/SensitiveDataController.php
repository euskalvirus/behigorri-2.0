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
use Illuminate\Pagination\Paginator as Paginator;
use Illuminate\Pagination\LengthAwarePaginator as LengthAwarePaginator;
use Illuminate\Support\Collection as Collection;
use Validator;
use Route;


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

    protected function sensitiveDataEdit(Request $request)
    {
        //$request->request() = $request->session()->get('_old_input');
        dd($request);
        if($request->session()->get('_old_input'))
        {
          $request->request->add($request->session()->get('_old_input'));
        }

        //dd($request);
        $inputs = $request->session()->get('_old_input');
        $id =$inputs['id'];
        //$this->confirmPassword($request);
    	  $data = $this->em->find("Behigorri\Entities\SensitiveData",$id);
        $loggedUser = Auth::user();
        if ($data && $data->getUser()->getId()==$loggedUser->getId() && $inputs['dataToken'] == $loggedUser->getDataToken())
        {
            $filteredGroups=[];
            if($data->getUser()->getId() == $loggedUser->getId())
            {
                $groups = $data->getGroups();
                $userGroups =  $loggedUser->getGroups();
                foreach($groups as $group)
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
	        if($groups == null){
	            $groups = [];
	        }
	        foreach ($groups as $groupId)
	        {
	            $group= $this->em->find("Behigorri\Entities\Group", $groupId);
	            $data->addGroup($group);
	        }

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
        if ($data && $data->getUser()->getId()==$loggedUser->getId())
        {

        	$data->setName($this->repository->avoidSqlInjection($request->input('name')));
            $data->setUpdatedAt($mysqltime = date("Y-m-d H:i:s"));
	        $newGroups=$request->input('groups', []);
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
	        }
	        $data = $this->updateTags($request->input('tags'),$data);
	        $this->em->persist($data);
	        $this->em->flush();
	        if(!$data->gethasFile())
		    {
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
		        unlink(storage_path() . '/' . 'encryption.key');
		    }

      }
        return redirect('/');
    }

    protected function sensitiveDataDelete($id)
    {
    	$data = $this->em->find("Behigorri\Entities\SensitiveData",$id);
        $loggedUser = Auth::user();
        if ($data->getUser()->getId()==$loggedUser->getId())
        {

            $groups = $data->getGroups();
            foreach ($groups as $group){
                $data->removeGroup($group);
            }
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

    protected function sensitiveDataView(Request $request)
    {
      $inputs = $request->session()->get('_old_input');
      $id =$inputs['id'];
    //$this->confirmPassword($request);
    $loggedUser = Auth::user();
    $data = $this->em->find("Behigorri\Entities\SensitiveData",$id);
    if ($data && $loggedUser->canBeViewSenstiveData($id) && $inputs['dataToken'] == $loggedUser->getDataToken())
    {
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
                          'groups' => '',
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
      		$searchDatas = $this->paginate($datas,15);
      	}else{
      		$searchDatas = $this->paginate($datas,15);
      	}
      	return $this->returnSearchView($searchDatas);
    }

    private function paginate($items,$perPage)
    {
    	if($items)
    	{
    		$pageStart = \Request::get('page', 1);
    		// Start displaying items from this number;
    		$offSet = ($pageStart * $perPage) - $perPage;
    		// Get only the items you need using array_slice
    		$itemsForCurrentPage = array_slice($items, $offSet, $perPage, true);

    		return new LengthAwarePaginator($itemsForCurrentPage, count($items), $perPage,Paginator::resolveCurrentPage(), array('path' => Paginator::resolveCurrentPath()));
    	}else{
    		return [];
    	}

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
   		$searchDatas = $this->paginate($datas,15);
   		return $this->returnSearchView($searchDatas);
   }

   private function returnSearchView($searchDatas)
   {
   	$loggedUser =Auth::user();
   	$sensitivedatas = $loggedUser->getUniqueSensitiveData();
   	$dataTags = $this->repository->getTags($sensitivedatas);
    $datas = $this->paginate($sensitivedatas,15);
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


           /*$keyFactoryFile  = fopen(storage_path() . '/' . 'encryption.key', "w") or die("Unable to open file!");
           fwrite($keyFactoryFile  , $owner->getSalt());
           fclose($keyFactoryFile );
           //dd($this->path . '/' . $this->fileName . '.0');
           $encryptionKey = KeyFactory::loadEncryptionKey(storage_path() . '/' . 'encryption.key');
           //$outputFile = fopen(storage_path() . '/' . $data->getFileName() .'.'. $data->getFileExtension() , "w") or die("Unable to open file!");
           //$inputFile  = fopen($this->path . '/' . $this->fileName . '.0', "w") or die("Unable to open file!");
           File::decrypt($this->path . '/' . $this->fileName . '.0', storage_path() . '/' . $data->getFileName() .'.'. $data->getFileExtension(), $encryptionKey);
           return response()->download(storage_path() . '/' . $data->getFileName() .'.'. $data->getFileExtension())->deleteFileAfterSend(true);
           //fclose($outputFile);
           unlink(storage_path() . '/' . 'encryption.key');*/
        }

     }else{
       return redirect('/');
     }

   }

   public function confirmPassword(Request $request)
   {
     //dd($request);
     $loggedUser = Auth::user();
     if(!password_verify( $request->input('password'),$loggedUser->getDecryptPassword()))
     {
       return redirect()->back()->withErrors(array('error' => 'incorrect password'));
     }
     $dataToken = ['dataToken' => '' . md5(microtime().rand()) . ''];
     $validator = $this->tokeValidator($dataToken);

     while ($validator->fails()) {
        $dataToken = $this->createDataToken();
        $validator = $this->tokeValidator($dataToken);
     }
     $loggedUser->setDataToken($dataToken['dataToken']);
     //$request->all()['dataToken'] = $dataToken['dataToken'];
     $request->request->set('dataToken', $dataToken['dataToken']);
     $this->generateArray($request);
     $this->em->persist($loggedUser);
     $this->em->flush();
     if($request->input('action')=='edit')
     {
       return redirect('/data/edit/' . $dataToken['dataToken'])->withInput($request->all());
       //return redirect()->route('/data/edit/{token?}')->with($request->all());
       //return redirect('/data/edit/' . $dataToken['dataToken'])->with('id',1);
     }elseif ($request->input('action')=='view')
     {
        return redirect('/data/view/' . $dataToken['dataToken'])->withInput($request->all());
     }else{
       return redirect()->back();
     }
   }

   private function tokeValidator(array $data)
   {
       return Validator::make($data, [
           'dataToken' => 'required|max:355|unique:User',
       ], $this->repository->getValitationMessages());
   }

   private function createDataToken()
   {
      return md5(microtime().rand());
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
