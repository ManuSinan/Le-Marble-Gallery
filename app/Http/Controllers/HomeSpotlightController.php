<?php

namespace App\Http\Controllers;

use App\Models\HomeSpotlight;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HomeSpotlightController extends Controller
{
    public function index()
    {
        $spotlights = HomeSpotlight::with('product')
            ->orderBy('sort_order')
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('backend/home-spotlight/index', compact('spotlights'));
    }

    public function create()
    {
        $products = Product::orderBy('name', 'asc')->pluck('name', 'id');

        return view('backend/home-spotlight/create', compact('products'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => ['required', 'exists:products,id'],
            'status' => ['required', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        HomeSpotlight::create([
            'product_id' => $request->product_id,
            'status' => $request->boolean('status'),
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()
            ->route('home.spotlight.index')
            ->with('success', __('Home spotlight created successfully.'));
    }

    public function edit(HomeSpotlight $homeSpotlight)
    {
        $products = Product::orderBy('name', 'asc')->pluck('name', 'id');

        return view('backend/home-spotlight/edit', [
            'spotlight' => $homeSpotlight,
            'products' => $products,
        ]);
    }

    public function update(Request $request, HomeSpotlight $homeSpotlight)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => ['required', 'exists:products,id'],
            'status' => ['required', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $homeSpotlight->update([
            'product_id' => $request->product_id,
            'status' => $request->boolean('status'),
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()
            ->route('home.spotlight.index')
            ->with('success', __('Home spotlight updated successfully.'));
    }

    public function destroy(HomeSpotlight $homeSpotlight)
    {
        $homeSpotlight->delete();

        return redirect()
            ->route('home.spotlight.index')
            ->with('success', __('Home spotlight deleted successfully.'));
    }
}

