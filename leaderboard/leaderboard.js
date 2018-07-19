window.onload = function() {

	 progressBars = document.querySelectorAll('.progress-bar');

	for(var i = 0; i<=progressBars.length; i++) {

		progressBars[i].style.transition = 'width 1s ease-in';
		var t = progressBars[i].dataset.value;

		progressBars[i].style.width = t+"%";
	}

	


}





