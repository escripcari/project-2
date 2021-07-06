<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;

class BasketController extends Controller
{

    public function basket(){
        /**
         * При входе в корзину
         * передается закак для отоброжения
         * если его нет, создоем заказ
         */
        $orderId = session('orderId');

        if(is_null($orderId)){
            $order = Order::findOrFail($orderId);
        }

        $order = Order::find($orderId);

        return view('basket', ['order' => $order]);
    }


    public function basketConfirm(Request $request){

        $orderId = session('orderId');

        if(is_null($orderId)){
            return redirect()->route('index');
        }

        $order = Order::find($orderId);

        if($order->status == 0) {
            $order->name = $request->name;
            $order->phone = $request->phone;
            $order->status = 1;
            $order->save();
            session()->forget('orderId');
            session()->flash('success', "Ваш заказ принят в обработку!");
        }else{
            session()->flash('warning', "Заказ не удалось оформить!!!");
        }

        return redirect(route('index'));
    }


    public function basketPlace(){
        $orderId = session('orderId');
        if(is_null($orderId)){
            return redirect()->route('index');
        }
        $order = Order::find($orderId);

        return view('order', ['order' => $order]);
    }


    public function basketAdd($productId){
        /**
         * Получаем из сессии Id заказа
         */
       $orderId = session('orderId');

        /**
         * Если его нет то создоем новый
         * Если есть то получаем из таблице
         */
       if(is_null($orderId)){
           $order = Order::create()->id;
           session(['orderId' => $order]);
       }else{
           $order = Order::find($orderId);
       }

        $count = $order->getCountProduct();
        session(['basketCount' => $count]);

      if($order->products->contains($productId)){
          $pivotRow = $order->products()->where('product_id', $productId)->first()->pivot;
          $pivotRow->count++;
          $pivotRow->update();

      }else{
          $order->products()->attach($productId);
      }

      if (Auth::check()){
          $order->user_id = Auth::id();
          $order->save();
      }

        $count++;
        session(['basketCount' => $count]);

        $product = Product::find($productId);
        session()->flash('success', "Добавлен товар: ".$product->name);


      return redirect()->route('basket');
    }


    public function basketRemove($productId){

        $orderId = session('orderId');
        if(is_null($orderId)){

            return redirect()->route('basket');
        }else{
            $order = Order::find($orderId);
            $count = $order->getCountProduct();
        }

        if($order->products->contains($productId)){

            $pivotRow = $order->products()->where('product_id', $productId)->first()->pivot;

            if($pivotRow->count<2){
                $order->products()->detach($productId);
            }else{
                $pivotRow->count--;
                $pivotRow->update();

            }
        }
        $count--;
        session(['basketCount' => $count]);

        $product = Product::find($productId);
        session()->flash('warning', "Удален товар: ".$product->name);

        return redirect()->route('basket');
    }
}
