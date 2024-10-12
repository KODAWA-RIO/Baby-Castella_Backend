<?php

namespace App\Http\Controllers;

use App\Models\Topping;
use Illuminate\Http\Request;

class ToppingController extends Controller
{
    // トッピングの一覧を取得
    public function index()
    {
        return response()->json(Topping::all());
    }

    // トッピングを登録
    public function store(Request $request)
    {
        // バリデーション
        $validatedData = $request->validate([
            'topping_name' => 'required|string|max:255',
            'topping_price' => 'required|numeric',
            'topping_display' => 'required|boolean', // 表示/非表示の追加
        ]);

        // トッピングを保存
        $topping = new Topping();
        $topping->topping_name = $validatedData['topping_name'];
        $topping->topping_price = $validatedData['topping_price'];
        $topping->topping_display = $validatedData['topping_display']; // 表示/非表示の設定
        $topping->save();

        return response()->json(['message' => 'Topping created successfully'], 201);
    }

    // トッピングを取得（編集時に元データを取得するため）
    public function show($id)
    {
        $topping = Topping::findOrFail($id);
        return response()->json($topping);
    }

    // トッピングを更新
    public function update(Request $request, $id)
    {
        // バリデーション
        $validatedData = $request->validate([
            'topping_name' => 'required|string|max:255',
            'topping_price' => 'required|numeric',
            'topping_display' => 'required|boolean', // 表示/非表示の追加
        ]);

        // トッピングをIDで取得
        $topping = Topping::findOrFail($id);

        // トッピングデータを更新
        $topping->topping_name = $validatedData['topping_name'];
        $topping->topping_price = $validatedData['topping_price'];
        $topping->topping_display = $validatedData['topping_display']; // 表示/非表示の設定
        $topping->save();

        return response()->json(['message' => 'Topping updated successfully'], 200);
    }

    // トッピングを削除
    public function destroy($id)
    {
        $topping = Topping::findOrFail($id);
        $topping->delete();

        return response()->json(['message' => 'Topping deleted successfully'], 200);
    }
}
