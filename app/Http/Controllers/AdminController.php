<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Content;
use App\Models\Facility;
use App\Models\Admin;
use App\Models\VrScene;
use App\Models\VrHotspot;
use App\Models\KritikSaran;
use App\Models\VisitorStat;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $contents = Content::orderBy('section')->orderBy('content_key')->get();
        $facilities = Facility::orderByDesc('created_at')->get();
        $users = Admin::orderByDesc('id')->get();
        $vrScenes = VrScene::orderBy('id')->get();
        $vrHotspots = VrHotspot::with('scene')->orderBy('scene_id')->orderBy('id')->get();
        $kritikSaran = KritikSaran::orderByDesc('created_at')->get();

        // Dashboard statistics
        $stats = [
            'total_visitors' => VisitorStat::where('is_admin', 0)->distinct('ip_address')->count('ip_address'),
            'vr_visitors' => VisitorStat::where('page_visited', 'virtual-tour')->where('is_admin', 0)->distinct('ip_address')->count('ip_address'),
            'kritik_count' => KritikSaran::count(),
            'today_visitors' => VisitorStat::where('visit_date', now()->toDateString())->where('is_admin', 0)->distinct('ip_address')->count('ip_address'),
        ];

        $recentVisitors = VisitorStat::where('is_admin', 0)
            ->orderByDesc('visit_time')
            ->limit(10)
            ->get();

        // Get images from asset folder
        $images = [];
        $assetPath = public_path('asset');
        if (File::isDirectory($assetPath)) {
            $files = File::glob($assetPath . '/*.{jpg,jpeg,png,gif,webp}', GLOB_BRACE);
            foreach ($files as $file) {
                $images[] = 'asset/' . basename($file);
            }
        }

        return view('admin.dashboard', compact(
            'contents', 'facilities', 'users', 'vrScenes', 'vrHotspots',
            'kritikSaran', 'stats', 'recentVisitors', 'images'
        ));
    }

    public function updateContent(Request $request)
    {
        $request->validate(['content_id' => 'required|integer', 'content_value' => 'required|string']);
        Content::where('id', $request->content_id)->update(['content_value' => $request->content_value]);
        return redirect()->route('admin.dashboard', ['tab' => 'content'])->with('success', 'Konten berhasil diupdate!');
    }

    public function addFacility(Request $request)
    {
        $request->validate(['name' => 'required|string', 'description' => 'required|string', 'image' => 'required|string']);
        Facility::create($request->only('name', 'description', 'image'));
        return redirect()->route('admin.dashboard', ['tab' => 'facilities'])->with('success', 'Fasilitas berhasil ditambahkan!');
    }

    public function updateFacility(Request $request)
    {
        $request->validate(['facility_id' => 'required|integer', 'name' => 'required|string', 'description' => 'required|string', 'image' => 'required|string']);
        Facility::where('id', $request->facility_id)->update($request->only('name', 'description', 'image'));
        return redirect()->route('admin.dashboard', ['tab' => 'facilities'])->with('success', 'Fasilitas berhasil diupdate!');
    }

    public function deleteFacility(Request $request)
    {
        $request->validate(['facility_id' => 'required|integer']);
        Facility::where('id', $request->facility_id)->delete();
        return redirect()->route('admin.dashboard', ['tab' => 'facilities'])->with('success', 'Fasilitas berhasil dihapus!');
    }

    public function addUser(Request $request)
    {
        $request->validate(['username' => 'required|string|max:50', 'password' => 'required|string|min:4']);
        Admin::create(['username' => $request->username, 'password' => Hash::make($request->password)]);
        return redirect()->route('admin.dashboard', ['tab' => 'users'])->with('success', 'User berhasil ditambahkan!');
    }

    public function updateUser(Request $request)
    {
        $request->validate(['user_id' => 'required|integer', 'username' => 'required|string|max:50']);
        $data = ['username' => $request->username];
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }
        Admin::where('id', $request->user_id)->update($data);
        return redirect()->route('admin.dashboard', ['tab' => 'users'])->with('success', 'User berhasil diupdate!');
    }

    public function deleteUser(Request $request)
    {
        $request->validate(['user_id' => 'required|integer']);
        if ($request->user_id == $request->session()->get('admin_id')) {
            return redirect()->route('admin.dashboard', ['tab' => 'users'])->with('error', 'Tidak dapat menghapus akun yang sedang digunakan!');
        }
        Admin::where('id', $request->user_id)->delete();
        return redirect()->route('admin.dashboard', ['tab' => 'users'])->with('success', 'User berhasil dihapus!');
    }

    public function addScene(Request $request)
    {
        $request->validate(['name' => 'required|string', 'scene_key' => 'required|string', 'image_360' => 'required|string', 'icon' => 'required|string', 'description' => 'required|string']);
        VrScene::create($request->only('name', 'description', 'scene_key', 'image_360', 'icon'));
        return redirect()->route('admin.dashboard', ['tab' => 'virtual-tour'])->with('success', 'Scene VR berhasil ditambahkan!');
    }

    public function updateScene(Request $request)
    {
        $request->validate(['scene_id' => 'required|integer', 'name' => 'required|string', 'scene_key' => 'required|string', 'image_360' => 'required|string', 'icon' => 'required|string', 'description' => 'required|string']);
        VrScene::where('id', $request->scene_id)->update($request->only('name', 'description', 'scene_key', 'image_360', 'icon'));
        return redirect()->route('admin.dashboard', ['tab' => 'virtual-tour'])->with('success', 'Scene VR berhasil diupdate!');
    }

    public function deleteScene(Request $request)
    {
        $request->validate(['scene_id' => 'required|integer']);
        VrHotspot::where('scene_id', $request->scene_id)->delete();
        VrScene::where('id', $request->scene_id)->delete();
        return redirect()->route('admin.dashboard', ['tab' => 'virtual-tour'])->with('success', 'Scene VR berhasil dihapus!');
    }

    public function addHotspot(Request $request)
    {
        $request->validate(['scene_id' => 'required|integer', 'name' => 'required|string', 'target_scene' => 'required|string', 'position_x' => 'required|numeric', 'position_y' => 'required|numeric', 'position_z' => 'required|numeric']);
        VrHotspot::create($request->only('scene_id', 'name', 'target_scene', 'position_x', 'position_y', 'position_z'));
        return redirect()->route('admin.dashboard', ['tab' => 'virtual-tour'])->with('success', 'Hotspot berhasil ditambahkan!');
    }

    public function deleteHotspot(Request $request)
    {
        $request->validate(['hotspot_id' => 'required|integer']);
        VrHotspot::where('id', $request->hotspot_id)->delete();
        return redirect()->route('admin.dashboard', ['tab' => 'virtual-tour'])->with('success', 'Hotspot berhasil dihapus!');
    }

    public function deleteKritikSaran(Request $request)
    {
        $request->validate(['kritik_id' => 'required|integer']);
        KritikSaran::where('id', $request->kritik_id)->delete();
        return redirect()->route('admin.dashboard', ['tab' => 'kritik-saran'])->with('success', 'Kritik & Saran berhasil dihapus!');
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png,gif,webp|max:5120',
        ]);

        $file = $request->file('image');
        $uniqueName = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('asset'), $uniqueName);

        return redirect()->route('admin.dashboard', ['tab' => 'upload'])->with('success', 'File berhasil diupload sebagai ' . $uniqueName);
    }

    public function deleteImage(Request $request)
    {
        $request->validate(['image_path' => 'required|string']);
        $imagePath = $request->image_path;

        if (str_starts_with($imagePath, 'asset/') && File::exists(public_path($imagePath))) {
            File::delete(public_path($imagePath));
            return redirect()->route('admin.dashboard', ['tab' => 'upload'])->with('success', 'File berhasil dihapus.');
        }

        return redirect()->route('admin.dashboard', ['tab' => 'upload'])->with('error', 'File tidak ditemukan atau tidak diizinkan.');
    }
}
