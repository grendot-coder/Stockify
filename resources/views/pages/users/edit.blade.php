@extends('layouts.dashboard')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('users.index') }}" class="text-xs text-[#A1A1AA] hover:text-[#F5C842] font-medium transition">
                    &larr; Kembali ke Daftar Pengguna
                </a>
            </div>
            <h1 class="text-xl sm:text-2xl font-editorial font-medium text-[#F5F5F5] tracking-tight">
                Edit Akun Pengguna: {{ $user->name }}
            </h1>
            <p class="text-xs text-[#A1A1AA] mt-1">Perbarui profil, hak akses peran, atau setel ulang kata sandi pengguna</p>
        </div>
    </div>

    <!-- Error Alert -->
    @if($errors->any())
    <div class="p-4 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-400 text-xs shadow-xs">
        <div class="font-bold mb-1">Terdapat kesalahan pengisian formulir:</div>
        <ul class="list-disc list-inside space-y-0.5">
            @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Form Container -->
    <div class="bg-[#202429] rounded-2xl border border-[#343A40] p-6 shadow-xs">
        <form action="{{ route('users.update', $user->id) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <!-- Nama Lengkap -->
            <div>
                <label for="name" class="block text-xs font-semibold text-[#F5F5F5] mb-1.5">
                    Nama Lengkap <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                    class="w-full px-3.5 py-2.5 text-xs bg-[#181C20] border border-[#343A40] rounded-xl text-[#F5F5F5] focus:bg-[#202429] focus:outline-none focus:border-[#F5C842] focus:ring-1 focus:ring-[#F5C842] transition">
            </div>

            <!-- Email & Telepon Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="email" class="block text-xs font-semibold text-[#F5F5F5] mb-1.5">
                        Alamat Email (Login) <span class="text-rose-500">*</span>
                    </label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                        class="w-full px-3.5 py-2.5 text-xs bg-[#181C20] border border-[#343A40] rounded-xl text-[#F5F5F5] focus:bg-[#202429] focus:outline-none focus:border-[#F5C842] focus:ring-1 focus:ring-[#F5C842] transition">
                </div>
                <div>
                    <label for="phone" class="block text-xs font-semibold text-[#F5F5F5] mb-1.5">
                        Nomor Telepon / WhatsApp
                    </label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}"
                        placeholder="08123456789"
                        class="w-full px-3.5 py-2.5 text-xs bg-[#181C20] border border-[#343A40] rounded-xl text-[#F5F5F5] focus:bg-[#202429] focus:outline-none focus:border-[#F5C842] focus:ring-1 focus:ring-[#F5C842] transition">
                </div>
            </div>

            <!-- Role & Status Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="role" class="block text-xs font-semibold text-[#F5F5F5] mb-1.5">
                        Peran Otorisasi (Role) <span class="text-rose-500">*</span>
                    </label>
                    <select name="role" id="role" required {{ $user->id === auth()->id() ? 'disabled' : '' }}
                        class="w-full px-3.5 py-2.5 text-xs bg-[#181C20] border border-[#343A40] rounded-xl text-[#F5F5F5] focus:bg-[#202429] focus:outline-none focus:border-[#F5C842] focus:ring-1 focus:ring-[#F5C842] transition {{ $user->id === auth()->id() ? 'opacity-60 cursor-not-allowed' : '' }}">
                        <option value="staff" {{ old('role', $user->role) === 'staff' ? 'selected' : '' }}>Staff Gudang (Penerimaan, Pengeluaran & Opname)</option>
                        <option value="manager" {{ old('role', $user->role) === 'manager' ? 'selected' : '' }}>Manajer Gudang (Laporan, Stok & Pengawasan)</option>
                        <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Administrator (Akses Sistem Menyeluruh)</option>
                    </select>
                    @if($user->id === auth()->id())
                    <input type="hidden" name="role" value="{{ $user->role }}">
                    <span class="text-[10px] text-[#A1A1AA] mt-1 block">Anda tidak dapat mengubah peran akun Anda sendiri.</span>
                    @endif
                </div>
                <div>
                    <label class="block text-xs font-semibold text-[#F5F5F5] mb-1.5">
                        Status Keaktifan Akun
                    </label>
                    <div class="pt-2">
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $user->is_active ? '1' : '0') == '1' ? 'checked' : '' }}
                                {{ $user->id === auth()->id() ? 'disabled' : '' }}
                                class="w-4 h-4 rounded text-[#F5C842] border-[#343A40] focus:ring-[#F5C842]">
                            <span class="text-xs text-[#F5F5F5] font-medium">Akun Aktif</span>
                        </label>
                        @if($user->id === auth()->id())
                        <span class="text-[10px] text-[#A1A1AA] mt-1 block">Anda tidak dapat menonaktifkan akun sendiri.</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Ganti Password Opsional -->
            <div class="pt-4 border-t border-[#343A40]">
                <div class="mb-3">
                    <h3 class="text-xs font-semibold text-[#F5F5F5]">Ganti Kata Sandi (Opsional)</h3>
                    <p class="text-[11px] text-[#A1A1AA]">Kosongkan kedua kolom di bawah jika tidak ingin mengubah kata sandi saat ini.</p>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-xs font-semibold text-[#F5F5F5] mb-1.5">
                            Kata Sandi Baru
                        </label>
                        <input type="password" name="password" id="password" minlength="8"
                            placeholder="Minimal 8 karakter baru"
                            class="w-full px-3.5 py-2.5 text-xs bg-[#181C20] border border-[#343A40] rounded-xl text-[#F5F5F5] focus:bg-[#202429] focus:outline-none focus:border-[#F5C842] focus:ring-1 focus:ring-[#F5C842] transition">
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-xs font-semibold text-[#F5F5F5] mb-1.5">
                            Ulangi Kata Sandi Baru
                        </label>
                        <input type="password" name="password_confirmation" id="password_confirmation" minlength="8"
                            placeholder="Konfirmasi kata sandi baru"
                            class="w-full px-3.5 py-2.5 text-xs bg-[#181C20] border border-[#343A40] rounded-xl text-[#F5F5F5] focus:bg-[#202429] focus:outline-none focus:border-[#F5C842] focus:ring-1 focus:ring-[#F5C842] transition">
                    </div>
                </div>
            </div>

            <!-- Tombol Aksi Simpan -->
            <div class="pt-4 flex items-center justify-end gap-3 border-t border-[#343A40]">
                <a href="{{ route('users.index') }}"
                    class="px-4 py-2.5 text-xs font-medium text-[#A1A1AA] hover:text-[#F5F5F5] hover:bg-[#181C20] rounded-xl transition">
                    Batal
                </a>
                <button type="submit"
                    class="px-5 py-2.5 text-xs font-semibold bg-[#F5C842] hover:bg-[#E8B92E] text-[#101214] rounded-xl shadow-xs transition duration-150">
                    Simpan Perubahan
                </button>
            </div>

        </form>
    </div>

</div>
@endsection