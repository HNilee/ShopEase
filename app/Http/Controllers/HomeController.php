<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $role = Auth::check() ? Auth::user()->role : 'guest';
        $usersCount = $ordersCount = $productsCount = 0;
        $totalIncome = 0;
        $chartData = [];
        $recentOrders = [];
        $cartItemCount = 0;

        if ($role === 'admin' || $role === 'owner') {
            $usersCount = User::count();
            $ordersCount = Order::count();
            $productsCount = Product::count();
            $totalIncome = Order::sum('total');

            $chartData = [
                'daily' => $this->getChartData('daily'),
                'weekly' => $this->getChartData('weekly'),
                'monthly' => $this->getChartData('monthly'),
                'yearly' => $this->getChartData('yearly'),
            ];
        } elseif ($role === 'buyer' || $role === 'seller') {
            $user = Auth::user();
            $recentOrders = Order::where('user_id', $user->id)
                ->orderByDesc('created_at')
                ->take(5)
                ->get();
            
            $cart = \App\Models\Cart::where('user_id', $user->id)->first();
            $cartItemCount = $cart ? $cart->items->sum('quantity') : 0;
        }
        
        return view('home', compact('role', 'usersCount', 'ordersCount', 'productsCount', 'totalIncome', 'chartData', 'recentOrders', 'cartItemCount'));
    }

    private function getChartData($period)
    {
        $dates = [];
        $income = [];
        $quantity = [];
        $format = 'Y-m-d';
        $displayFormat = 'Y-m-d';
        $loopCount = 30;
        $intervalMethod = 'subDays';

        if ($period === 'weekly') {
            $format = 'o-W'; // Year-Week number
            $displayFormat = 'Y \W W';
            $loopCount = 12;
            $intervalMethod = 'subWeeks';
        } elseif ($period === 'monthly') {
            $format = 'Y-m';
            $displayFormat = 'M Y';
            $loopCount = 12;
            $intervalMethod = 'subMonths';
        } elseif ($period === 'yearly') {
            $format = 'Y';
            $displayFormat = 'Y';
            $loopCount = 5;
            $intervalMethod = 'subYears';
        }

        // Calculate start date based on period
        $startDate = now()->$intervalMethod($loopCount - 1);
        if ($period === 'daily') $startDate = $startDate->startOfDay();
        elseif ($period === 'weekly') $startDate = $startDate->startOfWeek();
        elseif ($period === 'monthly') $startDate = $startDate->startOfMonth();
        elseif ($period === 'yearly') $startDate = $startDate->startOfYear();

        $orders = Order::where('created_at', '>=', $startDate)
            ->orderBy('created_at')
            ->get()
            ->groupBy(function($date) use ($format) {
                return $date->created_at->format($format);
            });

        $dates = [];
        $income = [];
        $quantity = [];

        for ($i = 0; $i < $loopCount; $i++) {
            $currentDate = now()->$intervalMethod($loopCount - 1 - $i);
            $key = $currentDate->format($format);
            $label = $currentDate->format($displayFormat);
            
            $dates[] = $label;
            
            if (isset($orders[$key])) {
                $periodOrders = $orders[$key];
                $income[] = $periodOrders->sum('total');
                
                $qty = 0;
                foreach ($periodOrders as $o) {
                    $qty += $o->items->sum('quantity');
                }
                $quantity[] = $qty;
            } else {
                $income[] = 0;
                $quantity[] = 0;
            }
        }

        return [
            'dates' => $dates,
            'income' => $income,
            'quantity' => $quantity
        ];
    }
}
