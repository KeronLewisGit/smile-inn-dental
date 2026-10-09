<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ManagesContent;
use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TeamController extends Controller
{
    use ManagesContent;

    public function index(): View
    {
        return view('admin.team.index', ['members' => TeamMember::orderBy('sort')->get()]);
    }

    public function create(): View
    {
        return view('admin.team.form', ['member' => new TeamMember(['active' => true, 'sort' => TeamMember::max('sort') + 1])]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['photo'] = $this->storeImage($request, 'photo', 'team');
        TeamMember::create($data);

        return redirect()->route('admin.team.index')->with('saved', 'Team member added.');
    }

    public function edit(TeamMember $team): View
    {
        return view('admin.team.form', ['member' => $team]);
    }

    public function update(Request $request, TeamMember $team): RedirectResponse
    {
        $data = $this->validated($request);
        $data['photo'] = $this->storeImage($request, 'photo', 'team', $team->photo);
        $team->update($data);

        return redirect()->route('admin.team.index')->with('saved', 'Team member updated.');
    }

    public function destroy(TeamMember $team): RedirectResponse
    {
        $team->delete();

        return back()->with('saved', 'Team member removed.');
    }

    /** @return array<string, mixed> */
    private function validated(Request $request): array
    {
        $data = $request->validate(['name' => ['required', 'string', 'max:120'], 'title' => ['required', 'string', 'max:160'], 'bio' => ['nullable', 'string', 'max:3000'], 'instagram' => ['nullable', 'url', 'max:255'], 'photo' => ['nullable', 'image', 'max:5120'], 'sort' => ['nullable', 'integer', 'min:0']]);
        unset($data['photo']);

        return $data + ['is_dentist' => $request->boolean('is_dentist'), 'accepts_bookings' => $request->boolean('accepts_bookings'), 'active' => $request->boolean('active')];
    }
}
