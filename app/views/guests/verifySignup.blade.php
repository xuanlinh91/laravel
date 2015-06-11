@extends ("main")
@section ('title')
<title>{{Auth::user()->nickname}}</title>
@endsection
@section('content')
<center>
	<h2>Email bạn sử dụng là: {{HTML::linkRoute('verify-signup',Auth::user()->email)}} Vui lòng kiểm tra Email veryfi !!!</h2>
	<h2>	Nhập code: </h2>
	<br />	{{ HTML::linkRoute('/','Để Sau') }}
</center>
@endsection
