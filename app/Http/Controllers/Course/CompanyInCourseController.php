<?php

namespace App\Http\Controllers\Course;

use App\Company;
use App\CompanyInternShipCourse;
use App\Http\Controllers\SessionController;
use App\InternShipCourse;
use App\News;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class CompanyInCourseController extends Controller
{
    public function companyRegister()
    {
        /*
         * get newNotify
         * get uniNotify
         * get comNotify
         */
        $notify = News::getNotify();

        $companySession = new  SessionController();
        $company = Company::getCompany($companySession->getCompanySession());
        $type = 'company';
        $companyID = $company->first()->id;

        /*
         * lay danh sach khoa thuc tap trong nam hien tai
         */
        $arrCourse = array();
        $nowDate = date('Y-m-d');//lay ngay thang hien tai
        $nowMonth = (int)date('m', strtotime($nowDate));
        $year = 0;

        //lấy năm học hiện tại
        if ($nowMonth >= 6 && $nowMonth <= 12) {
            $year = (int)date('Y', strtotime($nowDate));
        } else {
            $year = (int)date('Y', strtotime($nowDate)) - 1;
        }

        //lấy các khóa thực tập của năm học hiện tại
        $inCourse = InternShipCourse::getCourseFollowYear($year);
        return view('manage-register.company-register')->with([
            'companyID' => $companyID,
            'inCourse' => $inCourse,
            'notify' => $notify,
            'user' => $company,
            'type' => $type
        ]);
    }

    /**
     * cong ty dang ky tham gia vao khoa thuc tap
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function companyRegisterForm(Request $request)
    {
        $courseID = decrypt($request->input('courseIDAdd'));
        $companyID = decrypt($request->input('companyIDAdd'));
        if (CompanyInternShipCourse::checkCompanyInternShipCourse($companyID, $courseID)) {
            CompanyInternShipCourse::insert($companyID, $courseID, $request->input('quantityAdd'), $request->input('requireSkillAdd'));
            return redirect()->back()->with('companyRegisterSuccess', 'Tham gia thành công');
        } else {
            return redirect()->back()->with('companyRegisterError', 'Công ty đã tham gia');
        }
    }

    /**
     * edit thông tin khi đã tham gia khóa thực tập
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function companyEditRegisterForm(Request $request)
    {
        $courseID = decrypt($request->input('courseIDEdit'));
        $companyID = decrypt($request->input('companyIDEdit'));
        CompanyInternShipCourse::updateStudentQuantity($companyID, $courseID, $request->input('quantityEdit'));
        CompanyInternShipCourse::updateRequireSkill($companyID, $courseID, $request->input('requireSkillEdit'));
        return redirect()->back()->with('companyEditRegisterSuccess', 'Chỉnh sửa thành công');
    }

    /**
     * delete xóa việc đăng ký của công ty
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function companyDeleteRegisterForm(Request $request)
    {
        $courseID = decrypt($request->input('courseIDDelete'));
        $companyID = decrypt($request->input('companyIDDelete'));
        CompanyInternShipCourse::deleteCompanyInCourse($companyID, $courseID);
        return redirect()->back()->with('companyDeleteRegisterSuccess', 'Đã xóa đăng ký');
    }
}
