<x-admin.layout title="Staff logins">
    <div class="grid gap-6 lg:grid-cols-3">
        <div class="card overflow-x-auto p-0 lg:col-span-2"><table class="table"><thead><tr><th>Name</th><th>Email</th><th>Role</th><th></th></tr></thead><tbody>
            @foreach ($users as $u)<tr><td class="font-semibold">{{ $u->name }}{{ $u->id === auth()->id() ? ' (you)' : '' }}</td><td class="text-stone">{{ $u->email }}</td><td><form method="post" action="{{ route('admin.users.update', $u) }}" class="flex items-center gap-2">@csrf @method('patch')<select name="role" class="input input-sm max-w-28" onchange="this.form.submit()"><option value="staff" @selected($u->role === 'staff')>Staff</option><option value="admin" @selected($u->role === 'admin')>Admin</option></select></form></td><td class="text-right whitespace-nowrap"><form method="post" action="{{ route('admin.users.update', $u) }}" class="inline" onsubmit="return confirm('Generate a new password for this login?')">@csrf @method('patch')<input type="hidden" name="reset" value="1"><button class="btn-light btn-sm">New password</button></form> @if ($u->id !== auth()->id())<form method="post" action="{{ route('admin.users.destroy', $u) }}" class="inline" onsubmit="return confirm('Remove this login?')">@csrf @method('delete')<button class="btn-light btn-sm text-rose">Remove</button></form>@endif</td></tr>@endforeach
        </tbody></table></div>
        <form method="post" action="{{ route('admin.users.store') }}" class="card space-y-4">@csrf<h2 class="font-display text-2xl">Add a login</h2>
            <x-admin.field label="Name" name="name" required><input name="name" value="{{ old('name') }}" required class="input"></x-admin.field>
            <x-admin.field label="Email" name="email" required><input name="email" type="email" value="{{ old('email') }}" required class="input"></x-admin.field>
            <x-admin.field label="Role" name="role"><select name="role" class="input"><option value="staff">Staff (bookings, patients, content)</option><option value="admin">Admin (everything)</option></select></x-admin.field>
            <x-admin.field label="Password" name="password" hint="Leave blank to generate one."><input name="password" type="text" class="input" autocomplete="off"></x-admin.field>
            <button class="btn-gold w-full">Create login</button>
        </form>
    </div>
</x-admin.layout>
