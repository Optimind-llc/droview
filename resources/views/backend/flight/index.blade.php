@extends ('backend.layouts.master')

@section ('title', trans('menus.log_management'))

@section('page-header')
  <h1>
      Flight Management
      <small>フライト管理</small>
  </h1>
@endsection

@section('content')

  <div id="root"></div>
  <script src="http://localhost:3000/static/bundle.js"></script>

@endsection