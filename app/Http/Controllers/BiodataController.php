<?php

namespace App\Http\Controllers;

use App\Models\Biodata;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use RuntimeException;

class BiodataController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'biodataEntries' => Biodata::query()->latest()->paginate(8),
            'cv' => Biodata::query()->latest()->first(),
        ]);
    }

    public function resume(): View
    {
        return view('resume', [
            'cv' => Biodata::query()->latest()->first(),
        ]);
    }

    public function showResume(Biodata $biodata): View
    {
        return view('resume', [
            'cv' => $biodata,
        ]);
    }

    public function create(): View
    {
        return view('admin.biodata-form', [
            'biodata' => new Biodata,
            'isEditing' => false,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nim' => ['required', 'string', 'max:30', 'unique:Biodata_1021,nim'],
            'email' => ['required', 'email', 'max:255', 'unique:Biodata_1021,email'],
            'phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:2000'],
            'photo' => ['nullable', 'image', 'max:2048'],
            'professional_title' => ['nullable', 'string', 'max:255'],
            'summary' => ['nullable', 'string', 'max:5000'],
            'experience' => ['nullable', 'string', 'max:12000'],
            'education' => ['nullable', 'string', 'max:12000'],
            'skills' => ['nullable', 'string', 'max:5000'],
            'interests' => ['nullable', 'string', 'max:5000'],
            'awards' => ['nullable', 'string', 'max:5000'],
        ]);

        if (isset($validated['photo'])) {
            $validated['photo_path'] = $this->storePhoto($validated['photo']);
            unset($validated['photo']);
        }

        Biodata::query()->create($validated);

        return redirect()->route('dashboard')->with('success', 'Biodata berhasil ditambahkan.');
    }

    public function edit(Biodata $biodata): View
    {
        return view('admin.biodata-form', [
            'biodata' => $biodata,
            'isEditing' => true,
        ]);
    }

    public function update(Request $request, Biodata $biodata): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nim' => ['required', 'string', 'max:30', Rule::unique('Biodata_1021', 'nim')->ignore($biodata)],
            'email' => ['required', 'email', 'max:255', Rule::unique('Biodata_1021', 'email')->ignore($biodata)],
            'phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:2000'],
            'photo' => ['nullable', 'image', 'max:2048'],
            'remove_photo' => ['sometimes', 'boolean'],
            'professional_title' => ['nullable', 'string', 'max:255'],
            'summary' => ['nullable', 'string', 'max:5000'],
            'experience' => ['nullable', 'string', 'max:12000'],
            'education' => ['nullable', 'string', 'max:12000'],
            'skills' => ['nullable', 'string', 'max:5000'],
            'interests' => ['nullable', 'string', 'max:5000'],
            'awards' => ['nullable', 'string', 'max:5000'],
        ]);

        $oldPhotoPath = $biodata->photo_path;
        $removePhoto = $request->boolean('remove_photo');

        if (isset($validated['photo'])) {
            $validated['photo_path'] = $this->storePhoto($validated['photo']);
        } elseif ($removePhoto) {
            $validated['photo_path'] = null;
        }

        unset($validated['photo'], $validated['remove_photo']);
        $biodata->update($validated);

        if ($oldPhotoPath && ($request->hasFile('photo') || $removePhoto)) {
            Storage::disk('public')->delete($oldPhotoPath);
        }

        return redirect()->route('dashboard')->with('success', 'Biodata berhasil diperbarui.');
    }

    public function destroy(Biodata $biodata): RedirectResponse
    {
        $photoPath = $biodata->photo_path;
        $biodata->delete();

        if ($photoPath) {
            Storage::disk('public')->delete($photoPath);
        }

        return redirect()->route('dashboard')->with('success', 'Biodata berhasil dihapus.');
    }

    private function storePhoto(UploadedFile $photo): string
    {
        $path = $photo->store('biodata', 'public');

        if ($path === false) {
            throw new RuntimeException('Foto profil gagal disimpan.');
        }

        return $path;
    }
}
