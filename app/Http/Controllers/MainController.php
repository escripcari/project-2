<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;

class MainController extends Controller
{
    public function index()
    {
        $products = Product::get();
        if (is_null(session('orderId'))) {
            $order = Order::create()->id;
            session(['orderId' => $order]);
        }

        return view('index', ['products' => $products]);
    }

    public function categories()
    {
        $categories = Category::get();

        return view('categories', ['categories' => $categories]);
    }

    public function category($code)
    {
        $category = Category::where('code', $code)->first();

        return view('category', ['category' => $category]);
    }


    public function product($category, $product_id = null)
    {

        $product = Product::find($product_id);

        return view('product', ['product' => $product]);
    }
}
