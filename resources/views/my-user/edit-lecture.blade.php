@extends('home-user.index')
@section('title')
    {{'chỉnh sửa thông tin người dùng'}}
@endsection
@section('user-content')
    <style type="text/css">
        .name-notify {
            font-weight: bold;
        }

        .heading-panel {
            padding-left: 15px;
            padding-top: 5px;
        }

        label.error {
            color: #c0392b;
            margin-bottom: 20px;
        }
    </style>
    @if(session()->has('editSuccess'))
        <div class="alert alert-success myLabel" role="alert">{{session()->get('editSuccess')}}</div>
    @endif
    @if(session()->has('editError1'))
        <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            <strong>Lỗi: </strong>{{session()->get('editError1')}}
        </div>
    @endif
    @if(session()->has('editError2'))
        <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            <strong>Lỗi: </strong>{{session()->get('editError2')}}
        </div>
    @endif
    <div class="row well well-sm" style="margin-left: 0px;margin-right: 0px;margin-bottom: -1px">
        <span class="name-page-profile">Quản lý người dùng</span>
        <span class="glyphicon glyphicon-menu-right small"></span>
        <a href="list-user" style="color: #333">
            <span class="company-register name-page-profile">Danh sách người dùng</span>
        </a>
        <span class="glyphicon glyphicon-menu-right small"></span>
        <a href="edit-lecture?id={{$lectureID}}" style="color: #333">
            <span class="company-register name-page-profile">Chỉnh sửa thông tin</span>
        </a>
    </div>
    <div class="panel panel-default">
        <div class="panel-body">
            @foreach($myUser as $mu)
                <?php
                $lecture = \App\Lecture::getLecture($mu->id);
                ?>
                <form action="edit-lecture-form" method="POST" role="form" id="editLecture">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="myUserID" id="myUserID" value="{{encrypt($mu->id)}}">

                    <div class="form-group">
                        @foreach($lecture as $l)
                            <span>Chỉnh sửa thông tin giảng viên:</span>
                            <span style="font-weight: bold">{{$l->name}}</span>
                        @endforeach
                        <br>
                        <label>Email</label>
                        <input type="email" name="email" id="email" class="form-control" value="{{$mu->email}}"
                               placeholder="nhập email" required="required">
                        <br>
                        <label>Tên đăng nhập</label>
                        <input type="email" name="username" id="username" class="form-control" value="{{$mu->email}}"
                               readonly="readonly" required="required">
                        <br>
                        <label for="">Mật khẩu</label>
                        <input type="password" name="password" id="password"
                               class="form-control" placeholder="nhập mật khẩu">
                        <br>
                        <label for="">Nhập lại Mật khẩu</label>
                        <input type="password" name="rePassword" id="rePassword"
                               class="form-control" placeholder="nhập lại mật khẩu">
                        <br>
                        @foreach($lecture as $l)
                            <label>Họ tên</label>
                            <input type="text" name="fullName" id="fullname" class="form-control" value="{{$l->name}}">
                            <br>
                            <label>Ngày sinh</label>
                            @if(date('Y',strtotime($l->birthday))!=1970 &&date('Y',strtotime($l->birthday))!=-0001)
                                <input type="date" name="birthday" id="birthday" class="form-control"
                                       value="{{date("Y-m-d",strtotime($l->birthday))}}">
                            @else
                                <input type="date" name="birthday" id="birthday" class="form-control">
                            @endif
                            <br>
                            <label for="">Số điện thoại</label>
                            <input type="number" name="phone" id="inputPhone" class="form-control"
                                   value="{{$l->phone}}" min="0" max="99999999999" step="1">
                            <br>
                            <label>Trình độ</label>
                            @if($l->qualification=='thacsy')
                                <select name="qualification" id="qualification" class="form-control"
                                        required="required">
                                    <option value="thacsy" selected="selected">Thạc sỹ</option>
                                    <option value="ts">TS</option>
                                    <option value="pgsts">PGS.TS</option>
                                    <option value="gs">GS</option>
                                </select>
                            @elseif($l->qualification=='ts')
                                <select name="qualification" id="qualification" class="form-control"
                                        required="required">
                                    <option value="thacsy">Thạc sỹ</option>
                                    <option value="ts" selected="selected">TS</option>
                                    <option value="pgsts">PGS.TS</option>
                                    <option value="gs">GS</option>
                                </select>
                            @elseif($l->qualification=="pgsts")
                                <select name="qualification" id="qualification" class="form-control"
                                        required="required">
                                    <option value="thacsy">Thạc sỹ</option>
                                    <option value="ts">TS</option>
                                    <option value="pgsts" selected="selected">PGS.TS</option>
                                    <option value="gs">GS</option>
                                </select>
                            @elseif($l->qualification=='gs')
                                <select name="qualification" id="qualification" class="form-control"
                                        required="required">
                                    <option value="thacsy">Thạc sỹ</option>
                                    <option value="ts">TS</option>
                                    <option value="pgsts">PGS.TS</option>
                                    <option value="gs" selected="selected">GS</option>
                                </select>
                            @else
                                <select name="qualification" id="qualification" class="form-control"
                                        required="required">
                                    <option value="" selected="selected">Trình độ</option>
                                    <option value="thacsy">Thạc sỹ</option>
                                    <option value="ts">TS</option>
                                    <option value="pgsts">PGS.TS</option>
                                    <option value="gs">GS</option>
                                </select>
                            @endif
                            <br>
                            <label>Bộ môn</label>
                            @if($l->address=="công nghệ phần mềm")
                                <select name="address" id="address" class="form-control" required="required">
                                    <option value="công nghệ phần mềm" selected="selected">Công nghệ phần mềm</option>
                                    <option value="hệ thống thông tin">Hệ thống thông tin</option>
                                    <option value="khoa học máy tính">Khoa học máy tính</option>
                                    <option value="truyền thông và mạng máy tính">Truyền thông và mạng máy tính</option>
                                    <option value="kỹ thuật máy tính">Kỹ thuật máy tính</option>
                                </select>
                            @elseif($l->address=="hệ thống thông tin")
                                <select name="address" id="address" class="form-control" required="required">
                                    <option value="công nghệ phần mềm">Công nghệ phần mềm</option>
                                    <option value="hệ thống thông tin" selected="selected">Hệ thống thông tin</option>
                                    <option value="khoa học máy tính">Khoa học máy tính</option>
                                    <option value="truyền thông và mạng máy tính">Truyền thông và mạng máy tính</option>
                                    <option value="kỹ thuật máy tính">Kỹ thuật máy tính</option>
                                </select>
                            @elseif($l->address=="khoa học máy tính")
                                <select name="address" id="address" class="form-control" required="required">
                                    <option value="công nghệ phần mềm">Công nghệ phần mềm</option>
                                    <option value="hệ thống thông tin">Hệ thống thông tin</option>
                                    <option value="khoa học máy tính" selected="selected">Khoa học máy tính</option>
                                    <option value="truyền thông và mạng máy tính">Truyền thông và mạng máy tính</option>
                                    <option value="kỹ thuật máy tính">Kỹ thuật máy tính</option>
                                </select>
                            @elseif($l->address=="truyền thông và mạng máy tính")
                                <select name="address" id="address" class="form-control" required="required">
                                    <option value="công nghệ phần mềm">Công nghệ phần mềm</option>
                                    <option value="hệ thống thông tin">Hệ thống thông tin</option>
                                    <option value="khoa học máy tính">Khoa học máy tính</option>
                                    <option value="truyền thông và mạng máy tính" selected="selected">Truyền thông và
                                        mạng máy tính
                                    </option>
                                    <option value="kỹ thuật máy tính">Kỹ thuật máy tính</option>
                                </select>
                            @elseif($l->address=="kỹ thuật máy tính")
                                <select name="address" id="address" class="form-control" required="required">
                                    <option value="công nghệ phần mềm">Công nghệ phần mềm</option>
                                    <option value="hệ thống thông tin">Hệ thống thông tin</option>
                                    <option value="khoa học máy tính">Khoa học máy tính</option>
                                    <option value="truyền thông và mạng máy tính">Truyền thông và mạng máy tính
                                    </option>
                                    <option value="kỹ thuật máy tính" selected="selected">Kỹ thuật máy tính</option>
                                </select>
                            @else
                                <select name="address" id="address" class="form-control" required="required">
                                    <option value="" selected="selected">Bộ môn</option>
                                    <option value="công nghệ phần mềm">Công nghệ phần mềm</option>
                                    <option value="hệ thống thông tin">Hệ thống thông tin</option>
                                    <option value="khoa học máy tính">Khoa học máy tính</option>
                                    <option value="truyền thông và mạng máy tính">Truyền thông và mạng máy tính</option>
                                    <option value="kỹ thuật máy tính">Kỹ thuật máy tính</option>
                                </select>
                            @endif
                        @endforeach
                    </div>
                    <button type="submit" class="btn btn-primary" name="addOne">Chỉnh sửa</button>
                </form>
            @endforeach
        </div>
    </div>
    <script>
        var myUserID = $('#myUserID').val();
        $('#editLecture').validate({
            rules: {
                username: {
                    required: true
                },
                password: {
                    minlength: 6
                },
                rePassword: {
                    equalTo: "#password"
                },
                email: {
                    required: true,
                    email: true,
                    remote: {
                        url: "check-email-edit",
                        type: "POST",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "myUserID": myUserID
                        }
                    }
                }
            },
            messages: {
                password: {
                    minlength: "mật khẩu phải ít nhất là 6 ký tự"
                },
                rePassword: {
                    equalTo: "mật khẩu không khớp"
                },
                email: {
                    required: "vui lòng nhập email",
                    email: "không đúng định dạng email",
                    remote: "email đã tồn tại"
                },
                username: {
                    required: "vui lòng nhập username",
                }
            }
        });
        $('#email').keyup(function () {
            var email = $(this).val();
            $('#username').val(email);
        });
    </script>
@endsection