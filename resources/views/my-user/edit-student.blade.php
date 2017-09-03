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
        <a href="edit-student?id={{$studentID}}" style="color: #333">
            <span class="company-register name-page-profile">Chỉnh sửa thông tin</span>
        </a>
    </div>
    <div class="panel panel-default">
        <div class="panel-body">
            @foreach($myUser as $mu)
                <?php
                $student = \App\Student::getStudent($mu->id);
                ?>
                <form action="edit-student-form" method="POST" role="form" id="editStudent">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="myUserID" id="myUserID" value="{{encrypt($mu->id)}}">

                    <div class="form-group">
                        @foreach($student as $s)
                            <span>Chỉnh sửa thông tin sinh viên:</span>
                            <span style="font-weight: bold">{{$s->name}}</span>
                        @endforeach
                        <br>
                        <label for="">Mã sinh viên</label>
                        <input type="number" name="msv" id="msv" class="form-control" min="20120000" step="1"
                               required="required" maxlength="8" placeholder="nhập mã sinh viên"
                               value="{{$mu->user_name}}">
                        <br>
                        <label for="">Tên đăng nhập</label>
                        <input type="text" name="userName" id="userName" class="form-control"
                               readonly="readonly" placeholder="nhập tên đăng nhập" value="{{$mu->user_name}}">
                        <br>
                        <label>Email</label>
                        <input type="email" name="email" id="email" class="form-control" value="{{$mu->email}}"
                               placeholder="nhập email">
                        <br>
                        <label for="">Mật khẩu</label>
                        <input type="password" name="password" id="password"
                               class="form-control" placeholder="nhập mật khẩu">
                        <br>
                        <label for="">Nhập lại Mật khẩu</label>
                        <input type="password" name="rePassword" id="rePassword"
                               class="form-control" placeholder="nhập lại mật khẩu">
                        <br>
                        @foreach($student as $s)
                            <label id="labelFullName">Họ và Tên</label>
                            <input type="text" name="fullName" id="fullName" class="form-control" value="{{$s->name}}"
                                   required="required" placeholder="họ và tên">
                            <br>
                            <label>Khóa đào tạo</label>
                            <input type="number" name="grade" id="grade" class="form-control" min="57" step="1"
                                   required="required" maxlength="8" placeholder="Nhập khóa học" value="{{$s->grade}}">
                            <br>
                            <label>Chương trình đào tạo</label>
                            <select name="programingUniversity" id="programingUniversity" class="form-control">
                                @if($s->program_university=='kysu')
                                    <option value="kysu" selected="selected">Kỹ sư</option>
                                    <option value="cunhan">Cử nhân</option>
                                    <option value="vietnhat">Việt Nhật</option>
                                    <option value="clc">Chất lượng cao</option>
                                    <option value="kstn">Kỹ sư tài năng</option>
                                @elseif($s->program_university=='cunhan')
                                    <option value="kysu">Kỹ sư</option>
                                    <option value="cunhan" selected="selected">Cử nhân</option>
                                    <option value="vietnhat">Việt Nhật</option>
                                    <option value="clc">Chất lượng cao</option>
                                    <option value="kstn">Kỹ sư tài năng</option>
                                @elseif($s->program_university=='vietnhan')
                                    <option value="kysu">Kỹ sư</option>
                                    <option value="cunhan">Cử nhân</option>
                                    <option value="vietnhat" selected="selected">Việt Nhật</option>
                                    <option value="clc">Chất lượng cao</option>
                                    <option value="kstn">Kỹ sư tài năng</option>
                                @elseif($s->program_university=='clc')
                                    <option value="kysu">Kỹ sư</option>
                                    <option value="cunhan">Cử nhân</option>
                                    <option value="vietnhat">Việt Nhật</option>
                                    <option value="clc" selected="selected">Chất lượng cao</option>
                                    <option value="kstn">Kỹ sư tài năng</option>
                                @elseif($s->program_university=='kstn')
                                    <option value="kysu">Kỹ sư</option>
                                    <option value="cunhan">Cử nhân</option>
                                    <option value="vietnhat">Việt Nhật</option>
                                    <option value="clc">Chất lượng cao</option>
                                    <option value="kstn" selected="selected">Kỹ sư tài năng</option>
                                @else
                                    <option value="" selected="selected">Chương trình đào tạo</option>
                                    <option value="kysu">Kỹ sư</option>
                                    <option value="cunhan">Cử nhân</option>
                                    <option value="vietnhat">Việt Nhật</option>
                                    <option value="clc">Chất lượng cao</option>
                                    <option value="kstn">Kỹ sư tài năng</option>
                                @endif
                            </select>
                            <br>
                            <label for="">Số điện thoại</label>
                            <input type="number" name="phone" id="inputPhone" class="form-control"
                                   value="{{$s->phone}}" min="0" max="99999999999" step="1">
                            <br>
                            <label>Ngày sinh</label>
                            @if(date('Y',strtotime($s->birthday))!=1970&&date('Y',strtotime($s->birthday))!=-0001)
                                <input type="date" name="birthday" id="birthday" class="form-control"
                                       value="{{date("Y-m-d",strtotime($s->birthday))}}">
                            @else
                                <input type="date" name="birthday" id="birthday" class="form-control">
                            @endif
                            <br>
                            <label for="">Kỹ năng lập trình</label>
                            <input type="text" class="form-control" name="programingSkill"
                                   value="{{$s->programing_skill}}" maxlength="255">
                            <br>
                            <label for="">Kỹ năng lập trình tốt nhất</label>
                            <input type="text" class="form-control" name="programingSkillBest"
                                   value="{{$s->programing_skill_best}}" maxlength="255">
                            <br>
                            <label for="">Tiếng anh</label>
                            <input type="text" class="form-control" name="english" value="{{$s->english}}"
                                   maxlength="11">
                        @endforeach
                    </div>
                    <button type="submit" class="btn btn-primary" name="addOne">Chỉnh sửa</button>
                </form>
            @endforeach
        </div>
    </div>
    <script>
        var myUserID = $('#myUserID').val();
        $('#editStudent').validate({
            rules: {
                password: {
                    minlength: 6
                },
                rePassword: {
                    equalTo: "#password"
                },
                msv: {
                    required: true,
                    remote: {
                        url: "check-username-edit",
                        type: "POST",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "myUserID": myUserID
                        }
                    }
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
                msv: {
                    required: "vui lòng nhập msv",
                    remote: "mã sinh viên đã tồn tại"
                },
                email: {
                    required: "vui lòng nhập email",
                    email: "không đúng định dạng email",
                    remote: "email đã tồn tại"
                }
            }
        });
        $('#msv').keyup(function () {
            var msv = $(this).val();
            $('#userName').val(msv);
            var email = msv + "@student.hut.edu.vn";
            $('#email').val(email);
        });
    </script>
@endsection