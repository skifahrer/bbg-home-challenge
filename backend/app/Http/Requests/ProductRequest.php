<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{

	public function rules()
	{
		return [
			'locale' => ['nullable', 'string', 'exists:locales,locale'],
			'per_page' => ['nullable', 'integer', 'min:1', 'max:10'],
			'category_id' => ['nullable', 'integer'],
			'search' => ['nullable', 'string', 'max:255'],
		];
	}

	public function messages()
	{
		return [
			'locale_not_found' => 'The selected locale is not valid.',
			'invalid_category' => 'The selected category does not exist for this locale.',
		];
	}
}
