<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>{{ __('texts.product_page') }}</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

	<header class="bg-light p-3">

		<div class="container d-flex justify-content-between align-items-center">
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
		</div>
	</header>

	<div class="container py-2">
		<div class="row">
			<!-- Back to Listing -->
			<div class="col-md-6">
				<a href="{{ url('/products') . '?' . http_build_query(request()->only(['locale', 'search', 'page', 'category_id'])) }}" class="link-secondary">
					{{ __('texts.back_to_listing') }}
				</a>
			</div>
		</div>
	</div>
	@if(isset($error))
	<div class="container py-2">
		<div class="row">
			<div class="alert alert-danger">
				{{ $error }}
			</div>
		</div>
	</div>
	@else
	<div class="container py-5">
		<div class="row product-detail" data-artnum="{{ $product->product_id }}">
			<!-- Product Images -->
			<div class="col-md-6">
				<div class="card">
					<img src="{{ $product->image_url }}" class="card-img-top" alt="{{ $product->name }}">
				</div>
			</div>
			<!-- Product Details -->
			<div class="col-md-6 d-flex justify-content-between flex-column">
				<div class="row ">
					<h1 class="h2 mb-3 card-title">{{ $product->name }}</h1>
					<div class="mb-3">
						<span class="h4 me-2">{{ number_format($product->price, 2) }} €</span>
					</div>
					<p class="mb-4">{{ $product->description }}</p>
				</div>
				<div class="d-grid gap-2">
					<button class="btn btn-outline-primary add_to_cart" aria-label="{{ __('texts.add_to_cart') }}">
						<i class="bi bi-cart-plus"></i> {{ __('texts.add_to_cart') }}
					</button>
				</div>
			</div>
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
	</div>

	@endif

	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
	<script>
		$(document).ready(function() {
			// Attach event handler to language selector to handle language changes
			$('#languageSelect').on('change', changeLanguage);

			$('img').each(function() {
				const originalUrl = $(this).attr('src');
				const newUrl = originalUrl.replace('/w_300,', '/w_750,');
				$(this).attr('src', newUrl);
			});

			function changeLanguage() {
				let selectedLang = $('#languageSelect').val();

				// Parse the current URL to keep other parameters if they exist
				let urlParams = new URLSearchParams(window.location.search);
				urlParams.set('locale', selectedLang); // Update the locale parameter with the selected language

				let currentUrl = window.location.pathname; // Keep the current path, assuming it contains the product detail

				window.location.href = `${currentUrl}?${urlParams.toString()}`; // Update URL with new parameters
			}

			// Initialize add to cart popup
			$('.add_to_cart').on('click', function() {
				let card = $(this).closest('.product-detail');
				let productId = card.attr('data-artnum');
				let productName = card.find('.card-title').text();
				let productImage = card.find('img').attr('src');
				let productPrice = card.find('.h4').text();

				// Save the productId to local storage
				saveProductIdToLocalStorage(productId);

				addToCartPopup(productName, productImage, productPrice);
			});

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
			function addToCartPopup(productName, productImage, productPrice) {

				$('#popupMessage').html(`
					<img src="${productImage}" alt="${productName}" class="img-fluid" style="width: 100px;">
					<p class="fw-bold mt-2">"${productName}" {{ __('texts.added_to_cart') }}</p>
					<p class="fw-bold">${productPrice}</p>
				`);
				$('#cartPopup').modal('show');
			}
		})
	</script>
</body>

</html>