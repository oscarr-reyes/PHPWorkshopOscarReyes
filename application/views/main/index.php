<div id="loader-container" class="flex-column visible">
	<img src="assets/ring-alt.svg" alt="loader" id="loader-img">
	<h2 class="text-center">Loading</h2>
</div>
<div id="content" class="d-none">
	<div id="content-header">
		<div id="article-header-list" class="flexed">
			<p class="mr-auto">All Articles</p>
			<a href="javascript:void(0)" id="new-article-button">New article</a>
		</div>
		<div id="article-header-detail" class="flexed d-none">
			<a href="javascript:void(0)" id="back-to-list-button" class="mr-auto">Go back</a>
			<a href="javascript:void(0)" id="save-button">Save / Update</a>
		</div>
	</div>
	<div id="article-list" class="flexed flex-column">
	</div>

	<div id="article-detail" class="d-none">
		<form class="container">
			<div class="form-group-image d-flex">
				<div id="input-image-selector" class="rounded mr-auto ml-auto" name="image"><img></div>
				<input type="file" class="d-none" name="image" accept="image/*">
			</div>
			<div class="form-group">
				<label for="article-title-input">Title</label>
				<input type="text" class="form-control" id="article-title-input" name="title" aria-describedby="articleTitle">
			</div>
			<div class="form-group">
				<label for="article-description-input">Description</label>
				<textarea class="form-control" id="article-description-input" name="description" rows="3"></textarea>
			</div>
		</form>
	</div>
</div>

<script id="article-item-template" type="text/x-handlebars-template">
	{{#each this}}
	<div class="article-item border d-flex flex-row" data-id="{{id}}">
		<div class="article-item-img">
			<img src="http://via.placeholder.com/100x100" alt="article image">
		</div>
		<div class="article-item-content container">
			<h3>{{title}}</h3>
			<p>{{description}}</p>
		</div>
	</div>
	{{/each}}
</script>