<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\Member;

class MemberController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show(Member $member)
    {
        return view('driver.member.show', compact('member'));
    }
}
