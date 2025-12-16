<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ServiceStep;
use App\Models\Service;
use Illuminate\Support\Facades\Storage;

class ServiceStepController extends Controller
{
   public function index()
    {
        $steps = ServiceStep::with('service')
            ->orderBy('service_id')
            ->orderBy('step_order')
            ->get();

        return view('service_steps.index', compact('steps'));
    }

    public function create()
    {
        $services = Service::all();
        return view('service_steps.create', compact('services'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'title' => 'required|string|max:1000',
            'step_order' => 'nullable|integer',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->only([
            'service_id', 'title', 'description', 'step_order'
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('service_steps', 'public');
        }

        ServiceStep::create($data);

        return redirect()->route('service_steps.index')
            ->with('success', 'Thêm bước dịch vụ thành công');
    }

    public function edit(ServiceStep $serviceStep)
    {
        $services = Service::all();
        return view('service_steps.edit', compact('serviceStep', 'services'));
    }

    public function update(Request $request, ServiceStep $serviceStep)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
          'title' => 'required|string|max:1000',
            'step_order' => 'nullable|integer',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->only([
            'service_id', 'title', 'description', 'step_order'
        ]);

        if ($request->hasFile('image')) {
            if ($serviceStep->image) {
                Storage::disk('public')->delete($serviceStep->image);
            }
            $data['image'] = $request->file('image')->store('service_steps', 'public');
        }

        $serviceStep->update($data);

        return redirect()->route('service_steps.index')
            ->with('success', 'Cập nhật bước dịch vụ thành công');
    }
    public function show(ServiceStep $serviceStep)
    {
        return view('service_steps.show', compact('serviceStep'));
    }

    public function destroy(ServiceStep $serviceStep)
    {
        if ($serviceStep->image) {
            Storage::disk('public')->delete($serviceStep->image);
        }

        $serviceStep->delete();

        return back()->with('success', 'Đã xóa bước dịch vụ');
    }
}
