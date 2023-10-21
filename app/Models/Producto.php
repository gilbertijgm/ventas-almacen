<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;

class Producto extends Model
{
    use HasFactory;



    public function compras(): BelongsToMany
    {
        return $this->belongsToMany(Compras::class)->withPivot('cantidad', 'precio_compra', 'percio_venta');
    }

    public function ventas(): BelongsToMany
    {
        return $this->belongsToMany(Venta::class)->withPivot('cantidad', 'precio_venta', 'descuento');
    }

    public function categorias(): BelongsToMany
    {
        return $this->belongsToMany(Categoria::class);
    }

    public function marca(): BelongsTo
    {
        return $this->belongsTo(Marca::class);
    }

    public function presentacione(): BelongsTo
    {
        return $this->belongsTo(Presentacione::class);
    }

    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
        'fecha_vencimiento',
        'marca_id',
        'presentacione_id',
        'img_path'
    ];



    public function hanbleUploadImage($image){  //esta funcion es para que las imagenes se guarden en public
        $file = $image;
        $name = time() . $file->getClientOriginalName();
        //$file->move(public_path().'/img/productos/',$name);
        Storage::putFileAs('/public/productos/',$file,$name,'public');

        return $name;
    }
}
