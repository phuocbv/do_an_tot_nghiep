@foreach($arrAssign as $aa)
    @foreach($aa as $asub)
        <?php
        $student = \App\Student::getStudentFollowID($asub->student_id);
        $company = \App\Company::getCompanyFollowID($asub->company_id);
        $lecture = \App\Lecture::getLectureFollowID($asub->lecture_id);
        $studentInCourse = \App\StudentInternShipCourse::getStudentInCourse($asub->student_id, $asub->internship_course_id);
        $companyAssess = \App\CompanyAssess::getCompanyAssess($asub->id);
        $course = \App\InternShipCourse::getInCourse($asub->internship_course_id);
        ?>
        @foreach($studentInCourse as $sic)
            <?php
            $studentReport = \App\StudentReport::getStudentReport($sic->id);
            ?>
            @if(count($studentReport)>0)
                {{--modal xem bao cao cua sinh vien--}}
                <div class="modal fade" id="{{$sic->id}}{{"view-student-report"}}" tabindex="-1" role="dialog"
                     aria-labelledby="myModalLabel">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header" style="text-align: center">
                                <button type="button" class="close" data-dismiss="modal"
                                        aria-label="Close"><span
                                            aria-hidden="true">&times;</span>
                                </button>

                                <h4 class="modal-title" id="myModalLabel" style="font-weight: bold">Báo cáo</h4>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                                    </div>
                                    <div class="col-xs-12 col-sm-10 col-md-10 col-lg-10">
                                        <div class="row" id="{{$sic->id}}{{"printStudentReport"}}">
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6"
                                                 style="text-align: center;font-weight: bold">
                                                <span>TRƯỜNG ĐẠI HỌC BÁCH KHOA HÀ NỘI</span><br>
                                                <span>VIỆN CÔNG NGHỆ THÔNG TIN  VÀ TRUYỀN THÔNG</span><br>
                                                <span>––––––––––––</span>
                                            </div>
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6"
                                                 style="text-align: center;font-weight: bold">
                                                <span>CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM</span><br>
                                                <span>Độc Lập – Tự do – Hạnh phúc</span><br>
                                                <span>––––––––––––––––––––––––––––</span><br>
                                                @foreach($studentReport as $sr)
                                                    Hà Nội, ngày {{date('d',strtotime($sr->date_report))}}
                                                    tháng {{date('m',strtotime($sr->date_report))}}
                                                    năm {{date('Y',strtotime($sr->date_report))}}
                                                @endforeach
                                            </div>
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"
                                                 style="text-align: center;margin-top: 20px;margin-bottom: 15px;font-weight: bold">
                                                BÁO CÁO<br>
                                                KẾT QUẢ THỰC TẬP TẠI ĐƠN VỊ NGOÀI TRƯỜNG
                                            </div>
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                Kính gửi:
                                                @foreach($studentReport as $sr)
                                                    {{$sr->school}}
                                                @endforeach
                                            </div>
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                @foreach($student as $s)
                                                    <div>Họ và tên sinh viên:{{$s->name}}</div>
                                                    <div>Lớp, Khóa:{{$s->grade}}</div>
                                                    <div>Điện thoại:{{$s->phone}}</div>
                                                @endforeach
                                                @foreach($company as $c)
                                                    <div>Địa chỉ đến thực tập:{{($c->name)}}{{$c->address}}</div>
                                                @endforeach
                                                @foreach($lecture as $l)
                                                    <div>Giáo viên phụ trách:{{$l->name}}</div>
                                                @endforeach
                                            </div>
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                @foreach($student as $s)
                                                    <div>MSSV:{{$s->msv}}</div>
                                                    <div></div>
                                                @endforeach
                                            </div>
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">Thời gian được cử
                                                đi
                                                thực tập: từ ngày
                                                @foreach($course as $c)
                                                    {{date('d/m/Y',strtotime($c->from_date))}},
                                                    đến ngày
                                                    {{date('d/m/Y',strtotime($c->to_date))}}
                                                @endforeach
                                            </div>
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"
                                                 style="font-weight: bold">
                                                I, Nội dung công việc được giao<br>
                                                @foreach($studentReport as $sr)
                                                    <span style="font-weight: normal; margin-left: 15px">{{$sr->assign_work}}</span>
                                                @endforeach
                                            </div>
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"
                                                 style="font-weight: bold">
                                                II, Kết quả thực hiện<br>
                                                @foreach($studentReport as $sr)
                                                    <span style="font-weight: normal; margin-left: 15px">{{$sr->result}}</span>
                                                @endforeach
                                            </div>
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"
                                                 style="font-weight: bold">
                                                II, Tự đánh giá
                                            </div>
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"
                                                 style="font-weight: bold">
                                                - Ưu điểm<br>
                                                @foreach($studentReport as $sr)
                                                    <span style="font-weight: normal; margin-left: 15px">{{$sr->advantage}}</span>
                                                @endforeach
                                            </div>
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"
                                                 style="font-weight: bold">
                                                - Nhược điểm<br>
                                                @foreach($studentReport as $sr)
                                                    <span style="font-weight: normal; margin-left: 15px">{{$sr->dis_advantage}}</span>
                                                @endforeach
                                            </div>
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            </div>
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6"
                                                 style="text-align: center;margin-top: 30px;font-weight: bold">
                                                SINH VIÊN
                                                <br>
                                                @foreach($student as $s)
                                                    {{$s->name}}
                                                @endforeach
                                            </div>
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6"
                                                 style="text-align: center;margin-top: 50px;font-weight: bold">
                                                XÁC NHẬN NƠI THỰC TẬP
                                            </div>
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6"
                                                 style="text-align: center;margin-top: 50px;font-weight: bold">
                                                XÁC NHẬN CỦA VIỆN
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"
                                         style="text-align: center;margin-top: 20px">
                                        <hr>
                                        <form action="" method="POST" class="form-inline" role="form">
                                            <button type="button" class="btn btn-primary print-student-report"
                                                    data-id="{{$sic->id}}">In báo cáo
                                            </button>
                                        </form>
                                    </div>
                                    <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> {{--ket thuc xem bao cao cua sinh vien--}}
            @endif
            {{--modal xem nhan xet cua cong ty--}}
            @if(count($companyAssess)>0)
                <div class="modal fade" id="{{$sic->id}}{{"view-company-assess"}}" tabindex="-1" role="dialog"
                     aria-labelledby="myModalLabel">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header" style="text-align: center">
                                <button type="button" class="close" data-dismiss="modal"
                                        aria-label="Close"><span
                                            aria-hidden="true">&times;</span>
                                </button>

                                <h4 class="modal-title" id="myModalLabel" style="font-weight: bold">Nhận xét
                                    sinh viên</h4>
                            </div>
                            <div class="modal-body">
                                <div class="row" id="{{$sic->id}}{{"printAs"}}">
                                    <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                                    </div>
                                    <div class="col-xs-12 col-sm-10 col-md-10 col-lg-10">
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"
                                                 style="font-weight: bold;text-align: center">
                                                BẢNG ĐÁNH GIÁ KẾT QUẢ THỰC TẬP DOANH NGHIỆP
                                            </div>
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            </div>
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                                @foreach($companyAssess as $ca)
                                                    <span style="float:right;">Ngày {{date('d',strtotime($ca->date_assess))}}
                                                        tháng {{date('m',strtotime($ca->date_assess))}}
                                                        năm {{date('Y',strtotime($ca->date_assess))}}</span>
                                                @endforeach
                                            </div>
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                	 <span>Tên sinh viên:
                                                         @foreach($student as $s)
                                                             {{$s->name}}
                                                         @endforeach
                                                </span><br>
                                            </div>
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                @foreach($company as $c)
                                                    <span>Công ty tiếp nhận thực tập: {{$c->name}}</span><br>
                                                    <span>Người phụ trách: {{$c->hr_name}}</span><br>
                                                    <span>Email: {{$c->hr_mail}}</span>
                                                @endforeach
                                            </div>
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                <span style="font-weight: bold">Đánh giá chung về khóa thực tập</span>
                                                @foreach($companyAssess as $ca)
                                                    {{$ca->assess_general}}
                                                @endforeach
                                            </div>
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                <span style="font-weight: bold">Đánh giá kết quả thực tập</span>

                                                <div class="table-responsive">
                                                    <table class="table table-hover table-bordered">
                                                        <tbody>
                                                        @foreach($companyAssess as $ca)
                                                            <tr>
                                                                <td>Năng lực IT</td>
                                                                @if($ca->IT==1)
                                                                    <td>
                                                                        <input type="radio" name="it" id=""
                                                                               value="1" checked="checked">1
                                                                    </td>
                                                                @else
                                                                    <td>
                                                                        <input type="radio" name="it" id=""
                                                                               value="1" disabled="disabled">1
                                                                    </td>
                                                                @endif
                                                                @if($ca->IT==2)
                                                                    <td>
                                                                        <input type="radio" name="it" id=""
                                                                               value="2" checked="checked">2
                                                                    </td>
                                                                @else
                                                                    <td>
                                                                        <input type="radio" name="it" id=""
                                                                               value="2" disabled="disabled">2
                                                                    </td>
                                                                @endif
                                                                @if($ca->IT==3)
                                                                    <td>
                                                                        <input type="radio" name="it" id=""
                                                                               value="3" checked="checked">3
                                                                    </td>
                                                                @else
                                                                    <td>
                                                                        <input type="radio" name="it" id=""
                                                                               value="3" disabled="disabled">3
                                                                    </td>
                                                                @endif
                                                                @if($ca->IT==4)
                                                                    <td>
                                                                        <input type="radio" name="it" id=""
                                                                               value="4" checked="checked">4
                                                                    </td>
                                                                @else
                                                                    <td>
                                                                        <input type="radio" name="it" id=""
                                                                               value="4" disabled="disabled">4
                                                                    </td>
                                                                @endif
                                                                @if($ca->IT==5)
                                                                    <td>
                                                                        <input type="radio" name="it" id=""
                                                                               value="5" checked="checked">5
                                                                    </td>
                                                                @else
                                                                    <td>
                                                                        <input type="radio" name="it" id=""
                                                                               value="5" disabled="disabled">5
                                                                    </td>
                                                                @endif
                                                            </tr>
                                                            <tr>
                                                                <td>Phương pháp làm việc</td>
                                                                @if($ca->work==1)
                                                                    <td>
                                                                        <input type="radio" name="work" id=""
                                                                               value="1" checked="checked">1
                                                                    </td>
                                                                @else
                                                                    <td>
                                                                        <input type="radio" name="work" id=""
                                                                               value="1" disabled="disabled">1
                                                                    </td>
                                                                @endif
                                                                @if($ca->work==2)
                                                                    <td>
                                                                        <input type="radio" name="work" id=""
                                                                               value="2" checked="checked">2
                                                                    </td>
                                                                @else
                                                                    <td>
                                                                        <input type="radio" name="work" id=""
                                                                               value="2" disabled="disabled">2
                                                                    </td>
                                                                @endif
                                                                @if($ca->work==3)
                                                                    <td>
                                                                        <input type="radio" name="work" id=""
                                                                               value="3" checked="checked">3
                                                                    </td>
                                                                @else
                                                                    <td>
                                                                        <input type="radio" name="work" id=""
                                                                               value="3" disabled="disabled">3
                                                                    </td>
                                                                @endif
                                                                @if($ca->work==4)
                                                                    <td>
                                                                        <input type="radio" name="work" id=""
                                                                               value="4" checked="checked">4
                                                                    </td>
                                                                @else
                                                                    <td>
                                                                        <input type="radio" name="work" id=""
                                                                               value="4" disabled="disabled">4
                                                                    </td>
                                                                @endif
                                                                @if($ca->work==5)
                                                                    <td>
                                                                        <input type="radio" name="work" id=""
                                                                               value="5" checked="checked">5
                                                                    </td>
                                                                @else
                                                                    <td>
                                                                        <input type="radio" name="work" id=""
                                                                               value="5" disabled="disabled">5
                                                                    </td>
                                                                @endif
                                                            </tr>
                                                            <tr>
                                                                <td>Năng lực năm bắt công việc</td>
                                                                @if($ca->learn_work==1)
                                                                    <td>
                                                                        <input type="radio" name="learnWork"
                                                                               id=""
                                                                               value="1" checked="checked">1
                                                                    </td>
                                                                @else
                                                                    <td>
                                                                        <input type="radio" name="learnWork"
                                                                               id=""
                                                                               value="1" disabled="disabled">1
                                                                    </td>
                                                                @endif
                                                                @if($ca->learn_work==2)
                                                                    <td>
                                                                        <input type="radio" name="learnWork"
                                                                               id=""
                                                                               value="2" checked="checked">2
                                                                    </td>
                                                                @else
                                                                    <td>
                                                                        <input type="radio" name="learnWork"
                                                                               id=""
                                                                               value="2" disabled="disabled">2
                                                                    </td>
                                                                @endif
                                                                @if($ca->learn_work==3)
                                                                    <td>
                                                                        <input type="radio" name="learnWork"
                                                                               id=""
                                                                               value="3" checked="checked">3
                                                                    </td>
                                                                @else
                                                                    <td>
                                                                        <input type="radio" name="learnWork"
                                                                               id=""
                                                                               value="3" disabled="disabled">3
                                                                    </td>
                                                                @endif
                                                                @if($ca->learn_work==4)
                                                                    <td>
                                                                        <input type="radio" name="learnWork"
                                                                               id=""
                                                                               value="4" checked="checked">4
                                                                    </td>
                                                                @else
                                                                    <td>
                                                                        <input type="radio" name="learnWork"
                                                                               id=""
                                                                               value="4" disabled="disabled">4
                                                                    </td>
                                                                @endif
                                                                @if($ca->learn_work==5)
                                                                    <td>
                                                                        <input type="radio" name="learnWork"
                                                                               id=""
                                                                               value="5" checked="checked">5
                                                                    </td>
                                                                @else
                                                                    <td>
                                                                        <input type="radio" name="learnWork"
                                                                               id=""
                                                                               value="5" disabled="disabled">5
                                                                    </td>
                                                                @endif
                                                            </tr>
                                                            <tr>
                                                                <td>Năng lực quản lý</td>
                                                                @if($ca->manage==1)
                                                                    <td>
                                                                        <input type="radio" name="manage" id=""
                                                                               value="1" checked="checked">1
                                                                    </td>
                                                                @else
                                                                    <td>
                                                                        <input type="radio" name="manage" id=""
                                                                               value="1" disabled="disabled">1
                                                                    </td>
                                                                @endif
                                                                @if($ca->manage==2)
                                                                    <td>
                                                                        <input type="radio" name="manage" id=""
                                                                               value="2" checked="checked">2
                                                                    </td>
                                                                @else
                                                                    <td>
                                                                        <input type="radio" name="manage" id=""
                                                                               value="2" disabled="disabled">2
                                                                    </td>
                                                                @endif
                                                                @if($ca->manage==3)
                                                                    <td>
                                                                        <input type="radio" name="manage" id=""
                                                                               value="3" checked="checked">3
                                                                    </td>
                                                                @else
                                                                    <td>
                                                                        <input type="radio" name="manage" id=""
                                                                               value="3" disabled="disabled">3
                                                                    </td>
                                                                @endif
                                                                @if($ca->manage==4)
                                                                    <td>
                                                                        <input type="radio" name="manage" id=""
                                                                               value="4" checked="checked">4
                                                                    </td>
                                                                @else
                                                                    <td>
                                                                        <input type="radio" name="manage" id=""
                                                                               value="4" disabled="disabled">4
                                                                    </td>
                                                                @endif
                                                                @if($ca->manage==5)
                                                                    <td>
                                                                        <input type="radio" name="manage" id=""
                                                                               value="5" checked="checked">5
                                                                    </td>
                                                                @else
                                                                    <td>
                                                                        <input type="radio" name="manage" id=""
                                                                               value="5" disabled="disabled">5
                                                                    </td>
                                                                @endif
                                                            </tr>
                                                            <tr>
                                                                <td>Năng lực tiếng anh</td>
                                                                @if($ca->english==1)
                                                                    <td>
                                                                        <input type="radio" name="english" id=""
                                                                               value="1" checked="checked">1
                                                                    </td>
                                                                @else
                                                                    <td>
                                                                        <input type="radio" name="english" id=""
                                                                               value="1" disabled="disabled">1
                                                                    </td>
                                                                @endif
                                                                @if($ca->english==2)
                                                                    <td>
                                                                        <input type="radio" name="english" id=""
                                                                               value="2" checked="checked">2
                                                                    </td>
                                                                @else
                                                                    <td>
                                                                        <input type="radio" name="english" id=""
                                                                               value="2" disabled="disabled">2
                                                                    </td>
                                                                @endif
                                                                @if($ca->english==3)
                                                                    <td>
                                                                        <input type="radio" name="english" id=""
                                                                               value="3" checked="checked">3
                                                                    </td>
                                                                @else
                                                                    <td>
                                                                        <input type="radio" name="english" id=""
                                                                               value="3" disabled="disabled">3
                                                                    </td>
                                                                @endif
                                                                @if($ca->english==4)
                                                                    <td>
                                                                        <input type="radio" name="english" id=""
                                                                               value="4" checked="checked">4
                                                                    </td>
                                                                @else
                                                                    <td>
                                                                        <input type="radio" name="english" id=""
                                                                               value="4" disabled="disabled">4
                                                                    </td>
                                                                @endif
                                                                @if($ca->english==5)
                                                                    <td>
                                                                        <input type="radio" name="english" id=""
                                                                               value="5" checked="checked">5
                                                                    </td>
                                                                @else
                                                                    <td>
                                                                        <input type="radio" name="english" id=""
                                                                               value="5" disabled="disabled">5
                                                                    </td>
                                                                @endif
                                                            </tr>
                                                            <tr>
                                                                <td>Năng lực làm việc nhóm</td>
                                                                @if($ca->teamwork==1)
                                                                    <td>
                                                                        <input type="radio" name="teamWork"
                                                                               id=""
                                                                               value="1" checked="checked">1
                                                                    </td>
                                                                @else
                                                                    <td>
                                                                        <input type="radio" name="teamWork"
                                                                               id=""
                                                                               value="1" disabled="disabled">1
                                                                    </td>
                                                                @endif
                                                                @if($ca->teamwork==2)
                                                                    <td>
                                                                        <input type="radio" name="teamWork"
                                                                               id=""
                                                                               value="2" checked="checked">2
                                                                    </td>
                                                                @else
                                                                    <td>
                                                                        <input type="radio" name="teamWork"
                                                                               id=""
                                                                               value="2" disabled="disabled">2
                                                                    </td>
                                                                @endif
                                                                @if($ca->teamwork==3)
                                                                    <td>
                                                                        <input type="radio" name="teamWork"
                                                                               id=""
                                                                               value="3" checked="checked">3
                                                                    </td>
                                                                @else
                                                                    <td>
                                                                        <input type="radio" name="teamWork"
                                                                               id=""
                                                                               value="3" disabled="disabled">3
                                                                    </td>
                                                                @endif
                                                                @if($ca->teamwork==4)
                                                                    <td>
                                                                        <input type="radio" name="teamWork"
                                                                               id=""
                                                                               value="4" checked="checked">4
                                                                    </td>
                                                                @else
                                                                    <td>
                                                                        <input type="radio" name="teamWork"
                                                                               id=""
                                                                               value="4" disabled="disabled">4
                                                                    </td>
                                                                @endif
                                                                @if($ca->teamwork==5)
                                                                    <td>
                                                                        <input type="radio" name="teamWork"
                                                                               id=""
                                                                               value="5" checked="checked">5
                                                                    </td>
                                                                @else
                                                                    <td>
                                                                        <input type="radio" name="teamWork"
                                                                               id=""
                                                                               value="5" disabled="disabled">5
                                                                    </td>
                                                                @endif
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                <span style="font-weight: bold">Tổng điểm: </span>
                                                <?php
                                                $sum = 0;
                                                foreach ($companyAssess as $ca) {
                                                    $sum = $ca->IT + $ca->work + $ca->learn_work + $ca->manage + $ca->english + $ca->teamwork;
                                                }
                                                ?>
                                                {{$sum}} điểm
                                            </div>
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            </div>
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                                <span style="font-weight: bold;">XÁC NHẬN NGƯỜI PHỤ TRÁCH</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"
                                         style="text-align: center;margin-top: 20px">
                                        <button type="button" class="btn btn-primary print-as"
                                                name="print-assess" data-id="{{$sic->id}}">In nhận xét
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>{{--ket thuc modal xem nhan xet cong ty ve--}}
            @endif
        @endforeach
    @endforeach
@endforeach
<script>
    $('.print-student-report').click(function () {
        var sicID = $(this).attr('data-id');
        var w = window.open('', 'printwindow');
        w.document.open();
        w.document.onreadystatechange = function () {
            if (this.readyState === 'complete') {
                this.onreadystatechange = function () {
                };
                w.focus();
                w.print();
                w.close();
            }
        };
        w.document.write('<!DOCTYPE html>');
        w.document.write('<html><head>');
        w.document.write('<link rel="stylesheet" media="screen,print" type="text/css" href="public/bootstrap/css/bootstrap-theme.css">');
        w.document.write('<link rel="stylesheet" media="screen,print" type="text/css" href="public/bootstrap/css/bootstrap.min.css" >');
        w.document.write('<link rel="stylesheet" media="screen,print" type="text/css" href="public/bootstrap/css/bootstrap-theme.min.css" >');
        w.document.write('</head><body>');
        w.document.write($("#" + sicID + "printStudentReport").html());
        w.document.write('</body></html>');
        w.document.close();
    });
    $('.print-as').click(function () {
        var sicID = $(this).attr('data-id');
        var w = window.open('', 'printwindow');
        w.document.open();
        w.document.onreadystatechange = function () {
            if (this.readyState === 'complete') {
                this.onreadystatechange = function () {
                };
                w.focus();
                w.print();
                w.close();
            }
        };
        w.document.write('<!DOCTYPE html>');
        w.document.write('<html><head>');
        w.document.write('<link rel="stylesheet" media="screen,print" type="text/css" href="public/bootstrap/css/bootstrap-theme.css">');
        w.document.write('<link rel="stylesheet" media="screen,print" type="text/css" href="public/bootstrap/css/bootstrap.min.css" >');
        w.document.write('<link rel="stylesheet" media="screen,print" type="text/css" href="public/bootstrap/css/bootstrap-theme.min.css" >');
        w.document.write('</head><body>');
        w.document.write($("#" + sicID + "printAs").html());
        w.document.write('</body></html>');
        w.document.close();
    });
</script>
