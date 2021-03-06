<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

//Route::get('/', function () {
//    return view('welcome');
//});
/*
 * route process link some page general
 */
Route::get('/', 'HomeRootController@home');
Route::get('test1', function () {
    return view('test1');
});
Route::get('login', function () {
    return view('user-login');
});
Route::get('detail-notify', 'HomeRootController@detailNotify');
Route::post('login', 'MyUserController@login');
/*
 * route process reset password
 */
Route::get('password/reset/{token?}', 'Auth\PasswordController@showResetForm');
Route::post('password/email', 'Auth\PasswordController@sendResetLinkEmail');
Route::post('password/reset', 'Auth\PasswordController@reset');

/*
 * route student
 */
Route::group(['middleware' => 'Student'], function () {
    Route::get('student-home', 'HomeUserController@studentHome');
    Route::get('user-student-home', 'HomeUserController@userComplex');
    Route::get('student-profile', 'Profile\StudentProfileController@studentProfile');
    Route::post('edit-student-profile', 'Profile\StudentProfileController@editProfile');
    Route::get('register-intern', 'Course\StudentRegisterController@registerInternship');
    Route::get('student-register', 'Course\StudentRegisterController@studentRegister');
    Route::get('course-join', 'Course\StudentRegisterController@courseJoin');
    Route::post('upload-report', 'Course\StudentRegisterController@uploadReport');
    Route::get('student-change-pass', 'Profile\StudentProfileController@studentChangePass');
    Route::post('student-change-pass-form', 'Profile\StudentProfileController@changePass');
    Route::post('write-report', 'Course\ReportController@studentReport');
    Route::post('edit-report', 'Course\ReportController@editReport');
    Route::get('student-detail-notify', 'HomeUserController@notifyDetail');
    Route::post('student-delete-register', 'Course\StudentRegisterController@studentDeleteRegister');
    Route::get('update-vote', function () {
        $studentID = Input::get('studentID');
        $companyID = Input::get('companyID');
        $vote = Input::get('vote');
        return Response::json(\App\Http\Controllers\VoteController::updateVote($studentID, $companyID, $vote));
    });
    Route::get('company-cooperation', 'VoteController@companyCooperation');
    Route::get('vote-company-ajax', function () {
        return Response::json(\App\Http\Controllers\VoteController::statisticVoteAjax());
    });

});
/*
 * route lecture
 */
Route::group(['middleware' => 'Lecture'], function () {
    Route::get('lecture-home', 'HomeUserController@lectureHome');
    Route::get('user-lecture-home', 'HomeUserController@userComplex');
    Route::get('lecture-profile', 'Profile\LectureProfileController@lectureProfile');
    Route::post('edit-lecture-profile', 'Profile\LectureProfileController@editProfile');
    Route::get('lecture-join', 'AssessController@lectureJoin');
    Route::post('lecture-assess', 'AssessController@lectureAssess');
    Route::get('lecture-change-pass', 'Profile\LectureProfileController@lectureChangePass');
    Route::post('lecture-change-pass-form', 'Profile\LectureProfileController@changePass');
    Route::post('lecture-write-report', 'Course\ReportController@lectureReport');
    Route::post('lecture-edit-report', 'Course\ReportController@lectureEditReport');
    Route::get('lecture-detail-notify', 'HomeUserController@notifyDetail');

    Route::get('lecture-to-score', function () {
        $courseID = Input::get('courseID');
        $studentID = Input::get('studentID');
        $lecturePoint = Input::get('lecturePoint');
        return Response::json(\App\Http\Controllers\AssessController::lectureToScore($courseID, $studentID, $lecturePoint));
    });
});
/*
 * route company
 */
Route::group(['middleware' => 'Company'], function () {
    Route::get('company-home', 'HomeUserController@companyHome');
    Route::get('user-company-home', 'HomeUserController@userComplex');
    Route::get('company-profile', 'Profile\CompanyProfileController@companyProfile');
    Route::post('edit-company-profile', 'Profile\CompanyProfileController@editProfile');
    Route::get('company-register', 'Course\CompanyInCourseController@companyRegister');
    Route::post('company-register-form', 'Course\CompanyInCourseController@companyRegisterForm');
    Route::post('company-edit-register-form', 'Course\CompanyInCourseController@companyEditRegisterForm');
    Route::post('company-delete-register-form', 'Course\CompanyInCourseController@companyDeleteRegisterForm');
    Route::get('company-join', 'AssessController@companyJoin');
    Route::post('company-assess', 'AssessController@companyAssess');
    Route::get('company-change-pass', 'Profile\CompanyProfileController@companyChangePass');
    Route::post('company-change-pass-form', 'Profile\CompanyProfileController@changePass');
    Route::get('timekeeping', 'AssessController@companyTimeKeeping');
    Route::post('timekeeping-post', 'AssessController@timekeepingPost');
    Route::get('viewTimekeeping', 'AssessController@viewTimekeeping');
    Route::get('company-detail-notify', 'HomeUserController@notifyDetail');
    Route::get('company-to-score', function () {
        $courseID = Input::get('courseID');
        $studentID = Input::get('studentID');
        $companyPoint = Input::get('companyPoint');
        return Response::json(\App\Http\Controllers\AssessController::companyToScore($studentID, $courseID, $companyPoint));
    });
});
/*
 * route admin
 */
Route::group(['middleware' => 'Admin'], function () {
    Route::get('admin-home', 'HomeUserController@adminHome');
    Route::get('user-admin-home', 'HomeUserController@userComplex');
    Route::get('add-users', 'MyUserController@addUsers');
    Route::get('list-course', 'Course\ManageInCourseController@listCourse');
    Route::get('plan-learning', 'Course\ManageInCourseController@planLearning');
    Route::post('add-plan-learning', 'Course\ManageInCourseController@addPlanLearning');
    Route::get('create-course', 'Course\ManageInCourseController@createCourse');
    Route::post('create-course-form', 'Course\ManageInCourseController@createCourseForm');
    Route::get('course-detail', 'Course\ManageInCourseController@courseDetail');
    Route::get('course-student-register', 'Course\ManageInCourseController@courseStudentRegister');
    Route::get('course-lecture-join', 'Course\ManageInCourseController@courseLectureJoin');
    Route::get('course-assign', 'Course\ManageInCourseController@courseAssign');
    Route::get('course-result', 'Course\ManageInCourseController@courseResult');
    Route::post('update-time-register', 'Course\ManageInCourseController@updateTimeRegister');
    Route::post('assign-form', 'Course\AssignController@assignForm');
    Route::post('assign-additional-form', 'Course\AssignController@assignAdditional');
    Route::get('statistic-vote', 'VoteController@statisticVote');
    Route::get('manage-notify', 'NotifyController@listNotify');
    Route::get('edit-student', 'MyUserController@editStudent');
    Route::get('admin-change-pass', 'Profile\AdminProfileController@adminChangePass');
    Route::post('admin-change-pass-form', 'Profile\AdminProfileController@changePass');
    Route::post('edit-student-form', 'Profile\StudentProfileController@adminEditStudent');
    Route::get('edit-lecture', 'MyUserController@editLecture');
    Route::post('edit-lecture-form', 'Profile\LectureProfileController@adminEditLecture');
    Route::get('edit-company', 'MyUserController@editCompany');
    Route::post('edit-company-form', 'Profile\CompanyProfileController@adminEditCompany');
    Route::post('delete-student-form', 'MyUserController@deleteStudent');
    Route::post('delete-lecture-form', 'MyUserController@deleteLecture');
    Route::post('delete-company-form', 'MyUserController@deleteCompany');
    Route::post('delete-course-form', 'Course\ManageInCourseController@deleteCourse');
    Route::get('admin-detail-notify', 'HomeUserController@notifyDetail');
    Route::get('create-notify', 'NotifyController@createNotify');
    Route::post('create-notify-form', 'NotifyController@createNotifyForm');
    Route::post('delete-notify-form', 'NotifyController@deleteNotifyForm');
    Route::post('delete-many-notify-form', 'NotifyController@deleteManyNotify');
    Route::get('edit-notify', 'NotifyController@editNotify');
    Route::post('edit-notify-form', 'NotifyController@editNotifyForm');
    Route::post('delete-plan-form', 'Course\ManageInCourseController@deletePlan');
    Route::post('delete-manyPlan-form', 'Course\ManageInCourseController@deleteManyPlan');
    Route::post('edit-plan-learning', 'Course\ManageInCourseController@editPlan');
    Route::post('stop-join-lecture', 'Course\ManageInCourseController@stopJoinLecture');
    Route::post('replace-lecture', 'Course\ManageInCourseController@replaceLecture');
    Route::post('stop-intern', 'Course\ManageInCourseController@stopIntern');
    Route::post('change-company', 'Course\ManageInCourseController@changeCompany');
    Route::get('img-banner', 'ImageBannerController@listImageBanner');
    Route::get('create-img-banner','ImageBannerController@create');
    Route::post('create-img-banner-form','ImageBannerController@createForm');
    Route::post('edit-img-banner-form','ImageBannerController@editForm');
    Route::post('delete-img-banner-form','ImageBannerController@deleteForm');
    Route::post('delete-many-img-banner-form','ImageBannerController@deleteManyForm');
    /*
     * doan nay xu ly ajax
     */
    Route::post('check-username', function () {
        if (\App\MyUser::checkUsername($_POST['msv'])) {
            return "true";
        } else {
            return "false";
        }
    });
    Route::post('check-username-edit', function () {
        $myUserID = decrypt(Input::get('myUserID'));
        if (\App\MyUser::checkUserNameEdit($myUserID, $_POST['msv'])) {
            return "true";
        } else {
            return "false";
        }
    });
    Route::post('check-email-edit', function () {
        $myUserID = decrypt(Input::get('myUserID'));
        if (\App\MyUser::checkEmailEdit($myUserID, $_POST['email'])) {
            return "true";
        } else {
            return "false";
        }
    });
    Route::post('check-email-lecture', function () {//process ajax\
        if (\App\MyUser::checkEmail($_POST['emailLecture'])) {
            return "true";
        } else {
            return "false";
        }
    });
    Route::post('check-email-company', function () {//process ajax\
        if (\App\MyUser::checkEmail($_POST['emailCompany'])) {
            return "true";
        } else {
            return "false";
        }
    });
    Route::post('check-name-company', function () {
        if (\App\Company::checkNameCompany($_POST['nameCompany'])) {
            return "true";
        } else {
            return "false";
        }
    });
    Route::post('check-name-edit', function () {
        $myUserID = decrypt(Input::get('myUserID'));
        if (\App\Company::checkNameEdit($myUserID, $_POST['name'])) {
            return "true";
        } else {
            return "false";
        }
    });
    Route::get('get-time-term', function () {
        $term = Input::get('term');
        return Response::json(\App\Http\Controllers\Course\ManageInCourseController::termDate($term));
    });
    Route::post('check-course', function () {
        if (\App\InternShipCourse::checkCourse($_POST['term'])) {
            return "true";
        } else {
            return "false";
        }
    });
    Route::get('getCourse-updateTime', function () {
        $cousre = \App\InternShipCourse::all();
        return Response::json($cousre);
    });
    Route::get('vote-ajax', function () {
        return Response::json(\App\Http\Controllers\VoteController::statisticVoteAjax());
    });
    //ket thu xu ly ajax
    Route::post('form-add-users-many', 'MyUserController@addUserMany');
    Route::post('form-add-users-one', 'MyUserController@addUserOne');
    Route::get('list-user', 'MyUserController@listUser');
});


/*
 * Route test
 */
Route::get('test', function () {
    return view('test');
});
Route::get('compare', function () {
    $data = '123456';
    $check = bcrypt($data);
    $deCheck = \Illuminate\Support\Facades\Hash::check('123456', $check);
    return Response::json($deCheck);
});
Route::get('string', function () {
    $test1 = "long1";
    $test2 = "long2";
    $test = $test1 . '<br>' . $test2;
    echo($test);
});
Route::get('chia', function () {
    $chia1 = round(10 / 3, 2);
    $chia2 = 10 % 3;
    dump($chia1);
    dump($chia2);
});
//Route::get('insert', function () {
//    $myUser = new \App\MyUser();
//    $myUser->user_name = 'dalonghd94@gmail.com';
//    $myUser->email = 'dalonghd94@gmail.com';
//    $myUser->password = bcrypt('123456');
//    $myUser->type = 'company';
//    $myUser->save();
//});
