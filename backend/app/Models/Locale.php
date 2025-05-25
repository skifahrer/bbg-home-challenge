<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Locale extends Model
{
	use HasFactory;

	// Specify the locale table if not following Laravel's naming convention
	protected $table = 'locales';

	// Define fillable attributes if needed
	protected $fillable = ['locale', 'name'];
}
