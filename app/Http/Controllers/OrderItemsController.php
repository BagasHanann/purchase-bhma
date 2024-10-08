<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\OrderItems;
use App\Models\RequestItems;
use App\Models\Suppliers;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;


class OrderItemsController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin,staff');
    }

    public function index()
    {
        $products = Products::orderBy('name', 'ASC')->get()->pluck('name', 'id');
        $supplier = Suppliers::orderBy('name', 'ASC')->get()->pluck('name', 'id');

        // Mengambil semua request items yang memiliki status 'Approved' dan belum memiliki order item terkait
        $approvedRequests = RequestItems::where('status', 'Approved')
            ->whereDoesntHave('orderItem')
            ->get()
            ->pluck('formatted_id', 'id');

        // Mengambil semua produk beserta harganya
        $productsWithPrice = Products::pluck('price', 'id');

        return view('order.index', compact('products', 'supplier', 'productsWithPrice', 'approvedRequests'));
    }

    public function fetchApprovedRequestDetails(Request $request)
    {
        $requestItem = RequestItems::find($request->request_id);

        if (!$requestItem || $requestItem->status !== 'Approved') {
            return response()->json(['error' => 'Invalid request or not approved.'], 404);
        }

        $product = $requestItem->product;
        $stock = $requestItem->stock;
        $price = $product->price;

        return response()->json(compact('product', 'stock', 'price'));
    }

    public function store(Request $request)
    {
        try {
            // Validate the incoming request
            $request->validate([
                'request_id'    => 'required|exists:request_items,id',
                'supplier_id'   => 'required|exists:suppliers,id',
                'date'          => 'required|date',
            ]);

            // Fetch the request item
            $requestItem = RequestItems::find($request->request_id);

            // Check if the request item exists
            if (!$requestItem) {
                return response()->json(['message' => 'Request item not found.'], 404);
            }

            // Fetch the associated product and update its stock
            $product = Products::find($requestItem->product_id);
            if (!$product) {
                return response()->json(['message' => 'Product not found.'], 404);
            }
            $product->stock += $requestItem->stock;
            $product->save();

            // Calculate the total price
            $totalPrice = $requestItem->stock * $requestItem->product->price;

            // Create the order item
            $orderItem = OrderItems::create([
                'request_id'    => $request->request_id,
                'product_id'    => $requestItem->product_id,
                'supplier_id'   => $request->supplier_id,
                'stock'         => $requestItem->stock,
                'price'         => $requestItem->product->price,
                'total_price'   => $totalPrice,
                'date'          => $request->date,
            ]);

            // Return a success response
            return response()->json(['message' => 'Order item created successfully.'], 201);

        } catch (\Exception $e) {
            // Return an error response
            return response()->json(['message' => 'An error occurred. Please try again.', 'error' => $e->getMessage()], 500);
        }
    }

    public function edit($id)
    {
        $order = OrderItems::find($id);
        return $order;
    }

    public function update(Request $request, $id)
    {
        // Validate the request data
        $this->validate($request, [
            'request_id'    => 'required|exists:request_items,id',
            'supplier_id'   => 'required|exists:suppliers,id', // Corrected table name
            'date'          => 'required|date',
        ]);

        // Find the order item
        $orderItem = OrderItems::findOrFail($id);

        // Calculate the new total price
        $total_price = $request->stock * $request->price;

        // Update the order item with the new data
        $orderItem->update([
            'product_id'    => $request->product_id,
            'supplier_id'   => $request->supplier_id,
            'stock'         => $request->stock, // Make sure to update stock
            'price'         => $request->price, // Make sure to update price
            'total_price'   => $total_price,    // Update total price
            'date'          => $request->date,
        ]);

        // Update the product quantity
        $product = Products::findOrFail($request->product_id);
        $product->stock += ($request->stock - $orderItem->stock);
        $product->save();

        return response()->json([
            'success' => true,
            'message' => 'Order Item Updated Successfully'
        ]);
    }


    public function destroy($id)
    {
        OrderItems::destroy($id);

        return response()->json([
            'success'    => true,
            'message'    => 'Products In Deleted'
        ]);
    }

    public function apiProductsIn()
    {
    $orderItems = OrderItems::with('product', 'supplier')->get();

    return Datatables::of($orderItems)
        ->addColumn('product_name', function ($orderItem) {
            return $orderItem->product ? $orderItem->product->name : 'N/A';
        })
        ->addColumn('supplier_name', function ($orderItem) {
            return $orderItem->supplier ? $orderItem->supplier->name : 'N/A';
        })
        ->addColumn('action', function ($orderItem) {
            return view('order.action_buttons', compact('orderItem'))->render();
        })
        ->rawColumns(['action'])
        ->make(true);
    }   
    
    public function exportOrderItemsAll()
    {
        $orderItems = OrderItems::all();
        $pdf = FacadePdf::loadView('order.orderItemsAllPDF', compact('orderItems'));
        return $pdf->download('all_order_items.pdf');
    }

    public function exportPDF($id)
    {
        set_time_limit(300);
        $startTime = microtime(true); // Start time

        $order = OrderItems::findOrFail($id);
        
        $endTime = microtime(true); // End time for database fetch
        error_log('Database fetch time: ' . ($endTime - $startTime) . ' seconds');

        $pdf = FacadePdf::loadView('order.orderItemsPDF', compact('order'));

        $endTime = microtime(true); // End time for PDF generation
        error_log('PDF generation time: ' . ($endTime - $startTime) . ' seconds');

        return $pdf->download($order->id . '_order.pdf');
    }
}
