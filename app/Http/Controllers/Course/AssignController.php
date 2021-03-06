<?php

namespace App\Http\Controllers\Course;

use App\Company;
use App\CompanyInternShipCourse;
use App\Http\Controllers\FileController;
use App\InternShipCourse;
use App\InternShipGroup;
use App\Lecture;
use App\LectureInternShipCourse;
use App\MyUser;
use App\Student;
use App\StudentInternShipCourse;
use App\StudentTmp;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class AssignController extends Controller
{
    public function __construct()
    {
        $this->checkExtention = false;
        $this->checkForm = false;
        $this->checkRow = false;
        $this->arrTransition = array();
        $this->countSign1 = 0;
        $this->countSign2 = 0;
        $this->countSign3 = 0;
        $this->checkAssignAdditional = false;
    }

    public function assignForm(Request $request)
    {
        $courseID = decrypt($request->input('courseID'));
        $file = $request->file('file');
        $choseLecture = $request->input('choseLecture');
        /*lay course_term cua khoa thuc tap*/
        $course = InternShipCourse::getInCourse($courseID);
        $courseTerm = "";
        foreach ($course as $c) {
            $courseTerm = $c->course_term;
        }
        /*insert giang vien vao lecture_internship_course*/
        if ($choseLecture != null) {
            foreach ($choseLecture as $cl) {
                if (LectureInternShipCourse::checkLectureInternShipCourse($cl, $courseID)) {
                    LectureInternShipCourse::insertLectureInternShipCourse($cl, $courseID);
                }
            }
        }

        /*kiem tra file dau vao va chuyen sang arrTransition*/
        if (FileController::checkExtension($file)) {
            $this->checkExtention = true;
            Excel::load($file, function ($reader) {
                $reader->each(function ($sheet) {
                    $allRow = $sheet->all();//dem so dong cua moi sheet
                    if (count($allRow) > 0) {//neu row >0 moi cho chay
                        $this->checkRow = true;
                        $firstRow = $sheet->first();
                        if (isset($firstRow['msv']) && isset($firstRow['name']) && isset($firstRow['grade']) && isset($firstRow['program_university'])) {
                            $this->checkForm = true;
                            $sheet->each(function ($row) {
                                $input = array(
                                    'msv' => $row->msv,
                                    'name' => $row->name,
                                    'grade' => $row->grade,
                                    'program_university' => $row->program_university,
                                    'subject' => $row->subject
                                );
                                $rules = array(
                                    'msv' => 'required',
                                    'name' => 'required',
                                    'grade' => 'required',
                                    'program_university' => 'required',
                                    'subject' => 'required'
                                );
                                $validator = Validator::make($input, $rules);
                                if (!$validator->fails()) {
                                    $this->arrTransition[] = $row;
                                }
                            });
                        }
                    }
                });
            });
        }
        //them tai khoan cho nguoi chua co tai khoan
        $this->addNewUser($this->arrTransition);

        /*lấy danh sách sinh viên đã đăng ký. day co the bao gom cac sinh vien bi loai do chua dang ky hoc tap*/
        $studentInCourse = StudentInternShipCourse::getSICFCourseID($courseID);
        $arrStudent = array();
        foreach ($studentInCourse as $sic) {
            $student = Student::getStudentFollowID($sic->student_id);
            foreach ($student as $s) {
                $arrStudent[] = $s;
            }
        }

        $arrQue = array();//danh sach sinh vien co ten trong arrTransition nhung chua dang ky. cho vao cho phan cong sau
        $arrRegister = array();//danh sach sinh vien dang ky hop le
        $arrDanger = array();//danh sach sinh vien khong co ten trong arrTransition nhung dang ky. cho vao canh cao
        $arrRegisterTmp = array();
        foreach ($this->arrTransition as $at) {
            $count = 0;
            foreach ($arrStudent as $as) {
                if ($at->msv == $as->msv) {
                    $count++;
                }
            }
            if ($count == 0) {
                $arrQue[] = $at;
            } else {
                $arrRegisterTmp[] = $at;
            }
        }
        foreach ($arrStudent as $as) {
            $count = 0;
            $subject = "";
            foreach ($arrRegisterTmp as $arT) {
                if ($as->msv == $arT->msv) {
                    $count++;
                    $subject = $arT->subject;
                }
            }
            if ($count == 0) {
                $arrDanger[] = $as;
            } else {
                $arrRegister[] = $as->id . '*' . $subject;
            }
        }
        $this->updateSubject($arrRegister, $courseID);
        $this->insertStudentQue($arrQue, $courseTerm);
        $this->insertStudentDanger($arrDanger, $courseTerm);
        $this->deleteRegisterInvalid($arrDanger, $courseID);


        /*tim cong ty con slot va cho sinh vien dang trong bang chờ phân công vào đó vao do*/
        $companyInCourse = CompanyInternShipCourse::getCompanyInCourse($courseID);
        foreach ($companyInCourse as $cic) {
            $studentTmpQue = StudentTmp::getStudentTmp($courseTerm, 0);
            foreach ($studentTmpQue as $stq) {
                $countStudent = InternShipGroup::countStudentInCompany($cic->company_id, $courseID);
                if (!CompanyInternShipCourse::checkRegisterFull($countStudent, $cic->student_quantity)) {
                    $studentID = Student::getStudentIDFMsv($stq->msv);
                    StudentInternShipCourse::insertSICHasSubject($studentID, $courseID, $stq->subject);
                    InternShipGroup::insertGroup($studentID, $courseID, $cic->company_id);
                    StudentTmp::deleteStudentTmp($stq->id);
                }
            }
        }

        /*xu ly phan cong giang vien vao cong ty*/
        /*dem so cong ty da tham gia, dem so giang vien da tham gia*/

        //lay cac cong ty da co sinh vien dang ky
        $companyCourseTmp = CompanyInternShipCourse::getCompanyInCourse($courseID);
        $companyCourse = array();
        foreach ($companyCourseTmp as $ctmp) {
            if (InternShipGroup::countStudentInCompany($ctmp->company_id, $courseID) != 0) {
                $companyCourse[] = $ctmp;
            }
        }
        $lectureCourse = LectureInternShipCourse::getLectureInCourse($courseID);
        $countCompany = count($companyCourse);
        $countLecture = count($lectureCourse);
        $lengthCompany = count($companyCourse);
        $lengthLecture = count($lectureCourse);
        if ($countCompany > $countLecture) {
            $countAssign = (int)($countCompany / $countLecture);
            for ($i = 0; $i < $lengthLecture; $i++) {
                $countBreak = 0;
                for ($j = $this->countSign1; $j < $lengthCompany; $j++) {
                    InternShipGroup::updateLecture($companyCourse[$j]->company_id, $courseID, $lectureCourse[$i]->lecture_id);
                    $this->countSign1++;
                    $countBreak++;
                    if ($countBreak == $countAssign) {
                        break;
                    }
                }
            }
            if ($this->countSign1 < $countCompany) {
                for ($k = $this->countSign1; $k < $lengthCompany; $k++) {
                    for ($h = $this->countSign2; $h < $lengthLecture; $h++) {
                        InternShipGroup::updateLecture($companyCourse[$k]->company_id, $courseID, $lectureCourse[$h]->lecture_id);
                        $this->countSign2++;
                        break;
                    }
                }
            }
        } else {
            for ($k = 0; $k < $lengthCompany; $k++) {
                for ($h = $this->countSign3; $h < $lengthLecture; $h++) {
                    InternShipGroup::updateLecture($companyCourse[$k]->company_id, $courseID, $lectureCourse[$h]->lecture_id);
                    $this->countSign3++;
                    break;
                }
            }
        }

        /*cap nhat trang thai cua khoa thuc tap*/
        InternShipCourse::updateStatus($courseID);
        return redirect()->back()->with('assignSuccess', 'Phân công thành công');
    }

    /**
     *them nguoi dung chua co tai khoan khi phan cong
     * @param $arrTran
     */
    public function addNewUser($arrTran)
    {
        foreach ($arrTran as $at) {
            $email = $at->msv . '@student.hut.edu.vn';
            if (MyUser::checkUsername($at->msv) && MyUser::checkEmail($email)) {
                MyUser::insertUser($at->msv, $email, '123456', 'student');
                $userId = MyUser::getID($at->msv);
                Student::insertStudent($at->name, $at->grade, $at->program_university, $at->msv, $userId);
            }
        }
    }

    /**
     * them mon hoc cho sinh vien.
     * mon hoc duoc lay theo danh sach sinh vien da dang ky
     *
     * @param $arrRegister
     * @param $courseID
     */
    public function updateSubject($arrRegister, $courseID)
    {
        $length = count($arrRegister);
        for ($i = 0; $i < $length; $i++) {
            list($studentID, $subject) = explode("*", $arrRegister[$i]);
            StudentInternShipCourse::updateSubject($studentID, $courseID, $subject);
        }
    }

    /**
     * them sinh vien co trong arrTransision nhung khong co ten trong bang dang ky
     * vao bang student_tmp de cho phan cong sau
     *
     * @param $arrQue
     * @param $courseTerm
     */
    public function insertStudentQue($arrQue, $courseTerm)
    {
        /*insert sinh vien vao bang tam cho phan cong*/
        foreach ($arrQue as $aq) {
            StudentTmp::deleteStudentTmp2($aq->msv, $courseTerm);
            StudentTmp::insert($aq->msv, $courseTerm, $aq->subject, 0);
        }
    }

    /**
     * them nhung sinh vien khong co ten trong arrTransition nhung da dang ky. Nhung sinh vien nay se bi xoa dang ky
     * va cho canh cao
     *
     * @param $arrDanger
     * @param $courseTerm
     */
    public function insertStudentDanger($arrDanger, $courseTerm)
    {
        /*insert sinh vien vao bang tam chờ cảnh cáo*/
        foreach ($arrDanger as $ad) {
            StudentTmp::deleteStudentTmp2($ad->msv, $courseTerm);
            StudentTmp::insert($ad->msv, $courseTerm, "", -1);
        }
    }

    /**
     * xoa sinh vien khong co ten trong arrTransition nhung lai dang ky
     *
     * @param $arrDanger
     * @param $courseID
     */
    public function deleteRegisterInvalid($arrDanger, $courseID)
    {
        foreach ($arrDanger as $ad) {
            $student = Student::getStudentFMSV($ad->msv);
            foreach ($student as $s) {
                StudentInternShipCourse::deleteSIC($s->id, $courseID);
                InternShipGroup::deleteGroup($s->id, $courseID);
            }
        }
    }

    public function AssignAdditional(Request $request)
    {
        $studentID = $request->input('studentID');
        $courseID = $request->input('courseID');
        $companyID = $request->input('chooseCompany');

        $studentName = "";
        $msv = "";
        $courseTerm = "";
        $subject = "";

        /*
         * kiem tra dau vao vi ton tai input hidden. xem co sinh vien, cong ty, khoa thuc tap nhu vay khong
         */
        if (Student::checkStudent($studentID) && InternShipCourse::checkCourseFollowID($courseID) && Company::checkCompany($companyID)) {
            if (StudentInternShipCourse::checkSI($studentID, $companyID) && !CompanyInternShipCourse::checkCompanyInternShipCourse($companyID, $courseID)) {
                $this->checkAssignAdditional = true;
                $student = Student::getStudentFollowID($studentID);
                $course = InternShipCourse::getInCourse($courseID);
                foreach ($student as $s) {
                    $studentName = $s->name;
                    $msv = $s->msv;
                }
                foreach ($course as $c) {
                    $courseTerm = $c->course_term;
                }

                //them student_internship_course
                $studentTmp = StudentTmp::getStudentTmpFMC($msv, $courseTerm);
                foreach ($studentTmp as $stmp) {
                    $subject = $stmp->subject;
                }
                StudentInternShipCourse::insertSICHasSubject($studentID, $courseID, $subject);

                //xoa sinh vien trong bang cho phan cong di
                StudentTmp::deleteStudentTmp2($msv, $courseTerm);

                //cap nhat so luong sinh vien tiep nhan cua cong ty
                $quantity = CompanyInternShipCourse::getQuantity($companyID, $courseID);
                CompanyInternShipCourse::updateStudentQuantity($companyID, $courseID, $quantity + 1);

                //them vao bang group
                $lectureID = "";
                $lectureID1 = InternShipGroup::getLectureID($companyID, $courseID);
                if ($lectureID1 == null) {
                    $listLecture = LectureInternShipCourse::getLectureInCourse($courseID);
                    $lectureIDRandom = $listLecture->random()->lecture_id;
                    $lectureID = $lectureIDRandom;
                } else {
                    $lectureID = $lectureID1;
                }
                InternShipGroup::insertGroupAddLectureID($studentID, $lectureID, $companyID, $courseID);
            }
        }
        if ($this->checkAssignAdditional) {
            return redirect()->back()->with('assignAdditionSuccess', 'Phân công thành công cho sinh viên' . $studentName);
        } else {
            return redirect()->back()->with('errorAddition', 'Lỗi phân công');
        }
    }
}
