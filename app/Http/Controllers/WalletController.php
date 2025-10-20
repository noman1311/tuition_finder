<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;
use App\Models\Teacher;

class WalletController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (Auth::user()->role !== 'teacher') {
                return redirect()->route('home')->withErrors(['error' => 'Access denied. Only teachers can access wallet features.']);
            }
            return $next($request);
        });
    }

    public function index()
    {
        $teacher = Teacher::where('user_id', Auth::id())->firstOrFail();
        $transactions = Transaction::where('user_id', Auth::id())
            ->walletTransactions()
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('wallet.index', compact('teacher', 'transactions'));
    }

    public function create()
    {
        return view('wallet.add-funds');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'account_number' => ['required', 'string', 'max:50'],
            'payment_type' => ['required', 'in:bkash,rocket,banking,nagad'],
            'amount' => ['required', 'numeric', 'min:1', 'max:10000'],
        ]);

        // Create transaction record
        Transaction::create([
            'user_id' => Auth::id(),
            'account_number' => $data['account_number'],
            'type' => $data['payment_type'],
            'amount' => $data['amount'],
            'description' => 'Wallet recharge',
            'transaction_date' => now()->toDateString(),
        ]);

        // Add coins directly to teacher's account
        $teacher = Teacher::where('user_id', Auth::id())->first();
        if ($teacher) {
            $teacher->increment('coins', $data['amount']);
        }

        return redirect()->route('wallet.index')->with('success', 'Coins added successfully to your wallet!');
    }


}