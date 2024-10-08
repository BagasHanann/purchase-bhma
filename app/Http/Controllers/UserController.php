<?php

namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class UserController extends Controller {
	public function __construct() {
		$this->middleware('role:admin');
	}

	public function index() {
		return view('user.index');
	}

	public function store(Request $request) {
    $this->validate($request, [
        'name'      => 'required|string|min:2|max:255',
        'nip'       => 'required|string|max:255',
        'password'  => 'required|string|min:6|confirmed',
        'role'      => 'required|string|in:admin,field,staff',
    ]);

    $user = new User();
    $user->name = $request->name;
    $user->nip = $request->nip;
    $user->password = bcrypt($request->password);
    $user->role = $request->role;
    $user->save();

    return redirect()->route('user.index')->with('success', 'User created successfully.');
	}

	public function edit($id)
	{
    $user = User::find($id);
    return response()->json($user);
	}

	public function update(Request $request, $id) 
    {
        $this->validate($request, [
            'name' => 'string|min:2',
            'nip' => 'string|max:255',
            'role' => 'in:admin,field,staff',
        ]);

        $user = User::findOrFail($id);
        $user->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'User Updated',
        ]);
	}

	public function destroy($id)
	{
        $user = User::find($id);
        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
	}

	public function apiUsers() {
		$users = User::all();

		return Datatables::of($users)
				->addColumn('action', function ($user) {
						return '<a onclick="editForm(' . $user->id . ')" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-edit"></i> Edit</a> ' .
						'<a onclick="deleteData(' . $user->id . ')" class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
				})
				->rawColumns(['action'])->make(true);
	}
}
