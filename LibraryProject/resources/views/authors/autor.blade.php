@extends('template.main')

@section('title', 'Autores')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold">Gestión de Autores</h1>
            <p class="text-muted">Administra la información de los autores en tu biblioteca.</p>
        </div>
        <a href="#" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalAgregarAutor">
            <i class="fas fa-plus me-1"></i> Agregar Autor
        </a>
    </div>

    @if (session('mensaje'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('mensaje') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Lista de autores (en cards) -->
    <div class="row g-3">
        @foreach($allauthor as $autor)
        <div class="col-md-6 col-lg-4">
            <div class="card bg-dark text-white h-100">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-user-circle me-2 text-info fs-4"></i>
                            <div>
                                <h5 class="card-title mb-1">{{ $autor->nombre_autor }}</h5>
                                <small>{{ $autor->nacionalidad }}</small>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="#" class="btn btn-sm btn-outline-light" data-bs-toggle="modal" data-bs-target="#modalEditarAutor{{ $autor->id }}">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modalEliminarAutor{{ $autor->id }}">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal: Editar Autor -->
        <div class="modal fade" id="modalEditarAutor{{ $autor->id }}" tabindex="-1" aria-labelledby="modalEditarAutorLabel{{ $autor->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content bg-dark text-white">
                    <div class="modal-header border-secondary">
                        <h5 class="modal-title" id="modalEditarAutorLabel{{ $autor->id }}">
                            <i class="fas fa-edit me-2"></i> Editar Autor
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <form action="{{ route('author.update', $autor->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="nombre_autor_{{ $autor->id }}" class="form-label">Nombre *</label>
                                <input type="text" class="form-control bg-secondary text-white border-0" id="nombre_autor_{{ $autor->id }}" name="nombre_autor" value="{{ old( 'nombre_autor', $autor->nombre_autor) }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="nacionalidad_{{ $autor->id }}" class="form-label">Nacionalidad</label>
                                <input type="text" class="form-control bg-secondary text-white border-0" id="nacionalidad_{{ $autor->id }}" name="nacionalidad" value="{{ $autor->nacionalidad }}">
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

        <!-- Modal: Eliminar Autor -->
        <div class="modal fade" id="modalEliminarAutor{{ $autor->id }}" tabindex="-1" aria-labelledby="modalEliminarAutorLabel{{ $autor->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content bg-dark text-white">
                    <div class="modal-header border-secondary">
                        <h5 class="modal-title" id="modalEliminarAutorLabel{{ $autor->id }}">
                            <i class="fas fa-exclamation-triangle text-warning me-2"></i> Confirmar Eliminación
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <p>¿Estás seguro de que deseas eliminar al autor <strong>{{ $autor->nombre_autor }}</strong>?</p>
                        <p class="text-muted small">
                            Esta acción no se puede deshacer. Los libros asociados a este autor quedarán sin autor asignado.
                        </p>
                    </div>
                    <div class="modal-footer border-secondary">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i> Cancelar
                        </button>
                        <form action="{{ route('author.delete', $autor->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash-alt me-1"></i> Eliminar+
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Paginación -->
    <div class="d-flex justify-content-center mt-4">
        {{-- $autores->links('pagination::bootstrap-5') --}}
    </div>
</div>

<!-- Modal: Agregar Autor -->
<div class="modal fade" id="modalAgregarAutor" tabindex="-1" aria-labelledby="modalAgregarAutorLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content bg-dark text-white">
        <div class="modal-header border-secondary">
            <h5 class="modal-title" id="modalAgregarAutorLabel">
            <i class="fas fa-plus me-2"></i> Agregar Nuevo Autor
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <form action="{{ route('author.create') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="mb-3">
                    <label for="nombre_autor" class="form-label">Nombre *</label>
                    <input type="text" class="form-control bg-secondary text-white border-0" id="nombre_autor" name="nombre_autor" required>
                </div>
                <div class="mb-3">
                    <label for="nacionalidad" class="form-label">Nacionalidad</label>
                    <input type="text" class="form-control bg-secondary text-white border-0" id="nacionalidad" name="nacionalidad">
                </div>
            </div>
            <div class="modal-footer border-secondary">
            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                <i class="fas fa-times me-1"></i> Cancelar
            </button>
            <button type="submit" class="btn btn-success btn-sm">
                <i class="fas fa-save me-1"></i> Guardar Autor
            </button>
            </div>
        </form>
        </div>
    </div>
</div>
@endsection
