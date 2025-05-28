<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>{{ __('texts.category_name') }}</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
	<div class="container mt-4">
		<header class="bg-light p-3 d-flex justify-content-between align-items-center">
			<!-- Language Switcher -->
			<div class="language-switcher">
				<select id="languageSelect" class="form-select w-auto">
					@foreach($locales as $locale)
					@if(Lang::has('texts.language_switcher.' . $locale))
					<option value="{{ $locale }}" {{ session('locale', app()->getLocale()) === $locale ? 'selected' : '' }}>
						{{ __('texts.language_switcher.' . $locale) }}
					</option>
					@endif
					@endforeach
				</select>
			</div>
		</header>

		<main>
			<!-- Errors from server -->
			<div class="row justify-content-center mt-3">
				<div class="col-lg-6 col-md-8">
					@if(session('error'))
					<div class="alert alert-danger">
						{{ session('error') }}
					</div>
					@endif
				</div>
			</div>
			<!-- Category Selection Form -->
			<div class="row justify-content-center mt-3">
				<div class="col-lg-6 col-md-8">
					<form id="categoryForm" method="GET" role="search">
						<div class="input-group mb-3">
							<select id="categorySelect" name="category_id" class="form-select" style="max-width: 180px;">
								<option value="allCategories">{{ $categoryName }}</option>
								<!-- Categories will be loaded here -->
							</select>
							<input type="text" id="searchInput" class="form-control" name="search" placeholder="{{ __('texts.search_placeholder') }}">
							<button id="searchButton" class="btn btn-primary" type="button">{{ __('texts.search_button') }}</button>
						</div>
					</form>
				</div>
			</div>

			<!-- Products Listing -->
			<div id="product-list" class="container py-5">
				<h2 class="product-list-title text-center mb-4">{{ $title }}</h2>
				<div id="product-grid" class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-3 g-4">
					<!-- Products will be injected here -->
				</div>
			</div>
			<div class="d-flex justify-content-center mt-3">
				<ul id="pagination" class="pagination justify-content-center mt-4">
					<!-- Pagination buttons will be injected here -->
				</ul>
			</div>
			<!-- Popup add to cart -->
			<div id="cartPopup" class="modal" tabindex="-1" role="dialog">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title">{{ __('texts.product_added') }}</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<p id="popupMessage" class="fw-bold"></p>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('texts.close') }}</button>
						</div>
					</div>
				</div>
			</div>
		</main>
	</div>

	<footer class="bg-light text-center p-3">
		<p class="mb-0">&copy; {{ date('Y') }} AM</p>
	</footer>

	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
	<script>
		$(document).ready(function() {

			// Select necessary DOM elements
			let productGrid = $('#product-grid');
			let pagination = $('#pagination');
			let categorySelect = $('#categorySelect');
			let searchInput = $('#searchInput');

			// Retrieve URL parameters with defaults
			let locale = getUrlParam('locale', '{{ app()->getLocale() }}');
			let currentCategoryId = getUrlParam('category_id', null);

			// Parse current page number in URL, default to 1 if not found
			let currentPage = parseInt(new URLSearchParams(window.location.search).get('page')) || 1;

			// Get search query from URL
			let searchQuery = getUrlParam('search', '');
			// Set search input value from the URL parameter
			searchInput.val(searchQuery);

			// Load categories based on locale and setup products
			getCategories(locale);
			loadProducts(currentPage, currentCategoryId, locale, searchQuery);

			// Set up search handling
			setSearch();

			// Attach event handler to language selector to handle language changes
			$('#languageSelect').on('change', changeLanguage);


			function getUrlParam(name, defaultValue = null) {
				let urlParams = new URLSearchParams(window.location.search);
				return urlParams.get(name) || defaultValue;
			}

			function updateTitle(searchQuery, categoryId) {
				let titleElement = $('.product-list-title');
				if (searchQuery) {
					// Determine category text appropriately
					let categoryText = categoryId ? $('#categorySelect option:selected').text() : '{{ __("texts.all_categories") }}';
					titleElement.text(`{{ __("texts.search_for") }} "${searchQuery}" {{ __("texts.search_in") }} ${categoryText}`);
				} else {
					let categoryText = $('#categorySelect option:selected').text();
					titleElement.text(categoryId ? `${categoryText}` : '{{ __("texts.all_categories") }}');
				}
			}

			function disableControls() {
				//disabled form to avoid calling many ajax
				$('#categorySelect').prop('disabled', true);
				$('#searchButton').prop('disabled', true);
				$('#searchInput').prop('disabled', true);
				$('#pagination .page-link').addClass('disabled');
			}

			function enableControls() {
				$('#categorySelect').prop('disabled', false);
				$('#searchButton').prop('disabled', false);
				$('#searchInput').prop('disabled', false);
				$('#pagination .page-link').removeClass('disabled');
			}

			function changeLanguage() {
				let selectedLang = $('#languageSelect').val();
				let categoryId = categorySelect.val() === 'allCategories' ? null : categorySelect.val();
				let searchQuery = searchInput.val().trim();

				// Add params to URL
				let urlParams = new URLSearchParams({
					locale: selectedLang,
					page: currentPage,
					search: searchQuery
				});

				if (categoryId) {
					urlParams.append('category_id', categoryId); // Add category ID if exists
				}

				window.location.href = `/products?${urlParams.toString()}`; // Update URL with parameters
			}

			function getCategories(locale) {
				// Disable all controls to prevent user interaction during loading
				disableControls();

				// Retrieve category_id from URL parameters, if present
				const urlParams = new URLSearchParams(window.location.search);
				const categoryIdFromParams = urlParams.get('category_id');

				// Fetch categories based on the current locale
				axios.get(`/api/categories?locale=${locale}`)
					.then(response => {
						// Populate category select with options
						categorySelect.empty().append('<option value="allCategories">{{ __("texts.all_categories") }}</option>');
						response.data.forEach(category => {
							let categoryOption = $('<option></option>').val(category.category_id).text(category.name);
							categorySelect.append(categoryOption);
						});

						//Automatically select the category if category_id exists in URL
						if (categoryIdFromParams && categoryIdFromParams != 'null') {
							categorySelect.val(categoryIdFromParams);
						}

						// Re-enable controls after categories are loaded
						enableControls();

						// Set up event handler for category changes
						categorySelect.on('change', function() {
							let selectedCategoryId = $(this).val() === 'allCategories' ? null : $(this).val();

							disableControls();

							// Clear search input and update title for the new category
							searchInput.val('');
							updateTitle('', selectedCategoryId);

							// Load products for the selected category, excluding any search parameter
							if (!searchInput.val().trim()) {
								loadProducts(1, selectedCategoryId, locale);
							}
						});
					})
					.catch(error => {
						let title = $('.product-list-title');
						if (title.length) {
							title.text(error.response?.data?.error || 'An unexpected error occurred.');
						}
						console.error('Error loading categories:', error);
					})
					.finally(() => enableControls()); // Always re-enable controls
			}

			function loadProducts(page = 1, categoryId = null, locale, searchQuery = '') {

				disableControls();

				// Show loading state
				productGrid.css({
					'opacity': 0.7,
					'pointer-events': 'none'
				});

				// Update the current page variable
				currentPage = page;

				const itemsPerPage = 9; // Number of items per page
				// Set up request parameters for API call
				let params = {
					locale: locale,
					page: page,
					per_page: itemsPerPage,
					search: searchQuery
				};

				// Add category filter to parameters if applicable
				if (categoryId && categoryId != 'null') {
					params.category_id = categoryId;
				}

				console.log(params);

				// Make an API request to fetch products
				axios.get('/api/products', {
						params
					})
					.then(response => {
						let products = response.data.data; // Extract product data
						let meta = response.data.meta; // Extract pagination info
						productGrid.empty(); // Clear existing products
						renderProducts(products, page, categoryId, locale, searchQuery); // Render new products
						setupPagination(meta.total_pages, currentPage, categoryId); // Set up pagination
						history.pushState(null, '', `?page=${page}&category_id=${categoryId}&locale=${locale}&search=${searchQuery}`);
					})
					.catch(error => {
						let title = $('.product-list-title');
						if (title.length) {
							title.text(error.response?.data?.error || 'An unexpected error occurred.');
						}
						console.error('Error loading categories:', error);
						console.error('Error loading products:', error);
						// Reset grid style on error
						productGrid.css({
							'opacity': 1,
							'pointer-events': 'auto'
						});
					})
					.finally(() => enableControls()); // Re-enable controls after processing
			}

			function renderProducts(products, currentPage = 1, currentCategoryId = '', locale = 'en', currentSearch = '') {
				let html = '';

				if (products.length === 0) {
					html = `<div class="col-12 text-center">{{ __('texts.no_products_found') }}</div>`;
				} else {
					products.forEach(product => {
						// Construct URL for each product including all query parameters
						let productURL = `/products/${product.product_id}?locale=${locale}&page=${currentPage}`;

						// Append search query if exists
						if (currentSearch) {
							productURL += `&search=${encodeURIComponent(currentSearch)}`;
						}

						// Append categoryId if exists
						if (currentCategoryId) {
							productURL += `&category_id=${currentCategoryId}`;
						}

						html += `
							<div class="col card-product">
								<a href="${productURL}" class="text-decoration-none" data-artnum="${product.product_id}">
									<div class="card h-100 shadow-sm">
										<img src="${product.image_url || 'default.jpg'}" class="card-img-top" alt="${product.name}" loading="lazy">
										<div class="card-body">
											<h5 class="card-title">${product.name}</h5>
											<div class="d-flex justify-content-between align-items-center">
												<span class="h5 mb-0">${parseFloat(product.price).toFixed(2)} €</span>
												<button class="btn btn-outline-primary add_to_cart" aria-label="{{ __('texts.add_to_cart') }}">
													<i class="bi bi-cart-plus"></i> {{ __('texts.add_to_cart') }}
												</button>
											</div>
										</div>
									</div>
								</a>
							</div>
						`;
					});
				}

				productGrid.html(html);

				// Reset product grid opacity
				productGrid.css({
					'opacity': 1,
					'pointer-events': 'auto'
				});

				// Initialize add to cart popup
				$('.add_to_cart').on('click', function(event) {

					event.preventDefault();
					event.stopPropagation();

					let card = $(this).closest('.card-product');
					let productId = card.find('a').attr('data-artnum');
					let productName = card.find('.card-title').text();
					let productImage = card.find('img').attr('src');
					let productPrice = card.find('.h5').text();

					// Save the productId to local storage
					saveProductIdToLocalStorage(productId);

					addToCartPopup(productId, productName, productImage, productPrice);
				});
			}

			// Function to save productId to local storage
			function saveProductIdToLocalStorage(productId) {

				let cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];

				// Find the existing product in the cart
				let existingProduct = cartItems.find(item => item.productId === productId);

				if (existingProduct) {
					// If product exists, increment the quantity
					existingProduct.quantity += 1;
				} else {
					// If not, add a new product with quantity 1
					cartItems.push({
						productId,
						quantity: 1
					});
				}

				// Save the updated array back to local storage
				localStorage.setItem('cartItems', JSON.stringify(cartItems));
			}

			// Function to display the cart popup with product details
			function addToCartPopup(productId, productName, productImage, productPrice) {
				$('#popupMessage').html(`
					<img src="${productImage}" alt="${productName}" class="img-fluid" style="width: 100px;">
					<p class="fw-bold mt-2">"${productName}" {{ __('texts.added_to_cart') }}</p>
					<p class="fw-bold">${productPrice}</p>
				`);
				$('#cartPopup').modal('show');
			}

			function setSearch() {
				let searchButton = $('#searchButton');
				let productListTitle = $('.product-list-title');

				searchButton.on('click', function(e) {
					event.stopPropagation()
					let searchQuery = searchInput.val().trim();
					let categoryId = categorySelect.val() === 'allCategories' ? null : categorySelect.val();

					loadProducts(1, categoryId, locale, searchQuery); // Load products with searchQuery and category

					updateTitle(searchQuery, categoryId);
				});

				// Enable search on Enter key press
				searchInput.on('keypress', function(e) {
					if (e.which == 13) {
						e.preventDefault(); // Prevent default action
						searchButton.click(); // Trigger search action
					}
				});
			}

			function setupPagination(totalPages, currentPage, currentCategoryId) {
				pagination.empty(); // Clear existing pagination

				const maxPagesToShow = 5;
				let halfPagesToShow = Math.floor(maxPagesToShow / 2);
				let addedLeftDots = false;
				let addedRightDots = false;

				for (let i = 1; i <= totalPages; i++) {
					const inPageRange = i === 1 || i === totalPages ||
						(i >= currentPage - halfPagesToShow && i <= currentPage + halfPagesToShow);

					if (inPageRange) {
						let pageItem = $(`<li class="page-item"><a class="page-link" href="#">${i}</a></li>`);
						if (i === currentPage) {
							pageItem.addClass('active');
						}
						pageItem.on('click', function(e) {
							e.preventDefault();
							loadProducts(i, currentCategoryId, locale, searchInput.val().trim());
						});
						pagination.append(pageItem);
					} else {
						if (i < currentPage && !addedLeftDots) {
							pagination.append('<li class="page-item disabled"><span class="page-link">...</span></li>');
							addedLeftDots = true;
						} else if (i > currentPage && !addedRightDots) {
							pagination.append('<li class="page-item disabled"><span class="page-link">...</span></li>');
							addedRightDots = true;
						}
					}
				}
			}
		});
	</script>
</body>

</html>