<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Candidates;
use App\Models\Cities;
use App\Models\Days;
use App\Models\Industries;
use App\Models\Skills;
use App\Models\TypeJobs;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AuthControllerUser extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function updateProfile(Request $request)
    {
        Log::info('=== UPDATE PROFILE REQUEST ===');
        Log::info('All Request Data:', $request->all());
        Log::info('Request Files:', $request->allFiles());

        try {
            $user = auth()->user();
            $candidate = Candidates::where('user_id', $user->id)->first();

            if (!$candidate) {
                return response()->json([
                    'success' => false,
                    'message' => 'Candidate profile not found.'
                ], 404);
            }

            // ✅ Validasi input
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'phone_number' => 'required|string|max:15',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'new_password' => 'nullable|string|min:8',
                'confirm_password' => 'nullable|string|min:8|same:new_password',
                'gender' => 'required|in:male,female',
                'description' => 'nullable|string',
                'birth_date' => 'nullable|date',
                'level_english' => 'nullable|in:beginner,intermediate,expert',
                'level_mandarin' => 'nullable|in:beginner,intermediate,expert',
                'profile_photo' => 'nullable|image|mimes:jpeg,jpg,png|max:2048', // 2MB
                'preferred_skills' => 'nullable|array',
                'preferred_skills.*' => 'exists:skills,id',
                'preferred_cities' => 'nullable|array',
                'preferred_cities.*' => 'exists:cities,id',
                'preferred_days' => 'nullable|array',
                'preferred_days.*' => 'exists:days,id',
                'preferred_industries' => 'nullable|array',
                'preferred_industries.*' => 'exists:industries,id',
                'preferred_type_jobs' => 'nullable|array',
                'preferred_type_jobs.*' => 'exists:type_jobs,id',
                'portfolio' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
            ]);

            // ✅ UPDATE CANDIDATE DATA (termasuk photo)
            $candidateUpdated = false;
            $photoUrl = null;

            $fieldsToUpdate = [
                'name' => $request->name,
                'phone_number' => $request->phone_number,
                'gender' => $request->gender,
            ];

            if ($request->filled('description')) {
                $fieldsToUpdate['description'] = $request->description;
            }
            if ($request->filled('birth_date')) {
                $fieldsToUpdate['birth_date'] = $request->birth_date;
            }
            if ($request->filled('level_english')) {
                $fieldsToUpdate['level_english'] = $request->level_english;
            }
            if ($request->filled('level_mandarin')) {
                $fieldsToUpdate['level_mandarin'] = $request->level_mandarin;
            }

            // ✅ HANDLE PROFILE PHOTO UPLOAD (simpan di candidates table)
            if ($request->hasFile('profile_photo')) {
                Log::info('Processing profile photo upload');

                // Hapus foto lama jika ada
                if ($candidate->photo && Storage::exists('public/' . $candidate->photo)) {
                    Storage::delete('public/' . $candidate->photo);
                    Log::info('Old photo deleted');
                }

                // Upload foto baru
                $photo = $request->file('profile_photo');
                $photoPath = $photo->store('profile-photos', 'public');

                $fieldsToUpdate['photo'] = $photoPath;
                $photoUrl = Storage::url($photoPath);
                $candidateUpdated = true;

                Log::info('New photo uploaded:', ['path' => $photoPath]);
            }

            // Update fields yang berubah
            foreach ($fieldsToUpdate as $field => $value) {
                if ($candidate->$field !== $value) {
                    $candidate->$field = $value;
                    $candidateUpdated = true;
                }
            }

            if ($candidateUpdated) {
                $candidate->save();
            }

            // ✅ UPDATE USER DATA (hanya EMAIL dan PASSWORD)
            $userUpdated = false;

            if ($user->email !== $request->email) {
                $user->email = $request->email;
                $userUpdated = true;
            }

            if ($request->filled('new_password') && $request->filled('confirm_password')) {
                $user->password = Hash::make($request->new_password);
                $userUpdated = true;
            }

            if ($userUpdated) {
                $user->save();
            }

            // ✅ UPDATE RELASI
            DB::beginTransaction();

            try {
                if ($request->has('preferred_skills')) {
                    $candidate->skills()->sync($request->preferred_skills ?? []);
                }

                if ($request->has('preferred_cities')) {
                    $candidate->preferredCities()->sync($request->preferred_cities ?? []);
                }

                if ($request->has('preferred_days')) {
                    $candidate->days()->sync($request->preferred_days ?? []);
                }

                if ($request->has('preferred_industries')) {
                    $candidate->industries()->sync($request->preferred_industries ?? []);
                }

                if ($request->has('preferred_type_jobs')) {
                    $candidate->preferredTypeJobs()->sync($request->preferred_type_jobs ?? []);
                }

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

            // ✅ Handle portfolio upload jika ada
            if ($request->hasFile('portfolio')) {
                $portfolio = $request->file('portfolio');
                $filename = time() . '_' . uniqid() . '.' . $portfolio->getClientOriginalExtension();
                $path = $portfolio->storeAs('portfolios', $filename, 'public');

                $candidate->portofolios()->create([
                    'file' => $path,
                    'caption' => $request->caption ?? null,
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully',
                'photo_url' => $photoUrl // Return photo URL jika ada
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Profile Update Error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }



    public function index()
    {
        try {
            $user = Auth::user();

            // ✅ CHECK & CREATE CANDIDATE IF NOT EXISTS
            $candidate = Candidates::where('user_id', $user->id)->first();

            if (!$candidate) {
                // Auto-create candidate profile
                $candidate = Candidates::create([
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'phone_number' => null,
                    'gender' => null,
                    'description' => null,
                    'birth_date' => null,
                    'level_english' => 'beginner',
                    'level_mandarin' => 'beginner',
                    'point' => 0,
                    'avg_rating' => 0,
                ]);

                Log::info('Auto-created candidate profile for user: ' . $user->id);
            }

            // Load relationships
            $candidate->load([
                'user',
                'preferredTypeJobs',
                'preferredIndustries',
                'preferredCities',
                'days',
                'portofolios',
                'skills'
            ]);

            $cities = Cities::all();
            $days = Days::all();
            $skills = Skills::all();
            $industries = Industries::all();
            $typeJobs = TypeJobs::all();

            return view('candidates.profile', compact('candidate', 'cities', 'days', 'industries', 'typeJobs', 'skills'));
        } catch (Exception $e) {
            Log::error('Error retrieving candidate profile: ' . $e->getMessage());
            Log::error($e->getTraceAsString());

            return redirect()->route('index.home')->with('error', 'Terjadi kesalahan saat mengambil data profil.');
        }
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
        //
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
