<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserStatusController extends Controller
{
    /**
     * Responds with a welcome message with instructions
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $viewData = [];
        $viewData["title"] = "Admin Page - Active - Online Store";
        $users = User::get();
        return view('admin.users.index', compact('users'))->with("viewData", $viewData);
    }

    /**
     * Responds with a welcome message with instructions
     *
     * @return \Illuminate\Http\Response
     */
    public function userChangeStatus(Request $request)
    {
        \Log::info($request->all());
        $user = User::find($request->user_id);
        $user->status = $request->status;
        $user->save();

        return response()->json(['success'=>'Status change successfully.']);
    }
}
