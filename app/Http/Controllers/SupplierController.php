<?php

namespace App\Http\Controllers;

use App\Models\Suppliers;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class SupplierController extends Controller {
	public function __construct() {
		$this->middleware('role:admin,staff');
	}

	public function index() {
		$suppliers = Suppliers::all();
		return view('suppliers.index');
	}

	public function store(Request $request) {
		$this->validate($request, [
			'name' => 'required',
			'address' => 'required',
			'email' => 'required|unique:suppliers',
			'phone' => 'required',
		]);

		Suppliers::create($request->all());

		return response()->json([
			'success' => true,
			'message' => 'Suppliers Created',
		]);

	}

	public function edit($id) {
		$supplier = Suppliers::find($id);
		return $supplier;
	}

	public function update(Request $request, $id) 
	{
		$this->validate($request, [
			'name' 		=> 'required|string',
			'address' => 'required|string',
			'email' 	=> 'required|email',
			'phone' 	=> 'required|string',
		]);

		$supplier = Suppliers::findOrFail($id);

		$supplier->update($request->all());

		return response()->json([
			'success' => true,
			'message' => 'Supplier Updated',
		]);
	}

	public function destroy($id) {
		Suppliers::destroy($id);

		return response()->json([
			'success' => true,
			'message' => 'Supplier Delete',
		]);
	}

	public function apiSuppliers() {
		$suppliers = Suppliers::all();

		return Datatables::of($suppliers)
			->addColumn('action', function ($suppliers) {
				return '
				<a onclick="editForm(' . $suppliers->id . ')" class="btn btn-primary btn-sm">
					<i class="glyphicon glyphicon-edit"></i> Edit
				</a> ' .
				'<a onclick="deleteData(' . $suppliers->id . ')" class="btn btn-danger btn-sm">
					<i class="glyphicon glyphicon-trash"></i> Hapus
				</a>';
			})
			->rawColumns(['action'])->make(true);
	}
}
