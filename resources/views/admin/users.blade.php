@extends('layouts.app')

@section('content')
<section class="mx-auto max-w-7xl px-6 py-12">
    <h2 class="text-2xl font-semibold">Manage Users</h2>
    <div class="mt-6 overflow-hidden rounded-xl bg-white shadow-soft">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-sm font-medium text-text-secondary">Name</th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-text-secondary">Email</th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-text-secondary">Username</th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-text-secondary">Role</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach ($users as $u)
                    <tr>
                        <td class="px-4 py-3">{{ $u->name }}</td>
                        <td class="px-4 py-3">{{ $u->email }}</td>
                        <td class="px-4 py-3">{{ $u->username }}</td>
                        <td class="px-4 py-3">{{ $u->role }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="px-4 py-3">{{ $users->links() }}</div>
    </div>
</section>
@endsection
