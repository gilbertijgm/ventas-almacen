<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Producto extends Model
{
    use HasFactory;

    protected $guarded = [];

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
}
