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
	var templateArticle     = $("#article-item-template");

	var APIurl       = "index.php/api/articles";
	var currentState = {
		id: null,
		fetch: true
	};

	hideLoader(50)
		.then(function(){
			mainContent.removeClass("d-none");
			return fetchData();
		})
		.then(function(data){
			if(currentState.fetch){
				articleList.empty();

				compile(data);

				currentState.fetch = false;
			}
		});

	/*
	 * Listen for header click events
	 */
	contentHeader.on("click", "a", function(){
		if(this.id == "new-article-button"){
			displayTo("detail");

			// Restore form if there is any data
			document.forms[0].reset();

			currentState.id = null;
		}

		if(this.id == "back-to-list-button"){
			displayTo("list");

			if(currentState.fetch){
				articleList.empty();

				compile(data);

				currentState.fetch = false;
			}
		}

		if(this.id == "save-button"){
			var data = getFormData();

			sendData(data)
				.then(function(){
					currentState.fetch = true;
				});
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

	/*
	 * Transition to article detail
	 */
	articleList.on("click", ".article-item", function(){
		var id = $(this).data("id");

		fetchData(id)
			.then(function(data){
				displayTo("detail");
				setFormData(data);
				currentState.id = data.id;
			});
	});

	/**
	 * Hides the initial loader and transitions to list
	 * 
	 * @param  {Number}  ms The millisecond time to wait for the loader
	 * @return {Promise}    The result when the loader is hidden
	 */
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
	 * Compiles the html elements with the provided data
	 * 
	 * @param  {Array} data The list of articles data fetched from server
	 */
	function compile(data){
		var template = Handlebars.compile(templateArticle.html());

		articleList.html(template(data));
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
	 * Fills the input data in the form
	 * 
	 * @param {Object} data The object to extract the data
	 */
	function setFormData(data){
		Object.keys(data).forEach(function(e){
			var input = $("form *[name='" + e + "']");

			if(input){
				input.val(data[e]);
			}
		});
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

	/**
	 * Fetches the data from REST api service
	 * 
	 * @param  {Number}  $id The id of the resource in case is needed
	 * @return {Promise}     The result fof the fetch
	 */
	function fetchData($id){
		var url = APIurl;

		if($id){
			url += "/" + $id;
		}

		return $.ajax({
			url: url,
			method: "GET",
			dataType: "json"
		})
		.then(function(data){
			if(data){
				if($id){
					data.image = data.image || "http://via.placeholder.com/100x100";
				}

				else{
					data.forEach(function(e, i){
						data[i].image = data[i].image || "http://via.placeholder.com/100x100";
					});
				}
			}

			return data;
		});
	}

	/**
	 * Sends data to the API server
	 * 
	 * @param  {Object}  data The data to send to the server
	 * @param  {Number}  $id  The record to update
	 * @return {Promise}      The result for uploading the data
	 */
	function sendData(data, $id){
		return $.ajax({
			url: APIurl,
			data: JSON.stringify(data),
			method: "POST",
			dataType: "json",
			contentType: "application/json"
		});
	}
});