<?php

namespace App\Http\Controllers;

use App\Models\Topping;
use Illuminate\Http\Request;

class ToppingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Topping::all());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // バリデーション
        $validatedData = $request->validate([
            'topping_name' => 'required|string|max:255',
            'topping_price' => 'required|numeric',
        ]);

        // データベースにトッピングを保存
        $topping = new Topping();
        $topping->topping_name = $validatedData['topping_name']; // カラム名が 'name' だとエラーになる
        $topping->topping_price = $validatedData['topping_price'];
        $topping->save();

        return response()->json(['message' => 'Topping created successfully'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
