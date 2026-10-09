<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ManagesContent;
use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServiceController extends Controller
{
    use ManagesContent;

    public function index(): View
    {
        return view('admin.services.index', ['services' => Service::orderBy('sort')->get()]);
    }

    public function create(): View
    {
        return view('admin.services.form', ['service' => new Service(['active' => true, 'featured' => true, 'sort' => Service::max('sort') + 1]), 'treatments' => '', 'faqs' => '']);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['image'] = $this->storeImage($request, 'image', 'services');
        Service::create($data);

        return redirect()->route('admin.services.index')->with('saved', 'Service added.');
    }

    public function edit(Service $service): View
    {
        return view('admin.services.form', ['service' => $service, 'treatments' => $this->pairsToLines($service->treatments), 'faqs' => $this->pairsToLines($service->faqs, 'q', 'a')]);
    }

    public function update(Request $request, Service $service): RedirectResponse
    {
        $data = $this->validated($request);
        $data['image'] = $this->storeImage($request, 'image', 'services', $service->image);
        $service->update($data);

        return redirect()->route('admin.services.index')->with('saved', 'Service updated.');
    }

    public function destroy(Service $service): RedirectResponse
    {
        $service->delete();

        return back()->with('saved', 'Service removed.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate(['name' => ['required', 'string', 'max:120'], 'tagline' => ['nullable', 'string', 'max:200'], 'intro' => ['nullable', 'string', 'max:2000'], 'body' => ['nullable', 'string', 'max:10000'], 'icon' => ['nullable', 'string', 'max:40'], 'image' => ['nullable', 'image', 'max:5120'], 'treatments' => ['nullable', 'string', 'max:20000'], 'faqs' => ['nullable', 'string', 'max:20000'], 'sort' => ['nullable', 'integer', 'min:0']]);
        unset($data['image']);
        $data['treatments'] = $this->linesToPairs($data['treatments'] ?? null);
        $data['faqs'] = $this->linesToPairs($data['faqs'] ?? null, 'q', 'a');

        return $data + ['featured' => $request->boolean('featured'), 'active' => $request->boolean('active')];
    }
}
