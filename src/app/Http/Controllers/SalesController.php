<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class SalesController extends Controller
{
    // 日付ごとの注文一覧を取得
    public function getOrderDates()
    {
        // 日付ごとにグループ化して注文数を取得
        $orders = Order::selectRaw('DATE(created_at) as date, COUNT(*) as total_orders')
            ->groupBy('date')
            ->get();

        return response()->json($orders);
    }
}
