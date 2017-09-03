<?php

namespace App\Http\Controllers;

use App\News;
use Illuminate\Http\Request;

use App\Http\Requests;

class HomeRootController extends Controller
{
    /**
     * forget all session
     */
    public function forgetSession()
    {
        $data = session()->all();
        foreach ($data as $a => $value) {
            session()->forget($a);
        }
    }

    /**
     * return page home
     *
     * @return $this
     */
    public function home()
    {
        $this->forgetSession();
        $notify = News::getNotify();
        return view('home')->with([
            'notify' => $notify,
        ]);
    }

    public function detailNotify(Request $request)
    {
        $notifyID = $request->input('idNotify');
        $notify = News::getNotifyFID($notifyID);
        if (count($notify) == 0) {
            return redirect()->back();
        } else {
            return view('detail-notify')->with('notify', $notify);
        }
    }
}
