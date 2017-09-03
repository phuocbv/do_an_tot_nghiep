<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <title>Đăng nhập</title>

    <link rel="stylesheet" href="../public/bootstrap/css/bootstrap-theme.css" type="text/css">
    <link rel="stylesheet" href="../public/bootstrap/css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="../public/bootstrap/css/bootstrap-theme.min.css" type="text/css">
    <link rel="stylesheet" href="../public/bootstrap/css/style.css" type="text/css">

    <!-- jvascrip and jquery external -->
    <script src="../public/bootstrap/js/bootstrap.js" type="text/javascript"></script>
    <script src="../public/bootstrap/js/jquery.min.js" type="text/javascript"></script>
    <script src="../public/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="../public/bootstrap/js/jquery-1.11.2.min.js" type="text/javascript"></script>
    <style type="text/css" media="screen">
        .login {
            max-width: 450px;
            margin-left: auto;
            margin-right: auto;
            margin-top: 10px;
        }

        .name-school {
            font-size: 20px;
            font-weight: bold;
            color: #FFFFFF;
        }

        .name-project {
            font-size: 30px;
            font-weight: bold;
            color: #FFFFFF;
        }

        .tile-login {
            font-weight: bold;
            text-align: center;
            font-size: 20px;
        }

        .input {
            height: 50px;
            margin-bottom: 10px;
        }

        .body-login {
            max-width: 420px;
            margin-left: auto;
            margin-right: auto;
        }
    </style>
</head>
<body>
<div class="container-fluid" style="font-family: arial">
    <div class="row" id="header">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-top: 20px;margin-bottom: 20px">
                <span class="name-school">
                    VIỆN CÔNG NGHỆ THÔNG TIN VÀ TRUYỀN THÔNG</br>
                </span>
                <span class="name-project">
                    CỔNG THÔNG TIN THỰC TẬP DOANH NGHIỆP
                </span>
        </div>
    </div>
    <div class="row login">
        <div class="panel panel-default" style="background-color: #ECECEC;">
            <div class="panel-heading" style="height: 50px">
                <h3 class="panel-title tile-login">Đặt lại mật khẩu</h3>
            </div>
            <div class="panel-body">
                @if (session('status'))
                    <div class="alert alert-success">
                        Chúng tôi đã gửi qua email một đường dẫn để bạn đặt lại mật khẩu
                    </div>
                @endif
                <form action="{{ url('/password/email') }}" method="POST" role="form">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    <div class="form-group">
                        <label for="">email</label>
                        <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}">
                        @if ($errors->has('email'))
                            <span style="color:#c0392b">
                                    <strong>Địa chỉ email không đúng</strong>
                                </span>
                        @endif
                    </div>
                    <button type="submit" class="btn btn-primary">Gửi link đặt lại mật khẩu</button>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>