<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Gestionar Citas | Darketo</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/admin.png') }}" type="image/png">

    <!-- External CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Tailwind Configuration -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        beige: {
                            DEFAULT: '#D4B895',
                            light: '#E5D4BC',
                            dark: '#C3A074'
                        },
                        dark: {
                            100: '#2D2D2D',
                            200: '#242424',
                            300: '#1A1A1A',
                            400: '#121212',
                            500: '#0A0A0A'
                        }
                    },
                    fontFamily: {
                        'poppins': ['Poppins', 'sans-serif']
                    },
                    transitionDuration: {
                        '400': '400ms'
                    }
                }
            }
        }
    </script>

    <!-- Custom Styles -->
    <style>
        /* Base Styles */
        * {
            font-family: 'Poppins', sans-serif;
        }

        /* Sidebar Animations */
        .sidebar {
            transition: all 0.4s ease;
        }

        .sidebar-link {
            position: relative;
            transition: all 0.3s ease;
        }

        .sidebar-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 0;
            height: 100%;
            background: linear-gradient(90deg, var(--tw-color-beige) 0%, transparent 100%);
            opacity: 0;
            transition: all 0.3s ease;
            border-radius: 0.5rem;
            z-index: -1;
        }

        .sidebar-link:hover::before {
            width: 100%;
            opacity: 0.15;
        }

        /* Card Animations */
        .stats-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }

        /* Table Styles */
        .table-row-hover:hover {
            background-color: rgba(212, 184, 149, 0.05);
        }
    </style>
</head>

<body class="bg-dark-400 text-white font-poppins min-h-screen flex">
    <!-- Sidebar -->
    <aside class="w-64 h-screen bg-dark-300 fixed left-0 top-0 shadow-xl">
        <div class="p-6 flex flex-col h-full">
            <!-- Logo -->
            <div class="flex items-center gap-3 mb-8">
                <img src="{{ asset('images/logo.png') }}" alt="Darketo Logo" class="w-10 h-10 rounded-lg shadow-md">
                <h2 class="text-xl font-bold text-beige">DARKETO</h2>
            </div>

            <!-- Navigation -->
            <nav class="space-y-2 flex-grow">
                <a href="{{ route('admin.panel') }}" class="sidebar-link flex items-center gap-3 py-3 px-4 rounded-lg text-beige hover:bg-dark-200 transition-all duration-300">
                    <i class="fas fa-tachometer-alt w-5"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('admin.users.index') }}" class="sidebar-link flex items-center gap-3 py-3 px-4 rounded-lg text-beige hover:bg-dark-200 transition-all duration-300">
                    <i class="fas fa-users w-5"></i>
                    <span>Usuarios</span>
                </a>
                <a href="{{ route('admin.barbers.index') }}" class="sidebar-link flex items-center gap-3 py-3 px-4 rounded-lg text-beige hover:bg-dark-200 transition-all duration-300">
                    <i class="fas fa-cut w-5"></i>
                    <span>Barberos</span>
                </a>
                <a href="{{ route('admin.citas.index') }}" class="sidebar-link flex items-center gap-3 py-3 px-4 rounded-lg text-beige hover:bg-dark-200 transition-all duration-300">
                    <i class="fas fa-calendar-alt w-5"></i>
                    <span>Citas</span>
                </a>
            </nav>

            <!-- Logout Button -->
            <form action="{{ route('admin.logout') }}" method="POST" class="mt-auto">
                @csrf
                <button type="submit" class="w-full py-3 px-4 bg-red-600 hover:bg-red-700 text-white rounded-lg flex items-center justify-center gap-2 transition-colors duration-300 shadow-md">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Cerrar sesión</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 ml-64 p-8">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <header class="mb-8">
                <h1 class="text-3xl font-semibold text-white">Gestión de Citas</h1>
            </header>

            <!-- Action Button -->
            <div class="mb-6">
                <a href="{{ route('admin.citas.create') }}" class="bg-beige hover:bg-beige-dark text-dark-400 font-medium py-2 px-6 rounded-lg inline-flex items-center gap-2 transition-colors duration-300 shadow-md">
                    <i class="fas fa-plus"></i>
                    <span>Crear Cita</span>
                </a>
            </div>

            <!-- Notifications -->
            @if(session('success'))
                <div class="bg-green-500 text-white p-4 rounded-lg mb-6 shadow-md flex items-center gap-3">
                    <i class="fas fa-check-circle"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-500 text-white p-4 rounded-lg mb-6 shadow-md flex items-center gap-3">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ session('error') }}
                </div>
            @endif

            <!-- Table -->
            <div class="bg-dark-300 rounded-xl shadow-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full table-x-auto">
                        <thead class="bg-dark-200">
                            <tr>
                                <th class="py-4 px-6 text-left text-sm font-semibold text-white">ID</th>
                                <th class="py-4 px-6 text-left text-sm font-semibold text-white">Cliente</th>
                                <th class="py-4 px-6 text-left text-sm font-semibold text-white">Barbero</th>
                                <th class="py-4 px-6 text-left text-sm font-semibold text-white">Fecha</th>
                                <th class="py-4 px-6 text-left text-sm font-semibold text-white">Hora</th>
                                <th class="py-4 px-6 text-left text-sm font-semibold text-white">Estado</th>
                                <th class="py-4 px-6 text-center text-sm font-semibold text-white">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($citas as $cita)
                                <tr class="border-t border-dark-200 table-row-hover transition-colors duration-200">
                                    <td class="py-4 px-6 text-white">{{ $cita->id }}</td>
                                    <td class="py-4 px-6 text-white">{{ $cita->cliente->name }}</td>
                                    <td class="py-4 px-6 text-white">{{ $cita->barber->name }}</td>
                                    <td class="py-4 px-6 text-white">{{ $cita->fecha }}</td>
                                    <td class="py-4 px-6 text-white">{{ $cita->hora }}</td>
                                    <td class="py-4 px-6 text-white">{{ $cita->estado }}</td>
                                    <td class="py-4 px-6 text-center">
                                        <a href="{{ route('admin.citas.edit', $cita->id) }}" class="text-beige hover:text-beige-dark transition-colors duration-300">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.citas.destroy', $cita->id) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Estás seguro de eliminar esta cita?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-700 transition-colors duration-300 ml-4">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</body>

</html>
