<?php

namespace App\Http\Controllers\Product;

use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Transaction;
use App\User;
use Illuminate\Support\Facades\DB;

class ProductBuyerTransactionController extends ApiController
{
   
    public function store(Request $request, Product $product, User $buyer)
    {
        $rules =  [
            'quantity' => 'required|integer|min:1'
        ];
        $this->validate($request, $rules);  
        if($buyer->id == $product->seller_id){
            return $this->errorResponse('the buyer must be diffrent form seller', 409);
        }

        if (!$buyer->isVerified()) {
            return $this->errorResponse('the buyer must be verified', 409);
        }
        if (!$product->seller->isVerified()) {
            return $this->errorResponse('the seller must be verified', 409);
        }
        if (!$product->isAvailable()) {
            return $this->errorResponse('the Product is not available', 409);
        }
        if ($product->quantity < $request->quantity) {
            return $this->errorResponse('the Product does not have enough unit for this transaction', 409);
        }
        return DB::transaction(function() use($request, $product, $buyer) {
            $product->quantity -= $request->quantity;
            $product->save();

            $transaction = Transaction::create([
                'quantity' => $request->quantity,
                'buyer_id' => $buyer->id,
                'product_id' => $product->id,
            ]);
            return $this->showOne($transaction, 200);
        });

    }

    
}
