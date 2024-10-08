<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use Illuminate\Http\Request;
use Yajra\DataTables\Datatables;

class CategoriesController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin,staff');
    }

    public function index()
    {
        return view('categories.index');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|min:2',
        ]);

        Categories::create($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Category created successfully.'
        ]);
    }

    public function edit($id)
    {
        $category = Categories::find($id);

        if (!$category) {
            return response()->json(['error' => 'Category not found.'], 404);
        }

        return $category;
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|min:2',
        ]);

        $category = Categories::findOrFail($id);
        $category->update($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Category updated successfully.'
        ]);
    }

    public function destroy($id)
    {
        Categories::destroy($id);

        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully.'
        ]);
    }

    public function apiCategories()
    {
        $categories = Categories::all();

        return Datatables::of($categories)
            ->addColumn('action', function($category) {
                return '
                    <a onclick="editForm('. $category->id .')" class="btn btn-primary btn-sm">
                        <i class="glyphicon glyphicon-edit"></i> Edit
                    </a>
                    <a onclick="deleteData('. $category->id .')" class="btn btn-danger btn-sm">
                        <i class="glyphicon glyphicon-trash"></i> Delete
                    </a>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
