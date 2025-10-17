<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Sistema de Biblioteca')</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body class="d-flex flex-column min-vh-100 bg-dark text-white">
    <!-- Navbar: siempre expandido y horizontal -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <!-- Logo y título -->
            <a class="navbar-brand d-flex align-items-center" href="#">
                <i class="fas fa-book-open me-2"></i>
                BiblioSystem
            </a>

            <!-- Menú de navegación (siempre horizontal) -->
            <ul class="navbar-nav d-flex flex-row mb-2 mb-lg-0">
                <li class="nav-item me-2">
                    <a class="nav-link active" aria-current="page" href="{{ route('dash.index') }}">Dashboard</a>
                </li>
                <li class="nav-item me-2">
                    <a class="nav-link" href="{{ route('libro.index') }}">Libros</a>
                </li>
                <li class="nav-item me-2">
                    <a class="nav-link" href="{{ route('autor.index') }}">Autores</a>
                </li>
                <li class="nav-item me-2">
                    <a class="nav-link" href="{{ route('category.index') }}">Categorías</a>
                </li>
                <li class="nav-item me-2">
                    <a class="nav-link" href="{{ route('prestamo.index') }}">Préstamos</a>
                </li>
                <li class="nav-item me-2">
                    <a class="nav-link" href="{{ route('users.index') }}">Usuarios</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Contenido principal -->
    <main class="container py-4 flex-grow-1">
        @yield('content')
    </main>

    <!-- Footer semántico -->
    <footer class="bg-light border-top mt-auto py-3">
        <div class="container text-center text-muted">
            <p class="mb-0">
                <i class="fas fa-copyright"></i> {{ date('Y') }} Sistema de Gestión de Biblioteca
            </p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
