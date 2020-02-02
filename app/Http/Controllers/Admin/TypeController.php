<?php

namespace App\Http\Controllers\Admin;
use App\Type;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TypeController extends Controller
{
    public function index()
    {
        return view('admin.type.index', [
            'title' => 'All Types',
            'types' => Type::all(),
        ]);
    }

   /**
     * Show the form for creating a new resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.type.create');

    }

    /**
     * Handle contact form
     *
     * @param Request $request
     * @return Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->check($request);
        $this->add($request);
        return redirect()->back()->with([
            'message' => 'Successfully save'
        ]);
    }

    /**
     * Validate incoming form data
     *
     * @param Request $request
     * @return void
     */
    private function check(Request $request)
    {
        $rules = [
            'name' => 'required',

        ];
        $request->validate($rules);
    }

    private function add(Request $request)
    {
       $this->type = Type::create([
           'name' => $request->name,
           'value' => $request->value,

       ]);
    }

    public function edit($id)
    {
        $type = Type::find($id);
        return view('admin.type.edit', [
            'title' => 'All Types',
            'type' => $type,
        ]);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Type::destroy($id);
        return redirect()->back()->with(['ok' => true, 'msg' => ' Deleted']);
    }


/**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'value' => 'required',
          ]);

          $type = Type::find($id);
          $type->name = $request->get('name');
          $type->value = $request->get('value');
          $type->save();


        return redirect()->back()->with(['ok' => true, 'msg' => 'Updated']);

    }


}








