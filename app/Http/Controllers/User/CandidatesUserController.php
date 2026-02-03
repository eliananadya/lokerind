<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Candidates;
use App\Models\Portofolios;
use App\Models\Skills;
use Illuminate\Support\Facades\Storage;

class CandidatesUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function updateSkills(Request $request, $candidateId)
    {
        try {
            $candidate = Candidates::findOrFail($candidateId);

            // Validasi input, jika diperlukan
            $request->validate([
                'preferred_skills' => 'array|nullable',
                'preferred_skills.*' => 'exists:skills,id', // Pastikan id keterampilan ada di tabel skills
            ]);

            // Sinkronisasi keterampilan yang dipilih
            $candidate->skills()->sync($request->input('preferred_skills', [])); // Hapus keterampilan yang tidak dipilih dan tambahkan yang baru

            return redirect()->route('candidate.profile')->with('success', 'Skills updated successfully!');
        } catch (\Exception $e) {
            return redirect()->route('candidate.profile')->with('error', 'Failed to update skills.');
        }
    }

    public function addPortfolio(Request $request, $candidateId)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:10240', // validasi file
            'caption' => 'required|string|max:255',
        ]);

        try {
            $candidate = Candidates::findOrFail($candidateId);

            // Simpan file ke storage dan ambil path-nya
            $path = $request->file('file')->store('portfolios', 'public');

            // Simpan portofolio baru ke database
            $candidate->portofolios()->create([
                'file' => $path,
                'caption' => $request->caption,
                'candidates_id' => $candidate->id,
            ]);

            return redirect()->route('profile.index')->with('success', 'Portfolio successfully added.');
        } catch (\Exception $e) {
            return redirect()->route('profile.index')->with('error', 'Failed to add portfolio.');
        }
    }
    public function deletePortfolio($candidateId, $portfolioId)
    {
        try {
            $candidate = Candidates::findOrFail($candidateId);
            $portfolio = Portofolios::findOrFail($portfolioId);

            // Hapus file dari storage
            if (Storage::exists('public/' . $portfolio->file)) {
                Storage::delete('public/' . $portfolio->file);
            }

            // Hapus dari database
            $portfolio->delete();

            return response()->json([
                'success' => true,
                'message' => 'Portfolio successfully deleted.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete portfolio: ' . $e->getMessage()
            ], 500);
        }
    }

    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'preferred_skills' => 'required|array',
            'preferred_skills.*' => 'exists:skills,id', // Validasi apakah skill yang dipilih ada di database
            'new_skill' => 'nullable|string|max:255', // Validasi skill baru
        ]);

        try {
            // Menambahkan skill baru jika ada input dari pengguna
            if ($request->new_skill) {
                $newSkill = Skills::create([
                    'name' => $request->new_skill,
                ]);
            }

            // Menambahkan skill manual ke array preferred_skills
            $preferredSkills = $request->preferred_skills;

            // Jika ada skill baru, tambahkan ID-nya ke array preferred_skills
            if (isset($newSkill)) {
                $preferredSkills[] = $newSkill->id;
            }

            // Menyimpan atau memperbarui kandidat dengan skill yang dipilih
            $candidate = Candidates::find($request->candidate_id);
            $candidate->skills()->sync($preferredSkills); // Mengaitkan skill dengan kandidat

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
