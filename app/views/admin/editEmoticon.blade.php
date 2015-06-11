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
.row-emo{
	padding-top: 5px;
	padding-bottom: 5px;
	border-bottom: 1px solid #ccc;
}
.row-emo>span{
	border-left: 1px solid	#ccc;
}
.emoticons-admin-show{
	width: 29px;
	height: 29px;
}
.group-emo{
	margin-right: 5px;
}
</style>
<div id="post-page" class="wrap stream row">
	<div id="" class="media-max-min col-md-12 middle-stream middle-stream-media">
	<div class="trendsbox-wrapper">
		<div class="top-box-page">
			<div class="cate-top"><span >Emoticons !!!</span></div>
			<div class="divider"></div>
			<div class="col-md-12 row-emo">
				<a href="" class="btn btn-default col-md-1 group-emo" data-group="All">All</a>
				@foreach($group_emoticon as $group)
					<a href="" class="btn btn-default col-md-1 group-emo" data-group="{{$group->emo_group}}">{{$group->emo_group}}</a>
				@endforeach
			<div class="clearfix"></div>
			</div>
			<div class="col-md-12 row-emo">
				<span class="id_emoticon col-md-1"><b>ID</b></span>
				<span class="group col-md-1"><b>Group</b></span>
				<span class="char col-md-2"><b>Char</b></span>
				<span class="emoticon col-md-2"><b>Tên ảnh</b></span>
				<span class="url col-md-4"><b>URL</b></span>
				<span class=" col-md-1"><b>Emo</b></span>
				<span class=" col-md-1"><b>Action</b></span>
				<div class="clearfix"></div>
			</div>
			<div class="data-emo">
			@foreach($data as $item)
				<div class="col-md-12 row-emo" data-id="{{ $item->id}}">
					<span class="id_emoticon col-md-1">{{ $item->id}}</span>
					<span class="group col-md-1">@if (empty($item->emo_group)) Null @else {{ $item->emo_group}} @endif</span>
					<span class="char col-md-2">{{ $item->char}}</span>
					<span class="emoticon col-md-2">{{ $item->emoticon}}</span>
					<span class="url col-md-4">{{ $item->url}}</span>
					<span class=" col-md-1"><img class="emoticons-admin-show" src="/{{ $item->url}}{{ $item->emo_group}}/{{ $item->emoticon}}"></span>
					<span class=" col-md-1">
						<a href="#" onclick="javascript:void(0)" data-group="{{ $item->emo_group}}" data-id="{{ $item->id}}" data-char="{{ $item->char}}" data-emoticon="{{ $item->emoticon}}" data-url="{{ $item->url}}" class="edit">edit</a>
					</span>
					<div class="clearfix"></div>
				</div>
			@endforeach
			</div>
		</div>
	</div>
</div>
	<div class="col-md-9 left-stream-media ">
		<form id="newEmoticon" method="post" action="{{ url('/admin/emoticon') }}" enctype="application/x-www-form-urlencoded" autocomplete="off" class="form-horizontal col-md-12 ">
			<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
			<input type="hidden" name="id" value="" id="id">
			<div class="form-group">
			    <label for="char" class="col-sm-2 control-label">Char</label>
			    <div class="col-sm-10">
			      <input type="text" class="form-control title" name="char" id="char" placeholder="Char là ký tự mà ta quy định cho emoticons">
			    </div>
			</div>
			<div class="form-group">
		   		<label for="emoticon" class="col-sm-2 control-label">Tên ảnh</label>
			    <div class="col-sm-10">
			      <input type="text" class="form-control"name="emoticon" id="emoticon" placeholder="emoticon.gif">
			    </div>
			</div>
		  	<div class="form-group">
		   		<label for="group" class="col-sm-2 control-label">Group</label>
			    <div class="col-sm-10">
			      <input type="text" class="form-control"name="group" id="group" placeholder="Group là tên folder của mỗi 1 bộ emo trong thư mục emoticons">
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
		      		<button type="submit" id="submit-newEmoticon" data-loading-text="Loading..."class="btn btn-default submit-post" autocomplete="off">Đăng</button>
		      		<button type="button" id="submit-newEmoticon" data-loading-text="Loading..."class="btn btn-default clear-post" autocomplete="off">clear</button>
		    	</div>
		  	</div>
		</form>
	</div>
	<ul class="col-md-3 right-stream right-stream-media display-item-col pull-right">
		@include('templates/top')
		@include('templates/trendRight')
		@include('templates/analytics')
		@include('templates/footer')
	</ul>
</div>
<script type="text/javascript">
function create_element () {

}
$(document).ready(function(){
	$(document).on('click','.edit',function(e){
		e.preventDefault();
		$('#char').val($(this).attr('data-char'));
    	$('#emoticon').val($(this).attr('data-emoticon'));
    	$('#group').val($(this).attr('data-group'));
    	$('#id').val($(this).attr('data-id'));
	});
	$(document).on('click','.clear-post',function(e){
		$('#char').val('');
    	$('#emoticon').val('');
    	$('#group').val('');
    	$('#id').val('');
	});

	$(document).on('click','.group-emo',function(e) {
	   e.preventDefault();
	   $('.data-emo').html('');
	   var group = $(this).attr('data-group');
	   var url = 'loadEmoticon';
	   $.ajax({
            type: "POST",
            url: url,
            data: { group:group }
        }).done(function( data ) {
            if(data.error == 'false'){
            	var length_data = data.data.length;
            	for (var i = 0; i < length_data; i++) {
            		var div = document.createElement('div');
					var div_row_emo = document.createElement('div');
						div_row_emo.className = 'col-md-12 row-emo';
						div_row_emo.setAttribute('data-id',data.data[i].id);
	            			var span = document.createElement('span');
							span.className = 'id_emoticon col-md-1';
							span.appendChild(document.createTextNode(data.data[i].id));
						div_row_emo.appendChild(span);
	            			var span = document.createElement('span');
							span.className = 'group col-md-1';
							span.appendChild(document.createTextNode(data.data[i].emo_group));
						div_row_emo.appendChild(span);
	            			var span = document.createElement('span');
							span.className = 'char col-md-2';
							span.appendChild(document.createTextNode(data.data[i].char));
						div_row_emo.appendChild(span);
	            			var span = document.createElement('span');
							span.className = 'emoticon col-md-2';
							span.appendChild(document.createTextNode(data.data[i].emoticon));
						div_row_emo.appendChild(span);
	            			var span = document.createElement('span');
							span.className = 'url col-md-4';
							span.appendChild(document.createTextNode(data.data[i].url));
						div_row_emo.appendChild(span);
	            			var span = document.createElement('span');
							span.className = 'col-md-1';
								var img  = document.createElement('img');
								img.className = 'emoticons-admin-show';
								img.src = '../'+data.data[i].url + data.data[i].emo_group +'/'+data.data[i].emoticon;
							span.appendChild(img);
						div_row_emo.appendChild(span);
	            			var span = document.createElement('span');
							span.className = 'col-md-1';
								var a  = document.createElement('a');
								a.className = 'edit';
								a.href = '#';
								a.setAttribute('onclick','javascript:void(0)');
								a.setAttribute('data-group',data.data[i].emo_group);
								a.setAttribute('data-id',data.data[i].id);
								a.setAttribute('data-char',data.data[i].char);
								a.setAttribute('data-emoticon',data.data[i].emoticon);
								a.setAttribute('data-url',data.data[i].url);
								a.appendChild(document.createTextNode('edit'));
							span.appendChild(a);
						div_row_emo.appendChild(span);
							div.className = 'clearfix';
						div_row_emo.appendChild(div);
	            	$('.data-emo').append(div_row_emo);
            	};
            	$('#char').val('');
            	$('#emoticon').val('');
            	$('#group').val('');
            	$('#id').val('');
            }else {
            	alert(data.msg);
            }
        });
	});



	$('#newEmoticon').submit(function(e) {
	   e.preventDefault();
	   var url = $(this).attr('action');
	   var _char = $('#char').val();
	   var emoticon = $('#emoticon').val();
	   var group = $('#group').val();
	   var id = $('#id').val();
	   $.ajax({
            type: "POST",
            url: url,
            data: { id:id,_char:_char,emoticon:emoticon,group:group }
        }).done(function( data ) {
            if(data.error == 'false'){
            	console.log(data.data);
            	if(id != '' && id != ' '){
            		$('.row-emo[data-id="'+id+'"]').remove();
            	}
				var div = document.createElement('div');
				var div_row_emo = document.createElement('div');
					div_row_emo.className = 'col-md-12 row-emo';
					div_row_emo.setAttribute('data-id',data.data.id);
            			var span = document.createElement('span');
						span.className = 'id_emoticon col-md-1';
						span.appendChild(document.createTextNode(data.data.id));
					div_row_emo.appendChild(span);
            			var span = document.createElement('span');
						span.className = 'group col-md-1';
						span.appendChild(document.createTextNode(data.data.emo_group));
					div_row_emo.appendChild(span);
            			var span = document.createElement('span');
						span.className = 'char col-md-2';
						span.appendChild(document.createTextNode(data.data.char));
					div_row_emo.appendChild(span);
            			var span = document.createElement('span');
						span.className = 'emoticon col-md-2';
						span.appendChild(document.createTextNode(data.data.emoticon));
					div_row_emo.appendChild(span);
            			var span = document.createElement('span');
						span.className = 'url col-md-4';
						span.appendChild(document.createTextNode(data.data.url));
					div_row_emo.appendChild(span);
            			var span = document.createElement('span');
						span.className = 'col-md-1';
							var img  = document.createElement('img');
							img.className = 'emoticons-admin-show';
							img.src = '../'+data.data.url + data.data.emo_group +'/'+data.data.emoticon;
						span.appendChild(img);
					div_row_emo.appendChild(span);
            			var span = document.createElement('span');
						span.className = 'col-md-1';
							var a  = document.createElement('a');
							a.className = 'edit';
							a.href = '#';
							a.setAttribute('onclick','javascript:void(0)');
							a.setAttribute('data-group',data.data.emo_group);
							a.setAttribute('data-id',data.data.id);
							a.setAttribute('data-char',data.data.char);
							a.setAttribute('data-emoticon',data.data.emoticon);
							a.setAttribute('data-url',data.data.url);
							a.appendChild(document.createTextNode('edit'));
						span.appendChild(a);
					div_row_emo.appendChild(span);
						div.className = 'clearfix';
					div_row_emo.appendChild(div);
            	$('.data-emo').append(div_row_emo);
            	console.log($('.data-emo'));
            	$('#char').val('');
            	$('#emoticon').val('');
            	$('#group').val('');
            	$('#id').val('');
            }else {
            	alert(data.msg);
            }
        });
    });
});
</script>

@stop