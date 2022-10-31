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

    public function active(userChangeStatus $request): \Illuminate\Http\Response
    {
        $status = request('active');
        $request->active = $status;
        $request->save();

        return response()->json(['success'=>'Status change successfully.']);
    }
}
