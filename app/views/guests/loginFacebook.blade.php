@extends("main")
<title>Đăng Nhập</title>
@section('title')
Đăng ký bằng facebook
@endsection
@section('content')
<div id="login-page" class="col-md-6 share-after-post-f">
	{{ HTML::linkRoute('loginFacebook','Đăng nhập bằng Facebook',array(),array('class'=>'share-facebook share ')) }}
</div>
@endsection
