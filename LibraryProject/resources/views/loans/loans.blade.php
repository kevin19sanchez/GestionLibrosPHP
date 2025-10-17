@extends('template.main')

@section('title', 'Prestamos')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold">Gestión de Préstamos</h1>
            <p class="text-muted">Administra los préstamos de libros y controla las devoluciones.</p>
        </div>
        <a href="#" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalNuevoPrestamo">
            <i class="fas fa-plus me-1"></i> Nuevo Préstamo
        </a>
    </div>

    @if (session('mensaje'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('mensaje') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Registro de préstamos -->
    <div class="card bg-dark text-white">
        <div class="card-header">
            <h5 class="mb-0">Registro de Préstamos</h5>
            <small class="text-muted">Lista completa de transacciones de préstamos</small>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-dark table-striped table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Usuario</th>
                            <th>Libro</th>
                            <th>Fecha Préstamo</th>
                            <th>Fecha Devolución</th>
                            <th>Fecha Devuelto</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($allprestamo as $prestamo)
                        <tr>
                            <td>{{ $prestamo->usuario->nombre }} {{ $prestamo->usuario->apellido }}</td>
                            <td>{{ $prestamo->libro->titulo }}</td>
                            <td>{{ $prestamo->fecha_prestamo
                            ? \Carbon\Carbon::parse($prestamo->fecha_prestamo)->format('Y-m-d')
                            : 'N/A' }}</td>
                            <td>{{ $prestamo->fecha_devolver
                            ? \Carbon\Carbon::parse($prestamo->fecha_devolver)->format('Y-m-d')
                            : 'N/A'  }}</td>
                            <td>
                                @if($prestamo->fecha_devuelto)
                                    <span class="badge bg-success">{{ $prestamo->fecha_devuelto
                                    ? \Carbon\Carbon::parse($prestamo->fecha_devolver)->format('Y-m-d')
                                    : 'N/A' }}</span>
                                @else
                                    <span>No devuelto</span>
                                @endif
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-outline-light" data-bs-toggle="modal" data-bs-target="#modalDetallesPrestamo{{ $prestamo->id }}">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>

                        <!-- Modal: Detalles del Préstamo -->
                        <div class="modal fade" id="modalDetallesPrestamo{{ $prestamo->id }}" tabindex="-1" aria-labelledby="modalDetallesPrestamoLabel{{ $prestamo->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content bg-dark text-white">
                                    <div class="modal-header border-secondary">
                                        <h5 class="modal-title" id="modalDetallesPrestamoLabel{{ $prestamo->id }}">
                                            <i class="fas fa-eye me-2"></i> Detalles del Préstamo
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Usuario</label>
                                            <p class="form-control bg-secondary text-white border-0">{{ $prestamo->usuario->nombre }} {{ $prestamo->usuario->apellido }}</p>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Libro</label>
                                            <p class="form-control bg-secondary text-white border-0">{{ $prestamo->libro->titulo }}</p>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Fecha Préstamo</label>
                                            <p class="form-control bg-secondary text-white border-0">
                                                {{ $prestamo->fecha_prestamo
                                                ? \Carbon\Carbon::parse($prestamo->fecha_devolver)->format('Y-m-d')
                                                : 'N/A' }}
                                            </p>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Fecha Devolución</label>
                                            <p class="form-control bg-secondary text-white border-0">
                                                {{ $prestamo->fecha_devolver
                                                ? \Carbon\Carbon::parse($prestamo->fecha_devolver)->format('Y-m-d')
                                                : 'N/A' }}
                                            </p>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Fecha Devuelto</label>
                                            <p class="form-control bg-secondary text-white border-0">
                                                @if($prestamo->fecha_devuelto)
                                                    {{ $prestamo->fecha_devuelto
                                                    ? \Carbon\Carbon::parse($prestamo->fecha_devolver)->format('Y-m-d')
                                                    : 'N/A' }}
                                                @else
                                                    <span class="text-muted">No devuelto</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-secondary">
                                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                                            <i class="fas fa-times me-1"></i> Cerrar
                                        </button>
                                        @if(!$prestamo->fecha_devuelto)
                                            <form action="{{ route('loans.devolver', $prestamo->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-success btn-sm">
                                                    <i class="fas fa-undo me-1"></i> Devolver Libro
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Paginación -->
        <div class="card-footer bg-dark d-flex justify-content-between align-items-center">
            <small class="text-muted">
                {{-- Mostrando {{ $prestamos->firstItem() }}-{{ $prestamos->lastItem() }} de {{ $prestamos->total() }} préstamos --}}
            </small>
            <div>
                {{-- $prestamos->links('pagination::bootstrap-5') --}}
            </div>
        </div>
    </div>
</div>

<!-- Modal: Nuevo Préstamo -->
<div class="modal fade" id="modalNuevoPrestamo" tabindex="-1" aria-labelledby="modalNuevoPrestamoLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content bg-dark text-white">
        <div class="modal-header border-secondary">
            <h5 class="modal-title" id="modalNuevoPrestamoLabel">
            <i class="fas fa-plus me-2"></i> Nuevo Préstamo
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <form action="{{ route('loans.create') }}" method="POST">
            @csrf
            <div class="modal-body">
            <div class="mb-3">
                <label for="id_usuarios" class="form-label">Usuario *</label>
                <select class="form-select bg-secondary text-white border-0" id="id_usuarios" name="id_usuarios" required>
                <option value="">Seleccionar usuario</option>
                @foreach($allusuarios as $usuario)
                    <option value="{{ $usuario->id }}">
                    {{-- $usuario->id --}} {{ $usuario->nombre }} {{ $usuario->apellido }}{{--({{ $usuario->correo }})--}}
                    </option>
                @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="id_libro" class="form-label">Libro *</label>
                <select class="form-select bg-secondary text-white border-0" id="id_libro" name="id_libro" required>
                <option value="">Seleccionar libro</option>
                @foreach($alllibros as $libro)
                    <option value="{{ $libro->id }}" {{ $libro->estado != 'disponible' ? 'disabled' : '' }}>
                    {{-- [{{ $libro->id }}]--}} {{ $libro->titulo }}
                    @if($libro->estado != 'disponible')
                        — No disponible
                    @endif
                    </option>
                @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="fecha_prestamo" class="form-label">Fecha de Préstamo *</label>
                <input type="datetime-local" class="form-control bg-secondary text-white border-0" id="fecha_prestamo" name="fecha_prestamo" required>
            </div>
            <div class="mb-3">
                <label for="fecha_devolver" class="form-label">Fecha de Devolución *</label>
                <input type="datetime-local" class="form-control bg-secondary text-white border-0" id="fecha_devolver" name="fecha_devolver" required>
            </div>
            </div>
            <div class="modal-footer border-secondary">
            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                <i class="fas fa-times me-1"></i> Cancelar
            </button>
            <button type="submit" class="btn btn-success btn-sm">
                <i class="fas fa-save me-1"></i> Registrar Préstamo
            </button>
            </div>
        </form>
        </div>
    </div>
</div>
@endsection
