<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class ProductController extends Controller
{

    public function index()
    {
        try {

            $product = Product::all();

            if($product->count() == 0)
                return response()->json([
                    "data" => null,
                    "message" => "No hay productos en la base de datos"
                ], 404);

            return response()->json([
                "data" => $product,
                "mesagge" => "Productos encontrados correctamente"
            ], 200);

        } catch (\Exception $e){

            throw new \Exception($e);

        }

    }

    public function store(ProductStoreRequest $request)
    {

        try {

            DB::beginTransaction();

            $url = "";
            $image = $request->image_url;

            if($image){
                $image = str_replace('data:image/png;base64,', '', $image);
                $image = str_replace(' ', '+', $image);
                $imageName = Str::uuid().'.'.'png';
                \File::put(public_path(). '/public/productImages/' . $imageName, base64_decode($image));
                $url = URL::to('/') . '/public/productImages/' . $imageName;
            }

            $data = [
                "name" => $request['name'],
                "description" => $request['description'],
                "product_category_id" => $request['product_category_id'],
                "price" => $request['price'],
                "image_url" => $url,
                "estimated_time" => $request['estimated_time'],
                "score" => $request['score'],
                "status" => $request['status'],
            ];

            $product = Product::create($data);

            if(!$product)
                return response()->json([
                    "data" => null,
                    "message" => "No se pude crear el producto"
                ], 400);

            DB::commit();

            return response()->json([
                "data" => $data,
                "message" => "Producto creado correctamente"
            ], 201);

        } catch (Exception $e){

            DB::rollBack();

            throw new \Exception($e);

        }

    }

    public function show($id)
    {

        try {

            $product = Product::findOrFail($id);

            if(!$product)
                return response()->json([
                    "data" => null,
                    "message" => `El producto con el id: ${$id} no pudo ser encontrado`
                ], 404);

            return response()->json([
                "data" => $product,
                "mesagge" => "Producto encontrado correctamente"
            ], 200);

        } catch (\Exception $e){

            throw new \Exception($e);

        }

    }

//    TODO: ARREGLAR LO DE ACTUALIZAR LOS PRODUCTOS //
    public function update($id, ProductUpdateRequest $request)
    {

        try {

            DB::beginTransaction();

            $url = "";
            $image = $request->image_url;

            if($image){
                $image = str_replace('data:image/png;base64,', '', $image);
                $image = str_replace(' ', '+', $image);
                $imageName = Str::uuid().'.'.'png';
                \File::put(public_path(). '/public/productImages/' . $imageName, base64_decode($image));
                $url = URL::to('/') . '/public/productImages/' . $imageName;
            }

            $data = [
                "name" => $request['name'],
                "description" => $request['description'],
                "product_category_id" => $request['product_category_id'],
                "price" => $request['price'],
                "image_url" => $url,
                "estimated_time" => $request['estimated_time'],
                "score" => $request['score'],
                "status" => $request['status'],
            ];

            $product = Product::where('id', '=', $id)->update($data);

            if(!$product)
                return response()->json([
                    "data" => null,
                    "message" => "No se pude actualizar el producto"
                ], 400);

            DB::commit();

            return response()->json([
                "data" => $data,
                "message" => "Producto actualizado correctamente"
            ], 200);

        } catch (Exception $e){

            DB::rollBack();

            throw new \Exception($e);

        }

    }

    public function destroy($id)
    {

        try {

            $product = Product::findOrFail($id);

            $product->delete();

            return response()->json([
                "data" => $product,
                "message" => 'El producto fue eliminado correctamente'
            ], 200);

        } catch (\Exception $e){

            throw new \Exception($e);

        }

    }

}
