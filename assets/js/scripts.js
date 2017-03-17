jQuery(function($){
	var i = 0;
	$('.fieldset-job_location').after('<p class="addLocation"> Add another location</p>');
	$('body').on('click', '.addLocation', function(){
		$('.fieldset-job_location').append('<div class="field"><input type="text" class="input-text" name="additionallocation['+i+'][name]" id="job_location'+ i +'"></div>');
		wpjmel.input = '#job_location' + i;
		wpjmel.lat_input = 'additionallocation['+i+'][geo_lat]';
		wpjmel.lng_input = 'additionallocation['+i+'][geo_lng]';
		$( '#job_location' + i ).mapify(wpjmel);
		i++;
	});
});