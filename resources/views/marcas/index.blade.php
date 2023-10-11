@extends('template')

@section('title', 'Marcas')


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
        <h1 class="mt-4">Marcas</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active"><a href="{{ route('panel') }}">Inicio</a></li>
            <li class="breadcrumb-item active">Marcas</li>
        </ol>

        <div class="mb-4">

            <a href="{{ route('marcas.create') }}">
                <button type="button" class="btn btn-primary">Crear Marca</button>
            </a>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                Marcas
            </div>
            <div class="card-body">
                <table id="datatablesSimple" class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Descripcion</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>


                    <tbody>
                        @foreach ($marcas as $marca)
                            <tr>
                                <td>
                                    {{ $marca->caracteristica->nombre }}
                                </td>
                                <td>
                                    {{ $marca->caracteristica->descripcion }}
                                </td>
                                <td>
                                    <span
                                        class="fw-bolder p-1 rounded {{ $marca->caracteristica->estado == 1 ? 'bg-success text-white' : 'bg-danger' }}">
                                        {{ $marca->caracteristica->estado == 1 ? 'Activo' : 'Eliminado' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group" aria-label="Basic mixed styles example">

                                        <form action="{{route('marcas.edit', ['marca'=>$marca])}}"
                                            method="GET">

                                            <button type="submit" class="btn btn-warning">Editar</button>
                                        </form>
                                        @if ($marca->caracteristica->estado == 1)
                                            <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                                data-bs-target="#confirmModal-{{ $marca->id }}">Eliminar</button>
                                        @else
                                            <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                                data-bs-target="#confirmModal-{{ $marca->id }}">Restaurar</button>
                                        @endif

                                    </div>
                                </td>
                            </tr>

                            <!-- Modal -->
                            <div class="modal fade" id="confirmModal-{{ $marca->id }}" tabindex="-1"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Eliminar Marca</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            @if ($marca->caracteristica->estado == 1)
                                                Deseas eliminar la categoria: <span
                                                    class="fw-bolder">{{ $marca->caracteristica->nombre }}</span>?
                                            @else
                                                Deseas restaurar la categoria: <span
                                                    class="fw-bolder">{{ $marca->caracteristica->nombre }}</span>?
                                            @endif
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Cancelar</button>
                                            <form
                                                action="{{ route('marcas.destroy', ['marca' => $marca->id]) }}"
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
