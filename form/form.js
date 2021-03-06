Element.prototype.remove = function() {
    this.parentElement.removeChild(this);
}
NodeList.prototype.remove = HTMLCollection.prototype.remove = function() {
    for(var i = this.length - 1; i >= 0; i--) {
        if(this[i] && this[i].parentElement) {
            this[i].parentElement.removeChild(this[i]);
        }
    }
}

//Disable Enter Key
jQuery("#msform").keypress(function(e) {
  //Enter key
  if (e.which == 13) {
    return false;
  }
});

jQuery(document).ready(function($) {


		//Validation
		$("#zip").on("keyup", function() {
			$("#dhg-validate").prop("disabled", false);
				if( $("#zip").val().length < 5) {
				$("#dhg-validate").prop("disabled", true);
			}
		});

		$("body").on("keyup", function() {
			$("#dhg-validate-2").prop("disabled", false);
				if( $("#address").val().length < 5) {
				$("#dhg-validate-2").prop("disabled", true);
			}
		});

		//jQuery time
		var current_fs, next_fs, previous_fs; //fieldsets
		var left, opacity, scale; //fieldset properties which we will animate
		var animating; //flag to prevent quick multi-click glitches

		$(document).on("click", ".next", function(){
			if(animating) return false;
			animating = true;
			
			current_fs = $(this).parent();
			next_fs = $(this).parent().next();
			
			
			//show the next fieldset
			next_fs.show(); 
			//hide the current fieldset with style
			current_fs.animate({opacity: 0}, {
				step: function(now, mx) {
					//as the opacity of current_fs reduces to 0 - stored in "now"
					//1. scale current_fs down to 80%
					scale = 1 - (1 - now) * 0.2;
					//2. bring next_fs from the right(50%)
					left = (now * 50)+"%";
					//3. increase opacity of next_fs to 1 as it moves in
					opacity = 1 - now;
					current_fs.css({
		        'transform': 'scale('+scale+')',
		        'position': 'absolute'
		      });
					next_fs.css({'left': left, 'opacity': opacity});
				}, 
				duration: 800, 
				complete: function(){
					current_fs.hide();
					animating = false;
				}, 
				//this comes from the custom easing plugin
				easing: 'easeInOutBack'
			});
		});

		$(document).on("click", ".next-nested", function(){
			if(animating) return false;
			animating = true;
			
			current_fs = $(this).parent().parent();
			next_fs = $(this).parent().parent().next();
			
			//show the next fieldset
			next_fs.show(); 
			//hide the current fieldset with style
			current_fs.animate({opacity: 0}, {
				step: function(now, mx) {
					//as the opacity of current_fs reduces to 0 - stored in "now"
					//1. scale current_fs down to 80%
					scale = 1 - (1 - now) * 0.2;
					//2. bring next_fs from the right(50%)
					left = (now * 50)+"%";
					//3. increase opacity of next_fs to 1 as it moves in
					opacity = 1 - now;
					current_fs.css({
		        'transform': 'scale('+scale+')',
		        'position': 'absolute'
		      });
					next_fs.css({'left': left, 'opacity': opacity});
				}, 
				duration: 800, 
				complete: function(){
					current_fs.hide();
					animating = false;
				}, 
				//this comes from the custom easing plugin
				easing: 'easeInOutBack'
			});
		});

		$(document).on("click", ".previous", function(){
			if(animating) return false;
			animating = true;
			
			current_fs = $(this).parent();
			previous_fs = $(this).parent().prev();
			
			//show the previous fieldset
			previous_fs.show(); 
			//hide the current fieldset with style
			current_fs.animate({opacity: 0}, {
				step: function(now, mx) {
					//as the opacity of current_fs reduces to 0 - stored in "now"
					//1. scale previous_fs from 80% to 100%
					scale = 0.8 + (1 - now) * 0.2;
					//2. take current_fs to the right(50%) - from 0%
					left = ((1-now) * 50)+"%";
					//3. increase opacity of previous_fs to 1 as it moves in
					opacity = 1 - now;
					current_fs.css({'left': left});
					previous_fs.css({'transform': 'scale('+scale+')', 'opacity': opacity});
				}, 
				duration: 800, 
				complete: function(){
					current_fs.hide();
					animating = false;
				}, 
				//this comes from the custom easing plugin
				easing: 'easeInOutBack'
			});
		});

		/*Zip and City Lookup*/
		$(document).ready(function(){
			//when the user clicks off of the zip field:
			$('#zip').keyup(function(){
			if($(this).val().length == 5){
				var zip = $(this).val();
				var city = '';
				var state = '';

				//make a request to the google geocode api
				$.getJSON('http://maps.googleapis.com/maps/api/geocode/json?address='+zip)
				.success(function(response){

				//find the city and state
				var address_components = response.results[0].address_components;
				$.each(address_components, function(index, component){
					var types = component.types;
					$.each(types, function(index, type){
					if(type == 'locality') {
						city = component.long_name;
					}
					if(type == 'administrative_area_level_1') {
						state = component.short_name;
					}
					});
				});
				//pre-fill the city and state
				var cities = response.results[0].postcode_localities;
				if(cities) {
					//turn city into a dropdown if necessary
					var $select = $(document.createElement('select'));
					console.log(cities);
					$.each(cities, function(index, locality){
						var $option = $(document.createElement('option'));
						$option.html(locality);
						$option.attr('value',locality);
						if(city == locality) {
							$option.attr('selected','selected');
						}
						$select.append($option);
					});
					$select.attr('id','city');
					$('#city_wrap').html($select);
				} else {
				$('#city').val(city);
				}
				$('#state').val(state);
				});
			}
			});
		});

});

/*
	Limit Checkboxes
*/
	function smi_selectOnlyThis(id) {
		for (var i = 1;i <= 2; i++){
			document.getElementById("cb" + i).checked = false;
		}
		document.getElementById(id).checked = true;
	}

	function smi_selectOnlyThisProperty(id, parent) {
		//var inputCount = document.getElementById('frm-propertyvalue').getElementsByTagName('input').length;
		var inputCount = document.getElementById(parent).getElementsByTagName('input').length;
		console.log( "info" + parent ); 
		for (var i = 1;i <= inputCount; i++){
			document.getElementById(parent+"-box" + i).checked = false;
		}
		document.getElementById(id).checked = true;
	}

/*
	Dynamically load form elements
*/
	//If buying
	function smi_isbuying(){
		document.getElementById("fs-property").innerHTML = buying_content + '<hr>' + prev_button;
		document.getElementById("location-title").innerHTML = "Where are you looking to buy?";
		document.getElementById("fs-addressmort").innerHTML = mortage_content + "<hr>" + prev_button;
		var c = document.getElementById("fs-property-value").childNodes;
		c[1].innerHTML = "Value of Property You're Buying";
		
	}
	//If Selling
	function smi_isselling(){
		document.getElementById("fs-property").innerHTML = selling_content + '<!--<input type="button" name="previous" class="previous action-button" value="Previous" />-->';
		document.getElementById("location-title").innerHTML = "Where is your property located?";
		document.getElementById("fs-addressmort").innerHTML = '<h2 class="fs-title">What street address?</h2>' + "<input class='required form_address' placeholder='Address' type='text' id='address' name='address'/>" + "<hr>" + prev_button + '<input data-for="address" id="dhg-validate-2" type="button" name="next" class="next action-button" value="Next" disabled/>';
		var c = document.getElementById("fs-property-value").childNodes;
		c[1].innerHTML = "Value of Property You're Selling";
	}