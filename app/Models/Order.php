<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public function products(){
        return $this->belongsToMany(Product::class)->withPivot('count')->withTimestamps();
    }

    public function getFullPrice(){

        $price = 0;

        foreach ($this->products as $product){
            $price += $product->getPriceForCount();
        }
        return $price;
    }

    public function getCountProduct(){
        $count = 0;

        foreach($this->products as $product){
            $count += $product->pivot->count;
        }
        return $count;
    }

}
