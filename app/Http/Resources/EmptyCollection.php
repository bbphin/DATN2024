<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class EmptyCollection extends ResourceCollection
{
	public function toArray(Request $request): array
	{
		return [
			'data' => collect(),
		];
	}
}
