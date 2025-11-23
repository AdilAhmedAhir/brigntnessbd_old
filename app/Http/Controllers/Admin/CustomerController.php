<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'customer')
            ->withCount('orders')
            ->latest();

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search by name or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $customers = $query->paginate(15);

        return view('admin.customers.index', compact('customers'));
    }

    /**
     * Display the specified resource.
     */
    public function show(User $customer)
    {
        $customer->load(['orders.orderItems.product']);
        return view('admin.customers.show', compact('customer'));
    }

    /**
     * Update customer status.
     */
    public function updateStatus(Request $request, User $customer)
    {
        $request->validate([
            'status' => 'required|in:active,inactive',
        ]);

        $customer->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Customer status updated successfully!');
    }
}
