(function() {
	if (window.NodeList && !NodeList.prototype.forEach) {
	    NodeList.prototype.forEach = function (callback, thisArg) {
	        thisArg = thisArg || window;
	        for (var i = 0; i < this.length; i++) {
	            callback.call(thisArg, this[i], i, this);
	        }
	    };
	}

	document.addEventListener("DOMContentLoaded", function(event) { 
		let events = document.querySelectorAll(".event");
		events.forEach(function(item) {
			var self=item;
			item.addEventListener('click', function(target) {
				window.location = self.querySelector("a").getAttribute("href"); 
		  	return false;
			});
		});
	});
})();