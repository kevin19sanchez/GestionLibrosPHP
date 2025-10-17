@extends('template.main')

@section('title', 'Usuarios')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold">Gestión de Usuarios</h1>
            <p class="text-muted">Administra los usuarios de la biblioteca y sus préstamos</p>
        </div>
        <a href="#" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalNuevoUsuario">
            <i class="fas fa-plus me-1"></i> Nuevo Usuario
        </a>
    </div>

    @if (session('mensaje'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('mensaje') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Lista de usuarios -->
    <div class="card bg-dark text-white">
        <div class="card-header">
            <h5 class="mb-0">Usuarios Registrados</h5>
            <small class="text-muted">Total: {{-- $usuarios->total() --}} usuarios</small>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-dark table-striped table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Email</th>
                            <th>Préstamos</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($allusers as $usuario)
                        <tr>
                            <td>
                                <strong>{{ $usuario->nombre }}</strong><br>
                                <small>ID: {{ $usuario->id }}</small>
                            </td>
                            <td>{{ $usuario->apellido }}</td>
                            <td>{{ $usuario->correo }}</td>
                            <td>
                                <i class="fas fa-book me-1"></i>
                                {{-- $usuario->prestamos_count --}}
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-outline-light me-1" data-bs-toggle="modal" data-bs-target="#modalEditarUsuario{{ $usuario->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modalEliminarUsuario{{ $usuario->id }}">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>

                        <!-- Modal: Editar Usuario -->
                        <div class="modal fade" id="modalEditarUsuario{{ $usuario->id }}" tabindex="-1" aria-labelledby="modalEditarUsuarioLabel{{ $usuario->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content bg-dark text-white">
                                    <div class="modal-header border-secondary">
                                        <h5 class="modal-title" id="modalEditarUsuarioLabel{{ $usuario->id }}">
                                            <i class="fas fa-edit me-2"></i> Editar Usuario
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                    </div>
                                    <form action="{{ route('users.update', $usuario->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="nombre_{{ $usuario->id }}" class="form-label">Nombre *</label>
                                                <input type="text" class="form-control bg-secondary text-white border-0" id="nombre_{{ $usuario->id }}" name="nombre" value="{{ $usuario->nombre }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="apellido_{{ $usuario->id }}" class="form-label">Apellido *</label>
                                                <input type="text" class="form-control bg-secondary text-white border-0" id="apellido_{{ $usuario->apellido }}" name="apellido" value="{{ $usuario->apellido }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="correo_{{ $usuario->id }}" class="form-label">Email *</label>
                                                <input type="email" class="form-control bg-secondary text-white border-0" id="correo_{{ $usuario->id }}" name="correo" value="{{ $usuario->correo }}" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-secondary">
                                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                                                <i class="fas fa-times me-1"></i> Cancelar
                                            </button>
                                            <button type="submit" class="btn btn-success btn-sm">
                                                <i class="fas fa-save me-1"></i> Guardar Cambios
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Modal: Eliminar Usuario -->
                        <div class="modal fade" id="modalEliminarUsuario{{ $usuario->id }}" tabindex="-1" aria-labelledby="modalEliminarUsuarioLabel{{ $usuario->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content bg-dark text-white">
                                    <div class="modal-header border-secondary">
                                        <h5 class="modal-title" id="modalEliminarUsuarioLabel{{ $usuario->id }}">
                                            <i class="fas fa-exclamation-triangle text-warning me-2"></i> Confirmar Eliminación
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>¿Estás seguro de que deseas eliminar al usuario <strong>{{ $usuario->nombre }}</strong>?</p>
                                        <p class="text-muted small">
                                            Esta acción no se puede deshacer. Todos sus préstamos quedarán sin usuario asociado.
                                        </p>
                                    </div>
                                    <div class="modal-footer border-secondary">
                                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                                            <i class="fas fa-times me-1"></i> Cancelar
                                        </button>
                                        <form action="{{ route('users.delete', $usuario->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash-alt me-1"></i> Eliminar
                                            </button>
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

        <!-- Paginación -->
        <div class="card-footer bg-dark d-flex justify-content-between align-items-center">
            <small class="text-muted">
                {{-- Mostrando {{ $usuarios->firstItem() }}-{{ $usuarios->lastItem() }} de {{ $usuarios->total() }} usuarios --}}
            </small>
            <div>
                {{-- {{ $usuarios->links('pagination::bootstrap-5') }} --}}
            </div>
        </div>
    </div>
</div>

<!-- Modal: Nuevo Usuario -->
<div class="modal fade" id="modalNuevoUsuario" tabindex="-1" aria-labelledby="modalNuevoUsuarioLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content bg-dark text-white">
        <div class="modal-header border-secondary">
            <h5 class="modal-title" id="modalNuevoUsuarioLabel">
            <i class="fas fa-plus me-2"></i> Nuevo Usuario
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <form action="{{ route('users.create') }}" method="POST">
            @csrf
            <div class="modal-body">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre *</label>
                <input type="text" class="form-control bg-secondary text-white border-0" id="nombre" name="nombre" required>
            </div>
            <div class="mb-3">
                <label for="apellido" class="form-label">Apellido *</label>
                <input type="text" class="form-control bg-secondary text-white border-0" id="apellido" name="apellido" required>
            </div>
            <div class="mb-3">
                <label for="correo" class="form-label">Email *</label>
                <input type="email" class="form-control bg-secondary text-white border-0" id="correo" name="correo" required>
            </div>
            </div>
            <div class="modal-footer border-secondary">
            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                <i class="fas fa-times me-1"></i> Cancelar
            </button>
            <button type="submit" class="btn btn-success btn-sm">
                <i class="fas fa-save me-1"></i> Guardar Usuario
            </button>
            </div>
        </form>
        </div>
    </div>
</div>
@endsection
