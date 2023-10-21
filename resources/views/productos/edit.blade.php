@extends('template')

@section('title','Editar Producto')


@push('css')
<style>
    #descripcion{
        resize: none;
    }
</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
@endpush

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Editar Producto</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item active"><a href="{{ route('productos.index') }}">Producto</a></li>
        <li class="breadcrumb-item active">Editar Producto</li>
    </ol>

    <div class="container w-100 border boder-3 border-primary rounded p-4 mt-3">
        {{-- enctype="multipart/form-data" permiten que se envien archivos de tipo file en nuestro formulario --}}
        <form action="{{route('productos.update', ['producto'=>$producto->id])}}" method="POST" enctype="multipart/form-data">
            @method('PATCH')
            @csrf
            <div class="row g-3">

                <div class="col-md-6">
                    <label for="codigo" class="form-label">Codigo:</label>
                    <input type="text" name="codigo" id="codigo" class="form-control" value="{{old('codigo',$producto->codigo)}}">
                    @error('codigo')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="nombre" class="form-label">Nombre:</label>
                    <input type="text" name="nombre" id="nombre" class="form-control" value="{{old('nombre',$producto->nombre)}}">
                    @error('nombre')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-12">
                    <label for="descripcion" class="form-label">Descripcion:</label>
                    <textarea name="descripcion" id="descripcion"  rows="3" class="form-control">{{old('descripcion',$producto->descripcion)}}</textarea>
                    @error('descripcion')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="fecha_vencimiento" class="form-label">Fecha de vencimiento:</label>
                    <input type="date" name="fecha_vencimiento" id="fecha_vencimiento" class="form-control" value="{{old('fecha_vencimiento',$producto->fecha_vencimiento)}}">
                    @error('fecha_vencimiento')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="img_path" class="form-label">Imagen:</label>
                    <input type="file" name="img_path" id="img_path" class="form-control" accept="Image/*" value="{{old('img_path')}}">
                    @error('img_path')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="categorias" class="form-label">Categoria:</label>
                    <select data-size="5" title="Seleccione una categoria" data-live-search="true"
                    name="categorias[]" id="categorias" class="form-control selectpicker show-tick" multiple>
                        @foreach ($categorias as $categoria)
                        @if (in_array($categoria->id,$producto->categorias->pluck('id')->toArray()))
                        <option selected value="{{$categoria->id}}"
                            {{ (in_array($categoria->id, old('categorias',[]))) ? 'selected' : '' }}>
                                {{$categoria->nombre}}
                            </option>
                        @else
                        <option value="{{$categoria->id}}"
                        {{ (in_array($categoria->id, old('categorias',[]))) ? 'selected' : '' }}>
                            {{$categoria->nombre}}
                        </option>
                        @endif
                        @endforeach
                    </select>
                    @error('categorias')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="marca_id" class="form-label">Marca:</label>
                    <select data-size="5" title="Seleccione una marca" data-live-search="true" name="marca_id" id="marca_id" class="form-control selectpicker show-tick">
                        @foreach ($marcas as $marca)
                        @if ($producto->marca_id == $marca->id)
                        <option selected value="{{$marca->id}}" {{old('marca_id') == $marca->id ? 'selected' : ''}}>{{$marca->nombre}}</option>
                        @else
                        <option value="{{$marca->id}}" {{old('marca_id') == $marca->id ? 'selected' : ''}}>{{$marca->nombre}}</option>
                        @endif
                        @endforeach
                    </select>
                    @error('marca_id')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label title="Seleccione una presentacion" for="presentacione_id" class="form-label">Presentacion:</label>
                    <select data-size="5" title="Seleccione una presentacion" data-live-search="true" name="presentacione_id" id="presentacione_id" class="form-control selectpicker show-tick">
                        @foreach ($presentaciones as $presentacione)
                        @if ($producto->presentacione_id == $presentacione->id)
                        <option selected value="{{$presentacione->id}}" {{old('presentacione_id') == $presentacione->id ? 'selected' : ''}}>{{$presentacione->nombre}}</option>
                        @else
                        <option value="{{$presentacione->id}}" {{old('presentacione_id') == $presentacione->id ? 'selected' : ''}}>{{$presentacione->nombre}}</option>
                        @endif
                        @endforeach
                    </select>
                    @error('presentacione_id')
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
@endpush
