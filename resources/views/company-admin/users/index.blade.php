@extends('company-admin.layouts.app')

@section('title', 'Gestion des utilisateurs')
@section('page-title', 'Gestion des utilisateurs')

@section('content')
<!-- En-tête avec bouton d'action -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Gestion des utilisateurs</h2>
        <p class="text-gray-600 mt-1">Gérez les membres de votre équipe et leurs invitations</p>
    </div>
    <button onclick="openInviteModal()" class="btn-primary-modern mt-4 sm:mt-0">
        <i class="fas fa-user-plus mr-2"></i>
        Inviter un employé
    </button>
</div>

<!-- Invitations en attente -->
@if($invitations->count() > 0)
<div class="modern-card mb-8">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">Invitations en attente</h3>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rôle</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Envoyée</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expire</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($invitations as $invitation)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ $invitation->email }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        @if($invitation->role === 'company_admin')
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Administrateur
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                Membre
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $invitation->created_at->format('d/m/Y H:i') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <span class="{{ $invitation->expires_at->isPast() ? 'text-red-600' : 'text-gray-900' }}">
                            {{ $invitation->expires_at->format('d/m/Y H:i') }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <button onclick="resendInvitation({{ $invitation->id }})" 
                                    class="text-blue-600 hover:text-blue-900 text-xs">
                                <i class="fas fa-paper-plane mr-1"></i>Renvoyer
                            </button>
                            <button onclick="cancelInvitation({{ $invitation->id }})" 
                                    class="text-red-600 hover:text-red-900 text-xs">
                                <i class="fas fa-times mr-1"></i>Annuler
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

<!-- Liste des utilisateurs -->
<div class="modern-card">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg font-semibold text-gray-900">Membres de l'équipe</h3>
        <div class="flex items-center space-x-4">
            <!-- Filtres -->
            <select class="input-modern text-sm" onchange="filterUsers(this.value)">
                <option value="">Tous les rôles</option>
                <option value="company_admin">Administrateurs</option>
                <option value="member">Membres</option>
            </select>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Utilisateur</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rôle</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Projets</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Inscrit le</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($users as $user)
                <tr class="user-row" data-role="{{ $user->role }}">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                                <span class="text-white font-semibold text-sm">{{ substr($user->name, 0, 1) }}</span>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                <div class="text-sm text-gray-500">{{ $user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($user->role === 'company_admin')
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Administrateur
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                Membre
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $user->projects_count ?? 0 }} projets
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $user->created_at->format('d/m/Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            @if($user->id !== Auth::id())
                                <button onclick="changeUserRole({{ $user->id }}, '{{ $user->role }}')" 
                                        class="text-blue-600 hover:text-blue-900 text-xs">
                                    <i class="fas fa-user-edit mr-1"></i>Modifier
                                </button>
                                <button onclick="deleteUser({{ $user->id }}, '{{ $user->name }}')" 
                                        class="text-red-600 hover:text-red-900 text-xs">
                                    <i class="fas fa-trash mr-1"></i>Supprimer
                                </button>
                            @else
                                <span class="text-gray-400 text-xs">Vous</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                        Aucun utilisateur trouvé
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($users->hasPages())
        <div class="mt-6">
            {{ $users->links() }}
        </div>
    @endif
</div>

<!-- Modal d'invitation -->
<div id="inviteModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeInviteModal()"></div>
        
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form method="POST" action="{{ route('company-admin.users.invite') }}">
                @csrf
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fas fa-user-plus text-blue-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Inviter un nouvel employé
                            </h3>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                    <input type="email" name="email" id="email" required
                                           class="mt-1 input-modern @error('email') border-red-300 @enderror"
                                           placeholder="employe@entreprise.com">
                                    @error('email')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="role" class="block text-sm font-medium text-gray-700">Rôle</label>
                                    <select name="role" id="role" required
                                            class="mt-1 input-modern @error('role') border-red-300 @enderror">
                                        <option value="">Sélectionner un rôle</option>
                                        <option value="member">Membre</option>
                                        <option value="company_admin">Administrateur d'équipe</option>
                                    </select>
                                    @error('role')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="btn-primary-modern w-full sm:w-auto sm:ml-3">
                        Envoyer l'invitation
                    </button>
                    <button type="button" onclick="closeInviteModal()" 
                            class="mt-3 btn-secondary-modern w-full sm:mt-0 sm:w-auto">
                        Annuler
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de changement de rôle -->
<div id="roleModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="role-modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeRoleModal()"></div>
        
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form id="roleForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fas fa-user-edit text-blue-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="role-modal-title">
                                Modifier le rôle
                            </h3>
                            <div class="mt-4">
                                <label for="new_role" class="block text-sm font-medium text-gray-700">Nouveau rôle</label>
                                <select name="role" id="new_role" required
                                        class="mt-1 input-modern">
                                    <option value="member">Membre</option>
                                    <option value="company_admin">Administrateur d'équipe</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="btn-primary-modern w-full sm:w-auto sm:ml-3">
                        Modifier le rôle
                    </button>
                    <button type="button" onclick="closeRoleModal()" 
                            class="mt-3 btn-secondary-modern w-full sm:mt-0 sm:w-auto">
                        Annuler
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openInviteModal() {
    document.getElementById('inviteModal').classList.remove('hidden');
}

function closeInviteModal() {
    document.getElementById('inviteModal').classList.add('hidden');
}

function openRoleModal(userId, currentRole) {
    const form = document.getElementById('roleForm');
    form.action = `/company-admin/users/${userId}/role`;
    document.getElementById('new_role').value = currentRole;
    document.getElementById('roleModal').classList.remove('hidden');
}

function closeRoleModal() {
    document.getElementById('roleModal').classList.add('hidden');
}

function changeUserRole(userId, currentRole) {
    openRoleModal(userId, currentRole);
}

function deleteUser(userId, userName) {
    if (confirm(`Êtes-vous sûr de vouloir supprimer l'utilisateur "${userName}" ?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/company-admin/users/${userId}/delete`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        
        form.appendChild(csrfToken);
        form.appendChild(methodField);
        document.body.appendChild(form);
        form.submit();
    }
}

function filterUsers(role) {
    const rows = document.querySelectorAll('.user-row');
    rows.forEach(row => {
        if (role === '' || row.dataset.role === role) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

function resendInvitation(invitationId) {
    // TODO: Implémenter le renvoi d'invitation
    alert('Fonctionnalité de renvoi d\'invitation à implémenter');
}

function cancelInvitation(invitationId) {
    if (confirm('Êtes-vous sûr de vouloir annuler cette invitation ?')) {
        // TODO: Implémenter l'annulation d'invitation
        alert('Fonctionnalité d\'annulation d\'invitation à implémenter');
    }
}
</script>
@endpush
