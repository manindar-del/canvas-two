<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth;
use App\Wallet;
use App\User;

class WalletController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $agent = User::with(['wallets'])->findOrFail($id);
        return view('admin.wallets.index', [
            'title' => 'Wallet Balances',
            'seo_meta' => '',
            'agent' => $agent,
            'wallets' => $agent->wallets,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $agent = User::with(['wallets'])->findOrFail($id);
        return view('admin.wallets.create', [
            'title' => 'Add Wallet Balance',
            'seo_meta' => '',
            'agent' => $agent,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param int $id agent id
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        $agent = User::with(['wallets'])->findOrFail($id);
        $this->validate($request, [
            'amount' => ['required', 'numeric'],
        ]);
        $agent->wallets()->save(Wallet::create([
            'amount' => $request->amount,
            'slug' => 'admin-recharge',
        ]));
        return redirect()->route('agents.wallets.index', [$id])->with([
            'ok' => true,
            'msg' => 'Wallet Balance Added',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, $wallet_id)
    {
        $agent = User::with(['wallets'])->findOrFail($id);
        $wallet = Wallet::findOrFail($wallet_id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, $wallet_id)
    {
        $agent = User::findOrFail($id);
        $wallet = Wallet::findOrFail($wallet_id);
        return view('admin.wallets.edit', [
            'title' => 'Add Wallet Balance',
            'seo_meta' => '',
            'wallet' => $wallet,
            'agent' => $agent,
        ]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, $wallet_id)
    {
        $agent = User::with(['wallets'])->findOrFail($id);
        $wallet = Wallet::findOrFail($wallet_id);
        $this->validate($request, [
            'amount' => ['required', 'numeric'],
        ]);
        $wallet->amount = $request->amount;
        $wallet->slug = 'admin-recharge';
        $wallet->save();
        return redirect()->route('agents.wallets.index', [$id])->with([
            'ok' => true,
            'msg' => 'Wallet Balance Updated',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, $wallet_id)
    {
        Wallet::destroy($wallet_id);
        return redirect()->route('agents.wallets.index', [$id])->with([
            'ok' => true,
            'msg' => 'Wallet Balance Removed',
        ]);
    }



}
