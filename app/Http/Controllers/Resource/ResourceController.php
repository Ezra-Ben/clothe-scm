<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use Illuminate\Http\Request;

class ResourceController extends Controller
{
    public function index()
    {
        $resources = Resource::all();
        return view('resources.index', compact('resources'));
    }

    public function create()
    {
        return view('resources.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:resources,name',
            'type' => 'required|string',
            'capacity_units_per_hour' => 'nullable|numeric|min:0',
            'status' => 'required|string',
        ]);

        Resource::create($request->all());

        return redirect()->route('resources.index')->with('success', 'Resource created successfully.');
    }

    public function edit(Resource $resource)
    {
        return view('resources.edit', compact('resource'));
    }

    public function update(Request $request, Resource $resource)
    {
        $request->validate([
            'name' => 'required|unique:resources,name,' . $resource->id,
            'type' => 'required|string',
            'capacity_units_per_hour' => 'nullable|numeric|min:0',
            'status' => 'required|string',
        ]);

        $resource->update($request->all());

        return redirect()->route('resources.index')->with('success', 'Resource updated successfully.');
    }

    public function destroy(Resource $resource)
    {
        $resource->delete();
        return redirect()->route('resources.index')->with('success', 'Resource deleted successfully.');
    }
}