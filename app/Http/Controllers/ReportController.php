<?php

namespace App\Http\Controllers;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Location;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class ReportController extends Controller
{
    public function businessOverview(Request $request)
    {
        $period = CarbonPeriod::create(Carbon::today()->subMonths(11), '1 Month', Carbon::today());
 
        $data = [];
        foreach ($period as $dt) {
            $data[ $dt->format("Y M") ] = [
                'Orders' => Order::whereYear('created_at', '=', $dt->format("Y"))->whereMonth('created_at', '=', $dt->format("m"))->count(),
                'Products Sold' => OrderItem::whereYear('created_at', '=', $dt->format("Y"))->whereMonth('created_at', '=', $dt->format("m"))->count(),
                'Delivery Charges' => Order::whereYear('created_at', '=', $dt->format("Y"))->whereMonth('created_at', '=', $dt->format("m"))->sum('delivery_charge'),
                'Total Business' => Order::whereYear('created_at', '=', $dt->format("Y"))->whereMonth('created_at', '=', $dt->format("m"))->sum('final_amount'),
            ];
        }

        $data = array_reverse($data);
 
        return view('backend/report/business-overview', compact('data'));
    }


    public function location(Request $request)
    {
        $locations = Location::all();

        $dateS = Carbon::now()->startOfMonth()->subMonth(2);
        $dateE = Carbon::now(); 

        $data = [];
        foreach ($locations as $location) {
            $data[ $location->name ] = [
                'Customers' => Order::whereBetween('created_at',[$dateS,$dateE])->where('address_location', $location->name)->distinct('user_id')->count('user_id'),
                'Orders' => Order::whereBetween('created_at',[$dateS,$dateE])->where('address_location', $location->name)->count(),
                'Total Business' => Order::whereBetween('created_at',[$dateS,$dateE])->where('address_location', $location->name)->sum('final_amount'),
            ];
        }

        arsort($data);
 
        return view('backend/report/location', compact('data', 'dateS', 'dateE'));
    }



    public function mostPurchasedBrands(Request $request)
    {
        $dateS = Carbon::now()->startOfMonth()->subMonth(2);
        $dateE = Carbon::now(); 

        $brands = Brand::all();

        $data = [];
        foreach ($brands as $brand) {
            $data[ $brand->name ] = [
                'Products Sold' => OrderItem::whereBetween('created_at',[$dateS,$dateE])->where('brand_id', $brand->id)->sum('quantity'),
                'Total Business' => OrderItem::whereBetween('created_at',[$dateS,$dateE])->where('brand_id', $brand->id)->sum('final_price'),
            ];
        }
  
        arsort($data);

        return view('backend/report/most-purchased-brands', compact('data', 'dateS', 'dateE'));
    }



    public function mostPurchasedCategories(Request $request)
    {
        $dateS = Carbon::now()->startOfMonth()->subMonth(2);
        $dateE = Carbon::now(); 

        $categories = Category::all();

        $data = [];
        foreach ($categories as $category) {
            $data[ $category->name ] = [
                'Products Sold' => OrderItem::whereBetween('created_at',[$dateS,$dateE])->where('category_id', $category->id)->sum('quantity'),
                'Total Business' => OrderItem::whereBetween('created_at',[$dateS,$dateE])->where('category_id', $category->id)->sum('final_price'),
            ];
        }
  
        arsort($data);

        return view('backend/report/most-purchased-categories', compact('data', 'dateS', 'dateE'));
    }



    public function mostPurchasedProducts(Request $request)
    {
        $dateS = Carbon::now()->startOfMonth()->subMonth(2);
        $dateE = Carbon::now(); 

        $products = OrderItem::whereBetween('created_at',[$dateS,$dateE])->distinct('product_id')->get();

        $data = [];
        foreach ($products as $product) {
            $data[ $product->product_name ] = [
                'Products Sold' => OrderItem::whereBetween('created_at',[$dateS,$dateE])->where('product_id', $product->product_id)->sum('quantity'),
                'Total Business' => OrderItem::whereBetween('created_at',[$dateS,$dateE])->where('product_id', $product->product_id)->sum('final_price'),
            ];
        }
  
        arsort($data);

        return view('backend/report/most-purchased-products', compact('data', 'dateS', 'dateE'));
     }

    

}
