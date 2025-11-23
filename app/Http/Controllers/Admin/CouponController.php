<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $coupons = Coupon::orderBy('created_at', 'desc')->get();
        return view('admin.coupons.index', compact('coupons'));
    }

    /**
     * Display the specified resource for AJAX requests.
     */
    public function show($id)
    {
        $coupon = Coupon::findOrFail($id);
        
        // Format the coupon data for frontend consumption
        $couponData = $coupon->toArray();
        $couponData['start_date'] = $coupon->start_date->format('Y-m-d');
        $couponData['end_date'] = $coupon->end_date->format('Y-m-d');
        
        return response()->json([
            'success' => true,
            'coupon' => $couponData
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'minimum_amount' => 'nullable|numeric|min:0',
            'maximum_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'usage_limit_per_user' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|boolean'
        ]);

        // Additional validation for percentage coupons
        if ($request->type === 'percentage' && $request->value > 100) {
            return back()->withErrors(['value' => 'Percentage value cannot be greater than 100.'])->withInput();
        }

        $data = $request->all();
        $data['code'] = strtoupper($data['code']); // Convert to uppercase

        Coupon::create($data);

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon created successfully!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $coupon = Coupon::findOrFail($id);
        
        $request->validate([
            'code' => ['required', 'string', 'max:50', Rule::unique('coupons')->ignore($id)],
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'minimum_amount' => 'nullable|numeric|min:0',
            'maximum_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'usage_limit_per_user' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|boolean'
        ]);

        // Additional validation for percentage coupons
        if ($request->type === 'percentage' && $request->value > 100) {
            return back()->withErrors(['value' => 'Percentage value cannot be greater than 100.'])->withInput();
        }

        $data = $request->all();
        $data['code'] = strtoupper($data['code']); // Convert to uppercase

        $coupon->update($data);

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $coupon = Coupon::findOrFail($id);
        
        // Check if coupon has been used in orders
        if ($coupon->orders()->count() > 0) {
            return redirect()->route('admin.coupons.index')->with('error', 'Cannot delete coupon that has been used in orders!');
        }

        $coupon->delete();

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon deleted successfully!');
    }

    /**
     * Validate coupon for AJAX requests (for website)
     */
    public function validateCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'user_id' => 'nullable|integer'
        ]);

        $coupon = Coupon::where('code', strtoupper($request->code))->first();

        if (!$coupon) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid coupon code.'
            ]);
        }

        if (!$coupon->isValid()) {
            return response()->json([
                'success' => false,
                'message' => 'This coupon is not valid or has expired.'
            ]);
        }

        if ($request->user_id && !$coupon->canBeUsedByUser($request->user_id)) {
            return response()->json([
                'success' => false,
                'message' => 'You have reached the usage limit for this coupon.'
            ]);
        }

        $discount = $coupon->calculateDiscount($request->amount);

        if ($discount <= 0) {
            $message = $coupon->minimum_amount > 0 
                ? "Minimum order amount of $" . number_format($coupon->minimum_amount, 2) . " required for this coupon."
                : "This coupon cannot be applied to your order.";
                
            return response()->json([
                'success' => false,
                'message' => $message
            ]);
        }

        return response()->json([
            'success' => true,
            'coupon' => [
                'id' => $coupon->id,
                'code' => $coupon->code,
                'name' => $coupon->name,
                'type' => $coupon->type,
                'value' => $coupon->value,
                'discount' => $discount
            ],
            'discount_amount' => $discount,
            'new_total' => $request->amount - $discount
        ]);
    }
}
