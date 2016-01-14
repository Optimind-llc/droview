@extends ('backend.layouts.master')

@section ('title', trans('menus.log_management'))

@section('page-header')
    <h1>
        PinCode Management
        <small>PINコード生成</small>
    </h1>
@endsection

@section('content')

<h4>PinCodeが生成されました</h4>
<hr>
@foreach($pins as $pin)
	<p>{{ $pin['pinCode'] }} : 発行チケット{{ $pin['numberOfTickets'] }}枚</p>
@endforeach
<hr>

<input type="button" value="印刷する" class="btn btn-info" onclick="window.print();" />

@endsection
