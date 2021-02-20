<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $edit = $this->route()->getName() === 'product.update';
        $uniqueName = Rule::unique('products', 'name');
        return [
            'name' => ['required', $edit ? $uniqueName->ignoreModel($this->product) : $uniqueName],
            'description'=> ['required'],
            'price'=> ['required','numeric'],
            'in_stock'=> ['required','boolean'],
            'discount'=> ['required','boolean'],
            'published'=> ['required','boolean'],
            'is_service'=> ['required','boolean'],
            'image'=> [$edit?'':'required', 'image']
        ];
    }
}
