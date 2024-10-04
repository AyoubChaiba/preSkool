<?php

namespace App\Http\Controllers;

use App\Models\Documents;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentsController extends Controller
{
    public function index()
    {
        $documents = Documents::with('user')->get();
        return view('pages.documents.list', compact('documents'));
    }

    public function create()
    {
        return view('pages.documents.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,doc,docx,png,jpg,jpeg|max:2048',
        ]);

        $document = new Documents();
        $document->user_id = auth()->id();

        if ($request->hasFile('file')) {
            $document->file_path = $request->file('file')->store('documents');
        }

        $document->save();

        return response()->json(['success' => true, 'redirect_url' => route('documents.index')]);
    }

    public function show($id)
    {
        $document = Documents::with('user')->findOrFail($id);
        return response()->json($document);
    }

    public function edit($id)
    {
        $document = Documents::findOrFail($id);
        return view('pages.documents.edit', compact('document'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'file' => 'nullable|file|mimes:pdf,doc,docx,png,jpg,jpeg|max:2048',
        ]);

        $document = Documents::findOrFail($id);
        $document->title = $request->title;

        if ($request->hasFile('file')) {
            if ($document->file_path) {
                Storage::delete($document->file_path);
            }
            $document->file_path = $request->file('file')->store('documents');
        }

        $document->save();

        return response()->json(['success' => true, 'document' => $document]);
    }

    public function destroy($id)
    {
        $document = Documents::findOrFail($id);

        if ($document->file_path) {
            Storage::delete($document->file_path);
        }

        $document->delete();

        return response()->json(['success' => true]);
    }
}
