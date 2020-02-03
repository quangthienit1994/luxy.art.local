(function() {
  
	var list_array = new Array();
list_array.push("Red City");
list_array.push("Blue City");
list_array.push("United Kingdom");
list_array.push("Australia");
list_array.push("United Sates");
list_array.push("Finland");
list_array.push("Bulgeriya");
list_array.push("Hungary");
list_array.push("Istambul");
	
	$('#tag-input-33').tokenfield({
		autocomplete: {
			source: list_array,
			delay: 100
		},
		showAutocompleteOnFocus: true
	});
	

  $("#tokenlist-loaded").tokenfield();

  $("submit").on('click', function(e) {
    e.preventDefault();
    return $("#tokenlist-1").val($("#tag-input-33").tokenfield('getTokensList'));
  });

}).call(this);