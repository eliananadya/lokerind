<!DOCTYPE html>
<html lang="en">

<head>
    @include('layouts.meta')
    @include('layouts.style')
</head>

<body>
    @include('layouts.navbar')

    @yield('content')

    @include('layouts.footer')
</body>

</html>
