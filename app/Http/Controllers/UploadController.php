<?php

namespace App\Http\Controllers;

use App\Models\Upload;
use Illuminate\Http\Request;
// File upload Delete Important
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function getRows($id = 0)
    {
        if ($id == 0) {
            $data = Upload::select("*")->get();
            return view('welcome', ['list' => $data]);
        } else {
            $all = Upload::select("*")->get();
            $data = Upload::select("*")->where("id", $id)->get();
            return view('welcome', ['list' => $all, 'edit' => $data]);
        }
    }

    public function del_img($id){
        $data = Upload::select("*")->where("id", $id)->get();

        if ($data[0]->privacy == "public") {
            if (File::exists('uploads/' . $data[0]->filename)) {
                unlink('uploads/' . $data[0]->filename);
            }
        } else {
            Storage::delete('public/uploads/' . $data[0]->filename);
        }
        Upload::where("id", $id)->update(['filename' => ""]);
        return redirect()->back();
    }

    public function delete($id, $unlink_only = false)
    {
        $data = Upload::select("*")->where("id", $id)->get();
        if ($unlink_only == true) {
            if ($data[0]->privacy == "public") {
                if (!empty($data[0]->filename) && File::exists('uploads/' . $data[0]->filename)) {
                    unlink('uploads/' . $data[0]->filename);
                }
            } else {
                if (File::exists('uploads/' . $data[0]->filename)) {
                    Storage::delete('public/uploads/' . $data[0]->filename);
                }
            }
        } else {
            if ($data[0]->privacy == "public") {
                if (!empty($data[0]->filename) && File::exists('uploads/' . $data[0]->filename)) {
                    unlink('uploads/' . $data[0]->filename);
                }
            } else {
                Storage::delete('public/uploads/' . $data[0]->filename);
            }
            $data[0]->delete();
            return redirect()->back();
        }
    }

    public function formwork(Request $request)
    {
        if (isset($request->id)) {
            $data = Upload::select("*")->where("id", $request->id)->get();
            if ($request->hasFile('files')) {
                $this->delete($request->id, true);
                $file = $request->file('files');
                if ($request->filename != '') {
                    $fname = str_replace('_', ' ', $request->filename);
                    $newfilename = $fname . '_' . time() . '.' . $file->getClientOriginalExtension();
                } else {
                    $fname = str_replace('_', ' ', $file->getClientOriginalName());
                    $newfilename = pathinfo($fname, PATHINFO_FILENAME) . '_' . time() . '.' . $file->getClientOriginalExtension();
                }
                //Move Uploaded File
                if ($request->privacy == "public") {
                    $file->move('uploads', $newfilename);
                } else {
                    $file->storeAs('public/uploads', $newfilename);
                }
                Upload::where("id", $request->id)->update(['filename' => $newfilename]);
            }elseif (explode("_", $data[0]->filename)[0] != explode("_", $request->filename)[0]) {
                if ($request->filename != '') {
                    $newname = str_replace('_', ' ', $request->filename) . "_" . time() . "." . pathinfo($data[0]->filename, PATHINFO_EXTENSION);
                } else {
                    $newname = time() . "_." . pathinfo($data[0]->filename, PATHINFO_EXTENSION);
                }

                $old = 'uploads/' . $data[0]->filename;
                $new = 'uploads/' . $newname;
                if ($data[0]->privacy == "storage") {
                    $old = "storage/" . $old;
                    $new = "storage/" . $new;
                }
                rename($old, $new);
                Upload::where("id", $request->id)->update(['filename' => $newname]);
            }


            if ($request->privacy != $data[0]->privacy) {
                if ($request->privacy == "public") {
                    File::move('storage/uploads/' . $data[0]->filename, 'uploads/' . $data[0]->filename);
                } else {
                    File::move('uploads/' . $data[0]->filename, 'storage/uploads/' . $data[0]->filename);
                }
                Upload::where("id", $request->id)->update(['privacy' => $request->privacy]);
            }
            Upload::where("id", $request->id)->update(['name' => $request->name, 'email' => $request->email, 'privacy' => $request->privacy]);
            return redirect('/');
        } else {
            // $file->getClientOriginalName();$file->getClientOriginalExtension();$file->getRealPath();$file->getSize();$file->getMimeType();
            $name = $request->name;
            $email = $request->email;
            $privacy = $request->privacy;
            $newfilename = NULL;
            if ($request->hasFile('files')) {
                $file = $request->file('files');
                if ($request->filename != '') {
                    $fname = str_replace('_', ' ', $request->filename);
                    $newfilename = $fname . '_' . time() . '.' . $file->getClientOriginalExtension();
                } else {
                    $fname = str_replace('_', ' ', $file->getClientOriginalName());
                    $newfilename = pathinfo($fname, PATHINFO_FILENAME) . '_' . time() . '.' . $file->getClientOriginalExtension();
                }
                //Move Uploaded File
                if ($privacy == "public") {
                    $file->move('uploads/', $newfilename);
                } else {
                    $file->storeAs('public/uploads/', $newfilename);
                }
            }

            //Insert Record
            $upload = new Upload;
            $upload->name = $name;
            $upload->email = $email;
            $upload->privacy = $privacy;
            $upload->filename = $newfilename;
            $upload->save();

            return redirect("/");
        }
    }
}
