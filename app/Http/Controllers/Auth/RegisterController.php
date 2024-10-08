<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller {
	use RegistersUsers;
	protected $redirectTo = '/user';

	public function __construct() {
		$this->middleware('role:admin,staff,field');
	}

	protected function validator(array $data) {
		return Validator::make($data, [
			'name' => ['required', 'string', 'max:255'],
			'nip' => ['required', 'string', 'max:255'],
			'password' => ['required', 'string', 'min:6', 'confirmed'],
		]);
	}

	protected function create(array $data) {
		return User::create([
			'name' => $data['name'],
			'nip' => $data['nip'],
			'password' => Hash::make($data['password']),
		]);
	}
}
