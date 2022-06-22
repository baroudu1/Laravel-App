<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DroitAccesAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $CIN = Auth::user()->CIN;
        $admin=DB::select('select CIN from admin where CIN=:cin', ['cin'=>$CIN]);
        foreach ($admin as $keye) {
            $admin = $keye->CIN;
        }
        if( $admin != [] ){
            return $next($request);
        }
        return redirect()->back()->with('error','You are not allowed to be there');
    }
}
