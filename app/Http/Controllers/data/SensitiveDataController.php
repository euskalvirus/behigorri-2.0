<?php
namespace App\Http\Controllers\data;

use Behigorri\Entities\SensitiveData;
use Behigorri\Entities\User;
use Behigorri\Entities\Group;
use Doctrine\ORM\EntityManager;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;


class SensitiveDataController extends Controller
{
    protected $em;
    protected $path;
    protected $filePath;
    
    public function __construct(EntityManager $EntityManager, Request $request)
    {
        $this->em = $EntityManager; 
    }
    
    public function compare_objects($a, $b)
    {
        return strcmp(spl_object_hash($a), spl_object_hash($b));
    }
    
    public function sensitiveDataEdit($id)
    {
        $loggedUser = Auth::user();
        if ($loggedUser->canBeEditSenstiveData($id))
        {
            $data = $this->em->find("Behigorri\Entities\SensitiveData",$id);
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
            }
            //var_dump($this->path);exit;
            //var_dump($sensitiveDataText);exit;
           return view('data.userDataEdit')->with([
               'user' => $loggedUser,
               'title' => 'WELLCOME SIMPLE USER',
               'data' => $data,
               'groups' => $filteredGroups,
               'text'=> $sensitiveDataText
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
        $data->setName($request->input('name'));
        $data->setUser($loggedUser);
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
        fwrite($sensitiveDataText , $request->input('text'));
        fclose($sensitiveDataText );
        
        return redirect('/');
    
    }
    
    private function setPaths($id){
        $this->path = storage_path() . '/' . $this->idToPath($id);
        $this->filePath = $this->path . '/' . substr($id,-1);
    }
    
    private function idToPath($id) {
        if ($id < 10) {
            return "0/" . $id;
        }
        $idArray = str_split((string)$id);
        array_pop($idArray);
        return implode('/', $idArray);
    }
    
    protected function sensitiveDataUpdate(Request $request)
    {
        $data = $this->em->find("Behigorri\Entities\SensitiveData",$request->input('id'));
        $data->setName($request->input('name'));
        ///FALTA ENCRIPTAR Y GUARDAR LOS DATOS EN EL SERVIDOR
        $newGroups=$request->input('groups', []);
        $groups;
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
        $this->em->persist($data);
        $this->em->flush();
        $this->setPaths($request->input('id'));
        if (!file_exists($this->path)) {
            mkdir($this->path, 0777, true);
        }
        $sensitiveDataText  = fopen($this->filePath, "w") or die("Unable to open file!");
        fwrite($sensitiveDataText , $request->input('text'));
        fclose($sensitiveDataText );
        
        return redirect('/');

    }
    
    protected function sensitiveDataDelete($id)
    {
        $loggedUser = Auth::user();
        if ($loggedUser->canBeEditSenstiveData($id))
        {
            $data = $this->em->find("Behigorri\Entities\SensitiveData",$id);
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
        return redirect('/');
        
    }
    

}