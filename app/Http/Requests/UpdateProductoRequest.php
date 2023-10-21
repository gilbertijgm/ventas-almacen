<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $producto = $this->route('producto');
        return [
            'codigo' => 'required|unique:productos,codigo,'.$producto->id.'|max:50',
            'nombre' => 'required|unique:productos,nombre,'.$producto->id.'|max:80',
            'descripcion' => 'nullable|max:255',
            'fecha_vencimiento' => 'nullable|date',
            'img_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'marca_id' => 'required|integer|exists:marcas,id',
            'presentacione_id' => 'required|integer|exists:presentaciones,id',
            'categorias' => 'required'
        ];
    }
    public function attributes() //esta funcion nos permite personalisar el nombre en la validacion
    {
        return [
            'marca_id' => 'marca',
            'presentacione_id' => 'presentacion'
        ];
    }

    public function messages() //esta funcion permite personalisar el mensaje que va a lanzar la validacion
    {
        return [
            'codigo' => 'Se necesita un campo codigo'
        ];
    }
}
