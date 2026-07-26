<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\User;

use App\Models\Agent;
use App\Models\Product;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller {

	public function __construct() {
        // $this->middleware(['auth']);
    }

	public function dashboard()
    {

        $data = [];
        $user_id = Auth::user()->id;
        if(Auth()->user()->user_type=='admin') {
           $data['lead_list'] = []; 
        }
        else {
            $data['lead_list'] = [];
        }
        $data['camp_list'] = [];
        $data['agent_list'] = Agent::with('user')->where('status', 1)->orderBy('agent_id', 'desc')->limit(5)->get();
        $data['todo_list'] = [];
        $data['formName'] = [];

        $data['count_lead'] = 0;
        $data['active_agents'] = Agent::where('status', 1)->count();
        $data['active_products'] = Product::where('status', 1)->count();
        $data['const_task'] = config('constants.TASK_STATUS');
        // dd($data);
        return view('dashboard', $data);
    }

	function profile() {
		return view('single_form');
	}

}

