<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class updetaproductRequrst extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
             'name'=>'sometimes|string|max:50',
            'price'=>'sometimes|integer|max:10',
            'description'=>'nullable|string|max:150',
            'quantity'=>'sometimes|integer|max:1000'
        ];
    }
}
//     public function index()
//     {
//         $task= Product::all();
//         return Response()->json($task);
//     }
//   public function store(storeproductrequest $request)
//    {
//   $product= Product::create( $request->validate() );
//         return Response()->json($product);
    
//    }
//    public function updeta(updetaproductRequrst $request ,$id)
//    {
//     $product=Product::find($id);
//       $product->update($request->validated());
//     return Response()->json($product);
//    }

//    public function show(Request $request ,$id)
//    {
//    $product=Product::find($id);
//     return Response()->json($product);
//      }
//      public function destroy($id)
//      {
//         $product=Product::find($id);
//          $product->delete();
//          return Response()->json(null,204);

//      }