<?php

namespace App\Http\Controllers;

use App\Models\CandidateTag;
use Illuminate\Http\Request;

class CandidateTagController extends Controller
{
    public function index()
    {
        $tags = CandidateTag::latest()->paginate(10);
        return view('staff.tags.index', compact('tags'));
    }

    public function create()
    {
        return view('staff.tags.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:candidate_tags,name',
            'color' => 'required|string|max:7',
        ]);

        CandidateTag::create($request->only(['name', 'color']));

        return redirect()->route('staff.tags.index')
            ->with('success', 'Tag created successfully');
    }

    public function edit(CandidateTag $tag)
    {
        return view('staff.tags.edit', compact('tag'));
    }

    public function update(Request $request, CandidateTag $tag)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:candidate_tags,name,'.$tag->id,
            'color' => 'required|string|max:7',
        ]);

        $tag->update($request->only(['name', 'color']));

        return redirect()->route('staff.tags.index')
            ->with('success', 'Tag updated successfully');
    }

    public function destroy(CandidateTag $tag)
    {
        $tag->delete();
        return redirect()->route('staff.tags.index')
            ->with('success', 'Tag deleted successfully');
    }
}