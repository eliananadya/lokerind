<!DOCTYPE html>
<html lang="en">

<head>
    @include('layouts.meta')
    @include('layouts.style')
    @stack('css')
</head>

<body>
    @include('sweetalert::alert')
    @if (!isset($hideNavbar) || !$hideNavbar)
        @include('layouts.navbar')
    @endif

    @yield('content')

    @if (!isset($hideFooter) || !$hideFooter)
        @include('layouts.footer')
    @endif

    @include('layouts.script')
    @stack('js')
</body>

</html>
