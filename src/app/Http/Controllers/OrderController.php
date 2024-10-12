<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Merchandise;
use App\Models\Topping;
use App\Models\MerchandiseToOrder;
use App\Models\ToppingToOrder;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {  
        // Orderデータにupdated_atをフォーマットして追加
        $orders = Order::all()->map(function ($order) {
            // updated_at を日付のみにフォーマット
            $order->formatted_date = $order->updated_at->format('Y-m-d');
            return $order;
        });

        // 加工したデータを返す
        return response()->json($orders);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // バリデーション
        $validatedData = $request->validate([
            'customer' => 'required|string|max:30',
            'total_amount' => 'required|integer',
            'deposit_amount' => 'required|integer',
            'change' => 'required|integer',
            'memo' => 'nullable|string|max:100',
            'situation' => 'required|integer',
            'flavors' => 'required|array',
            'flavors.*.name' => 'required|string',
            'flavors.*.quantity' => 'required|integer',
            'toppings' => 'array',
        ]);

        // 注文を保存
        $order = new Order();
        $order->customer = $validatedData['customer'];
        $order->total_amount = $validatedData['total_amount'];
        $order->deposit_amount = $validatedData['deposit_amount'];
        $order->change = $validatedData['change'];
        $order->memo = $validatedData['memo'] ?? null;
        $order->situation = $validatedData['situation'];
        $order->save();

        // 商品（フレーバー）の注文を保存
        foreach ($validatedData['flavors'] as $flavor) {
            $merchandise = Merchandise::where('merchandise_name', $flavor['name'])->first(); // 商品を取得

            if ($merchandise) {
                MerchandiseToOrder::create([
                    'merchandise_id' => $merchandise->id,
                    'order_id' => $order->id,
                    'pieces' => $flavor['quantity'],
                ]);
            } else {
                // 商品が見つからない場合の処理（例: ログに記録）
                \Log::warning("Merchandise not found: " . $flavor['name']);
            }
        }

        // トッピングの注文を保存
        if (!empty($validatedData['toppings'])) {
            foreach ($validatedData['toppings'] as $topping) {
                $toppingModel = Topping::where('topping_name', $topping['topping_name'])->first(); // トッピングを取得

                if ($toppingModel) {
                    ToppingToOrder::create([
                        'topping_id' => $toppingModel->id,
                        'order_id' => $order->id,
                    ]);
                } else {
                    // トッピングが見つからない場合の処理（例: ログに記録）
                    \Log::warning("Topping not found: " . $topping['topping_name']);
                }
            }
        }

        return response()->json(['message' => 'Order created successfully', 'order_id' => $order->id], 201);
    }

    /**
     * Display the specified resource.
     */
    // 注文詳細を取得
    public function show($id)
    {
        // 注文を取得
        $order = Order::findOrFail($id);
    
        // 商品（フレーバー）を取得
        $merchandises = MerchandiseToOrder::where('order_id', $id)
            ->with('merchandise') // 関連する商品データを取得
            ->get();
    
        // トッピングを取得
        $toppings = ToppingToOrder::where('order_id', $id)
            ->with('topping') // 関連するトッピングデータを取得
            ->get();
    
        // フォーマットされた日付を返す
        $formatted_date = $order->updated_at->format('Y-m-d');
    
        return response()->json([
            'order' => [
                'id' => $order->id,
                'customer' => $order->customer,
                'total_amount' => $order->total_amount,
                'deposit_amount' => $order->deposit_amount,
                'change' => $order->change,
                'memo' => $order->memo,
                'date' => $formatted_date,
            ],
            'merchandises' => $merchandises->map(function ($merchandise) {
                return [
                    'id' => $merchandise->merchandise->id,
                    'merchandise_name' => $merchandise->merchandise->merchandise_name,
                    'merchandise_price' => $merchandise->merchandise->merchandise_price,
                    'pieces' => $merchandise->pieces,
                ];
            }),
            'toppings' => $toppings->map(function ($topping) {
                return [
                    'id' => $topping->topping->id,
                    'topping_name' => $topping->topping->topping_name,
                    'topping_price' => $topping->topping->topping_price,
                ];
            }),
        ]);
    }
    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
