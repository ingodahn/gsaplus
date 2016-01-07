<!DOCTYPE html>
<html lang="de">
  <head>
    @include('layouts.head')
  </head>
  <body>
    @include('layouts.header')
    @yield('content')
    @include('layouts.footer')
  </body>
</html>
