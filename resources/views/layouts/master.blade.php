<!DOCTYPE html>
<html lang="de">
  <head>
    @include('layouts.head')
    @yield('additional-head')
  </head>
  <body id="body">
    @include('sweet::alert')
    {{-- show laravel specific errors (like validation errors or the cause of a failed login attempt) --}}
    @include('layouts.errors')
    @include('layouts.header')
    @yield('content')
    @include('layouts.footer')
  </body>
</html>
