<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
	public function handle($request, Closure $next)
	{
		// Check if `locale` is present in the query parameters
		if ($locale = $request->query('locale')) {
			App::setLocale($locale);          // Set the application locale
			Session::put('locale', $locale);  // Store it in session
		} elseif (Session::has('locale')) {
			App::setLocale(Session::get('locale')); // Retrieve from session if available
		}

		return $next($request);
	}
}
