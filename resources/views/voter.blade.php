<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Vote Page</title>

        <meta name="csrf-token" content="{{ csrf_token() }}">
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
                   
                        <div style="display: none" id="message" ></div>
                       
                            <form method="post"  id="form">
                                @csrf
                                <div>
                                    <label for="">Enter User Name *</label>
                                    <input type="text" class="form-control" name="username">
                                    <br>
                                </div>
                                <div id="questions"></div>

                           <button type="button" class="form-control" id="submit">Submit </button>
                        </form>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
 
    <script
  src="https://code.jquery.com/jquery-3.6.0.min.js"
  integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
  crossorigin="anonymous"></script>
    <script>
        var submitButton = $("#submit");
        function query($method,$url,$data = {}) {
          return  $.ajax({
                method: $method,
                url: $url,
            
                error: function($error) {
                    printError($error.responseJSON.message)

                },
                headers:{
                    'X-CSRF-TOKEN' : $("[name=csrf-token]").attr('content')
                    
                },
                data: $data
                
            });
            
        }


        query('GET','api/referendum/questions',{}).done(
            function (params) {
              
                let referendums = params.referendums;
                $n = generateQuestions(referendums)
                $html = $('#questions')

                $next = $n.next();
                if(!($next.done)){

                    printToHtml($html,$next)
                }

                buttonControl(submitButton,"Vote ("+$next.value.id+"/"+referendums.length+")")
                
                $("#submit").click(function (e) {
                    e.preventDefault();
                    
                    clearError()
                    
                    const form = document.getElementById('form');
                    $data = new FormData(form);
                    
                    
                    votes = new Array()
                    
                    for (const [key, value] of $data) {
                        
                        if (key.split('_')[1] == 'vote') {
                            votes.push({question_id : key.split('_')[0], vote : value })
                            
                        }else if (key == 'referendum_id') {
                            referendum_id = value
                        }else if (key == 'username') {
                            
                            username = value
                        }
                        
                        
                        
                    }
                    
                    query('POST','api/referendum/vote',{
                        username : username,
                        referendum_id : referendum_id,
                        votes : votes == null ? new Array([votes]) : votes
                    }).done(
                        function ($res) {
                            if ($res.success){
                                $html.empty()
                                $next = $n.next();
                                if(!($next.done)){
                                    
                                    printToHtml($html,$next)
                                    buttonControl(submitButton,"Vote ("+$next.value.id+"/"+referendums.length+")")

                                }else {

                                    buttonControl(submitButton,"Vote Completed", 'disabled')
                                }
                                
                
                            }
                           
                        }
                    )
                       
                        
                })
                
            })
            
            
        function *generateQuestions(params) {
           let index = 0
           
            while(index < params.length){
                yield  params[index];
                index++;
            }
        }

        function printToHtml(e,data){
            str = `<h3> Referendum ${data.value.id} Questions</h3>`;
            str += `<input type="hidden" name="referendum_id" value="${data.value.id}">`;
            data.value.questions.forEach(element => {
                str += (`<strong> ${element.title}</strong>
                <br>
                <input type="radio" name="${element.id}_vote" value="true" id="${element.id}_true">
                <label for="${element.id}_true">True</label><br>
                <input type="radio" name="${element.id}_vote" value="false" id="${element.id}_false">
                <label for="${element.id}_false">False</label><br>
                <br>
                `)
                
            });
            
            
            e.html(str)
            
        }

        function buttonControl(e,text,disabled=false){
            e.html(text)
            e.attr('disabled',disabled)
        }

        function printError($message){
            var loc = $('#message')
            loc.empty()
            loc.html(`<div class="alert alert-danger">${$message}</div>`)
            loc.show()
        }
        function clearError(){
            var loc = $('#message')
            loc.empty()
        }
      
        

    </script>
</body>
</html>
