<?php

namespace App\Http\Controllers;

use App\Http\Requests\CmpRequest;
use App\Http\Requests\CmpUpdateRequest;
use App\Models\Caracteristica;
use App\Models\Categoria;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //latest() para ordenar segun su fecha de creacion
        $categorias = Categoria::with('caracteristica')->latest()->get();
        return view('categorias.index', compact('categorias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('categorias.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CmpRequest $request)
    {
        try{
            DB::beginTransaction();
            $caracteristica = Caracteristica::create($request->validated());
            $caracteristica->categoria()->create([
                'caracteristica_id' => $caracteristica->id
            ]);
            DB::commit();
        }catch (Exception $e){
            DB::rollBack();
        }

        return redirect()->route('categorias.index')->with('success','categoria registrada');
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
    public function edit(Categoria $categoria)
    {
        return view('categorias.edit', compact('categoria'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CmpUpdateRequest $request, Categoria $categoria)
    {
        Caracteristica::where('id', $categoria->caracteristica->id)->update($request->validated());

        return redirect()->route('categorias.index')->with('success', 'Categoria editada');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $message = '';
        $categoria = Categoria::find($id);
        if ($categoria->caracteristica->estado == 1) {
            Caracteristica::where('id',$categoria->caracteristica->id)->update([
                'estado' => 0
            ]);
            $message = 'Categoria eliminada';
        } else {
            Caracteristica::where('id',$categoria->caracteristica->id)->update([
                'estado' => 1
            ]);
            $message = 'Categoria restaurada';
        }



        return redirect()->route('categorias.index')->with('success', $message);
    }
}
