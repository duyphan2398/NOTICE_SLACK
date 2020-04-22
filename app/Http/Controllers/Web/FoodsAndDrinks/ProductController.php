<?php


namespace App\Http\Controllers\Web\FoodsAndDrinks;

use App\Http\Controllers\WebBaseController;
use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateIngredientRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Material;
use App\Models\Product;
use App\Transformers\ProductTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;


class ProductController extends WebBaseController
{

    public function index(Request $request)
    {
        if ($request->ajax){
            $drinks_product = Product::where('type', 'Drink')
                ->orderBy('created_at', 'desc')
                ->get();
            $drinks_array = [];
            foreach ($drinks_product as $drink_product) {
                 array_push($drinks_array, (new ProductTransformer)->transform($drink_product));
            }
            $foods_product = Product::where('type','Food')
                ->orderBy('created_at', 'desc')
                ->get();
            $foods_array = [];
            foreach ($foods_product as $food_product) {
                 array_push($foods_array, (new ProductTransformer)->transform($food_product));
            }
            return response()->json([
                'drinks' =>  $drinks_array,
                'foods' =>   $foods_array,
            ], 200);
        }
        return view('workspace.foodsAndDrinks.product');
    }

    public function show(Product $product){
        $ingredient_orther = Material::whereDoesntHave('products',function($query) use ($product) {
            $query->where('product_id', $product->id);
        })->get();
        return response()->json([
            'product' =>  (new ProductTransformer)->transform($product),
            'ingredient_orther' => $ingredient_orther
        ], 200);
    }

    public function update(UpdateProductRequest $request, Product $product){
        DB::beginTransaction();
        try {
            if ($request->url){
                if ($product->url != 'default_url_product.png' ){
                    File::delete(public_path('images\products\\' .$product->url));
                };
                $destinationPath = 'images/products/';
                $profileImage = $product->id. "." . $request->url->getClientOriginalExtension();
                $request->url->move($destinationPath, $profileImage);
                $product->url = $profileImage;
            }
            $product->update($request->except(['url','sale_price']));
            DB::commit();
        }
        catch (\Exception $exception){
            DB::rollBack();
            return response()->json([
                'status' => 'fails'
            ],422);
        }
    }

    public function destroy(Product $product){
        $product->materials()->detach();
        if ($product->url != 'default_url_product.png' ){
            File::delete(public_path('images\products\\' .$product->url));
        };
        $product->delete();
        return response()->json([
            'status' =>  'success',
        ], 200);
    }

    public function updateIngredient(UpdateIngredientRequest $request, Product $product){
        $product->materials()->attach($request->material_id, [
            'quantity'  => $request->quantity,
            'unit'      => $request->unit
        ]);
        $ingredient_orther = Material::whereDoesntHave('products',function($query) use ($product) {
            $query->where('product_id', $product->id);
        })->get();
        return response()->json([
            'product'   =>(new ProductTransformer)->transform($product),
            'status'    => 'success',
            'ingredient_orther' => $ingredient_orther
        ],200);
    }

    public function deleteIngredient(Product $product, Material $material){
        $product->materials()->detach($material->id);
        $ingredient_orther = Material::whereDoesntHave('products',function($query) use ($product) {
            $query->where('product_id', $product->id);
        })->get();
        return response()->json([
            'product'   =>(new ProductTransformer)->transform($product),
            'status'    => 'success',
            'ingredient_orther' => $ingredient_orther
        ],200);
    }

    public function store(CreateProductRequest $request){
        DB::beginTransaction();
        try {
            $product = new  Product();
            $product->fill( $request->only(['name', 'price', 'type']));
            $product = Product::query()->create($request->except('url'));
            if ($request->url){
                $destinationPath = 'images/products/';
                $profileImage = $product->id. "." . $request->url->getClientOriginalExtension();
                $request->url->move($destinationPath, $profileImage);
                $product->url = $profileImage;
            }
            $product->save();
            return response()->json([
                'status' => 'success'
            ],201);
            DB::commit();
        }
        catch (\Exception $exception){
            DB::rollBack();
            return response()->json([
                'status' => 'fails'
            ],422);
        }


    }
}