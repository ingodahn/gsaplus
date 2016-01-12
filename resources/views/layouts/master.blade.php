<!DOCTYPE html>
<html lang="de">
  <head>
    @include('layouts.head')
    @yield('additional-head')
  </head>
  <body>
    @include('layouts.header')
    @yield('content')
    @include('layouts.footer')
  </body>
</html>
