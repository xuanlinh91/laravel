@extends("main")

@section('title')
Đăng ký
@endsection
@section('content')

<script type="text/javascript" 	src ="{{ URL::Asset('assets/js/jquery-validate/jquery.validate.js')}}"></script>
<form method="post" action="{{Asset('signup')}}" id="form-signup">
	<h2>Đăng ký</h2>
	<input type="text"		name="firstname" 				id="firstname"				placeholder="Firstname" 	class="form-control" autocomplete = "off"/>
	<input type="text"		name="lastname" 				id="lastname"				placeholder="Lastname" 		class="form-control" autocomplete = "off"/>
	<input type="text" 		name="username" 				id="username" 				placeholder="Username" 		class="form-control" autocomplete = "off"/>
	<input type="password" 	name="password" 				id="password" 				placeholder="Password"		class="form-control" autocomplete = "off"/>
	<input type="password" 	name="password_confirmation" 	id="password_confirmation" 	placeholder="Re-password" 	class="form-control" autocomplete = "off"/>
	<input type="email"		name="email" 					id="email"					placeholder="Email" 		class="form-control" autocomplete = "off"/>
   <div>
		male {{ Form::radio('gender', '1') }}
		female {{ Form::radio('gender', '2') }}
   </div>
	<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
	<button class="btn btn-lg btn-primary btn-block " id="btn-signup">Đăng kí</button>
</form>

<script type="text/javascript">
 $(document).ready(function(){
    $.validator.addMethod("username",function(value,element)
    {
    return this.optional(element) || /^[a-zA-Z0-9]{3,16}$/i.test(value); 
    },"Username không được chứa ký tự đặc biệt");

    $.validator.addMethod("email",function(value,element)
    {
    return this.optional(element) || /^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@(?:\S{1,63})$/i.test(value); 
    },"Email không được chứa ký tự đặc biệt");


//su dung jquery-validate.min.js cho validate
	$("#form-signup").validate({
		rules:{
			firstname:{
				required:true,
				minlength:1,
				maxlength:45,
			},
			lastname:{
				required:true,
				minlength:1,
				maxlength:45,
			},
			username:{
				username:true,
				required:true,
				minlength:3,
				maxlength:20,
				remote:{
					url:"{{Asset('check/check-username')}}",
					type:"POST"
				}
			},
			password:{
				required:true,
				minlength:6,
				maxlength:70,
			},
			password_confirmation:{
				equalTo:"#password"
			},
			email:{
				required:true,
				email:true,
				remote:{
					url:"{{Asset('check/check-email')}}",
					type:"POST",
					complete: function(data){
                    }
				}
			},
				gender:{
				required:true,
			},
		},
		messages:{
			firstname:{
				required:"Vui lòng nhập Firstname",
				minlength:"Username phải có 3 kí tự trở lên",
				maxlength:"Chuỗi bạn nhập quá dài",
			},
			lastname:{
				required:"Vùi lòng nhập Lastname",
				minlength:"Username phải có 3 kí tự trở lên",
				maxlength:"Chuỗi bạn nhập quá dài",
			},
			username:{
				required:"Vui lòng nhập Username",
				maxlength:"Độ dài tối đa cho Username là 20 ký tự",
				remote:"Username đã tồn tại",
			},
			password:{
				required:"Vui lòng nhập Password",
				minlength:"Password phải có 6 kí tự trở lên",
				maxlength:"Độ dài tối đa cho Password là 70 ký tự",
			},
			password_confirmation:{
				equalTo:"Mật khẩu xác nhận không đúng",
			},
			email:{
				required:"Vui lòng nhập Email",
				email:"Không dúng định dạng Email",
				remote:"Email này đã tồn tại",
			},
			gender:{
				required:"Vui lòng chọn giới tính",
			},
		}
 	});

    $("#form-signup").submit(function(e){
    	e.preventDefault();
      if($(this).valid()) $("#btn-signup").button('loading');
    });
  });
</script>
@endsection