<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>상담어플</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/global/plugins/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css"/>
    <script src="{{ asset('assets/global/plugins/bootstrap/js/bootstrap.min.js')}}" type="text/javascript"></script>

    <!-- Styles -->
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Raleway', sans-serif;
            font-weight: 100;
            height: 100vh;
            margin: 0;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: left;
        }

        .title {
            font-size: 84px;
        }

        .links > a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        .m-b-md {
            margin-bottom: 30px;
        }
    </style>
</head>
<body>

<div style="margin: 10px;">

    <div class="content">
        <div class="m-b-md">

            <?php echo $content?>
        </div>

        <?php
        $file_url = isset($file_url) ?  $file_url:null;
        if($file_url != null) {

        ?>
        <div class="col-md-12">
            <img id="img_url_image" width="100%" height="100%"
                 src="<?php
                 $uploadDirectory = Config::get('config.uploadDirectory');
                 $uploadURL = url('/') . "/" . $uploadDirectory . "/" . $file_url;
                 echo $uploadURL;?>"
                 style="margin-bottom: 0px"/>
        </div>
        <?php
            }
        ?>
    </div>
</div>
</body>
</html>
