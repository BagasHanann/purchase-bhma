<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Products;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ProductsController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin,staff');
    }

    public function index()
    {
        $categories = Categories::orderBy('name','ASC')
            ->get()
            ->pluck('name','id');

        $producs = Products::all();
        return view('products.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $categories = Categories::orderBy('name','ASC')
            ->get()
            ->pluck('name','id');

        $this->validate($request , [
            'name'          => 'required|string',
            'description'   => 'required',
            'price'         => 'required',
            'stock'         => 'required',
            'categories_id'   => 'required',
        ]);

        $input = $request->all();
        Products::create($input);

        return response()->json([
            'success' => true,
            'message' => 'Products Created'
        ]);
    }

    public function edit($id)
    {
        $categories = Categories::orderBy('name','ASC')
            ->get()
            ->pluck('name','id');
        $product = Products::find($id);
        return $product;
    }

    public function update(Request $request, $id)
    {
        $categories = Categories::orderBy('name','ASC')
            ->get()
            ->pluck('name','id');

        $this->validate($request , [
            'name'              => 'required|string',
            'description'       => 'required',
            'price'             => 'required',
            'stock'             => 'required',
            'categories_id'     => 'required',
        ]);

        $input = $request->all();
        $product = Products::findOrFail($id);
        $product->update($input);

        return response()->json([
            'success' => true,
            'message' => 'Products Update'
        ]);
    }

    public function destroy($id)
    {
        Products::findOrFail($id);
        Products::destroy($id);

        return response()->json([
            'success' => true,
            'message' => 'Products Deleted'
        ]);
    }

    public function apiProducts()
    {
        $product = Products::all();

        return Datatables::of($product)
            ->addColumn('categories_name', function ($product){
                return $product->categories->name;
            })
            ->addColumn('action', function($product){
                return'
                <a onclick="editForm('. $product->id .')" class="btn btn-primary btn-sm">
                    <i class="glyphicon glyphicon-edit"></i> Edit
                </a> ' .
                '<a onclick="deleteData('. $product->id .')" class="btn btn-danger btn-sm">
                    <i class="glyphicon glyphicon-trash"></i> Hapus
                </a>';
            })
            ->rawColumns(['categories_name','action'])->make(true);
    }
}
