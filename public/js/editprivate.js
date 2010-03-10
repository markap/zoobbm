$(document).ready(function() {

	$('#animal-0').click(function() {

 		if ($(this).val() == 0) {
        	$(this).parents("form")
               .find("input:checkbox")
               .attr("checked","checked")
               .val("1");
      	} else {
        	$(this).parents("form")
               .find("input:checkbox")
               .attr("checked","")
               .val("0");
		}
	});
});

