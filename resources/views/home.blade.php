@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    <div class="links"><a href="cardlist">Card List</a></div><br/>
                    <div class="links"><a href="makepayment">Make Payment Using Direct Card</a></div><br/>
                    <div class="links"><a href="storedcardpayment">Make Payment Using Stored Card</a></div><br/>
                    <div class="links"><a href="transactions">Transactions Log</a></div><br/>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
