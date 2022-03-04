<?php

namespace App\Http\Controllers;

use App\Models\multi;
use App\Models\multifiles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class MultiController extends Controller
{
    public function del_img($id, $update = false)
    {
        $data = multifiles::select("*")->where("id", $id)->get();
        if ($update == true) {
            Storage::delete('public/multi/' . $data[0]->filename);
        } else {
            $count = multifiles::select("*")->get();
            if ($count->count() == 1) {
                multi::where('id', $data[0]->user_id)->delete();
            }
            Storage::delete('public/multi/' . $data[0]->filename);
            $data[0]->delete();
            return redirect()->back();
        }
    }

    public function getRows($id = 0)
    {
        if ($id == 0) {
            $data = multi::select("*")->get();
            $files = multifiles::select("*")->get();
            return view('multi', ['list' => $data, "imgrow" => $files]);
        } else {
            $user = multi::select("*")->get();
            $files = multifiles::select("*")->get();
            $data = multi::join('multifiles', 'multis.id', '=', 'multifiles.user_id')->select("multis.*", "multifiles.filename", "multifiles.id as image_id")->where('multis.id', $id)->get();
            return view('multi', ['list' => $user, "imgrow" => $files, 'edit' => $data]);
        }
    }

    public function formwork(Request $request)
    {
        if (isset($request->id)) {
            $id = $request->id;
            multi::where("id", $id)->update(['name' => $request->name]);
        } elseif(isset($request->updateid) && $request->hasFile('updateimg')){
            $this->del_img($request->updateid,true);
            $file = $request->file('updateimg');
            $fname = str_replace('_', ' ', $file->getClientOriginalName());
            $newfilename = pathinfo($fname, PATHINFO_FILENAME) . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/multi', $newfilename);
            multifiles::where("id", $request->updateid)->update(['filename' => $newfilename]);
            return redirect()->back();
        }else{
            $upload = new multi;
            $upload->name = $request->name;
            $upload->save();
            $id = $upload->id;
        }
        if ($request->hasFile('files')) {
            $file = $request->file('files');
            foreach ($file as $file) {
                $fname = str_replace('_', ' ', $file->getClientOriginalName());
                $newfilename = pathinfo($fname, PATHINFO_FILENAME) . '_' . time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/multi', $newfilename);
                $fileup = new multifiles;
                $fileup->user_id = $id;
                $fileup->filename = $newfilename;
                $fileup->save();
            }
        }
        return redirect("/multi");
    }

    public function delete($id)
    {
        $data = multifiles::select("*")->where('user_id', $id)->get();

        foreach ($data as $val) {
            Storage::delete('public/multi/' . $val["filename"]);
        }

        multi::where('id', $id)->delete();
        multifiles::where('user_id', $id)->delete();
        return redirect()->back();
    }
}
