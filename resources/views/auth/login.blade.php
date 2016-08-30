<!DOCTYPE HTML>
<html>
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title></title>
<link rel="stylesheet" type="text/css" href="{{ asset('/css/login.css') }}"> 
<script src="{{ asset('/js/jquery.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('/js/login.js') }}" type="text/javascript"></script>
<script src="{{ asset('/js/jquery.mousewheel.min.js') }}"  type="text/javascript"></script>
<script type="text/javascript">

</script> 
</head>
<body>
<div class="top section"></div>
<!-- container -->
<section id="section1" class="section section1">
  <article class="sectionWrapper section1Wrapper fadeInDown">
      <div class="container">
      @if (count($errors) > 0)
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif 
    <div class="login">
        <div class="login-first">
            <div class="login-font1">
                <font style="margin-left:10px; color:#232323">
                    <a class="navbar-brand" href="{{url('/')}}">{{ Cache::get('settings:site_name') }}</a>
                    <a class="navbar-brand" href="{{url('/')}}">{{ Cache::get('settings:site_sub_name') }}</a>
                </font>
            </div>
            <div class="login-font">统一身份认证</div>
            <div class="login-input">
                <form  role="form" method="POST" action="{{ url('/auth/login') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <font class="font">账号:</font>
                        <input type="text" class="userName"  name="username" value="{{ old('username') }}" placeholder="用户名/邮箱/手机" style="margin-top:2%;">
                    <font class="font">密码:</font>
                        <input type="password" class="password"  name="password" placeholder="密码">
                     @if ($loginFailed)
                        <script src="//captcha.luosimao.com/static/js/api.js"></script>
                        <div class="form-group">
                            <label class="col-md-2 control-label">验证</label>
                            <div class="col-md-10">
                                <div class="l-captcha" data-site-key="{{ env('CAPTCHA_SITE_KEY') }}" data-width="100%"></div>
                            </div>
                        </div>
                        @endif
                            <input type="checkbox" class="checkbox">
                            <label>
                            <font class="rememberPassword" name="remember">记住密码</font></label>
                    <p class="logIn">
                        <button type="submit" class="button">登录</button>
                        <font class="weChat"><a href="{{ url('/auth/register') }}">注册</a></font>
                        <font class="weChat"><a href="{{ url('/password/phone') }}">忘记密码</a></font>
                        <font class="weChat">
                        <img src="{{ asset('/images/icon24_appwx_logo.png') }}"
                                    onclick="javascript:window.location.href='<?php echo
                                    "https://open.weixin.qq.com/connect/qrconnect?appid=" . env('WECHAT_APPID') .
                                    "&redirect_uri=" . urlencode(url('oauth/wechatCallback?goto=/auth/login')) .
                                    "&response_type=code&scope=snsapi_login&state=" . md5(time()) .
                                    "#wechat_redirect"; ?>'">
                        </font>
                    </p>
                </form>
            </div>

        </div>
    </div>
</div>
 <div class="arrow_down"> <a href="#section6"> <img src="/images/arrow_down.png" width="42px" height="24px" alt="arrow_down"> </a> </div>
  </article>
</section>
<section id="section6" class="section section6">
  <article class="sectionWrapper section6Wrapper">
  <div class="">
        <p class="details-title">全球领先、安全、稳定的云计算产品</p></div>
<div class="flash4">
    <ul>
        <li class="first" style="width: 280px; height:405px; overflow: hidden;">
            <div class="imgTop">
                <div style="width:278px;height:405px;" alt="" class="tm"></div>
            </div>
            <div class="imgBot">
                <a href=""><p class="bt_1">全球领先</p><p class="bt_2" ></p></a>
            </div>
        </li>
        <li style="width: 260px; overflow: hidden; height:350px"">
            <div class="imgTop">
                <div style="width:278px;height:405px;" alt="" class="tm"></div>
            </div>
            <div class="imgBot">
                <a href=""><p class="bt_1">全球领先</p><p class="bt_2"></p></a>
            </div>
        </li>
        <li style="width: 260px; overflow: hidden; height:350px"">
            <div class="imgTop">
                <div style="width:278px;height:405px;" alt="" class="tm"></div>
            </div>
            <div class="imgBot">
                <a href=""><p class="bt_1">全球领先</p><p class="bt_2"></p></a>
            </div>
        </li>
        <li style="width: 260px; overflow: hidden; height:350px"">
            <div class="imgTop">
                <div style="width:278px;height:405px;" alt="" class="tm"></div>
            </div>
            <div class="imgBot">
                <a href=""><p class="bt_1">全球领先</p><p class="bt_2"></p></a>
            </div>
        </li>
    </ul>   
</div>
  </article>
</section>

</body>
</html>