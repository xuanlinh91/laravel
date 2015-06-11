function get_data_report_of_user(username) {
	$.ajax({
        type: "POST",
        url: url+"/admin/get_data_report_user",
        data: { username:username }
    }).done(function( data ) {
        console.log(data);
        if(data.error != 'true'){
	        $('ul.left-stream').remove();
	        var ul = profile_report(data.data.data_user);
	        $('div.left-stream:not(.col-md-12)').append(ul);

	        var ul = document.createElement('ul');
			ul.className = "left-stream"
				var li = document.createElement('li');
				li.className = 'col-md-12 header-profile-list';
					var b = document.createElement('b');
						var span = document.createElement('span');
						span.className = 'pull-left col-md-6';
							var i = document.createElement('i');
							i.className = 'icon-user';
						span.appendChild(i);
						span.appendChild(document.createTextNode("baned"));
					b.appendChild(span);
				li.appendChild(b);
			ul.appendChild(li);

			var length_data_bane = data.data.data_bane.length;
			for (var i = 0; i < length_data_bane; i++) {
				ul = data_bane(ul,data.data.data_bane[i]);
			}
			$('div.left-stream:not(.col-md-12)').append(ul);

			var ul = document.createElement('ul');
			ul.className = "left-stream"
				var li = document.createElement('li');
				li.className = 'col-md-12 header-profile-list';
					var b = document.createElement('b');
						var span = document.createElement('span');
						span.className = 'pull-left col-md-6';
							var i = document.createElement('i');
							i.className = 'icon-user';
						span.appendChild(i);
						span.appendChild(document.createTextNode("block content"));
					b.appendChild(span);
				li.appendChild(b);
			ul.appendChild(li);
			var length_data_blockContent = data.data.data_blockContent.length;
			for (var i = 0; i < length_data_blockContent; i++) {
				ul = data_bane(ul,data.data.data_blockContent[i]);
			}
			$('div.left-stream:not(.col-md-12)').append(ul);
		}


    });
}