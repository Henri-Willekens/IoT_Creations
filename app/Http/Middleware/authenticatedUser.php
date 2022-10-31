<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class authenticatedUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user_balance = Auth::user()->getBalance();
        $user_created_date = date_create(Auth::user()->getCreatedAt());

        $current_date = date_create();
        $account_age = date_diff($user_created_date, $current_date);

        $MIN_BALANCE = 500;
        $MIN_AGE_DAYS = 5;

        $errmsg = "";

        if ($user_balance <= $MIN_BALANCE) {
            $errmsg = "Your account balance should be at least ".$MIN_BALANCE;
        }

        if ($account_age->days < $MIN_AGE_DAYS) {
            $errmsg = $errmsg."<br/>Your account should be at least ".$MIN_AGE_DAYS." days old.";
        }

        if ($errmsg != "")
            return redirect('/cart')->with('success', $errmsg);

        return $next($request);
    }
}
