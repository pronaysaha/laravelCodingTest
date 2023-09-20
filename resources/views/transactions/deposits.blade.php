@extends('layouts.app')

@section('content')
    <h1>Your Deposited Transactions</h1>

    <table>
        <thead>
            <tr>
                <th>Amount</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($deposits as $deposit)
                <tr>
                    <td>${{ number_format($deposit->amount, 2) }}</td>
                    <td>{{ $deposit->created_at->format('m/d/Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
