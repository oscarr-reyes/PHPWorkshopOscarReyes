$(document).ready(function(){
	var loader              = $("#loader-container");
	var mainContent         = $("#content");
	var contentHeader       = $("#content-header");
	var articleHeaderList   = $("#article-header-list");
	var articleHeaderDetail = $("#article-header-detail");
	var articleList         = $("#article-list");
	var articleDetail       = $("#article-detail");
	var imageSelector       = $("#input-image-selector");
	var inputImage          = $("input[type='file'");

	hideLoader(5000)
		.then(function(){
			mainContent.removeClass("d-none");
		});

	/*
	 * Listen for header click events
	 */
	contentHeader.on("click", "a", function(){
		if(this.id == "new-article-button"){
			displayTo("detail");
		}

		if(this.id == "back-to-list-button"){
			displayTo("list");
		}

		if(this.id == "save-button"){
			console.log(getFormData());
		}
	});

	/*
	 * Trigger file selector when image selector is clicked
	 */
	imageSelector.on("click", function(){
		var siblings = $(this).siblings();
		var input = $(siblings[0]);

		input.click();
	});

	/*
	 * Display a preview when an image is selected
	 */
	inputImage.on("change", function(){
		setPreview();
	});


	function hideLoader(ms){
		return new Promise(function(resolve){
			setTimeout(function(){
				loader.addClass("d-none");

				resolve();
			}, ms);
		});
	}

	/**
	 * Transition to a view state
	 * 
	 * @param  {String} state The name of the view state
	 */
	function displayTo(state){
		if(state == "detail"){
			articleHeaderList.addClass("d-none");
			articleHeaderDetail.removeClass("d-none");

			articleList.addClass("d-none");
			articleDetail.removeClass("d-none");
		}

		if(state == "list"){
			articleHeaderDetail.addClass("d-none");
			articleHeaderList.removeClass("d-none");

			articleDetail.addClass("d-none");
			articleList.removeClass("d-none");
		}
	}

	/**
	 * Gets the form data by iterating the inputs in a form
	 * 
	 * @return {Oject} The object obtained from the input
	 */
	function getFormData(){
		var inputs   = $("form *[name]");
		var formData = {};
		
		inputs.each(function(i, e){
			if(e.value){
				formData[e.name] = e.value;
			}
		});

		return formData;
	}

	/**
	 * Sets the image preview by reading the input file value
	 */
	function setPreview(){
		var reader = new FileReader();

		reader.onload = function(evt){
			imageSelector.children()[0].setAttribute("src", evt.target.result);
		};

		reader.readAsDataURL(inputImage[0].files[0]);
	}
});