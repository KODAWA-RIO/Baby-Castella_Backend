<?php

namespace App\Http\Controllers;

use App\Models\Merchandise;
use Illuminate\Http\Request;

class MerchandiseController extends Controller
{
    public function index()
    {
        return response()->json(Merchandise::all());
    }

    public function store(Request $request)
    {
        // バリデーション
        $validatedData = $request->validate([
            'merchandise_name' => 'required|string|max:255',
            'merchandise_price' => 'required|numeric', // 商品値段をバリデート
            'stock' => 'required|integer',
        ]);

        // データベースに商品を保存
        $merchandise = new Merchandise();
        $merchandise->merchandise_name = $validatedData['merchandise_name'];
        $merchandise->merchandise_price = $validatedData['merchandise_price']; // 正しいフィールド名で保存
        $merchandise->stock = $validatedData['stock'];
        $merchandise->save();

        return response()->json(['message' => 'Merchandise created successfully'], 201);
    }
}
