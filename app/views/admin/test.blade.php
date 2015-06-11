@extends ("main")
@section ('title')
<title>Chém gió cùng Gutlo</title>
@endsection
@section('content')
<style type="text/css">
.tweet-box{
	border: 1px solid black;
	min-height: 200px;
}
.red {
	color: red !important;
}
.sm-emo-p{
	margin-left: -15px !important;
	margin-right: -15px !important;
}
.facebox-post{
	background: #fff;
}
</style>
<script type="text/javascript">
</script>
<div id="post-page" class="wrap stream row">
	<div class="col-md-9 left-stream-media ">
		<form id="newPost" method="post" action="{{ url('/newPost2') }}" enctype="application/x-www-form-urlencoded" autocomplete="off" class="form-horizontal col-md-12 ">
			<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
			<input type="hidden" name="img" id="image" value="">

			<div class="form-group">
			    <label for="inputTitle" class="col-sm-2 control-label">Tiêu đề</label>
			    <div class="col-sm-10">
			      <input type="text" class="form-control title" name="title" id="inputTitle" placeholder="Tiêu đề">
			      <span class="count_text">111</span>
			    </div>
			</div>
			<div class="form-group">
		   		<label for="inputLink" class="col-sm-2 control-label">Đường dẫn liên kết</label>
			    <div class="col-sm-10">
			      <input type="text" class="form-control" name="inputLink" id="inputLink" placeholder="http://www.example.com/">
			    </div>
			</div>
		  	<div class="form-group">
		   		<label for="inputCate" class="col-sm-2 control-label">Chuyên mục</label>
			    <div class="col-sm-10">
			      <select class="form-control" name="category" id="inputCate"  >
			      	@foreach($categories as $item )
			      		<option value="{{$item->id}}">{{$item->name}}</option>
			      	@endforeach
			      </select>
			    </div>

			</div>
			<div class="form-group">
			    <label for="inputContent" class="col-sm-2 control-label">Nội Dung</label>
			    <div class="col-sm-10">
					<div id="port_content" class="form-control contenteditable" contenteditable="false" data-content="false"></div>
					<input class="typeahead" type="hidden">
					<textarea class="content hide" name="content" value=""  rows="4" cols="50"></textarea>
					<span>	+ Bạn nên sử dụng trend trong bài viết để bổ trợ cho chuyên mục vd #HtcOne<br>
							+ trend không được vượt quá 18 ký tự
					</span>
					<span class="count_text">500</span>
				</div>
			</div>
		    @if ($errors->first('title'))
		    <span class="help-block">{{ $errors->first('title') }}</span>
		    @endif
		    @if (isset($error))
		    <span class="help-block">{{ $error }}</span>
		    @endif
		    @if(Session::has('msg')) 
			  <span class="help-block">{{ Session::get('msg') }}</span>
			@endif
		    @if ($errors->first('content'))
		    <span class="help-block">{{ $errors->first('content') }}</span>
		    @endif
		    @if ($errors->first('categories'))
		    <span class="help-block">{{ $errors->first('categories') }}</span>
		    @endif
		    <div class="form-group hide">
		   		<label for="inputCaptcha" class="col-sm-2 control-label">Captcha</label>
			    <div class="col-sm-10">
			      <input type="text" class="form-control" id="inputCaptcha">
			    </div>
			</div>
			<div class="form-group">
		    	<div class="col-sm-offset-2 col-sm-10">
		      		<button type="submit" id="submit-newPost" data-loading-text="Loading..."class="btn btn-default submit-post" autocomplete="off">Đăng</button>
		    		<a id="" class="btn btn-default pull-left button-margin emo-cmm add-emoticon" autocomplete="off">
		      			<span class="add-emoticons icon-emo-sunglasses">
		      				<span>Emo</span>
		      			</span>
		      		</a>
		    	</div>
		  	</div>
		  	<div class="form-group submit-emo  sm-emo-p hide">
		  		<div class="col-sm-10 pull-right">
		  			<div class="facebox facebox-post">
		  				<div class="f-header col-md-12">
								<a class="group-emo col-md-1 selected" data-group="Hot">Hot</a>
		  				
		    				<div class="clearfix"></div>
		  				</div>
		  				<div class="f-content col-md-12"></div>
			    		<div class="clearfix"></div>
		  			</div>
		  		</div>
		  	</div>
		</form>
	</div>
	@include('templates/navRight2')
</div>
<script type="text/javascript">
	$(document).ready(function(){
		$(document).on('change','#inputLink',function(e) {
			$.ajax({
		        type: "POST",
		        url: url+'get-data-link',
		        data: {link:$(this).val()}
			    }).done(function( data ) {
			        if(data.error == 'false'){
			        	$('#image').val(data.data.image);
			        }else {
			        }
		    });
		});
	});
</script>

@stop