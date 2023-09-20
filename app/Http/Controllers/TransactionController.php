<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
{
    // Get the currently authenticated user
    $user = Auth::user();

    // Retrieve all the transactions for this user
    $transactions = Transaction::where('user_id', $user->id)->get();

    // If you're building an API:
    return response()->json(['transactions' => $transactions, 'balance' => $user->balance]);

    // If you're returning a view:
   // return view('transactions.index', [
      //  'transactions' => $transactions,
       // 'balance' => $user->balance
   // ]);
}
public function showDeposits()
{
    // Get the currently authenticated user
    $user = Auth::user();

    // Retrieve all deposit transactions for this user
    $deposits = Transaction::where('user_id', $user->id)
                ->where('type', 'deposit')
                ->get();

    // If you're building an API:
     return response()->json(['deposits' => $deposits]);

    // If you're returning a view:
   // return view('transactions.deposits', [
       // 'deposits' => $deposits
   // ]);
}

public function deposit(Request $request)
{
    // Validate the request
    $data = $request->validate([
        'amount' => 'required|numeric|min:0.01'
    ]);

    // Start a database transaction
    \DB::beginTransaction();

    try {
        // Get the currently authenticated user
        $user = Auth::user();

        // Update the user's balance
        $user->balance += $data['amount'];
        $user->save();

        // Create a new deposit transaction
        Transaction::create([
            'user_id' => $user->id,
            'type' => 'deposit',
            'amount' => $data['amount']
        ]);

        // Commit the database transaction
        \DB::commit();

        // If you're building an API:
        // return response()->json(['message' => 'Deposit successful!', 'balance' => $user->balance]);

        // If you're returning to a web page:
        return redirect('/')->with('success', 'Deposit successful!');

    } catch (\Exception $e) {
        // If there's an error, rollback any database operations
        \DB::rollback();

        // If you're building an API:
         return response()->json(['error' => 'Deposit failed!'], 500);

        // If you're returning to a web page:
       // return redirect('/deposit')->with('error', 'Deposit failed!');
    }
}

public function showWithdrawals()
{
    // Get the currently authenticated user
    $user = Auth::user();

    // Retrieve all withdrawal transactions for this user
    $withdrawals = Transaction::where('user_id', $user->id)
                ->where('type', 'withdrawal')
                ->get();

    // If you're building an API:
     return response()->json(['withdrawals' => $withdrawals]);

    // If you're returning a view:
   // return view('transactions.withdrawals', [
       // 'withdrawals' => $withdrawals
   // ]);
}

public function withdraw(Request $request)
{
    // Validate the request
    $data = $request->validate([
        'amount' => 'required|numeric|min:0.01'
    ]);

    $user = Auth::user();

    // Start database transaction
    \DB::beginTransaction();

    try {
        // Check if user has sufficient balance
        if ($user->balance < $data['amount']) {
            return response()->json(['error' => 'Insufficient funds'], 400);
        }

        // Calculate the withdrawal fee based on account type
        $fee = 0;

        if ($user->account_type == 'Individual') {
            $fee = $data['amount'] * 0.015;

            if (date('w') == 5) {
                $fee = 0;
            } elseif ($data['amount'] <= 1000) {
                $fee = 0;
            } elseif (Transaction::where('user_id', $user->id)
                      ->where('type', 'withdrawal')
                      ->whereMonth('created_at', date('m'))
                      ->sum('amount') <= 5000) {
                $fee = 0;
            }

        } elseif ($user->account_type == 'Business') {
            $fee = $data['amount'] * 0.025;

            $totalWithdrawn = Transaction::where('user_id', $user->id)
                               ->where('type', 'withdrawal')
                               ->sum('amount');

            if ($totalWithdrawn >= 50000) {
                $fee = $data['amount'] * 0.015;
            }
        }

        // Deduct fee from withdrawal amount
        $data['amount'] -= $fee;

        // Deduct the withdrawal amount from user's balance
        $user->balance -= $data['amount'];
        $user->save();

        // Record the withdrawal transaction
        Transaction::create([
            'user_id' => $user->id,
            'type' => 'withdrawal',
            'amount' => $data['amount']
        ]);

        // Commit the transaction
        \DB::commit();

        return response()->json(['message' => 'Withdrawal successful!', 'balance' => $user->balance]);

    } catch (\Exception $e) {
        // Rollback any database operations in case of error
        \DB::rollback();

        return response()->json(['error' => 'Withdrawal failed!'], 500);
    }
}

}
