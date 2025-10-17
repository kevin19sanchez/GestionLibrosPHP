@extends('template.main')

@section('title', 'Dashboard')

@section('content')
<!-- Contenido principal del dashboard -->
    <main class="container-fluid mt-4">
        <div class="row">
            <!-- Columna izquierda: Filtros y acciones -->
            <div class="col-lg-3">
                <!-- Acciones Rápidas -->
                <div class="card bg-dark text-white mb-4">
                    <div class="card-header">Acciones Rápidas</div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('libro.index') }}" class="btn btn-outline-primary">
                                <i class="fas fa-book-open me-2"></i> Agregar Libro
                            </a>
                            <a href="{{ route('prestamo.index') }}" class="btn btn-outline-info">
                                <i class="fas fa-clock me-2"></i> Nuevo Préstamo
                            </a>
                            <a href="{{ route('autor.index') }}" class="btn btn-outline-success">
                                <i class="fas fa-user-plus me-2"></i> Agregar Autor
                            </a>
                            <a href="{{ route('category.index') }}" class="btn btn-outline-warning">
                                <i class="fas fa-folder-plus me-2"></i> Nueva Categoría
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Filtros Avanzados -->
                <div class="card bg-dark text-white mb-4">
                    <div class="card-header">Filtros Avanzados</div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('dash.index') }}">
                            <div class="mb-3">
                                <label class="form-label">Autor</label>
                                <select name="id_autor" class="form-select bg-secondary text-white border-dark">
                                    <option value="">Todos los autores</option>
                                    @foreach ($allautores as $autor)
                                        <option value="{{ $autor->id }}" {{ request('id_autor') == $autor->id ? 'selected' : '' }}>
                                            {{ $autor->nombre_autor }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Categoría</label>
                                <select name="id_categoria" class="form-select bg-secondary text-white border-dark">
                                    <option value="">Todas las categorías</option>
                                    @foreach ($allcategorias as $category)
                                        <option value="{{ $category->id }}" {{ request('id_categoria') == $category->id ? 'selected' : ''}}>
                                            {{ $category->nombre_categoria }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Disponibilidad</label>
                                <select name="disponibilidad" class="form-select bg-secondary text-white border-dark">
                                    <option value="">Todos</option>
                                    <option value="disponible" {{ request('disponibilidad') == 'disponible' ? 'selected' : '' }}>Disponibles</option>
                                    <option value="prestado" {{ request('disponibilidad') == 'prestado' ? 'selected' : '' }}>Prestados</option>
                                </select>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <label class="form-label">Año Desde</label>
                                    <input name="anio_desde" type="number"
                                    class="form-control bg-secondary text-white border-dark"
                                    placeholder="Desde"
                                    value="{{ request('anio_desde') }}">
                                </div>
                                <div class="col">
                                    <label class="form-label">Hasta</label>
                                    <input name="anio_hasta" type="number"
                                    class="form-control bg-secondary text-white border-dark"
                                    placeholder="Hasta"
                                    value="{{ request('anio_hasta') }}">
                                </div>
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-filter me-2"></i> Aplicar Filtros
                                </button>
                                <a href="{{ route('dash.index') }}" class="btn btn-outline-secondary text-white">Limpiar Filtros</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Columna derecha: Búsqueda y resultados -->
            <div class="col-lg-9">
                <!-- Barra de búsqueda global -->
                <div class="card bg-dark text-white mb-4">
                    <div class="card-body">
                        <form method="GET" action="{{ route('dash.index') }}" class="d-flex">
                            <input type="hidden" name="autor_id" value="{{ request('id_autor') }}">
                            <input type="hidden" name="categoria_id" value="{{ request('id_categoria') }}">
                            <input type="hidden" name="disponibilidad" value="{{ request('disponibilidad') }}">
                            <input type="hidden" name="anio_desde" value="{{ request('anio_desde') }}">
                            <input type="hidden" name="anio_hasta" value="{{ request('anio_hasta') }}">

                            <div class="input-group">
                                <span class="input-group-text bg-secondary border-dark">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" name="buscar" value="{{ request('buscar') }}"
                                class="form-control bg-secondary text-white border-dark"
                                placeholder="Buscar por título, autor, ISBN...">
                            </div>
                            <button class="btn btn-outline-light ms-2" type="submit">Buscar</button>
                        </form>
                    </div>
                </div>

                <!-- Resultados -->
                <div class="card bg-dark text-white mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        Resultados de Búsqueda
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                Más relevantes
                            </button>
                            <ul class="dropdown-menu dropdown-menu-dark">
                                <li><a class="dropdown-item" href="#">Más recientes</a></li>
                                <li><a class="dropdown-item" href="#">Título A-Z</a></li>
                                <li><a class="dropdown-item" href="#">Autor</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <p>Se encontraron {{ $libros->total() }} libro(s) que coinciden con tu búsqueda.</p>

                        <!-- Tarjeta de libro 1 -->
                        @foreach ($libros as $book)
                        <div class="card mb-3 bg-secondary text-white">
                            <div class="card-body d-flex align-items-start">
                                <div class="me-3">
                                    <i class="fas fa-book fa-2x text-primary"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="mb-1">{{ $book->titulo }}</h5>
                                    <small>por
                                        @if ($book->autores->count() > 0)
                                            {{ $book->autores->pluck('nombre_autor')->join(', ') }}
                                        @else
                                            Autor Desconocido
                                        @endif
                                    </small>
                                    <div class="row mt-2">
                                        <div class="col-md-3"><strong>Categoría:</strong>{{ $book->categoria->nombre_categoria ?? 'N/A' }}</div>
                                        <div class="col-md-3"><strong>ISBN:</strong>{{ $book->ISBN }}</div>
                                        <div class="col-md-2"><strong>Año:</strong>{{ $book->anio }}</div>
                                        <div class="col-md-2 text-end">
                                            <span class="badge {{ $book->estado == 'disponible' ? 'bg-success' : 'bg-danger' }}">
                                                {{ ucfirst($book->estado) }}
                                            </span>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
