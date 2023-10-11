<?php

namespace App\Http\Controllers;

use App\Http\Requests\CmpRequest;
use App\Http\Requests\PresentacionUpdate;
use App\Models\Caracteristica;
use App\Models\Presentacione;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PresentacioneController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //latest() para ordenar segun su fecha de creacion
        $presentaciones = Presentacione::with('caracteristica')->latest()->get();
        return view('presentaciones.index', compact('presentaciones'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('presentaciones.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CmpRequest $request)
    {
        try{
            DB::beginTransaction();
            $caracteristica = Caracteristica::create($request->validated()); //creamos los datos de la tabla caracteristica
            $caracteristica->presentacione()->create([
                'caracteristica_id' => $caracteristica->id //aqui le asiganamos id de caracteristica a la tabla presentaciones
            ]);
            DB::commit();
        }catch (Exception $e){
            DB::rollBack();
        }

        return redirect()->route('presentaciones.index')->with('success','presentacion registrada');
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
    public function edit(Presentacione $presentacione)
    {
        return view('presentaciones.edit', compact('presentacione'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PresentacionUpdate $request, Presentacione $presentacione)
    {
        Caracteristica::where('id', $presentacione->caracteristica->id)->update($request->validated());

        return redirect()->route('presentaciones.index')->with('success', 'Presentacion editada');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $message = '';
        $presentacione = Presentacione::find($id);
        if ($presentacione->caracteristica->estado == 1) {
            Caracteristica::where('id',$presentacione->caracteristica->id)->update([
                'estado' => 0
            ]);
            $message = 'Presentacion eliminada';
        } else {
            Caracteristica::where('id',$presentacione->caracteristica->id)->update([
                'estado' => 1
            ]);
            $message = 'Presentacion restaurada';
        }



        return redirect()->route('presentaciones.index')->with('success', $message);
    }
}
