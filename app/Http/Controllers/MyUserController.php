<?php

namespace App\Http\Controllers;

use App\Admin;
use App\Company;
use App\CompanyAssess;
use App\CompanyInternShipCourse;
use App\CompanyVote;
use App\InternShipGroup;
use App\Lecture;
use App\LectureInternShipCourse;
use App\LectureReport;
use App\MyUser;
use App\News;
use App\Student;
use App\StudentInternShipCourse;
use App\StudentReport;
use App\StudentTmp;
use App\Timekeeping;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class MyUserController extends Controller
{
    public function __construct()
    {
        $this->checkInsertMany = false;
        $this->checkRow = false;
        $this->checkForm = false;
        $this->checkExtension = false;
        $this->loopEmail = "";
        $this->loopMSV = "";
        $this->loopNameCompany = "";
        $this->loopError = "";
    }

    public function forgetSession()
    {
        session()->forget('studentLogin');
        session()->forget('lectureLogin');
        session()->forget('companyLogin');
        session()->forget('adminLogin');
    }

    /**
     * user login
     *
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function login(Request $request)
    {
        $this->forgetSession();
        $username = $request->input('username');
        $password = $request->input('password');
        if (MyUser::checkLogin($username, $password)) {
            $myUser = MyUser::getUser($username);
            foreach ($myUser as $mu) {
                if ($mu->type == 'student') {
                    Session::put('studentLogin', encrypt($mu->id));
                    return redirect('student-home');
                } elseif ($mu->type == 'lecture') {
                    Session::put('lectureLogin', encrypt($mu->id));
                    return redirect('lecture-home');
                } elseif ($mu->type == 'admin') {
                    Session::put('adminLogin', encrypt($mu->id));
                    return redirect('admin-home');
                } elseif ($mu->type == 'company') {
                    Session::put('companyLogin', encrypt($mu->id));
                    return redirect('company-home');
                }
            }
        } else {
            return view('user-login')->with('error', 'username hoặc mật khẩu không đúng');
        }
    }

    /**
     * return view my-user.add-users
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addUsers()
    {
        /*
       * get newNotify
       * get uniNotify
       * get comNotify
       */
        $notify = News::getNotify();

        $adminSession = new  SessionController();
        $admin = Admin::getAdmin($adminSession->getAdminSession());
        $type = 'admin';
        return view('my-user.add-user')->with([
            'notify' => $notify,
            'user' => $admin,
            'type' => $type
        ]);
    }

    /**
     * return page listUser view
     *
     * @return $this
     */
    public function listUser()
    {
        /*
      * get newNotify
      * get uniNotify
      * get comNotify
      */
        $notify = News::getNotify();

        $adminSession = new  SessionController();
        $admin = Admin::getAdmin($adminSession->getAdminSession());
        $type = 'admin';
        /*
         * get all student
         */
        $students = Student::all();
        $students2 = Student::all();
        /*
         * get all lecture
         */
        $lectures = Lecture::all();
        /*
         * get all company
         */
        $company = Company::all();
        return view('my-user.list-user')->with([
            'student2' => $students2,
            'student' => $students,
            'lecture' => $lectures,
            'company' => $company,
            'notify' => $notify,
            'user' => $admin,
            'type' => $type
        ]);
    }

    public function addUserOne(Request $request)
    {
        $emailStudent = $request->input('emailStudent');
        $emailLecture = $request->input('emailLecture');
        $emailCompany = $request->input('emailCompany');

        $password = $request->input('password');
        $type = $request->input('type');
        if ($type == 'student') {
            /*
             * validate du lieu truoc khi them vao bang
             */
            $input = $request->all();
            $rules = array(
                'studentUserName' => 'required',
                'password' => 'required|min:6',
                'emailStudent' => 'required|email',
                'fullName' => 'required',
                'type' => 'required',
                'grade' => 'required',
                'programingUniversity' => 'required'
            );
            $validator = Validator::make($input, $rules);
            /*
             * ket thuc doan validate
             */
            if (!$validator->fails()) {
                MyUser::insertUser($request->input('studentUserName'), $emailStudent, $password, $type);
                $userID = MyUser::getID($request->input('studentUserName'));
                Student::insertStudent($request->input('fullName'), $request->input('grade'), $request->input('programingUniversity'), $request->input('msv'), $userID);
                return redirect('list-user')->with('insertSuccess', 'OK! Thêm người dùng thành công');
            } else {
                return redirect()->back()->with('addOneError', 'Lỗi thêm người dùng');
            }
        } else if ($type == 'lecture') {
            /*
            * validate du lieu truoc khi them vao bang
            */
            $input = $request->all();
            $rules = array(
                'lectureUserName' => 'required',
                'password' => 'required|min:6',
                'emailLecture' => 'required|email',
                'fullName' => 'required',
                'type' => 'required',
                'qualification' => 'required',
                'address' => 'required'
            );
            $validator = Validator::make($input, $rules);
            /*
             * ket thuc doan validate
             */
            if (!$validator->fails()) {
                MyUser::insertUser($request->input('lectureUserName'), $emailLecture, $password, $type);
                $userID = MyUser::getID($request->input('lectureUserName'));
                Lecture::insertLecture($request->input('fullName'), $request->input('qualification'), $request->input('address'), $userID);
                return redirect('list-user')->with('insertSuccess', 'OK! Thêm người dùng thành công');
            } else {
                return redirect()->back()->with('addOneError', 'Lỗi thêm người dùng');
            }
        } else if ($type == 'company') {
            /*
            * validate du lieu truoc khi them vao bang
            */
            $input = $request->all();
            $rules = array(
                'companyUserName' => 'required',
                'password' => 'required|min:6',
                'emailCompany' => 'required|email',
                'type' => 'required',
                'nameCompany' => 'required'
            );
            $validator = Validator::make($input, $rules);
            /*
             * ket thuc doan validate
             */
            if (!$validator->fails()) {
                MyUser::insertUser($request->input('companyUserName'), $emailCompany, $password, $type);
                $userID = MyUser::getID($request->input('companyUserName'));
                Company::insertCompany($request->input('nameCompany'), $userID);
                return redirect('list-user')->with('insertSuccess', 'OK! Thêm người dùng thành công');
            } else {
                return redirect()->back()->with('addOneError', 'Lỗi thêm người dùng');
            }
        }
    }

    public function addUserMany(Request $request)
    {
        $file = $request->file('excelFile');
        if (FileController::checkExtension($file)) {
            $this->checkExtension = true;
            Excel::load($file, function ($reader) {
                $reader->each(function ($sheet) {
                    $allRow = $sheet->all();//dem so dong cua moi sheet
                    if (count($allRow) > 0) {//neu row >0 moi cho chay
                        $this->checkRow = true;
                        $firstRow = $sheet->first();
                        if (isset($firstRow['username']) && isset($firstRow['password']) && isset($firstRow['email']) && isset($firstRow['type'])
                            && isset($firstRow['grade']) && isset($firstRow['name']) && isset($firstRow['address'])
                            && isset($firstRow['qualification']) && isset($firstRow['programing_university'])
                        ) {
                            $this->checkForm = true;
                            $sheet->each(function ($row) {
                                $input = array(
                                    'username' => $row->username,
                                    'password' => $row->password,
                                    'email' => $row->email,
                                    'name' => $row->name,
                                    'type' => $row->type,
                                    'grade' => $row->grade,
                                    'programing_university' => $row->programing_university,
                                    'qualification' => $row->qualification,
                                    'address' => $row->address,
                                );
                                if ($row->type == 'student') {
                                    $rules = array(
                                        'username' => 'required',
                                        'password' => 'required|min:6',
                                        'email' => 'required|email',
                                        'type' => 'required',
                                        'name' => 'required',
                                        'grade' => 'required',
                                        'programing_university' => 'required',
                                    );
                                    $validator = Validator::make($input, $rules);
                                    if (!$validator->fails()) {
                                        if (MyUser::checkEmail($row->email) && MyUser::checkUsername($row->username)) {
                                            MyUser::insertUser($row->username, $row->email, $row->password, $row->type);
                                            $userID = MyUser::getID($row->username);
                                            Student::insertStudent($row->name, $row->grade, $row->programing_university, $row->username, $userID);
                                        } else {
                                            if (!MyUser::checkEmail($row->email)) {
                                                $this->loopEmail = $this->loopEmail . $row->email . ',';
                                            }
                                            if (!MyUser::checkUsername($row->username)) {
                                                $this->loopMSV = $this->loopMSV . $row->username . ',';
                                            }
                                        }
                                    }
                                } else if ($row->type == 'lecture') {
                                    $rules = array(
                                        'username' => 'required',
                                        'password' => 'required|min:6',
                                        'email' => 'required|email',
                                        'name' => 'required',
                                        'type' => 'required',
                                        'qualification' => 'required',
                                        'address' => 'required'
                                    );
                                    $validator = Validator::make($input, $rules);
                                    if (!$validator->fails()) {
                                        if (MyUser::checkEmail($row->email) && MyUser::checkUsername($row->username)) {
                                            MyUser::insertUser($row->username, $row->email, $row->password, $row->type);
                                            $userID = MyUser::getID($row->username);
                                            Lecture::insertLecture($row->name, $row->qualification, $row->address, $userID);
                                        } else {
                                            if (!MyUser::checkEmail($row->email)) {
                                                $this->loopEmail = $this->loopEmail . $row->email . ',';
                                            }
                                            if (!MyUser::checkUsername($row->username)) {
                                                $this->loopMSV = $this->loopMSV . $row->username . ',';
                                            }
                                        }
                                    }
                                } else if ($row->type == 'company') {
                                    $rules = array(
                                        'username' => 'required',
                                        'password' => 'required|min:6',
                                        'email' => 'required|email',
                                        'name' => 'required',
                                        'type' => 'required',
                                    );
                                    $validator = Validator::make($input, $rules);
                                    if (!$validator->fails()) {
                                        if (MyUser::checkEmail($row->email) && MyUser::checkUsername($row->username)
                                            && Company::checkNameCompany($row->name)
                                        ) {
                                            MyUser::insertUser($row->username, $row->email, $row->password, $row->type);
                                            $userID = MyUser::getID($row->username);
                                            Company::insertCompany($row->name, $userID);
                                        } else {
                                            if (!MyUser::checkEmail($row->email)) {
                                                $this->loopEmail = $this->loopEmail . $row->email . ',';
                                            }
                                            if (!MyUser::checkUsername($row->username)) {
                                                $this->loopMSV = $this->loopMSV . $row->username . ',';
                                            }
                                            if (!Company::checkNameCompany($row->name)) {
                                                $this->loopNameCompany = $this->loopNameCompany . $row->name . ',';
                                            }
                                        }
                                    }
                                }
                            });
                        }
                    }
                });
            });
        }
        if (!$this->checkExtension) {
            return redirect()->back()->with('extensionError', 'Error! không phải file excel');
        } else if (!$this->checkRow) {
            return redirect()->back()->with('rowError', 'Error! Bảng chưa có dữ liệu');
        } else if (!$this->checkForm) {
            return redirect()->back()->with('formError', 'Error! Bảng không đúng định dạng');
        } else if ($this->checkForm) {
            $this->loopError = $this->loopError . $this->loopMSV . $this->loopEmail . $this->loopNameCompany;
            if ($this->loopError == "") {
                return redirect('list-user')->with('insertSuccess', 'OK! Thêm người dùng thành công');
            } else {
                return redirect('list-user')->with([
                    'insertWarning' => 'warning',
                    'loopUserName' => $this->loopMSV,
                    'loopEmail' => $this->loopEmail,
                    'loopNameCompany' => $this->loopNameCompany
                ]);
            }
        }
    }

    public function editStudent(Request $request)
    {
        /*
       * get newNotify
       * get uniNotify
       * get comNotify
       */
        $notify = News::getNotify();

        $adminSession = new  SessionController();
        $admin = Admin::getAdmin($adminSession->getAdminSession());
        $type = 'admin';

        /*
         * lay nguoi dung la sinh vien
         */
        $studentID = decrypt($request->id);
        $student = Student::getStudentFollowID($studentID);
        $userID = $student->first()->user_id;
        $myUser = MyUser::getUserFollowUserID($userID);

        return view('my-user.edit-student')->with([
            'studentID' => $studentID,
            'myUser' => $myUser,
            'notify' => $notify,
            'user' => $admin,
            'type' => $type
        ]);
    }

    public function editLecture(Request $request)
    {
        /*
       * get newNotify
       * get uniNotify
       * get comNotify
       */
        $notify = News::getNotify();

        $adminSession = new  SessionController();
        $admin = Admin::getAdmin($adminSession->getAdminSession());
        $type = 'admin';

        /*
         * lay nguoi dung la giang vien
         */
        $lectureID = decrypt($request->id);
        $lecture = Lecture::getLectureFollowID($lectureID);
        $userID = $lecture->first()->user_id;
        $myUser = MyUser::getUserFollowUserID($userID);

        return view('my-user.edit-lecture')->with([
            'lectureID' => $lectureID,
            'myUser' => $myUser,
            'notify' => $notify,
            'user' => $admin,
            'type' => $type
        ]);
    }

    public function editCompany(Request $request)
    {
        /*
       * get newNotify
       * get uniNotify
       * get comNotify
       */
        $notify = News::getNotify();

        $adminSession = new  SessionController();
        $admin = Admin::getAdmin($adminSession->getAdminSession());
        $type = 'admin';

        /*
         * lay nguoi dung la giang vien
         */
        $companyID = decrypt($request->id);
        $company = Company::getCompanyFollowID($companyID);
        $userID = $company->first()->user_id;
        $myUser = MyUser::getUserFollowUserID($userID);

        return view('my-user.edit-company')->with([
            'companyID' => $companyID,
            'myUser' => $myUser,
            'notify' => $notify,
            'user' => $admin,
            'type' => $type
        ]);
    }

    /**
     * xoa sinh vien khoi he thong
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public static function deleteStudent(Request $request)
    {
        $studentID = decrypt($request->input('studentID'));
        $student = Student::getStudentFollowID($studentID);
        $message = "";

        //xoa nhung bao cao cua sinh vien
        $studentInCourse = StudentInternShipCourse::getSIC($studentID);
        foreach ($studentInCourse as $sic) {
            StudentReport::deleteReport($sic->id);
        }

        //xoa nhung bang cong cua sinh vien
        Timekeeping::deleteTimekeepingStudent($studentID);

        //xoa nhung nhan xet cua cong ty ve sinh vien
        $group = InternShipGroup::getInGroup($studentID);
        foreach ($group as $g) {
            CompanyAssess::deleteAsess($g->id);
        }

        //xoa những khóa thực tập mà sinh viên tham gia
        StudentInternShipCourse::deleteStudentRegister($studentID);

        //xóa những nhóm mà sinh viên tham gia
        InternShipGroup::deleteStudentRegister($studentID);

        /*
         * xoa tai khoan dang nhap cua sinh vien
         * xoa sinh vien khoi danh sach tam
         */
        foreach ($student as $s) {
            MyUser::deleteAccount($s->user_id);
            StudentTmp::deleteStudent($s->msv, -1);
            $message = $s->name;
        }

        //xoa sinh vien
        Student::deleteStudent($studentID);
        return redirect()->back()->with('deleteSuccess', 'Đã xóa sinh viên ' . $message . ' khỏi hệ thống');
    }

    /**
     * xoa giang vien khoi he thong
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public static function deleteLecture(Request $request)
    {
        $lectureID = decrypt($request->input('lectureID'));
        $lecture = Lecture::getLectureFollowID($lectureID);
        $message = "";

        //xoa nhung nhan xet chung cua giang vien
        $lectureInCourse = LectureInternShipCourse::getLectureInCourseFLID($lectureID);
        foreach ($lectureInCourse as $lic) {
            LectureReport::deleteLectureReport($lic->id);
        }

        //xoa khoa thuc tap ma giang vien tham gia
        LectureInternShipCourse::deleteLectureJoin($lectureID);

        /*
         * xoa tai khoan cua giang vien
         */
        foreach ($lecture as $l) {
            MyUser::deleteAccount($l->user_id);
            $message = $l->name;
        }

        //xoa giang vien
        Lecture::deleteLecture($lectureID);
        return redirect()->back()->with('deleteSuccess', 'Đã xóa giảng viên ' . $message . ' khỏi hệ thống');
    }

    public static function deleteCompany(Request $request)
    {
        $companyID = decrypt($request->input('companyID'));
        $company = Company::getCompanyFollowID($companyID);
        $message = "";

        //xoa khoa thuc tap ma cong ty da tham gia
        CompanyInternShipCourse::deleteCompanyJoin($companyID);

        //xoa nhung danh gia ve cong ty
        CompanyVote::deleteCompanyVote($companyID);

        //xoa tai khoan cua cong ty
        foreach ($company as $c) {
            MyUser::deleteAccount($c->user_id);
            $message = $c->name;
        }

        //xoa cong ty
        Company::deleteCompany($companyID);
        return redirect()->back()->with('deleteSuccess', 'Đã xóa công ty ' . $message . ' khỏi hệ thống');
    }
}
