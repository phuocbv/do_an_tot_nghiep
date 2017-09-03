<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <title>gui link reset password</title>

    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap-theme.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}" type="text/css">
    {{--<link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap-theme.min.css') }}" type="text/css">--}}
    <link rel="stylesheet" href="{{ asset('bootstrap/css/style.css') }}" type="text/css">

    <!-- jvascrip and jquery external -->
    {{--<script src="../../public/bootstrap/js/bootstrap.js" type="text/javascript"></script>--}}
    {{--<script src="../../public/bootstrap/js/jquery.min.js" type="text/javascript"></script>--}}
    {{--<script src="../../public/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>--}}
    {{--<script src="../../public/bootstrap/js/jquery-1.11.2.min.js" type="text/javascript"></script>--}}
</head>
<body>
	<div class="container-fluid">
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 col-lg-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Reset password</h3>
                </div>  
                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    <form action="{{ url('/password/reset') }}" method="POST" role="form">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="token" value="{{ $token }}">              
                        <div class="form-group">
                            <label for="">email</label>
                            <input id="email" type="email" class="form-control" name="email" value="{{$email}}">
                            <label for="">password</label>
                            <input id="password" type="password" class="form-control" name="password">
                            <label for="">confirm-password</label>
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation">
                        </div>
                        <button type="submit" class="btn btn-primary">Reset Pass</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="{{ asset('bootstrap/js/jquery.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('bootstrap/js/bootstrap.js') }}" type="text/javascript"></script>
{{--<script src="{{ asset('bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>--}}
{{--<script src="{{ asset('bootstrap/js/jquery-1.11.2.min.js') }}" type="text/javascript"></script>--}}
</html>