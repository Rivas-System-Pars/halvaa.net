<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Str;

class FamillyTreeController extends Controller
{

public function create()
{
    $user = auth()->user();
    abort_unless($user, 403);

    return view('back.users.famillytree.create', compact('user'));
}


    public function store(Request $request)
    {
        $request->validate([
            'familly_tree' => ['required', 'file', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $user = Auth::user();
        $file = $request->file('familly_tree');

        // Target folder per user
        $targetDir = public_path("uploads/famillytree/{$user->id}");
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        // Unique filename
        $ext = strtolower($file->getClientOriginalExtension());
        $name = Str::uuid() . '.' . $ext;

        // Move file into public/uploads/famillytree/{user_id}
        $file->move($targetDir, $name);

        // Relative path to save in DB
        $relativePath = "uploads/famillytree/{$user->id}/{$name}";

        // Remove old family tree (if any)
        if ($existing = $user->familyTree()->first()) {
            $oldFile = public_path($existing->image);
            if (is_file($oldFile)) {
                @unlink($oldFile);
            }
            $existing->delete();
        }

        // Create new gallery record
        $record = new Gallery([
            'image' => $relativePath, // your column name is 'image'
            'ordering' => 1,             // optional marker
        ]);

        $user->familyTree()->save($record);

        return back()->with('success', 'عکس شجره نامه با موفقیت اپلود شد .');
    }
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($existing = $user->familyTree()->first()) {
            // delete file from public
            $oldFile = public_path($existing->image);
            if (is_file($oldFile)) {
                @unlink($oldFile);
            }

            // delete from DB
            $existing->delete();
        }

        return back()->with('success', 'تصویر درخت خانوادگی حذف شد.');
    }

}

