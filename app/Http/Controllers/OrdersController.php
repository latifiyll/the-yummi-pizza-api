<?php

namespace App\Http\Controllers;

use App\Guest;
use App\Http\Resources\Order as ResourcesOrder;
use App\Menu;
use App\Order;
use App\OrderItems;
use Illuminate\Http\Request;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Support\Facades\Auth;

class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::all();

        return ResourcesOrder::collection($orders);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $config = [
            'table' => 'orders',
            'field' => 'order_no',
            'length' => 9,
            'prefix' => 'ORD-'
        ];
        $order_number = IdGenerator::generate($config);
        if(Auth::check()){
            $user = Auth::user()->id;
        }else {
            $guest = Guest::create([
                'full_name' => $request->full_name,
                'address' => $request->address,
                'phone' => $request->phone,
            ]);
        }

        $order = Order::create([
            'order_no' => $order_number,
            'user_id' => $user->id ?? null,
            'guest_id' => $guest->id ?? null,
            'currency' => $request->currency,
            'delivery_fee'=> 2,
            'amount' => $request->amount + $request->delivery_fee,
            'status' => "pending",
            'delivery_time' => $request->delivery_time,
        ]);
        foreach ($request->items as $item) {
            $product = Menu::find($item['menu_id']);
            $order_item = OrderItems::create([
                'order_id' => $order->id,
                'menu_id' => $product->id,
                'comment' => $item["comment"],
                'quantity' => $item["quantity"],
                'price' => $product->price,
            ]);
        }
        
        return new ResourcesOrder($order);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = Order::find($id);

        return new ResourcesOrder($order);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $order = Order::find($id);
        $order->delete();

        return new ResourcesOrder($order);
    }
}
