const $ = jQuery;

$(document).ready(function(){
	$("#harvester-cache-refresh").click(function(){
		$(this).attr("disabled", true);
		$.ajax({
			type: 'POST',
			url: ajax_obj.path + 'api.php',
			beforeSend: function() {
				$('.placeholder').html('Writing presentation data to cache file... ');
			},
			success: function() {
				$('.placeholder').append(' Done.');
				$("#harvester-cache-refresh").attr("disabled", false);
			},
			error: function(xhr) {
				$("#harvester-cache-refresh").attr("disabled", false);
				$('.placeholder').html('Error retrieving data: ' + xhr.statusText + xhr.responseText);
			}
		});
	});
});
