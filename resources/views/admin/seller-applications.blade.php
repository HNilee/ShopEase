@extends('layouts.app')

@section('content')
<section class="mx-auto max-w-7xl px-6 py-8">
    <div class="bg-white rounded-xl shadow-soft p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-semibold">Seller Applications</h2>
            <div class="flex gap-2">
                <a href="{{ route('admin.users') }}" class="bg-gray-500 text-white px-4 py-2 rounded-full hover:bg-gray-600 transition-colors">
                    Manage Users
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Applicant</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Details</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submitted</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($applications as $application)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="text-sm font-medium text-gray-900">{{ $application->user->name }}</div>
                                </div>
                                <div class="text-sm text-gray-500">{{ $application->user->username }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">
                                    <div><strong>Full Name:</strong> {{ $application->full_name }}</div>
                                    <div><strong>Age:</strong> {{ $application->age }}</div>
                                    <div><strong>Email:</strong> {{ $application->email }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if($application->status === 'pending')
                                        bg-yellow-100 text-yellow-800
                                    @elseif($application->status === 'approved')
                                        bg-green-100 text-green-800
                                    @else
                                        bg-red-100 text-red-800
                                    @endif
                                ">
                                    {{ ucfirst($application->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $application->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex gap-2">
                                    <button onclick="viewApplication({{ $application->id }})" 
                                            class="text-blue-600 hover:text-blue-900 bg-blue-100 px-3 py-1 rounded">
                                        View
                                    </button>
                                    @if($application->status === 'pending')
                                        <button onclick="approveApplication({{ $application->id }})" 
                                                class="text-green-600 hover:text-green-900 bg-green-100 px-3 py-1 rounded">
                                            Approve
                                        </button>
                                        <button onclick="rejectApplication({{ $application->id }})" 
                                                class="text-red-600 hover:text-red-900 bg-red-100 px-3 py-1 rounded">
                                            Reject
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                No seller applications found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $applications->links() }}
        </div>
    </div>
</section>

<!-- Application Details Modal -->
<div id="applicationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Application Details</h3>
            <div id="applicationDetails"></div>
            <div class="flex justify-end gap-2 mt-4">
                <button onclick="closeModal()" class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Reject Application</h3>
            <form id="rejectForm" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rejection Reason</label>
                    <textarea name="rejection_reason" required 
                              class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500"
                              rows="4" placeholder="Please provide a detailed reason for rejection"></textarea>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeRejectModal()" class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">
                        Cancel
                    </button>
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                        Reject
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function viewApplication(id) {
    // Fetch application details via AJAX
    fetch(`/admin/seller-applications/${id}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('applicationDetails').innerHTML = `
                <div class="space-y-3">
                    <div><strong>Full Name:</strong> ${data.full_name}</div>
                    <div><strong>Age:</strong> ${data.age}</div>
                    <div><strong>Email:</strong> ${data.email}</div>
                    <div><strong>Purpose:</strong> ${data.purpose}</div>
                    <div><strong>Security Confidence:</strong> ${data.security_confidence}</div>
                    <div><strong>Status:</strong> ${data.status}</div>
                    ${data.rejection_reason ? `<div><strong>Rejection Reason:</strong> ${data.rejection_reason}</div>` : ''}
                    <div class="mt-4">
                        <strong>KTP:</strong><br>
                        <img src="/storage/${data.ktp_path}" alt="KTP" class="w-full max-w-xs rounded border">
                    </div>
                </div>
            `;
            document.getElementById('applicationModal').classList.remove('hidden');
        })
        .catch(error => console.error('Error:', error));
}

function approveApplication(id) {
    if (confirm('Are you sure you want to approve this application?')) {
        fetch(`/admin/seller-applications/${id}/approve`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        })
        .catch(error => console.error('Error:', error));
    }
}

function rejectApplication(id) {
    const form = document.getElementById('rejectForm');
    form.action = `/admin/seller-applications/${id}/reject`;
    document.getElementById('rejectModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('applicationModal').classList.add('hidden');
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
}

// Close modals when clicking outside
window.onclick = function(event) {
    if (event.target.classList.contains('fixed')) {
        event.target.classList.add('hidden');
    }
}
</script>
@endsection