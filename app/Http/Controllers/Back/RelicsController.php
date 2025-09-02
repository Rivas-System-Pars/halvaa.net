<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Relics;
use File;
use Illuminate\Http\Request;

class RelicsController extends Controller
{

    public function create()
    {
        $relics = Relics::where('user_id', auth()->id())->get();
        return view('back.users.relics.create', compact('relics'));
    }


    public function store(Request $request)
    {

        $request->validate([
            'title'      => 'required|string|max:255',
            'content'    => 'nullable|string',
            'image'      => 'nullable|file|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $userId = auth()->id();

        // dd($request->content);
        $relics = Relics::create([
            'title'   => $request->title,
            'content' => $request->content,
            'user_id' => $userId,
        ]);

        if ($request->hasFile('image')) {
            $uploadPath = public_path("uploads/relics/{$userId}/");

            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true);
            }

            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move($uploadPath, $filename);

            $relativePath = "uploads/relics/{$userId}/{$filename}";
            $relics->image = $relativePath;
            $relics->save();
        }

        return redirect()
            ->route('admin.relics.create')
            ->with('success', 'آثار متوفی ثبت شد.');
    }

    public function edit($id)
    {
        $relics = Relics::findOrFail($id);
        return view('back.users.relics.edit', compact('relics'));
    }

       public function update(Request $request, $id)
    {
        $relics = Relics::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $relics->update([
            'title' => $request->title,
            'content' => $request->content,
        ]);

        $userId = auth()->id();
        $uploadPath = public_path("uploads/relics/{$userId}/");

        if ($request->hasFile('image')) {
            if ($relics->image && File::exists(public_path($relics->image))) {
                File::delete(public_path($relics->image));
            }

            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true);
            }

            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move($uploadPath, $filename);

            $relics->image = "uploads/relics/{$userId}/{$filename}";
            $relics->save();
        }

        return redirect()->route('admin.relics.create')->with('success', 'آثار متوفی بروزرسانی شد.');
    }
    public function destroy($id)
    {
        $relics = Relics::findOrFail($id);

        if ($relics->image && File::exists(public_path($relics->image))) {
            File::delete(public_path($relics->image));
        }

        $relics->delete();

        return redirect()->route('admin.relics.create')->with('success', 'آثارمتوفی حذف شد.');
    }


}
