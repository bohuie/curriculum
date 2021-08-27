<html>
    <head></head>
    <body>
        <div id="celebration">
            @include('errors.500')
        </div>
        <script type="application/javascript">
            $(document).ready(function () {
                setTimeout(function() { 
                    $('#celebration').html('<h1>Explosion</h1>');
                }, 5000);

                setTimeout(function() {
                    $('#celebration').html("<h1>Farewell Daulton, we  will miss you! ... well, some of us ;) </h1>")
                }, 10000)

                setTimeout(function() { 
                    $('#celebration').html('<h1>Videos</h1>');
                }, 15000);

            });

        </script>
    </body>
</html>



