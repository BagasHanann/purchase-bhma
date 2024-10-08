<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Products;
use App\Models\RequestItems;
use Yajra\DataTables\DataTables;
use App\Models\User;

class RequestItemsController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin,staff,field');
    }

    public function index()
    {
        $product = Products::orderBy('name','ASC')
            ->get()
            ->pluck('name','id');

        $requests = RequestItems::all();
        $pendingRequestsCount = RequestItems::where('status', 'pending')->count();

        $requests->each(function($request) {
            $request->formatted_id = $request->formatted_id;
        });

        return view('requestItems.index', compact('product', 'requests', 'pendingRequestsCount'));
    }

    public function edit($id)
    {
        $category = RequestItems::find($id);
        return $category;
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'stock' => 'required|int',
        ]);

        RequestItems::create([
            'user_id'       => auth()->id(),
            'product_id'    => $request->product_id,
            'stock'         => $request->stock,
            'status'        => 'pending',
        ]);

        return redirect()->route('requestItems.index')->with('success', 'Request created successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'user_id'       => 'exists:id',
            'product_id'    => 'exists:products,id',
            'stock'         => 'required|int',
            'status'        => 'in:Pending,Approved,Rejected',
        ]);

        $staffRequest = RequestItems::findOrFail($id);

        $staffRequest->update(['stock' => $request->stock]);

        return redirect()->route('requestItems.index')->with('success', 'Request updated successfully.');
    }

    public function destroy($id)
    {
        RequestItems::destroy($id);

        return redirect()->route('requestItems.index')->with('success', 'Request deleted successfully.');
    }

    public function apiRequest()
    {
        $products = RequestItems::all();
        $user =  User::find(auth()->id());

        return DataTables::of($products)
            ->addColumn('formatted_id', function ($requestItem) {
                return $requestItem->formatted_id;
            })
            ->addColumn('user_name', function ($requestItem) {
                return $requestItem->user ? $requestItem->user->name : 'N/A';
            })
            ->addColumn('product_name', function ($requestItem) {
                return $requestItem->product ? $requestItem->product->name : 'N/A';
            })
            ->addColumn('action', function ($requestItem) use ($user) {
                $actions = '';

                if ($user->hasRole(['admin']) && $requestItem->status === 'Pending') {
                    $actions .= '<button onclick="updateStatus(' . $requestItem->id . ', \'approved\')" style="font-size: 14px;" class="btn btn-success btn-sm">Approve</button>';
                    $actions .= ' <button onclick="updateStatus(' . $requestItem->id . ', \'rejected\')" style="font-size: 14px;" class="btn btn-danger btn-sm">Reject</button>';
                }

                if ($user->hasRole(['staff', 'field'])) {
                    $actions .= '<a onclick="editForm('. $requestItem->id .')" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-pencil"></i> Edit</a>';
                    $actions .= ' <a onclick="deleteData('. $requestItem->id .')" class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
                }
                
                return $actions;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    public function updateStatus(Request $request, $id)
    {
        $requestItem = RequestItems::findOrFail($id);
        $requestItem->status = $request->status;
        $requestItem->save();
    
        // Hitung kembali jumlah permintaan pending setelah status diperbarui
        $pendingRequestsCount = RequestItems::where('status', 'pending')->count();
    
        return response()->json(['message' => 'Status updated successfully', 'pendingRequestsCount' => $pendingRequestsCount]);
    }
}
