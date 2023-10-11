<?php

namespace App\Http\Controllers;

use App\Http\Requests\CmpRequest;
use App\Http\Requests\CmpUpdateRequest;
use App\Http\Requests\MarcaUpdate;
use App\Models\Caracteristica;
use App\Models\Marca;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MarcaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //latest() para ordenar segun su fecha de creacion
        $marcas = Marca::with('caracteristica')->latest()->get();
        return view('marcas.index', compact('marcas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('marcas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CmpRequest $request)
    {
        try{
            DB::beginTransaction();
            $caracteristica = Caracteristica::create($request->validated()); //creamos los datos de la tabla caracteristica
            $caracteristica->marca()->create([
                'caracteristicas' => $caracteristica->id //aqui le asiganamos id de caracteristica a la tabla marcas
            ]);
            DB::commit();
        } catch(Exception $e){
            DB::rollBack();
        }

        return redirect()->route('marcas.index')->with('success','Marca registrada');
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
    public function edit(Marca $marca)
    {
        return view('marcas.edit', compact('marca'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MarcaUpdate $request, Marca $marca)
    {
        Caracteristica::where('id', $marca->caracteristica->id)->update($request->validated());

        return redirect()->route('marcas.index')->with('success', 'Marca editada');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $message = '';
        $marca = Marca::find($id);
        if ($marca->caracteristica->estado == 1) {
            Caracteristica::where('id',$marca->caracteristica->id)->update([
                'estado' => 0
            ]);
            $message = 'Marca eliminada';
        } else {
            Caracteristica::where('id',$marca->caracteristica->id)->update([
                'estado' => 1
            ]);
            $message = 'Marca restaurada';
        }



        return redirect()->route('marcas.index')->with('success', $message);
    }
}
