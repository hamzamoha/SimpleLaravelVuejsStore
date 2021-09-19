<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only('store', 'destroy', 'update');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Product::paginate(4);
    }
    public function indexLatest($num = 5)
    {
        return Product::orderBy('updated_at', 'desc')->take($num)->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'required',
            'price' => 'required|numeric',
        ]);
        $request->name = Str::title($request->name);
        $request->name = Str::of($request->name)->trim();
        if ($request->hasFile('image')) {
            $request->validate(['image' => 'nullable|image|max:5120']);
            $ext = $request->file('image')->getClientOriginalExtension();
            $file_name_no_ext = Str::of($request->name)->slug('_');
            $file_name_no_ext = Str::of($file_name_no_ext)->trim('_');
            $file_name = $file_name_no_ext . '.' . $ext;
            if (Storage::exists('products/' . $file_name)) {
                $number = 0;
                do {
                    $file_name = $file_name_no_ext . '_' . ++$number . '.' . $ext;
                } while (Storage::exists('products/' . $file_name));
            }
            if (!$request->file('image')->storeAs('/products', $file_name))
                $file_name = 'no_image.png';
        } else $file_name = "no_image.png";
        if ($product = Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'image' => $file_name,
        ])) {
            return Response([
                'product' => $product,
                'status' => 200
            ]);
        } else {
            return "problem";
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if($id == 'random') {
            $product= Product::inRandomOrder()->first();
            $product->description = Str::words($product->description, 15);
        }
        else $product = Product::findOrFail($id);
        $product->created_at_str = $product->created_at->diffForHumans();
        $product->updated_at_str = $product->updated_at->diffForHumans();
        $product->description = nl2br($product->description);
        return $product;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //return $request;
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'required',
            'price' => 'required|numeric',
        ]);
        $request->description = Str::of($request->description)->trim();
        $request->name = Str::of($request->name)->trim();
        $request->name = Str::title($request->name);
        $product = [
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'image' => Product::find($id)->image,
        ];
        if ($request->image == "null") {
            Storage::delete('products/' . $product['image']);
            $product['image'] = 'no_image.png';
        }

        if ($request->hasFile('imageToUpload')) {
            $request->validate(['imageToUpload' => 'nullable|image|max:5120']);
            $ext = $request->file('imageToUpload')->getClientOriginalExtension();
            $file_name_no_ext = Str::of($request->name)->slug('_');
            $file_name_no_ext = Str::of($file_name_no_ext)->trim('_');
            $file_name = $file_name_no_ext . '.' . $ext;
            if (Storage::exists('products/' . $file_name)) {
                $number = 0;
                do {
                    $file_name = $file_name_no_ext . '_' . ++$number . '.' . $ext;
                } while (Storage::exists('products/' . $file_name));
            }

            if ($request->file('imageToUpload')->storeAs('/products', $file_name)) {
                if ($product['image'] != 'no_image.png') {
                    Storage::delete('products/' . $product['image']);
                }
                $product['image'] = $file_name;
            }
        }

        return Product::findOrFail($id)->update($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::find($id);
        if ($product->image != 'no_image.png')
            if (Product::destroy($id)) Storage::delete('products/' . $product->image);
        return $product;
        return "Oops!";
    }
    /**
     * Search in the resource.
     *
     * @param  int  $query
     * @return \Illuminate\Http\Response
     */
    public function search($query)
    {
        return Product::where($query, 'like', "%$query%")->get();
    }
}
