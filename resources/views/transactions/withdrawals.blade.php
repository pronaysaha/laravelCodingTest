@extends('layouts.app')

@section('content')
    <h1>Your Withdrawal Transactions</h1>

    <table>
        <thead>
            <tr>
                <th>Amount</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($withdrawals as $withdrawal)
                <tr>
                    <td>${{ number_format($withdrawal->amount, 2) }}</td>
                    <td>{{ $withdrawal->created_at->format('m/d/Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
