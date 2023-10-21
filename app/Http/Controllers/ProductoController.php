<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductoRequest;
use App\Http\Requests\UpdateProductoRequest;
use App\Models\Categoria;
use App\Models\Marca;
use App\Models\Presentacione;
use App\Models\Producto;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $productos = Producto::with([
            'categorias.caracteristica',
            'marca.caracteristica',
            'presentacione.caracteristica'])->latest()->get();
        return view('productos.index', compact('productos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $marcas = Marca::join('caracteristicas as c', 'marcas.caracteristica_id','=','c.id')
        ->select('marcas.id as id','c.nombre as nombre') //para buscar el id de la marca
        ->where('c.estado',1)
        ->get();

        $presentaciones = Presentacione::join('caracteristicas as c', 'presentaciones.caracteristica_id','=','c.id')
        ->select('presentaciones.id as id','c.nombre as nombre') //para buscar el id de la presentacion
        ->where('c.estado',1)
        ->get();

        $categorias = Categoria::join('caracteristicas as c', 'categorias.caracteristica_id','=','c.id')
        ->select('categorias.id as id','c.nombre as nombre') //para buscar el id de la categoria
        ->where('c.estado',1)
        ->get();
        return view('productos.create', compact('marcas','presentaciones','categorias'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductoRequest $request)
    {
        try{
            DB::beginTransaction();
            //tabla producto
            $producto = new Producto();

            //verificamos si se va a registrar un archivo file
            if ($request->hasFile(('img_path'))) {
                $name = $producto->hanbleUploadImage($request->file('img_path'));
            } else {
                $name = null;
            }

            $producto->fill([
                'codigo' => $request->codigo,
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'fecha_vencimiento' => $request->fecha_vencimiento,
                'img_path' => $name,
                'marca_id' => $request->marca_id,
                'presentacione_id' => $request->presentacione_id
            ]);
            $producto->save();


            //tabla categoria_producto
            $categorias = $request->get('categorias'); //capturamos todos los datos que esta recbiendo el campo categoria
            //usaremos el metodo attach() para llenar la tabla pivote categoria_producto
            $producto->categorias()->attach($categorias); //asi nos aseguramos de gurdar todas nuestras categorias en el modelo product

            DB::commit();
        }catch (Exception $e){
            DB::rollBack();
        }

        return redirect()->route('productos.index')->with('success','Producto registrado');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Producto $producto)
    {
        $marcas = Marca::join('caracteristicas as c', 'marcas.caracteristica_id','=','c.id')
        ->select('marcas.id as id','c.nombre as nombre') //para buscar el id de la marca
        ->where('c.estado',1)
        ->get();

        $presentaciones = Presentacione::join('caracteristicas as c', 'presentaciones.caracteristica_id','=','c.id')
        ->select('presentaciones.id as id','c.nombre as nombre') //para buscar el id de la presentacion
        ->where('c.estado',1)
        ->get();

        $categorias = Categoria::join('caracteristicas as c', 'categorias.caracteristica_id','=','c.id')
        ->select('categorias.id as id','c.nombre as nombre') //para buscar el id de la categoria
        ->where('c.estado',1)
        ->get();
        return view('productos.edit', compact('producto','marcas','presentaciones','categorias'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductoRequest $request, Producto $producto)
    {
        try{
            DB::beginTransaction();
            //tabla producto


            //verificamos si se va a registrar un archivo file
            if ($request->hasFile(('img_path'))) {
                $name = $producto->hanbleUploadImage($request->file('img_path'));

                //eliminar si existiese una imagen antigua
                if (Storage::disk('public')->exists('productos/'.$producto->img_path)) {
                    Storage::disk('public')->delete('productos/'.$producto->img_path);
                }
            } else {
                $name = $producto->img_path;
            }

            $producto->fill([
                'codigo' => $request->codigo,
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'fecha_vencimiento' => $request->fecha_vencimiento,
                'img_path' => $name,
                'marca_id' => $request->marca_id,
                'presentacione_id' => $request->presentacione_id
            ]);
            $producto->save();


            //tabla categoria_producto
            $categorias = $request->get('categorias'); //capturamos todos los datos que esta recbiendo el campo categoria
            //usaremos el metodo sync() elimina todas las categorias y añade las nuevas
            $producto->categorias()->sync($categorias); //asi nos aseguramos de gurdar todas nuestras categorias en el modelo product

            DB::commit();
        }catch (Exception $e){
            DB::rollBack();
        }

        return redirect()->route('productos.index')->with('success','Producto Modificado');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $message = '';
        $producto = Producto::find($id);
        if ($producto->estado == 1) {
            Producto::where('id',$producto->id)->update([
                'estado' => 0
            ]);
            $message = 'Producto eliminado';
        } else {
            Producto::where('id',$producto->id)->update([
                'estado' => 1
            ]);
            $message = 'Producto restaurado';
        }



        return redirect()->route('productos.index')->with('success', $message);
    }
}
