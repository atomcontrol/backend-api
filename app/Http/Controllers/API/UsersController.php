<?php namespace App\Http\Controllers\API;

use App\Models\User;
use App\Http\Controllers\Controller;
use Auth;
use Validator;
use Illuminate\Http\Request;
use Input;
use Log;
use Carbon\Carbon;
use AWS;
class UsersController extends Controller {

	public function __construct() {
       // Apply the jwt.auth middleware to all methods in this controller
       // Except allows for fine grain exclusion if necessary
       $this->middleware('jwt.auth', ['except' => []]);
	}
	public function getMe() {
		 return Auth::user();
	}
	public function updateMe(Request $request)
	{
		$user = Auth::user();
		$data = $request->all();
	}
}