<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <link rel="icon" type="image/png" href="{!! asset('/images/logo.png')!!}">
        <title>{!!config('app.name')!!}</title>
        <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap.min.css') }}">

        <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css')}}" />
		<link rel="stylesheet" href="{{ asset('css/ace-fonts.css')}}" />

		<!-- ace styles -->

		<link rel="stylesheet" href="{{ asset('css/ace.min.css')}}" />
		<link rel="stylesheet" href="{{ asset('css/ace-rtl.min.css')}}" />
        
        <script src="{{ asset('js/jquery-2.0.3.min.js')}}"></script>
        <script src="{{ asset('js/jquery.validate.min.js')}}"></script>
    	<script src="{{ asset('js/jquery.form.js')}}"></script>
    
    </head>
    <body ng-app='MyApp' class="login-layout">
        @if (env('APP_DEBUG') == true)
             <div class="alert alert-danger text-center no-mg-b">You are on the development server! If you are here by mistake, please let me know at <strong>admin@gmail.com</strong></div>
        @endif
   
        <div class="container signin-cont">
            @yield('content')
        </div>
      
        @yield('scripts')
    </body>
</html>