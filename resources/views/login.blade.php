@extends('layouts.layout')
@section('content')
    <div style="text-align: center">
        <h1 style="font-size: 24px;">登入</h1>
        <br />
        <form action="{{ route('loginAction') }}" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            @if (!session('result'))
                <div style="color: #ff0000;">{!! session('message') !!}</div>
            @endif
            <div>帳號：<input type="text" id="email" name="email"></div>
            <div>密碼：<input type="password" id="password" name="password"></div>
            <div>
                <input type="submit" value="送出" style="margin-right: 5px;">
                <a href="{{ route('register') }}">註冊</a>
            </div>
        </form>
    </div>
@endsection