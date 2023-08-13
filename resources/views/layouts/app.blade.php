<!DOCTYPE html>
<html lang="en">
    <head>

        @include('includes.head')

        @section('css_global')
        @show

    </head>
    <body class="sb-nav-fixed">

        @include('includes.navbar')

        <div id="layoutSidenav">

            @include('includes.sidebar')

            <div id="layoutSidenav_content">

                <main>
                    @yield('content')                    
                </main>

                @include('includes.footer')

            </div>

        </div>

        @section('js_global')
        @show

        @section('js_app')
        @show

        @routes

    </body>
</html>
