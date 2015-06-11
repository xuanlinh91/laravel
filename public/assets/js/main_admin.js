$(document).ready(function() {

	// submit from bane
	$(document).on('submit','#bane_User',function(e){
		e.preventDefault();
		var username = $('#inputKey').val();
		var _url = $(this).attr('action');
		if(username == '' || username == ' '){
			return ;
		}
		var reason = $('#reason').val();
		var end_time = new Date($('#end_time').val());
		end_time = end_time.getTime()/1000;
		$.ajax({
	        type: "POST",
	        url: _url,
	        data: { username:username,reason:reason,end_time:end_time }
	    }).done(function( data ) {
	        if(data.error == 'false'){
	        	// reset from
	        	$('ul.left-stream').remove();
	        	$(':input','#bane_User')
				  .removeAttr('checked')
				  .removeAttr('selected')
				  .not(':button, :submit, :reset, :hidden, :radio, :checkbox')
				  .val('');
				$('.help-block').remove();
	        }else {
	        	var span_error = document.createElement('span');
     			span_error.className = 'help-block';
     			span_error.appendChild(document.createTextNode(data.msg));
     			$('#bane_User').append(span_error);
	        }
	    });
	});

	$('.typeahead').on('typeahead:selected', function(obj, datum, name) {
	        var id = datum.id;
	        var username = datum.username;
	        get_data_report_of_user(username);
	});

	$('#end_time').datetimepicker();

	var username = new Bloodhound({
	  datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
	  queryTokenizer: Bloodhound.tokenizers.whitespace,
	  remote: {
	    url: url+'admin/searchUserName/%QUERY',
	    wildcard: '%QUERY'
	  }
	});
	$('.typeahead').typeahead(null, {
	  	name: 'username',
	  	display: 'username',
	  	source: username,
	  	templates: {
        	empty : function(data) {
	          return [
	            ''
	          ].join('\n');
	        },
	        footer : function(data){
	          if(data.isEmpty == false){
	            return [
	              ''
	            ].join('\n');
	          }
	        },
	        suggestion: function(data){
	            return '<div class="value"><strong>'+ data.username + '</strong> '
	                          +'</div>' ;
	        }
	    }
	});
});