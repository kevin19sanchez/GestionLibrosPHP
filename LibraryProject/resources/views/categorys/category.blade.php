@extends('template.main')

@section('title', 'Categorias')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold">Gestión de Categorías</h1>
            <p>Organiza los libros por categorías temáticas para facilitar su búsqueda.</p>
        </div>
        <a href="#" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalAgregarCategoria">
            <i class="fas fa-plus me-1"></i> Agregar Categoría
        </a>
    </div>

    @if (session('mensaje'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('mensaje') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Lista de categorías (en cards) -->
    <div class="row g-3">
        @foreach($viewcategory as $categoria)
        <div class="col-md-6 col-lg-4">
            <div class="card bg-dark text-white h-100">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-tag me-2 text-info fs-4"></i>
                            <div>
                                <h5 class="card-title mb-1">{{ $categoria->nombre_categoria }}</h5>
                                <small class="text-muted">
                                    {{--  --}}
                                </small>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="#" class="btn btn-sm btn-outline-light" data-bs-toggle="modal" data-bs-target="#modalEditarCategoria{{ $categoria->id }}">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modalEliminarCategoria{{ $categoria->id }}">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mt-auto">
                        <div class="d-flex justify-content-between">
                            <span>Libros</span>
                            <strong class="text-primary">{{--  --}}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal: Editar Categoría -->
        <div class="modal fade" id="modalEditarCategoria{{ $categoria->id }}" tabindex="-1" aria-labelledby="modalEditarCategoriaLabel{{ $categoria->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content bg-dark text-white">
                    <div class="modal-header border-secondary">
                        <h5 class="modal-title" id="modalEditarCategoriaLabel{{--  --}}">
                            <i class="fas fa-edit me-2"></i> Editar Categoría
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <form action="{{ route('category.update', $categoria->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="nombre_categoria" class="form-label">Nombre *</label>
                                <input
                                type="text"
                                class="form-control bg-secondary text-white border-0"
                                id="nombre_categoria{{ $categoria->id }}"
                                name="nombre_categoria"
                                value="{{ old('nombre_categoria', $categoria->nombre_categoria) }}"
                                required>
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

        <!-- Modal de confirmación de eliminación -->
        <div class="modal fade" id="modalEliminarCategoria{{ $categoria->id }}" tabindex="-1" aria-labelledby="modalEliminarCategoriaLabel{{ $categoria->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content bg-dark text-white">
                    <div class="modal-header border-secondary">
                        <h5 class="modal-title" id="modalEliminarCategoriaLabel{{ $categoria->id }}">
                            <i class="fas fa-exclamation-triangle text-warning me-2"></i> Confirmar Eliminación
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <p>¿Estás seguro de que deseas eliminar la categoría <strong>{{ $categoria->nombre_categoria }}</strong>?</p>
                        <p class="text-muted small">
                            Esta acción no se puede deshacer. Si hay libros asociados, podrían quedar sin categoría.
                        </p>
                    </div>
                    <div class="modal-footer border-secondary">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i> Cancelar
                        </button>
                        <form action="{{ route('category.delete', $categoria->id) }}" method="POST" class="d-inline">
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
    </div>

    <!-- Paginación -->
    <div class="d-flex justify-content-center mt-4">
        {{--  --}}
    </div>
</div>

<!-- Modal: Agregar Categoría -->
<div class="modal fade" id="modalAgregarCategoria" tabindex="-1" aria-labelledby="modalAgregarCategoriaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content bg-dark text-white">
        <div class="modal-header border-secondary">
            <h5 class="modal-title" id="modalAgregarCategoriaLabel">
            <i class="fas fa-plus me-2"></i> Agregar Nueva Categoría
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <form action="{{ route('category.create') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="mb-3">
                    <label for="nombre_categoria" class="form-label">Nombre de la Categoría *</label>
                    <input type="text" class="form-control bg-secondary text-white border-0" id="nombre_categoria" name="nombre_categoria" required>
                </div>
            </div>
            <div class="modal-footer border-secondary">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Cancelar
                </button>
                <button type="submit" class="btn btn-success btn-sm">
                    <i class="fas fa-save me-1"></i> Guardar Categoría
                </button>
            </div>
        </form>
        </div>
    </div>
</div>
@endsection
