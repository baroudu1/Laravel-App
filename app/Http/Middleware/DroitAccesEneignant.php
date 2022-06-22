<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class DroitAccesEneignant
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
        $coordinateur=DB::select('select coordinateur from enseignant where CIN=:cin ', ['cin'=>$CIN]);

        foreach ($coordinateur as $keeye) {
            $coordinateur = $keeye->coordinateur;
        }
        if( $coordinateur != [] ){
            return $next($request);
        }
        return redirect()->back()->with('error','You are not allowed to be there');
    }
}
