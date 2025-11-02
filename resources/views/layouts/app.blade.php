<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'Dashboard')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />

    <style>
        body {
            overflow-x: hidden;
        }

        .content {
            margin-left: 250px;
            padding: 20px;
        }

        .sidebar {
            background-color: #1e293b;
            /* dark blue-gray background */
            color: #fff;
            width: 250px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            padding: 1rem;
            overflow-y: auto;
            border-right: 1px solid #334155;
        }

        .sidebar h4 {
            font-weight: bold;
            color: #f8fafc;
            margin-bottom: 2rem;
        }

        .sidebar .nav-link {
            color: #cbd5e1;
            font-size: 16px;
            margin-bottom: 0.5rem;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .sidebar .nav-link:hover {
            color: #fff;
            background-color: #334155;
            border-radius: 5px;
        }

        .sidebar .nav-link.fw-bold {
            color: #fff;
            font-weight: bold;
        }

        .sidebar .collapse .nav-link {
            padding-left: 1.5rem;
        }

        .sidebar .nav-item .bi-chevron-down {
            transition: transform 0.3s ease;
        }

        .sidebar .nav-item .collapsed .bi-chevron-down {
            transform: rotate(0deg);
        }

        .sidebar .nav-item[aria-expanded="true"] .bi-chevron-down {
            transform: rotate(180deg);
        }

        .sidebar .nav-link.active {
            background-color: #495057;
            color: #ffffff !important;
            border-radius: 5px;
        }
    </style>
</head>

<body>

    @include('partials.sidebar')

    <div class="main-content">
        @include('partials.navbar')

        <div class="content">
            @yield('content')
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>