<!DOCTYPE html>
<html lang="en">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
@include('layout.head')
<body>
    @include('layout.navbar')    

    @yield('main')

    @yield('scripts')
</body>
</html>