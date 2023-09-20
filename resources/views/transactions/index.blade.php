@extends('layouts.app')

@section('content')
    <h1>Your Transactions</h1>

    <h2>Current Balance: ${{ number_format($balance, 2) }}</h2>

    <table>
        <thead>
            <tr>
                <th>Type</th>
                <th>Amount</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $transaction)
                <tr>
                    <td>{{ $transaction->type }}</td>
                    <td>${{ number_format($transaction->amount, 2) }}</td>
                    <td>{{ $transaction->created_at->format('m/d/Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
