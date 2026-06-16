<?php

namespace App\Http\Controllers;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $period = CarbonPeriod::create(Carbon::today()->subDays(7), '1 Day', Carbon::today());
 
        $orders = [];
        foreach ($period as $dt) {
            $orders[ $dt->format("M d") ] = Order::whereDate('created_at', '=', $dt->format('Y-m-d'))->count();
        }
 
        return view('backend/dashboard/index', compact('orders'));
    }

    public function subscribePushNotification(Request $request)
    {
        $token = $request->token ?? false;
        
        if($token){
            return subscribePushNotification($token);
        }
        
        return false;
    }


    public function connect(Request $request)
    {
        return view('backend/help/connect');
    }

}
