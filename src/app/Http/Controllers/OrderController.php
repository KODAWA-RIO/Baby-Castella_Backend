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
    public function index(Request $request)
    {
        // 検索クエリがあればフィルターを追加
        $query = Order::query();

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('customer', 'like', "%$search%");
        }

        // ページネーションの実装（1ページあたり10件表示）
        $orders = $query->paginate(10);

        // updated_at をフォーマットして追加
        $orders->getCollection()->transform(function ($order) {
            // updated_at を日付のみにフォーマット
            $order->formatted_date = $order->updated_at->format('Y-m-d');
            return $order;
        });

        // ページネーションと加工したデータを返す
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
            'flavors.*.quantity' => 'required|integer|min:0',  // 0以上の数量を許可
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

        // 商品（フレーバー）の注文を保存し、pieces が 0 でない場合のみ保存
        foreach ($validatedData['flavors'] as $flavor) {
            // piecesが0の場合、スキップ
            if ($flavor['quantity'] > 0) {
                $merchandise = Merchandise::where('merchandise_name', $flavor['name'])->first(); // 商品を取得

                if ($merchandise) {
                    // 在庫が足りているかチェック
                    if ($merchandise->stock >= $flavor['quantity']) {
                        // 在庫を減少させる
                        $merchandise->stock -= $flavor['quantity'];
                        $merchandise->save();

                        // MerchandiseToOrderテーブルに登録
                        MerchandiseToOrder::create([
                            'merchandise_id' => $merchandise->id,
                            'order_id' => $order->id,
                            'pieces' => $flavor['quantity'],
                        ]);
                    } else {
                        return response()->json(['error' => '在庫が不足しています'], 400);
                    }
                } else {
                    \Log::warning("Merchandise not found: " . $flavor['name']);
                    return response()->json(['error' => '商品が見つかりません'], 404);
                }
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
                    \Log::warning("Topping not found: " . $topping['topping_name']);
                    return response()->json(['error' => 'トッピングが見つかりません'], 404);
                }
            }
        }

        return response()->json(['message' => 'Order created and stock updated successfully', 'order_id' => $order->id], 201);
    }

    /**
     * Display the specified resource.
     */
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
     * 指定されたsituationに基づく注文データを取得する
     */
    public function ordersBySituation($situation)
    {
        // 指定されたsituationの注文を取得
        $orders = Order::where('situation', $situation)
            ->with(['merchandiseToOrders.merchandise', 'toppingToOrders.topping']) // 中間テーブルを通して商品やトッピングを取得
            ->get();

        // データを整形して返す
        $formattedOrders = $orders->map(function ($order) {
            // 商品情報を items フィールドに変換
            $items = $order->merchandiseToOrders->map(function ($merchandiseToOrder) {
                return [
                    'flavor' => $merchandiseToOrder->merchandise->merchandise_name,
                    'quantity' => $merchandiseToOrder->pieces,
                ];
            });

            // トッピング情報を toppings フィールドに変換
            $toppings = $order->toppingToOrders->map(function ($toppingToOrder) {
                return $toppingToOrder->topping->topping_name;
            });

            return [
                'id' => $order->id,
                'name' => $order->customer,
                'items' => $items,
                'toppings' => $toppings,
                'memo' => $order->memo,
            ];
        });

        return response()->json($formattedOrders);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // バリデーションを追加（situationは整数値）
        $validatedData = $request->validate([
            'situation' => 'required|integer',
        ]);

        // 注文をIDで検索し、見つからない場合は404を返す
        $order = Order::findOrFail($id);

        // situationを更新
        $order->situation = $validatedData['situation'];
        $order->save();

        return response()->json([
            'message' => 'Order situation updated successfully',
            'order' => $order,
        ], 200);
    }
}
