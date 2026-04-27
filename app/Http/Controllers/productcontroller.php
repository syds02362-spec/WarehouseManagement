<?php

namespace App\Http\Controllers;

use App\Http\Requests\storeproductrequest;
use App\Http\Requests\updetaproductRequrst;
use App\Models\Product;
use Illuminate\Http\Request;

class productcontroller extends Controller
{
      public function index()
    {
         $produc= Product::all();
         return Response()->json($produc);
     }
   public function store(storeproductrequest $request)
    {
   $product= Product::create( $request->validate() );
         return Response()->json($product);
    
    }
    public function update(updetaproductRequrst $request ,$id)
    {
     $product=Product::find($id);
       $product->update($request->validated());
     return Response()->json($product);
    }

    public function show(Request $request ,$id)
    {
    $product=Product::find($id);
    return Response()->json($product);
      }
      public function destroy($id)
      {
         $product=Product::find($id);
          $product->delete();
          return Response()->json(null,204);

     }
  
}
