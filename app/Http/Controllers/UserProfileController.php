<?php

namespace App\Http\Controllers;

use App\Models\UserProfile;
use Illuminate\Http\Request;

class UserProfileController extends Controller
{
    public function edit()
    {
        $profile = UserProfile::ambil();
        return view('profile.edit', compact('profile'));
    }

    public function update(Request $request)
    {
        $profile = UserProfile::ambil();

        $validated = $request->validate([
            'nama'        => 'required|string|max:100',
            'jabatan'     => 'required|string|max:100',
            'foto'        => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
            'foto_kepsek' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
        ]);

        $profile->nama    = $validated['nama'];
        $profile->jabatan = $validated['jabatan'];

        // Foto profil operator
        if ($request->hasFile('foto')) {
            if ($profile->foto_path && file_exists(public_path($profile->foto_path))) {
                @unlink(public_path($profile->foto_path));
            }
            $file = $request->file('foto');
            $filename = 'profile-' . time() . '.' . $file->getClientOriginalExtension();
            if (!file_exists(public_path('assets/profile'))) {
                mkdir(public_path('assets/profile'), 0755, true);
            }
            $file->move(public_path('assets/profile'), $filename);
            $profile->foto_path = 'assets/profile/' . $filename;
        }

        // Foto Kepala Sekolah
        if ($request->hasFile('foto_kepsek')) {
            if ($profile->foto_kepsek && file_exists(public_path($profile->foto_kepsek))) {
                @unlink(public_path($profile->foto_kepsek));
            }
            $file = $request->file('foto_kepsek');
            $filename = 'kepsek-' . time() . '.' . $file->getClientOriginalExtension();
            if (!file_exists(public_path('assets/kepsek'))) {
                mkdir(public_path('assets/kepsek'), 0755, true);
            }
            $file->move(public_path('assets/kepsek'), $filename);
            $profile->foto_kepsek = 'assets/kepsek/' . $filename;
        }

        $profile->save();

        return redirect()->route('profile.edit')
            ->with('success', 'Profil berhasil diupdate!');
    }

    public function hapusFoto()
    {
        $profile = UserProfile::ambil();
        if ($profile->foto_path && file_exists(public_path($profile->foto_path))) {
            @unlink(public_path($profile->foto_path));
        }
        $profile->foto_path = null;
        $profile->save();
        return redirect()->route('profile.edit')
            ->with('success', 'Foto profil berhasil dihapus.');
    }

    public function hapusFotoKepsek()
    {
        $profile = UserProfile::ambil();
        if ($profile->foto_kepsek && file_exists(public_path($profile->foto_kepsek))) {
            @unlink(public_path($profile->foto_kepsek));
        }
        $profile->foto_kepsek = null;
        $profile->save();
        return redirect()->route('profile.edit')
            ->with('success', 'Foto Kepala Sekolah berhasil dihapus.');
    }
}