<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ProductWebController extends Controller
{
    public function index()
    {
        $products = Product::all();

        // إحصائيات
        $expired = $products->where('quantity', '<=', 0)->count();
        $low_stock = $products->filter(function($product) {
            return $product->quantity > 0 && $product->quantity <= $product->min_quantity;
        })->count();
        $total_items = $products->count();
        $total_quantity = $products->sum('quantity');

        // آخر الحركات
        $recent_activities = StockMovement::with('product')->latest()->take(5)->get()->map(function($movement) {
            return [
                'name' => $movement->product->name,
                'quantity' => $movement->quantity,
                'type' => $movement->type == 'in' ? 'إدخال' : 'إخراج',
                'time' => $movement->created_at->diffForHumans(),
            ];
        });

        return view('home', compact('products', 'expired', 'low_stock', 'total_items', 'total_quantity', 'recent_activities'));
    }

    public function create()
    {
        return view('product_form', ['product' => null]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'description' => 'required',
            'quantity' => 'required|integer',
            'min_quantity' => 'required|integer',
        ]);

        Product::create($request->all());
        return redirect()->route('products.index')->with('success', 'تم إضافة الصنف بنجاح');
    }

    public function edit(Product $product)
    {
        return view('product_form', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'description' => 'required',
            'quantity' => 'required|integer',
            'min_quantity' => 'required|integer',
        ]);

        $product->update($request->all());
        return redirect()->route('products.index')->with('success', 'تم تحديث الصنف بنجاح');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'تم حذف الصنف بنجاح');
    }

    public function stockIn(Request $request, Product $product)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);
        $product->increment('quantity', $request->quantity);

        // تسجيل الحركة
        StockMovement::create([
            'product_id' => $product->id,
            'type' => 'in',
            'quantity' => $request->quantity,
        ]);

        return redirect()->route('products.index')->with('success', 'تم إدخال المخزون بنجاح');
    }

    public function stockOut(Request $request, Product $product)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);
        if ($product->quantity >= $request->quantity) {
            $product->decrement('quantity', $request->quantity);

            // تسجيل الحركة
            StockMovement::create([
                'product_id' => $product->id,
                'type' => 'out',
                'quantity' => $request->quantity,
            ]);

            return redirect()->route('products.index')->with('success', 'تم إخراج المخزون بنجاح');
        }
        return redirect()->route('products.index')->with('error', 'الكمية غير كافية');
    }

    public function report()
    {
        $products = Product::all();
        return view('report', compact('products'));
    }

    public function export()
    {
        $products = Product::all();
        $csv = "Name,Price,Description,Quantity,Min Quantity\n";
        foreach ($products as $product) {
            $csv .= "{$product->name},{$product->price},{$product->description},{$product->quantity},{$product->min_quantity}\n";
        }
        return Response::make($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="products.csv"',
        ]);
    }
}