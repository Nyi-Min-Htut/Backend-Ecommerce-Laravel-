<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
</head>
<body style="margin:0; font-family: Arial, sans-serif;">

    @include('layouts.navbar')

    <div style="display: flex;">
        @include('layouts.sidebar')

        <main style="flex: 1; padding: 20px;">
            @yield('content')
        </main>
    </div>

</body>
</html>
