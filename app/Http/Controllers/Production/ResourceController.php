<?php

namespace App\Http\Controllers\Production;

use App\Models\Resource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ResourceController extends Controller
{
    public function index()
    {
        $resources = Resource::all();
        return view('production.resources.index', compact('resources'));
    }

    public function create()
    {
        return view('production.resources.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'capacity_units_per_hour' => 'nullable|numeric|min:0',
            'status' => 'required|string',
            'description' => 'nullable|string',
        ]);

        Resource::create($data);
        return redirect()->route('resources.index')->with('success', 'Resource added.');
    }

    public function edit(Resource $resource)
    {
        return view('production.resources.edit', compact('resource'));
    }

    public function update(Request $request, Resource $resource)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'capacity_units_per_hour' => 'nullable|numeric|min:0',
            'status' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $resource->update($data);
        return redirect()->route('resources.index')->with('success', 'Resource updated.');
    }

    public function destroy(Resource $resource)
    {
        $resource->delete();
        return redirect()->route('resources.index')->with('success', 'Resource deleted.');
    }
}
