<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">

        <style>
            body {
                font-family: 'Nunito', sans-serif;
            }
        </style>
    </head>
    <body class="antialiased">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <span>Results</span>
                            <span> <a href="/voter">Vote Page</a></span>
                        </div>
                        
                        <div class="card-body">
                            @foreach ($results as $result)
                            @if ($result['votes'])
                                
                            <span>{{$result['question']}}</span>
                            <br>
                            <span>Result :</span>
                             <strong>{{$result['yesVotes']}} </strong>  voted <strong>Yes</strong><br>
                             <strong>{{$result['votes']}}  </strong>total voters
                             <br><br>
                            @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
        