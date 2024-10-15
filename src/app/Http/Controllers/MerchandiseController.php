<?php

namespace App\Http\Controllers;

use App\Models\Merchandise;
use Illuminate\Http\Request;

class MerchandiseController extends Controller
{
    // 商品一覧を取得
    public function index()
    {
        return response()->json(Merchandise::all());
    }

    // 商品の登録
    public function store(Request $request)
    {
        // バリデーション
        $validatedData = $request->validate([
            'merchandise_name' => 'required|string|max:255',
            'merchandise_price' => 'required|numeric',
            'stock' => 'required|integer',
            'merchandise_display' => 'required|boolean', // 新しく追加
        ]);

        // 商品を保存
        $merchandise = new Merchandise();
        $merchandise->merchandise_name = $validatedData['merchandise_name'];
        $merchandise->merchandise_price = $validatedData['merchandise_price'];
        $merchandise->stock = $validatedData['stock'];
        $merchandise->merchandise_display = $validatedData['merchandise_display']; // 新しく追加
        $merchandise->save();

        return response()->json(['message' => 'Merchandise created successfully'], 201);
    }

    // 商品の更新
    public function update(Request $request, string $id)
    {
        // 商品をIDで取得
        $merchandise = Merchandise::findOrFail($id);

        // バリデーション
        $validatedData = $request->validate([
            'merchandise_name' => 'required|string|max:255',
            'merchandise_price' => 'required|numeric',
            'stock' => 'required|integer',
            'merchandise_display' => 'required|boolean', // 新しく追加
        ]);

        // 商品データを更新
        $merchandise->merchandise_name = $validatedData['merchandise_name'];
        $merchandise->merchandise_price = $validatedData['merchandise_price'];
        $merchandise->stock = $validatedData['stock'];
        $merchandise->merchandise_display = $validatedData['merchandise_display']; // 新しく追加
        $merchandise->save();

        return response()->json(['message' => 'Merchandise updated successfully'], 200);
    }

    // 商品の取得 (編集時に元データを取得するため)
    public function show(string $id)
    {
        $merchandise = Merchandise::findOrFail($id);
        return response()->json($merchandise);
    }

    public function destroy(string $id)
    {
        $merchandise = Merchandise::findOrFail($id);
        $merchandise->delete();

        return response()->json(['message' => 'Merchandise deleted successfully'], 200);
    }
}
