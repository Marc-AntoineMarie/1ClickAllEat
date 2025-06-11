<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use App\Policies\FoodOrderPolicy;
use App\Models\FoodOrder;
use App\Models\Rating;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = $this->getGlobalStats();
        $latestRestaurants = Restaurant::with('owner')
            ->latest()
            ->take(5)
            ->get();
        $latestUsers = User::latest()
            ->take(5)
            ->get();
        $latestOrders = FoodOrder::with(['client', 'restaurant'])
            ->latest()
            ->take(5)
            ->get();
        $latestRatings = Rating::with(['user', 'restaurant'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'latestRestaurants',
            'latestUsers',
            'latestOrders',
            'latestRatings'
        ));
    }

    public function users()
    {
        $users = User::withCount(['orders', 'ratings'])
            ->latest()
            ->paginate(20);

        return view('admin.users', compact('users'));
    }

    public function restaurants()
    {
        $restaurants = Restaurant::withCount(['orders', 'ratings'])
            ->with('owner')
            ->latest()
            ->paginate(20);

        return view('admin.restaurants', compact('restaurants'));
    }

    public function ratings()
    {
        $ratings = Rating::with(['user', 'restaurant'])
            ->latest()
            ->paginate(20);

        return view('admin.ratings', compact('ratings'));
    }

    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'role_id' => 'required|exists:roles,id'
        ]);

        $user->update($validated);

        return redirect()->back()->with('success', 'Rôle mis à jour avec succès.');
    }

    public function destroyUser(User $user)
    {
        if ($user->role->name === 'admin') {
            return redirect()->back()->with('error', 'Impossible de supprimer un administrateur.');
        }

        $user->delete();

        return redirect()->back()->with('success', 'Utilisateur supprimé avec succès.');
    }

    public function destroyRating(Rating $rating)
    {
        $rating->delete();

        return redirect()->back()->with('success', 'Avis supprimé avec succès.');
    }

    private function getGlobalStats()
    {
        $now = Carbon::now();
        $startOfDay = $now->copy()->startOfDay()->format('Y-m-d H:i:s');
        $startOfWeek = $now->copy()->startOfWeek()->format('Y-m-d H:i:s');
        $startOfMonth = $now->copy()->startOfMonth()->format('Y-m-d H:i:s');

        $userStats = User::select([
            DB::raw('COUNT(*) as total_users'),
            DB::raw("SUM(CASE WHEN created_at >= '". $startOfDay ."' THEN 1 ELSE 0 END) as today_users"),
            DB::raw("SUM(CASE WHEN created_at >= '". $startOfWeek ."' THEN 1 ELSE 0 END) as week_users"),
            DB::raw("SUM(CASE WHEN created_at >= '". $startOfMonth ."' THEN 1 ELSE 0 END) as month_users"),
            DB::raw("COUNT(CASE WHEN role_id = (SELECT id FROM roles WHERE name = 'client') THEN 1 END) as total_clients"),
            DB::raw("COUNT(CASE WHEN role_id = (SELECT id FROM roles WHERE name = 'restaurateur') THEN 1 END) as total_restaurateurs")
        ])->first();

        $restaurantStats = Restaurant::select([
            DB::raw('COUNT(*) as total_restaurants'),
            DB::raw("SUM(CASE WHEN created_at >= '". $startOfDay ."' THEN 1 ELSE 0 END) as today_restaurants"),
            DB::raw("SUM(CASE WHEN created_at >= '". $startOfWeek ."' THEN 1 ELSE 0 END) as week_restaurants"),
            DB::raw("SUM(CASE WHEN created_at >= '". $startOfMonth ."' THEN 1 ELSE 0 END) as month_restaurants")
        ])->first();

        $orderStats = FoodOrder::select([
            DB::raw('COUNT(*) as total_orders'),
            DB::raw("SUM(CASE WHEN created_at >= '". $startOfDay ."' THEN 1 ELSE 0 END) as today_orders"),
            DB::raw("SUM(CASE WHEN created_at >= '". $startOfWeek ."' THEN 1 ELSE 0 END) as week_orders"),
            DB::raw("SUM(CASE WHEN created_at >= '". $startOfMonth ."' THEN 1 ELSE 0 END) as month_orders")
        ])->first();

        $revenueStats = DB::table('food_orders')
            ->join('order_items', 'food_orders.id', '=', 'order_items.order_id')
            ->select([
                DB::raw('SUM(order_items.price * order_items.quantity) as total_revenue'),
                DB::raw("SUM(CASE WHEN food_orders.created_at >= '". $startOfDay ."' THEN order_items.price * order_items.quantity ELSE 0 END) as today_revenue"),
                DB::raw("SUM(CASE WHEN food_orders.created_at >= '". $startOfWeek ."' THEN order_items.price * order_items.quantity ELSE 0 END) as week_revenue"),
                DB::raw("SUM(CASE WHEN food_orders.created_at >= '". $startOfMonth ."' THEN order_items.price * order_items.quantity ELSE 0 END) as month_revenue")
            ])
            ->first();

        $ratingStats = Rating::select([
            DB::raw('COUNT(*) as total_ratings'),
            DB::raw('AVG(score) as average_rating'),
            DB::raw("SUM(CASE WHEN created_at >= '". $startOfDay ."' THEN 1 ELSE 0 END) as today_ratings"),
            DB::raw("SUM(CASE WHEN created_at >= '". $startOfWeek ."' THEN 1 ELSE 0 END) as week_ratings"),
            DB::raw("SUM(CASE WHEN created_at >= '". $startOfMonth ."' THEN 1 ELSE 0 END) as month_ratings")
        ])->first();

        return [
            'users' => $userStats,
            'restaurants' => $restaurantStats,
            'orders' => $orderStats,
            'revenue' => $revenueStats,
            'ratings' => $ratingStats
        ];
    }
}
