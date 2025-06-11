<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use App\Models\FoodOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RestaurateurDashboardController extends Controller
{
    public function index()
    {
        $restaurants = Auth::user()->restaurants;
        $selectedRestaurant = $restaurants->first();
        $orders = collect();
        $stats = null;

        if ($selectedRestaurant) {
            $stats = $this->getRestaurantStats($selectedRestaurant);
            $orders = $selectedRestaurant->orders()
                ->with(['client', 'items'])
                ->whereIn('status', [
                    'pending', 'confirmed', 'accepted', 'preparing', 'ready', 'delivering'
                ])
                ->get();
        }

        return view('restaurateur.dashboard', compact('restaurants', 'selectedRestaurant', 'orders', 'stats'));
    }

    public function show(Restaurant $restaurant)
    {
        if ($restaurant->owner_id !== Auth::id()) {
            abort(403);
        }

        $orders = $restaurant->orders()
            ->with(['client', 'items'])
            ->whereIn('status', ['pending', 'accepted', 'preparing', 'ready', 'delivering'])
            ->latest()
            ->take(10)
            ->get();

        $stats = $this->getRestaurantStats($restaurant);

        return view('restaurateur.dashboard', [
            'restaurants' => Auth::user()->restaurants,
            'selectedRestaurant' => $restaurant,
            'orders' => $orders,
            'stats' => $stats
        ]);
    }

    private function getRestaurantStats(Restaurant $restaurant)
    {
        $now = Carbon::now();
        $startOfDay = $now->copy()->startOfDay();
        $startOfWeek = $now->copy()->startOfWeek();
        $startOfMonth = $now->copy()->startOfMonth();

        $orderStats = FoodOrder::where('restaurant_id', $restaurant->id)
            ->select([
                DB::raw('COUNT(*) as total_orders'),
                DB::raw("SUM(CASE WHEN created_at >= '" . $startOfDay->format('Y-m-d H:i:s') . "' THEN 1 ELSE 0 END) as today_orders"),
                DB::raw("SUM(CASE WHEN created_at >= '" . $startOfWeek->format('Y-m-d H:i:s') . "' THEN 1 ELSE 0 END) as week_orders"),
                DB::raw("SUM(CASE WHEN created_at >= '" . $startOfMonth->format('Y-m-d H:i:s') . "' THEN 1 ELSE 0 END) as month_orders")
            ])
            ->first();

        $revenueStats = DB::table('food_orders')
            ->join('order_items', 'food_orders.id', '=', 'order_items.order_id')
            ->where('food_orders.restaurant_id', $restaurant->id)
            ->select([
                DB::raw('SUM(order_items.price * order_items.quantity) as total_revenue'),
                DB::raw("SUM(CASE WHEN food_orders.created_at >= '" . $startOfDay->format('Y-m-d H:i:s') . "' THEN order_items.price * order_items.quantity ELSE 0 END) as today_revenue"),
                DB::raw("SUM(CASE WHEN food_orders.created_at >= '" . $startOfWeek->format('Y-m-d H:i:s') . "' THEN order_items.price * order_items.quantity ELSE 0 END) as week_revenue"),
                DB::raw("SUM(CASE WHEN food_orders.created_at >= '" . $startOfMonth->format('Y-m-d H:i:s') . "' THEN order_items.price * order_items.quantity ELSE 0 END) as month_revenue")
            ])
            ->first();

        $topItems = DB::table('order_items')
            ->join('food_orders', 'order_items.order_id', '=', 'food_orders.id')
            ->join('items', 'order_items.item_id', '=', 'items.id')
            ->where('food_orders.restaurant_id', $restaurant->id)
            ->select([
                'items.name',
                DB::raw('SUM(order_items.quantity) as total_quantity'),
                DB::raw('SUM(order_items.price * order_items.quantity) as total_revenue')
            ])
            ->groupBy('items.id', 'items.name')
            ->orderByDesc('total_quantity')
            ->limit(5)
            ->get();

        return [
            'orders' => $orderStats,
            'revenue' => $revenueStats,
            'top_items' => $topItems,
            'rating' => [
                'average' => $restaurant->averageRating(),
                'count' => $restaurant->ratingCount()
            ]
        ];
    }
}
