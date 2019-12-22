(function() {
	document.addEventListener("DOMContentLoaded", function(event) { 
		document.findElementsByClassName(".event").click(function() {
		  window.location = $(this).find("a").attr("href"); 
		  return false;
		});
	});
})();