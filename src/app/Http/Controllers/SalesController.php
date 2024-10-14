<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use DB;

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
    // 指定された日付の商品の売り上げデータを取得
    public function merchandiseSalesByDate($date)
    {
        $sales = DB::table('merchandise_to_orders')
            ->join('merchandises', 'merchandise_to_orders.merchandise_id', '=', 'merchandises.id')
            ->select(DB::raw('HOUR(merchandise_to_orders.created_at) as hour'), 'merchandises.merchandise_name', DB::raw('SUM(merchandise_to_orders.pieces) as total_sales'))
            ->whereDate('merchandise_to_orders.created_at', $date)
            ->groupBy('hour', 'merchandises.merchandise_name')
            ->get();

        return response()->json($sales);
    }

    // 指定された日付のトッピングの売り上げデータを取得
    public function toppingSalesByDate($date)
    {
        $sales = DB::table('topping_to_orders')
            ->join('toppings', 'topping_to_orders.topping_id', '=', 'toppings.id')
            ->select(DB::raw('HOUR(topping_to_orders.created_at) as hour'), 'toppings.topping_name', DB::raw('COUNT(*) as total_sales'))
            ->whereDate('topping_to_orders.created_at', $date)
            ->groupBy('hour', 'toppings.topping_name')
            ->get();

        return response()->json($sales);
    }
}
