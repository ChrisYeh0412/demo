@extends('layouts.layout')
@section('content')
<form action="{{ route('loginAction') }}" method="post" enctype="multipart/form-data">
    {{ csrf_field() }}
    @if (session('result') == 0)
        <div>{{ session('message') }}</div>
    @endif
    <div>帳號：<input type="text" id="email" name="email"></div>
    <div>密碼：<input type="password" id="password" name="password"></div>
    <input type="submit" value="送出">
</form>
@endsection