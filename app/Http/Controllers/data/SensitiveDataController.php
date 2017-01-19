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
        var_dump(SensitiveData::class);
        $this->repository = $this->em->getRepository(SensitiveData::class);
        $this->userRepo = $this->em->getRepository(User::class);
        var_dump(get_class($this->userRepo));
    }

    public function compare_objects($a, $b)
    {
        return strcmp(spl_object_hash($a), spl_object_hash($b));
    }

    public function sensitiveDataEdit($id)
    {
    	$data = $this->em->find("Behigorri\Entities\SensitiveData",$id);
        $loggedUser = Auth::user();
        if ($data->getUser()->getId()==$loggedUser->getId())
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
                //	var_dump($filteredGroups);exit;
            }
            $sensitiveDataText = '';
            $this->setPaths($id);
            if (file_exists($this->path) && !$data->getIsFile()) {
                $sensitiveDataText = file_get_contents($this->filePath);
                $keyFactoryFile  = fopen(storage_path() . '/' . 'encryption.key', "w") or die("Unable to open file!");
                fwrite($keyFactoryFile  , $loggedUser->getSalt());
                fclose($keyFactoryFile );
                $encryptionKey = KeyFactory::loadEncryptionKey(storage_path() . '/' . 'encryption.key');
                $decrypted = Symmetric::decrypt($sensitiveDataText, $encryptionKey);
                unlink(storage_path() . '/' . 'encryption.key');
            }
            //var_dump($this->path);exit;
            //var_dump($sensitiveDataText);exit;
            if(!$data->getIsFile())
            {
           		return view('data.userDataEdit')->with([
           			'user' => $loggedUser,
               		'title' => 'WELLCOME SIMPLE USER',
              		'data' => $data,
               		'groups' => $filteredGroups,
               		'text'=> $decrypted,
            		'tags' => $this->getTagsStrings($data->getTags())
    				]);
            }else{
            	return view('data.userDataEdit')->with([
            			'user' => $loggedUser,
            			'title' => 'WELLCOME SIMPLE USER',
            			'data' => $data,
            			'groups' => $filteredGroups,
            			'text'=> "isfile",
            			'tags' => $this->getTagsStrings($data->getTags())
    				]);
            }
        } else
        {
            //return redirect('/');
        	return redirect()->back();
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
    		//var_dump($request->input('tags'));exit;
	        $loggedUser = Auth::user();
	        $data = new SensitiveData();
	        $data->setName($request->input('name'));
	        $data->setUser($loggedUser);
	        $data->setIsFile(false);
	        $groups=$request->input('groups');
	        if($groups == null){
	            $groups = [];
	        }
	        foreach ($groups as $groupId)
	        {
	            $group= $this->em->find("Behigorri\Entities\Group", $groupId);
	            $data->addGroup($group);
	        }

	        ///FALTA ENCRIPTAR Y GUARDAR LOS DATOS EN EL SERVIDOR
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
	        unlink(storage_path() . '/' . 'encryption.key');
	        $dataWithTags = $this->splitAndCreateTags($request->input('tags'),$data);
	        $this->em->persist($dataWithTags);
	        $this->em->flush();

        return redirect('/');
        //return redirect()->back();

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
        if ($data->getUser()->getId()==$loggedUser->getId())
        {

        	$data->setName($request->input('name'));
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
	        if(!$data->getIsFile())
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

	        //return redirect()->back();

	        //return Redirect::to(URL::previous())->withInput()->withErrors($validation);
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
            $this->em->remove($data);
            $this->em->flush();
        }
        //return redirect('/');
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

    protected function sensitiveDataFileSave(Request $request)
    {
    	$loggedUser = Auth::user();
    	$data = new SensitiveData();

    	$file = $request->file('dataFile');
		$fileName= $file->getClientOriginalName();

    	$data->setName($fileName);
    	$data->setUser($loggedUser);
    	$data->setIsFile(true);
    	$groups=$request->input('groups');
    	if($groups == null){
    		$groups = [];
    	}
    	foreach ($groups as $groupId)
    	{
    		$group= $this->em->find("Behigorri\Entities\Group", $groupId);
    		$data->addGroup($group);
    	}

    	///FALTA ENCRIPTAR Y GUARDAR LOS DATOS EN EL SERVIDOR
    	$this->em->persist($data);
    	$this->em->flush();

    	$this->setPaths($data->getId());
    	if (!file_exists($this->path)) {
    		mkdir($this->path, 0777, true);
    	}
		$file->move($this->path, $fileName);
		//var_dump($this->filePath);exit();
    	$outputFile  = fopen($this->filePath , "w") or die("Unable to open file!");


    	$keyFactoryFile  = fopen(storage_path() . '/' . 'encryption.key', "w") or die("Unable to open file!");
    	fwrite($keyFactoryFile  , $loggedUser->getSalt());
    	fclose($keyFactoryFile );

    	$encryptionKey = KeyFactory::loadEncryptionKey(storage_path() . '/' . 'encryption.key');

    	unlink(storage_path() . '/' . 'encryption.key');

    	//$encryptionKey = KeyFactory::deriveEncryptionKey('dad67a87sd78a678a0sd9as0896657645asd', KeyFactory::generateEncryptionKey());
    	File::encrypt($this->path . '/' . $fileName, $outputFile, $encryptionKey);
    	fclose($outputFile);
    	unlink($this->path . '/' . $fileName);

    	$dataWithTags = $this->splitAndCreateTags($request->input('tags'),$data);
    	$this->em->persist($dataWithTags);
    	$this->em->flush();
    	return redirect('/');
    	//return redirect()->back();

    	//File::encrypt($file, $this->path, $loggedUser->getSalt());


    }

    protected function sensitiveDataView($id)
    {
    	$loggedUser = Auth::user();
    	$data = $this->em->find("Behigorri\Entities\SensitiveData",$id);
    	if ($loggedUser->canBeViewSenstiveData($id))
    	{
    		$owner = $data->getUser();
    		$this->setPaths($id);
    		if (file_exists($this->path))
    		{

    			if ($data->getIsFile())
    			{
    				//$sensitiveDataText  = fopen($this->filePath , "r") or die("Unable to open file!");
    			 	//dd("is a file");
    			 	$keyFactoryFile  = fopen(storage_path() . '/' . 'encryption.key', "w") or die("Unable to open file!");
    			 	fwrite($keyFactoryFile  , $owner->getSalt());
    			 	fclose($keyFactoryFile );
    			 	$encryptionKey = KeyFactory::loadEncryptionKey(storage_path() . '/' . 'encryption.key');
    			 	$outputFile  = fopen(storage_path() . '/' . $data->getName(), "w") or die("Unable to open file!");
    			 	File::decrypt($this->path . '/' . $this->fileName, storage_path() . '/' . $data->getName(), $encryptionKey);
    			 	return response()->download(storage_path() . '/' . $data->getName())->deleteFileAfterSend(true);
    			 	fclose($outputFile);
    			 	unlink(storage_path() . '/' . 'encryption.key');
    			 	//return Response::download($file);

    			}else{
    				$sensitiveDataText = file_get_contents($this->filePath);
                	$keyFactoryFile  = fopen(storage_path() . '/' . 'encryption.key', "w") or die("Unable to open file!");
                	fwrite($keyFactoryFile  , $loggedUser->getSalt());
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
    		}

    	}else{
    		return redirect('/');
    	}

    }

    protected function splitAndCreateTags($tagsString, $data)
    {
    	$tags = explode(',', $tagsString);
    	return $this->filterTags($tags,$data);
    }

    protected function filterTags($tags,$data)
    {
    	$tagRep = $this->em->getRepository('Behigorri\Entities\Tag');
    	foreach ($tags as $tag){
    		if(!$this->tagExist($tag, $tagRep)){
    			$newTag = new Tag();
    			$newTag->setName($tag);
    			$this->em->persist($newTag);
    			$this->em->flush();
    			$data->addTag($newTag);
    		}else {
    			$tag = $tagRep->findBy(['name' => $tag]);
    			$data->addTag($tag[0]);
    		}
    	}
    	return $data;
    }

    protected function tagExist($tag, $tagRep)
    {
    	$search = $tagRep->findBy(['name' => $tag]);
    	if(empty($search)){
    		return false;
    	} else {
    		return true;
    	}
    }

    protected function getTagsStrings($tags){
    	$tagsString = "";
    	foreach ($tags as $tag){
    		$tagsString = $tagsString . $tag->getName() . ',';
    	}
    	return $tagsString;
    }

    protected function updateTags($tagsString, $data)
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
      // $result = $this->repository->search($request->input('search'));
      var_dump(get_class($this->repository));exit;
      $datas = $this->paginate($result,15);
      if($loggedUser->getGod())
      {
          return view('god.index')->with([
              'user' => $loggedUser,
              'title' => 'BEHIGORRI PASSWORD MANAGER',
              'datas' => $datas
          ]);
      } else {

        if($loggedUser->getUserActive())
        {

            return view('user.index')->with([
                'user' => $loggedUser,
                'title' => 'WELLCOME SIMPLE USER',
                'datas' => $datas
            ]);
        } else {
          //var_dump($loggedUser->getUserActive());exit;
          Auth::logout();
          $error='not activated';
          //return redirect('/auth/login')->with($error);;
          return view('auth.login')->with(['error' => $error]);
        }
      }
    }



}
