@extends('template.main')

@section('title', 'Libros')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold">Gestión de Libros</h1>
            <p class="text-muted">Administra el catálogo completo de libros de tu biblioteca.</p>
        </div>
        <a href="#" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalAgregarLibro">
            <i class="fas fa-plus me-1"></i> Agregar Libro
        </a>
    </div>

    @if (session('mensaje'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('mensaje') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Catálogo de libros -->
    <div class="card bg-dark text-white">
        <div class="card-header">
            <h5 class="mb-0">Catálogo de Libros</h5>
            <small class="text-muted">Lista completa de libros registrados en el sistema</small>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-dark table-striped table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Título</th>
                            <th>Categoría</th>
                            <th>Año</th>
                            <th>Editorial</th>
                            <th>ISBN</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($viewbooks as $libro)
                        <tr>
                            <td>
                                <i class="bi bi-book-fill me-2 text-info"></i>
                                <strong>{{ $libro->titulo }}</strong>
                            </td>
                            <td>{{ $libro->categoria->nombre_categoria ?? 'Sin categoria' }}</td>
                            <td>
                                <span class="badge bg-secondary">{{ $libro->anio }}</span>
                            </td>
                            <td>{{ $libro->editorial }}</td>
                            <td>
                                <span class="badge">
                                    {{ $libro->ISBN }}
                                </span>
                            </td>
                            <td>{{ $libro->estado }}</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-outline-light me-1" data-bs-toggle="modal" data-bs-target="#modalEditarLibro{{ $libro->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modalEliminarLibro{{ $libro->id }}">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>

                        <!-- Modal: Editar Libro -->
                        <div class="modal fade" id="modalEditarLibro{{ $libro->id }}" tabindex="-1" aria-labelledby="modalEditarLibroLabel{{ $libro->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content bg-dark text-white">
                            <div class="modal-header border-secondary">
                                <h5 class="modal-title" id="modalEditarLibroLabel{{ $libro->id }}">
                                <i class="fas fa-edit me-2"></i> Editar Libro
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                            </div>
                            <form action="{{ route('book.update', $libro->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                <div class="row g-3">
                                    <div class="col-md-12">
                                    <label for="titulo_{{ $libro->id }}" class="form-label">Título *</label>
                                    <input type="text" class="form-control bg-secondary text-white border-0" id="titulo_{{ $libro->id }}" name="titulo" value="{{ $libro->titulo }}" required>
                                    </div>

                                    <div class="col-md-6">
                                    <label for="id_categoria_{{ $libro->id }}" class="form-label">Categoría *</label>
                                    <select class="form-select bg-secondary text-white border-0" id="id_categoria_{{ $libro->id }}" name="id_categoria" required>
                                        <option value="">Seleccionar categoría</option>
                                        @foreach($viewbooks as $categoria)
                                            <option value="{{ $categoria->id }}" {{ $libro->id_categoria == $categoria->id ? 'selected' : '' }}>
                                                {{ $categoria->categoria->nombre_categoria}}
                                            </option>
                                        @endforeach
                                    </select>
                                    </div>

                                    <div class="col-md-6">
                                    <label for="anio_{{ $libro->id }}" class="form-label">Año</label>
                                    <input type="number" class="form-control bg-secondary text-white border-0" id="anio_{{ $libro->id }}" name="anio" value="{{ $libro->anio }}" min="1000" max="2025">
                                    </div>

                                    <div class="col-md-6">
                                    <label for="editorial_{{ $libro->id }}" class="form-label">Editorial</label>
                                    <input type="text" class="form-control bg-secondary text-white border-0" id="editorial_{{ $libro->id }}" name="editorial" value="{{ $libro->editorial }}">
                                    </div>

                                    <div class="col-md-6">
                                    <label for="ISBN{{ $libro->id }}" class="form-label">ISBN *</label>
                                    <input type="text" class="form-control bg-secondary text-white border-0" id="ISBN{{ $libro->id }}" name="ISBN" value="{{ $libro->ISBN }}" required>
                                    </div>

                                    <div class="col-md-6">
                                    <label for="estado_{{ $libro->id }}" class="form-label">Estado</label>
                                    <select class="form-select bg-secondary text-white border-0" id="estado_{{ $libro->id }}" name="estado">
                                        <option value="disponible" {{ $libro->estado == 'disponible' ? 'selected' : '' }}>Disponible</option>
                                        <option value="prestado" {{ $libro->estado == 'prestado' ? 'selected' : '' }}>Prestado</option>
                                    </select>
                                    </div>
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

                        <!-- Modal: Eliminar Libro -->
                        <div class="modal fade" id="modalEliminarLibro{{ $libro->id }}" tabindex="-1" aria-labelledby="modalEliminarLibroLabel{{ $libro->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content bg-dark text-white">
                                <div class="modal-header border-secondary">
                                    <h5 class="modal-title" id="modalEliminarLibroLabel{{ $libro->id }}">
                                    <i class="fas fa-exclamation-triangle text-warning me-2"></i> Confirmar Eliminación
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                </div>
                                <div class="modal-body">
                                    <p>¿Estás seguro de que deseas eliminar el libro <strong>{{ $libro->titulo }}</strong>?</p>
                                    <p class="text-muted small">
                                    Esta acción no se puede deshacer.
                                    </p>
                                </div>
                                <div class="modal-footer border-secondary">
                                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                                    <i class="fas fa-times me-1"></i> Cancelar
                                    </button>
                                    <form action="{{ route('book.delete', $libro->id) }}" method="POST" class="d-inline">
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
                Mostrando {{--  --}}-{{--  --}} de {{--  --}} libros
            </small>
            <div>
                {{--  --}}
            </div>
        </div>
    </div>
</div>

<!-- Modal: Agregar Libro -->
<div class="modal fade" id="modalAgregarLibro" tabindex="-1" aria-labelledby="modalAgregarLibroLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content bg-dark text-white">
        <div class="modal-header border-secondary">
            <h5 class="modal-title" id="modalAgregarLibroLabel">
            <i class="fas fa-plus me-2"></i> Agregar Nuevo Libro
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <form action="{{ route('books.create') }}" method="POST">
            @csrf
            <div class="modal-body">
            <div class="row g-3">
                <!-- Título -->
                <div class="col-md-12">
                <label for="titulo" class="form-label">Título *</label>
                <input type="text" class="form-control bg-secondary text-white border-0" id="titulo" name="titulo" required>
                </div>

                <!-- Categoría -->
                <div class="col-md-6">
                    <label for="id_categoria" class="form-label">Categoría *</label>
                    <select class="form-select bg-secondary text-white border-0" id="id_categoria" name="id_categoria" required>
                        <option value="">Seleccionar categoría</option>
                        @foreach($allcategory as $categoria)
                            <option value="{{ $categoria->id }}">{{ $categoria->categoria->nombre_categoria }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Año -->
                <div class="col-md-6">
                <label for="anio" class="form-label">Año *</label>
                <input type="number" class="form-control bg-secondary text-white border-0" id="anio" name="anio" min="1000" max="2025" required>
                </div>

                <!-- Editorial -->
                <div class="col-md-6">
                <label for="editorial" class="form-label">Editorial</label>
                <input type="text" class="form-control bg-secondary text-white border-0" id="editorial" name="editorial">
                </div>

                <!-- ISBN -->
                <div class="col-md-6">
                <label for="ISBN" class="form-label">ISBN *</label>
                <input type="text" class="form-control bg-secondary text-white border-0" id="ISBN" name="ISBN" required>
                </div>

                <!-- Estado (por defecto: disponible) -->
                <div class="col-md-12">
                    <label for="estado" class="form-label">Estado</label>
                    <input type="text" class="form-control bg-secondary text-white border-0" id="estado" name="estado" value="disponible" readonly>
                    <input type="hidden" name="estado" value="disponible">
                </div>
            </div>
            </div>
            <div class="modal-footer border-secondary">
            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                <i class="fas fa-times me-1"></i> Cancelar
            </button>
            <button type="submit" class="btn btn-success btn-sm">
                <i class="fas fa-save me-1"></i> Guardar Libro
            </button>
            </div>
        </form>
        </div>
    </div>
</div>
@endsection
