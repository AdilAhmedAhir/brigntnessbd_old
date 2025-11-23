<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * Show the admin dashboard.
     */
    public function dashboard(Request $request)
    {
        // Check if user is admin (middleware will be handled in routes)
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            return redirect()->route('admin.login')->with('error', 'Access denied. Admin privileges required.');
        }

        // Handle date range filtering
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        
        // Default to last 30 days if no date range provided
        if (!$startDate || !$endDate) {
            $startDate = now()->subDays(30)->format('Y-m-d');
            $endDate = now()->format('Y-m-d');
        }
        
        // Parse dates
        $startDateTime = Carbon::parse($startDate)->startOfDay();
        $endDateTime = Carbon::parse($endDate)->endOfDay();

        // Calculate today's sales (always for today regardless of filter)
        $todaysSales = Order::whereDate('created_at', today())
            ->where('payment_status', 'paid')
            ->sum('total_amount') ?? 0;

        // Calculate sales for selected date range
        $periodSales = Order::whereBetween('created_at', [$startDateTime, $endDateTime])
            ->where('payment_status', 'paid')
            ->sum('total_amount') ?? 0;

        // Total orders count for selected period
        $totalOrders = Order::whereBetween('created_at', [$startDateTime, $endDateTime])->count();

        // Total customers count (all time)
        $totalCustomers = User::where('role', 'customer')->count();

        // Recent orders from selected period (last 5)
        $recentOrders = Order::with(['user', 'orderItems'])
            ->whereBetween('created_at', [$startDateTime, $endDateTime])
            ->latest()
            ->take(5)
            ->get();

        // Top selling products for selected period
        $topProductIds = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_sold'))
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.payment_status', 'paid')
            ->whereBetween('orders.created_at', [$startDateTime, $endDateTime])
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->take(5)
            ->pluck('total_sold', 'product_id');

        $topProducts = Product::whereIn('id', $topProductIds->keys())
            ->get()
            ->map(function($product) use ($topProductIds) {
                $product->total_sold = $topProductIds[$product->id] ?? 0;
                return $product;
            })
            ->sortByDesc('total_sold');

        // Orders data for selected date range (for chart)
        $ordersInPeriod = Order::whereBetween('created_at', [$startDateTime, $endDateTime])
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get()
            ->pluck('count', 'date')
            ->toArray();

        // Generate chart data for the selected period
        $chartLabels = [];
        $chartData = [];
        $daysDiff = $startDateTime->diffInDays($endDateTime);
        
        for ($i = 0; $i <= $daysDiff; $i++) {
            $date = $startDateTime->copy()->addDays($i)->format('Y-m-d');
            $chartLabels[] = $startDateTime->copy()->addDays($i)->format('M j');
            $chartData[] = $ordersInPeriod[$date] ?? 0;
        }

        // Order status distribution for selected period (for pie chart)
        $orderStatusData = Order::whereBetween('created_at', [$startDateTime, $endDateTime])
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status')
            ->toArray();

        // Ensure all statuses are represented
        $allStatuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
        $statusCounts = [];
        $statusLabels = [];
        
        foreach ($allStatuses as $status) {
            $count = $orderStatusData[$status] ?? 0;
            if ($count > 0) {
                $statusCounts[] = $count;
                $statusLabels[] = ucfirst($status);
            }
        }

        // If no orders exist, provide default data
        if (empty($statusCounts)) {
            $statusCounts = [1, 1, 1, 1];
            $statusLabels = ['Processing', 'Completed', 'Shipped', 'Pending'];
        }

        $data = [
            'todaysSales' => $todaysSales,
            'monthlySales' => $periodSales, // Now represents sales for selected period
            'totalOrders' => $totalOrders,
            'totalCustomers' => $totalCustomers,
            'recentOrders' => $recentOrders,
            'topProducts' => $topProducts,
            'chartLabels' => $chartLabels,
            'chartData' => $chartData,
            'statusLabels' => $statusLabels,
            'statusCounts' => $statusCounts,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ];

        // Return JSON response for AJAX requests
        if ($request->wantsJson()) {
            return response()->json($data);
        }

        return view('admin.dashboard', $data);
    }

    /**
     * Show admin profile.
     */
    public function profile()
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            return redirect()->route('admin.login')->with('error', 'Access denied. Admin privileges required.');
        }
        
        return view('admin.profile');
    }

    /**
     * Show activity log.
     */
    public function activityLog()
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            return redirect()->route('admin.login')->with('error', 'Access denied. Admin privileges required.');
        }
        
        return view('admin.activity-log');
    }
}
