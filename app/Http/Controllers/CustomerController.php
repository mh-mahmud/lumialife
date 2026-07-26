<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CustomerService;
use Illuminate\Support\Facades\Validator;
use App\Models\Product;
use Auth;

class CustomerController extends Controller
{
    protected $service;
    public function __construct(CustomerService $customer_service) {
        $this->middleware('auth');
        $this->service = $customer_service;
    }

    public function index() {
        $customers = $this->service->get_all_customers();
        return view('customers.index', compact('customers'));
    }

    public function add_customer($id) {

        $data = [];
        $cus_data = $this->service->get_customer_data($id);
        //dd($cus_data);
        if(empty($cus_data->id)) {
            return redirect()->back();
        }

        $rand_str = $this->generateRandomString();

        $data['cus'] = $cus_data;
        $data['products'] = Product::all(['id', 'name']);
        $data['rand_str'] = $rand_str;
        $data['groups'] = config('constants.customer_group');
        return view('customers.add_customer', $data);
    }

    public function save_customer(Request $request) {

        $request->validate([
            'customer_notes' => 'required|string|max:191',
            'product_id' => 'required'

        ]);
        $data = $this->service->createCustomer($request);

        if($data) {
            return redirect()->route('customers')->with('success', 'Customer created successfully.');
        }
        session()->flash('error', 'Can not Add!');
    }

    public function generateRandomString($length = 5) {
        // Define the characters to use in the string
        $characters = '0123456789ABCDEFGHIJKLMN';
        $charactersLength = strlen($characters);
        $randomString = '';

        // Generate a random string of the specified length
        for ($i = 0; $i < $length; $i++) {
            $randomIndex = random_int(0, $charactersLength - 1);
            $randomString .= $characters[$randomIndex];
        }

        // check rand str
        if($this->service->check_rand_string($randomString)) {
            return $this->generateRandomString($length=5);
        }

        return $randomString;
    }
}
