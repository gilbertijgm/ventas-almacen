@extends('template')

@section('title','Crear Marca')


@push('css')
<style>
    #descripcion{
        resize: none;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Crear Marca</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item active"><a href="{{ route('marcas.index') }}">Marcas</a></li>
        <li class="breadcrumb-item active">Crear Marca</li>
    </ol>

    <div class="container w-100 border boder-3 border-primary rounded p-4 mt-3">
        <form action="{{route('marcas.store')}}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-12">
                    <label for="nombre" class="form-label">Nombre:</label>
                    <input type="text" name="nombre" id="nombre" class="form-control" value="{{old('nombre')}}">
                    @error('nombre')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-12">
                    <label for="descripcion" class="form-label">Descripcion:</label>
                    <textarea name="descripcion" id="descripcion"  rows="3" class="form-control">{{old('descripcion')}}</textarea>
                    @error('descripcion')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-12 text-center">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </div>

        </form>
    </div>

</div>
@endsection

@push('js')

@endpush