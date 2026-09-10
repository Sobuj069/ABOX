@extends('admin.layout')

@section('title', 'User Accounts & VIP Subscriptions')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4">
        <div>
            <h2 class="text-xl font-black text-white">User Accounts & VIP Membership</h2>
            <p class="text-xs text-slate-400">View user balances, grant credits, and manage VIP subscriptions</p>
        </div>

        <!-- Search Bar -->
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex items-center gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or email..." class="bg-dark-800 border border-dark-700 rounded-xl px-3.5 py-1.5 text-xs text-white focus:outline-none focus:border-purple-500 w-52">
            <button type="submit" class="p-2 rounded-xl bg-purple-600 hover:bg-purple-500 text-white text-xs">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
        </form>
    </div>

    <!-- Users Table -->
    <div class="bg-dark-900 border border-dark-700/70 rounded-2xl overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-300">
                <thead class="bg-dark-800 text-[11px] uppercase tracking-wider text-slate-400 font-bold border-b border-dark-700">
                    <tr>
                        <th class="p-4">User</th>
                        <th class="p-4">Role</th>
                        <th class="p-4">Credits Balance</th>
                        <th class="p-4">VIP Status</th>
                        <th class="p-4">Joined Date</th>
                        <th class="p-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-dark-800">
                    @foreach($users as $user)
                        <tr class="hover:bg-dark-850/60 transition">
                            <td class="p-4">
                                <div class="flex items-center gap-3">
                                    <img src="{{ asset($user->avatar ?: '/images/voices/ai_male4.svg') }}" class="w-9 h-9 rounded-xl object-cover bg-dark-800 border border-dark-700">
                                    <div>
                                        <div class="font-bold text-white text-sm">{{ $user->name }}</div>
                                        <span class="text-[11px] text-slate-400">{{ $user->email }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="p-4">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase {{ $user->isAdmin() ? 'bg-purple-950 text-purple-300 border border-purple-500/40' : 'bg-dark-800 text-slate-400' }}">
                                    {{ $user->role }}
                                </span>
                            </td>
                            <td class="p-4 font-mono font-bold text-amber-400">
                                <i class="fa-solid fa-gem text-xs mr-1"></i> {{ $user->credits }}
                            </td>
                            <td class="p-4">
                                @if($user->hasVip())
                                    <span class="px-2 py-0.5 rounded-full bg-amber-500/20 border border-amber-500/40 text-amber-400 font-black text-[10px] inline-flex items-center gap-1">
                                        <i class="fa-solid fa-crown text-[8px]"></i> VIP ACTIVE
                                    </span>
                                    <span class="block text-[9px] text-slate-400 mt-0.5">Expires: {{ $user->vip_expires_at ? $user->vip_expires_at->format('M d, Y') : 'Lifetime' }}</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full bg-dark-800 text-slate-400 text-[10px] font-bold">
                                        Free Tier
                                    </span>
                                @endif
                            </td>
                            <td class="p-4 text-slate-400 text-[11px]">
                                {{ $user->created_at->format('M d, Y') }}
                            </td>
                            <td class="p-4 text-right">
                                <button onclick="openEditUserModal({{ $user->id }}, '{{ $user->name }}', '{{ $user->role }}', {{ $user->credits }}, {{ $user->is_vip ? 'true' : 'false' }})" class="px-3 py-1.5 rounded-lg bg-dark-800 hover:bg-dark-700 text-purple-300 hover:text-purple-200 text-xs font-semibold">
                                    <i class="fa-solid fa-pen-to-square mr-1"></i> Edit User
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-dark-800">
            {{ $users->links() }}
        </div>
    </div>

</div>

<!-- Edit User Modal -->
<div id="editUserModal" class="fixed inset-0 bg-black/75 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-dark-850 border border-dark-700 rounded-3xl p-6 space-y-4 shadow-2xl">
        <div class="flex justify-between items-center pb-2 border-b border-dark-700">
            <div>
                <h3 class="font-bold text-white text-base">Edit User Account</h3>
                <p class="text-xs text-slate-400" id="modalUserNameLabel">Alex Voice</p>
            </div>
            <button onclick="closeEditUserModal()" class="text-slate-400 hover:text-white">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <form id="editUserForm" method="POST" action="" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-300">Credits Balance</label>
                <input type="number" name="credits" id="modalCreditsInput" required min="0" class="w-full bg-dark-800 border border-dark-700 rounded-xl px-3.5 py-2 text-sm text-white focus:outline-none focus:border-purple-500">
            </div>

            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-300">Account Role</label>
                <select name="role" id="modalRoleSelect" class="w-full bg-dark-800 border border-dark-700 rounded-xl px-3.5 py-2 text-sm text-white focus:outline-none focus:border-purple-500">
                    <option value="user">Standard User</option>
                    <option value="admin">Administrator</option>
                </select>
            </div>

            <div class="p-3.5 rounded-xl bg-dark-800/60 border border-dark-700 space-y-3">
                <label class="flex items-center gap-2 cursor-pointer text-xs font-bold text-amber-400">
                    <input type="checkbox" name="is_vip" id="modalIsVipCheckbox" value="1" class="w-4 h-4 rounded accent-amber-500">
                    <span>Grant VIP Membership</span>
                </label>

                <div class="space-y-1" id="vipDurationGroup">
                    <label class="text-[11px] font-medium text-slate-300">VIP Duration (Days, 0 for Lifetime)</label>
                    <input type="number" name="vip_days" value="30" min="0" class="w-full bg-dark-900 border border-dark-700 rounded-lg px-2.5 py-1.5 text-xs text-white">
                </div>
            </div>

            <div class="pt-3 border-t border-dark-800 flex justify-end gap-2">
                <button type="button" onclick="closeEditUserModal()" class="px-4 py-2 rounded-xl bg-dark-800 text-slate-300 text-xs font-bold">Cancel</button>
                <button type="submit" class="px-5 py-2 rounded-xl gradient-btn text-white text-xs font-bold">Update Account</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditUserModal(userId, name, role, credits, isVip) {
        document.getElementById('modalUserNameLabel').innerText = name;
        document.getElementById('modalCreditsInput').value = credits;
        document.getElementById('modalRoleSelect').value = role;
        document.getElementById('modalIsVipCheckbox').checked = isVip;
        document.getElementById('editUserForm').action = `/admin/users/${userId}`;
        document.getElementById('editUserModal').classList.remove('hidden');
    }
    function closeEditUserModal() {
        document.getElementById('editUserModal').classList.add('hidden');
    }
</script>
@endsection
