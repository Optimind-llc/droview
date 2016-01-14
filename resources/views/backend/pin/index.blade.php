@extends ('backend.layouts.master')

@section ('title', trans('menus.log_management'))

@section('page-header')
    <h1>
        PinCode Management
        <small>PINコード生成</small>
    </h1>
@endsection

@section('content')

    <form class="col-md-6" method="post">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="form-group">
            <label for="num">PINコード１つで発行するチケット枚数</label>
            <select class="form-control" id="num" name="num">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
                <option value="7">7</option>
                <option value="8">8</option>
                <option value="9">9</option>
                <option value="10">10</option>
            </select>
        </div>
        <div class="form-group">
            <label for="circulation">PINコードの発行部数</label>
            <select class="form-control" id="circulation" name="circulation">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
                <option value="7">7</option>
                <option value="8">8</option>
                <option value="9">9</option>
                <option value="10">10</option>
            </select>
            <input class="btn btn-default" type="submit" value="html出力" formaction="/admin/pin/pdf">
            <div class="input-group">
                <span class="input-group-btn">
                    <input class="btn btn-default" type="submit" value="メール送信" formaction="/admin/pin/mail">
                </span>
                <input type="text" class="form-control" placeholder="sample@sample.com" name="mail">
            </div>
        </div>
    </form>

@endsection
