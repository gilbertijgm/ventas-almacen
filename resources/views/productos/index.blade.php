@extends('template')

@section('title', 'Productos')


@push('css')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
@endpush


@section('content')
    @if (session('success'))
        <script>
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 1500,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            })

            Toast.fire({
                icon: 'success',
                title: 'Operacion Exitosa'
            })
        </script>
    @endif

    <div class="container-fluid px-4">
        <h1 class="mt-4">Productos</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active"><a href="{{ route('panel') }}">Inicio</a></li>
            <li class="breadcrumb-item active">Productos</li>
        </ol>

        <div class="mb-4">

            <a href="{{ route('productos.create') }}">
                <button type="button" class="btn btn-primary">Crear Producto</button>
            </a>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                Presentaciones
            </div>
            <div class="card-body">
                <table id="datatablesSimple" class="table table-striped">
                    <thead>
                        <tr>
                            <th>Codigo</th>
                            <th>Nombre</th>
                            <th>Categorias</th>
                            <th>Marca</th>
                            <th>Presentacion</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>


                    <tbody>
                        @foreach ($productos as $producto)
                            <tr>
                                <td>
                                    {{ $producto->codigo }}
                                </td>
                                <td>
                                    {{ $producto->nombre }}
                                </td>
                                <td>
                                    @foreach ($producto->categorias as $categoria)
                                        <div class="container">
                                            <div class="row">
                                                <span class="m-1 rounded-pill p-1 bg-secondary text-black text-center">
                                                    {{ $categoria->caracteristica->nombre }}
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                </td>
                                <td>
                                    {{ $producto->marca->caracteristica->nombre }}
                                </td>
                                <td>
                                    {{ $producto->presentacione->caracteristica->nombre }}
                                </td>
                                <td>
                                    <span class="fw-bolder p-1 rounded {{ $producto->estado == 1 ? 'bg-success text-white' : 'bg-danger' }}">
                                        {{ $producto->
                                        estado == 1 ? 'Activo' : 'Eliminado' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group" aria-label="Basic mixed styles example">

                                        <button type="submit" class="btn btn-success" data-bs-toggle="modal"
                                        data-bs-target="#verModal-{{$producto->id}}">Ver</button>

                                        <form action="{{route('productos.edit', ['producto'=>$producto])}}"
                                            method="GET">

                                            <button type="submit" class="btn btn-warning" >Editar</button>
                                        </form>
                                        @if ($producto->estado == 1)
                                            <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                                data-bs-target="#confirmModal-{{ $producto->id }}">Eliminar</button>
                                        @else
                                            <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                                data-bs-target="#confirmModal-{{ $producto->id }}">Restaurar</button>
                                        @endif

                                    </div>
                                </td>
                            </tr>

                             <!-- Modal para ver detalle -->
                            <div class="modal fade" id="verModal-{{$producto->id}}" tabindex="-1"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-scrollable">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5 fw-bolder text-center" id="exampleModalLabel">{{$producto->nombre}}</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row mb-3">
                                                <label for="">
                                                    <span class="fw-bolder">Descripcion: </span>
                                                    {{$producto->descripcion}}
                                                </label>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="">
                                                    <span class="fw-bolder">Fecha de Vencimiento: </span>
                                                    {{$producto->fecha_vencimiento== '' ? 'No tiene' : $producto->fecha_vencimiento}}
                                                </label>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="">
                                                    <span class="fw-bolder">Stock: </span>
                                                    {{$producto->stock}}
                                                </label>
                                            </div>
                                            <div class="row mb-3">
                                                <label class="fw-bolder">Imagen de producto:</label>
                                                <div>
                                                    @if ($producto->img_path != null)
                                                        <img
                                                        src="{{Storage::url('public/productos/'.$producto->img_path)}}"
                                                        alt="{{$producto->nombre}}"
                                                        class="img-fluid img-thumbail border border-4 rounded">
                                                    @else
                                                        <img src="" alt="{{$producto->nombre}}">
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Cancelar</button>

                                        </div>
                                    </div>
                                </div>
                            </div>

                             <!-- Modal  para eliminar producto -->
                             <div class="modal fade" id="confirmModal-{{ $producto->id }}" tabindex="-1"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Eliminar Producto</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            @if ($producto->estado == 1)
                                                Deseas eliminar el Producto: <span
                                                    class="fw-bolder">{{ $producto->nombre }}</span>?
                                            @else
                                                Deseas restaurar el Producto: <span
                                                    class="fw-bolder">{{ $producto->nombre }}</span>?
                                            @endif
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Cancelar</button>
                                            <form
                                                action="{{ route('productos.destroy', ['producto' => $producto->id]) }}"
                                                method="post">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-primary">Confirmar</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>

    </div>

@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" type="text/javascript"></script>
    <script src="{{ asset('js/datatables-simple-demo.js') }}"></script>
@endpush
