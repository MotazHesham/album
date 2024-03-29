<?php

namespace App\Http\Requests;

use App\Models\Album;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateAlbumRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
            ],
            'pictures' => [
                'array',
            ],
        ];
    }
}
