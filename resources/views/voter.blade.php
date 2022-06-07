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
                <div class="col-md-6">
                    <div class="card m-5">
                        <div class="card-header d-flex justify-content-between">
                            <span>Vote Page</span>
                            <span> <a href="/results">View results</a></span>
                        </div>
                        
                        <div class="card-body">
                            @if (session('message'))
                            <div class="alert alert-success" role="alert">
                                {{ session('message') }}
                            </div>
                        @endif
                        @error('username')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                        @error('referendums')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                            <form action="vote" method="post">
                                @csrf
                                <div>
                                    <label for="">Enter User Name *</label>
                                    <input type="text" class="form-control" name="username">
                                    <br>
                                </div>
                                @foreach ($questions as $question)
                                <div>
                                    <strong>{{$question->title}}</strong>
                                    <br>
                                               
                                    <input type="radio" name="referendums[{{$question->referendum_id}}][{{$question->id}}][vote]" value="true" id="{{$question->id}}_true">
                                    <label for="{{$question->id}}_true">True</label><br>
                                    
                                    <input type="radio" name="referendums[{{$question->referendum_id}}][{{$question->id}}][vote]" value="false" id="{{$question->id}}_false">
                                    <label for="{{$question->id}}_false">False</label><br>
                                    <br>
                                </div>
                                
                                @endforeach
                            <input type="submit" class="form-control" value="Vote">
                        </form>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>    
</body>
</html>
