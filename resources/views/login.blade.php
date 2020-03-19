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
                <fb:login-button
                        scope="public_profile,email"
                        onlogin="checkLoginState();">
                </fb:login-button>
            </div>
        </form>
    </div>
    <div style="display: none" id="facebookRegister">{{ route('facebookRegisterAction') }}</div>
    <script>
        window.fbAsyncInit = function() {
            FB.init({
                appId      : 639653423496399,
                cookie     : true,
                xfbml      : true,
                version    : 'v6.0'
            });
            FB.AppEvents.logPageView();
        };

        (function(d, s, id){
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) {return;}
            js = d.createElement(s); js.id = id;
            js.src = "https://connect.facebook.net/zh_TW/sdk.js";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));

        function checkLoginState() {
            FB.getLoginStatus(function(response) {
                console.log(response);
                statusChangeCallback(response);
            });
        }

        let statusChangeCallback = function(response) {
            if (response.status === 'connected') {
                FB.api('/me?fields=id,name,email', function (response) {
                    if (response && !response.error) {
                        const data = {
                            fbid: response.id,
                            name: response.name,
                            email: response.email,
                        };
                        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
                        $.post($('#facebookRegister').html(), data, function(response) {
                            if (!response.result) {
                                alert(response.message);
                            }
                        }, 'json');
                    }}
                );
            }
        }
    </script>
@endsection