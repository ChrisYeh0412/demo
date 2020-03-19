@extends('layouts.layout')
@section('content')
    <div style="text-align: center">
        <h1 style="font-size: 24px;">註冊</h1>
        <br />
        <form action="{{ route('registerAction') }}" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            @if (!session('result'))
                <div style="color: #ff0000;">{!! session('message') !!}</div>
            @endif
            <div>姓名：<input type="text" id="name" name="name"></div>
            <div>帳號：<input type="text" id="email" name="email"></div>
            <div>密碼：<input type="password" id="password" name="password"></div>
            <div>確認密碼：<input type="password" id="password_confirmation" name="password_confirmation"></div>
            <div>
                <input type="submit" value="送出" style="margin-right: 5px;">
                <a href="{{ route('login') }}">登入</a>
            </div>
        </form>
    </div>
@endsection