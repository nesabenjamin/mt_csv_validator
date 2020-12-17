<html>
    <head>
        <title>CSV Validator</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    </head>
    <body>
        <div class="container mt-2 mb-5">                        
            
            <nav class="navbar navbar-expand-sm bg-dark navbar-dark">
                <ul class="navbar-nav">
                    <li class="nav-item active">
                        <a class="nav-link" href="{{URL::to('/')}}">CSV Validator</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('configure')}}">Configuration</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('documentation')}}">Documentation</a>
                    </li>
                    <li class="nav-item">
                        <!-- <a class="nav-link" href="{{route('upload')}}">Test</a> -->
                    </li>
                   
                </ul>
            </nav>
        </div>

        <div class="container">
            @yield('content')
        </div>
    </body>
</html>