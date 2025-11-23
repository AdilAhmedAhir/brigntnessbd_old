<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
    /**
     * Show account dashboard
     */
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('account.login');
        }

        $user = Auth::user();
        $recentOrders = Order::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('website.my-account.account', compact('user', 'recentOrders'));
    }

    /**
     * Show login page
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('account.dashboard');
        }

        return view('website.my-account.login');
    }

    /**
     * Handle login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        $credentials['status'] = 'active';
        $credentials['role'] = 'customer';

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            
            $redirectTo = $request->input('redirect_to', route('account.dashboard'));
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Login successful!',
                    'redirect_url' => $redirectTo
                ]);
            }

            return redirect()->intended($redirectTo);
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials or account is inactive.'
            ], 401);
        }

        return back()->withErrors([
            'email' => 'Invalid credentials or account is inactive.',
        ])->onlyInput('email');
    }

    /**
     * Handle registration
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'customer',
            'status' => 'active',
        ]);

        Auth::login($user);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Account created successfully!',
                'redirect_url' => route('account.dashboard')
            ]);
        }

        return redirect()->route('account.dashboard');
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    /**
     * Show orders page
     */
    public function orders()
    {
        if (!Auth::check()) {
            return redirect()->route('account.login');
        }

        $user = Auth::user();
        $orders = Order::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('website.my-account.orders', compact('orders'));
    }

    /**
     * Show single order
     */
    public function orderView($id)
    {
        if (!Auth::check()) {
            return redirect()->route('account.login');
        }

        $user = Auth::user();
        $order = Order::with('orderItems.product')
            ->where('user_id', $user->id)
            ->findOrFail($id);

        $isNewOrder = request()->get('new_order') === 'true';

        return view('website.my-account.order-view', compact('order', 'isNewOrder'));
    }

    /**
     * Show profile page
     */
    public function profile()
    {
        if (!Auth::check()) {
            return redirect()->route('account.login');
        }

        $user = Auth::user();
        return view('website.my-account.profile', compact('user'));
    }

    /**
     * Update profile
     */
    public function updateProfile(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('account.login');
        }

        $user = Auth::user();
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'current_password' => 'nullable|string',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        // Check current password if new password is provided
        if ($request->filled('password')) {
            if (!$request->filled('current_password') || !Hash::check($request->current_password, $user->password)) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Current password is incorrect.'
                    ], 400);
                }
                return back()->withErrors(['current_password' => 'Current password is incorrect.']);
            }
        }

        // Update user data
        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully!'
            ]);
        }

        return back()->with('success', 'Profile updated successfully!');
    }
}
