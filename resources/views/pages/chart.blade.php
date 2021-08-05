@extends('layouts.app')

@section('content')
<?php

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Chart with VueJS</title>

    </head>
    <body>
        <div id="app">
            {!! $chart->container() !!}
        </div>
        
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js" charset="utf-8"></script>
        {!! $chart->script() !!}
    </body>
</html>





<!-- End here -->
@endsection