<?php
namespace App\Http\Controllers;

use Behigorri\Entities\User;
use Auth;
use SebastianBergmann\Environment\Console;
use Behigorri\Repositories\UserRepository;
use Doctrine\ORM\EntityManager;
use Illuminate\Support\Facades\DB as DB;
use Illuminate\Pagination\Paginator as Paginator;
use Illuminate\Pagination\LengthAwarePaginator as LengthAwarePaginator;
use Illuminate\Support\Collection as Collection;
class IndexController extends Controller
{
    protected $repository;
    protected $em;
    public function __construct(EntityManager $em)
    {
    	$this->em = $em;
        $this->repository = $em->getRepository('Behigorri\Entities\User');
    }
    public function index()
    {
        $loggedUser = Auth::user();
        $Sensitivedatas = $loggedUser->getUniqueSensitiveData();
        $dataTags = $this->repository->getTags($Sensitivedatas);
        $datas = $this->paginate($Sensitivedatas,15);
        if($loggedUser->getGod())
        {
            return view('god.index')->with([
                'user' => $loggedUser,
                'title' => 'BEHIGORRI PASSWORD MANAGER',
                'datas' => $datas,
            	'tags' => $dataTags
            ]);
        } else {

        	if($loggedUser->getUserActive())
        	{

	            return view('user.index')->with([
	                'user' => $loggedUser,
	                'title' => 'WELLCOME SIMPLE USER',
	                'datas' => $datas,
            		'tags' => $dataTags
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

    public function paginate($items,$perPage)
    {
    	$pageStart = \Request::get('page', 1);
    	// Start displaying items from this number;
    	$offSet = ($pageStart * $perPage) - $perPage;

    	// Get only the items you need using array_slice
    	$itemsForCurrentPage = array_slice($items, $offSet, $perPage, true);

    	return new LengthAwarePaginator($itemsForCurrentPage, count($items), $perPage,Paginator::resolveCurrentPage(), array('path' => Paginator::resolveCurrentPath()));
    }
}
