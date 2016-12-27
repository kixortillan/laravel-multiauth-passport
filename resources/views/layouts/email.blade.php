<html>
    <head>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            body{
                font-family: sans-serif;
            }

            .text-center{
                text-align: center;
            }

            .btn{
                padding: 20px 56px;
                border: 1px solid #337ab7;
                background-color: #337ab7;
                color: #fff;
                line-height: 68px;
                font-weight: bold;
                text-decoration: none;
            }

            .btn:hover{
                text-decoration: underline;
            }

            .container{
                margin: auto;
                width: 75%;
                border: 1px solid #eee;
                padding: 32px 48px;
                border-radius: 4px;
                background: rgba(238, 238, 238, 0.35) none repeat scroll 0 0;
                box-shadow: 0 1px 1px rgba(0, 0, 0, 0.125);
            }
        </style>
    </head>
    <body>
        <div class="container">
            @yield('content')
        </div>
    </body>
</html>