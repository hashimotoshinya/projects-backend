<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Store;

class StoreController extends Controller
{
    /**
     * 店舗一覧
     */
    public function index()
{
    try {
        $stores = Store::where('user_id', auth()->id())
            ->latest()
            ->get();

        return response()->json($stores);

    } catch (\Throwable $e) {
        return response()->json([
            'error' => $e->getMessage()
        ], 500);
    }
}

    /**
     * 店舗登録
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'address' => 'nullable|max:255',
            'phone' => 'nullable|max:50',
            'lat' => 'nullable|numeric',
            'lng' => 'nullable|numeric',
            'staff_name' => 'nullable|max:255',
        ]);

        $store = Store::create([
            'name' => $validated['name'],
            'address' => $validated['address'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'lat' => $validated['lat'] ?? null,
            'lng' => $validated['lng'] ?? null,
            'staff_name' => $validated['staff_name'] ?? null,
            'user_id' => auth()->id(),
        ]);

        return response()->json($store, 201);
    }

    /**
     * 店舗詳細
     */
    public function show($id)
    {
        $store = Store::where('user_id', auth()->id())
            ->findOrFail($id);

        return response()->json($store);
    }

    /**
     * 店舗更新
     */
    public function update(Request $request, $id)
    {
        $store = Store::where('user_id', auth()->id())
            ->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|max:255',
            'address' => 'nullable|max:255',
            'phone' => 'nullable|max:50',
            'lat' => 'nullable|numeric',
            'lng' => 'nullable|numeric',
            'staff_name' => 'nullable|max:255',
        ]);

        $store->update($validated);

        return response()->json($store);
    }

    /**
     * 店舗削除
     */
    public function destroy($id)
    {
        $store = Store::where('user_id', auth()->id())
            ->findOrFail($id);

        $store->delete();

        return response()->json([
            'message' => 'deleted'
        ]);
    }
}