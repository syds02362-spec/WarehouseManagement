<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ProductWebController extends Controller
{
    protected function productRules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'required|string',
            'quantity' => 'required|integer|min:0',
            'min_quantity' => 'required|integer|min:0',
        ];
    }

    public function index()
    {
        $products = Product::all();

        $expired = $products->where('quantity', '<=', 0)->count();
        $low_stock = $products->filter(fn($product) => $product->quantity > 0 && $product->quantity <= $product->min_quantity)->count();
        $total_items = $products->count();
        $total_quantity = $products->sum('quantity');
        $alerts = $products->filter(fn($product) => $product->quantity > 0 && $product->quantity <= $product->min_quantity);

        $recent_activities = StockMovement::with('product')->latest()->take(5)->get()->map(fn($movement) => [
            'name' => $movement->product->name,
            'quantity' => $movement->quantity,
            'type' => $movement->type === 'in' ? 'إدخال' : 'إخراج',
            'time' => $movement->created_at->diffForHumans(),
        ]);

      return view('home', compact(
    'products',
    'expired',
    'low_stock',
    'total_items',
    'total_quantity',
    'alerts',
    'recent_activities'
));
    }

    public function create()
    {
        return view('product_form', ['product' => null]);
    }

    public function store(Request $request)
    {
        Product::create($request->validate($this->productRules()));

        return redirect()->route('products.index')->with('success', 'تم إضافة الصنف بنجاح');
    }

    public function edit(Product $product)
    {
        return view('product_form', [('product')]);
    }

    public function update(Request $request, Product $product)
    {
        $product->update($request->validate($this->productRules()));

        return redirect()->route('products.index')->with('success', 'تم تحديث الصنف بنجاح');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')->with('success', 'تم حذف الصنف بنجاح');
    }

    public function stockIn(Request $request, Product $product)
    {
        $quantity = $request->validate(['quantity' => 'required|integer|min:1'])['quantity'];
        $product->increment('quantity', $quantity);

        StockMovement::create([
            'product_id' => $product->id,
            'type' => 'in',
            'quantity' => $quantity,
        ]);

        return redirect()->route('products.index')->with('success', 'تم إدخال المخزون بنجاح');
    }

    public function stockOut(Request $request, Product $product)
    {
        $quantity = $request->validate(['quantity' => 'required|integer|min:1'])['quantity'];

        if ($product->quantity < $quantity) {
            return redirect()->route('products.index')->with('error', 'الكمية غير كافية');
        }

        $product->decrement('quantity', $quantity);

        StockMovement::create([
            'product_id' => $product->id,
            'type' => 'out',
            'quantity' => $quantity,
        ]);

        return redirect()->route('products.index')->with('success', 'تم إخراج المخزون بنجاح');
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
            $csv .= implode(',', [
                str_replace(',', ' ', $product->name),
                $product->price,
                str_replace(',', ' ', $product->description),
                $product->quantity,
                $product->min_quantity,
            ]) . "\n";
        }

        return Response::make($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="products.csv"',
        ]);
    }
}