<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use App\Quotation;
use App\User;

class ProfileController extends Controller
{
    /**
     * Show profile details
     *
     * @param Request $request
     * @return Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('agent.profile.index', [
            'title' => 'Profile Info',
            'seo_meta' => '',
        ]);
    }
    public function update(Request $request)
    {
        // $name = $request->name;
        // $tel = $request->tel;
        // $address = $request->address;
        // $id = $request->id;
        // $sql = "UPDATE users SET name= ?,tel= ?,address= ? WHERE Id= ?";
        // DB::update($sql, array($name, $tel,$address, $id));
        $user = Auth::user();
        $user->name = $request->name;
        $user->tel = $request->tel;
        $user->address = $request->address;
        $user->save();
        return redirect()->route('profile.index')->with([
            'ok' => true,
            'msg' => 'Profile Updated',
        ]);

    }

    public function destroy($id)
    {
        User::destroy($id);
    }

}
