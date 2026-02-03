<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Companies;
use App\Models\Industries;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileCompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // ✅ Get company data
        $company = Companies::where('user_id', $user->id)->first();

        // ✅ If company doesn't exist, create empty one or redirect to create
        if (!$company) {
            return redirect()->route('company.profile.create')
                ->with('info', 'Lengkapi profil perusahaan Anda terlebih dahulu.');
        }

        // ✅ Get industries for dropdown
        $industries = Industries::orderBy('name', 'asc')->get();

        return view('company.profile', compact('user', 'company', 'industries'));
    }
    public function uploadPhoto(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'profile_photo' => 'required|image|mimes:jpeg,jpg,png|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            $user = Auth::user();

            // ✅ GET COMPANY DATA (BUKAN USER)
            $company = Companies::where('user_id', $user->id)->firstOrFail();

            // ✅ Delete old photo FROM COMPANIES TABLE
            if ($company->photo && Storage::disk('public')->exists($company->photo)) {
                Storage::disk('public')->delete($company->photo);
            }

            // ✅ Store new photo
            $photoPath = $request->file('profile_photo')->store('company_photos', 'public');

            // ✅ UPDATE COMPANIES TABLE (BUKAN USERS)
            $company->update(['photo' => $photoPath]);

            return response()->json([
                'success' => true,
                'message' => 'Foto profil berhasil diperbarui!',
                'photo_url' => Storage::url($photoPath)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupload foto: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function update(Request $request)
    {
        try {
            $user = Auth::user();
            $company = Companies::where('user_id', $user->id)->firstOrFail();

            // ✅ Validation
            $validator = Validator::make($request->all(), [
                'company_name' => 'required|string|max:255',
                'phone_number' => 'required|string|max:20',
                'location' => 'required|string|max:255',
                'description' => 'nullable|string',
                'industries_id' => 'required|exists:industries,id',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'new_password' => 'nullable|min:8|confirmed',
                'profile_photo' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            // ✅ Prepare Company Data
            $companyData = [
                'name' => $request->company_name,
                'phone_number' => $request->phone_number,
                'location' => $request->location,
                'description' => $request->description,
                'industries_id' => $request->industries_id,
            ];

            // ✅ Handle Photo Upload - SIMPAN DI TABEL COMPANIES
            if ($request->hasFile('profile_photo')) {
                // Delete old photo
                if ($company->photo && Storage::disk('public')->exists($company->photo)) {
                    Storage::disk('public')->delete($company->photo);
                }

                // Store new photo
                $photoPath = $request->file('profile_photo')->store('company_photos', 'public');
                $companyData['photo'] = $photoPath; // ← SIMPAN DI COMPANIES
            }

            // ✅ Update Company Data
            $company->update($companyData);

            // ✅ Update User Data (tanpa photo)
            $userData = [
                'name' => $request->company_name,
                'email' => $request->email,
            ];

            // ✅ Update Password if provided
            if ($request->filled('new_password')) {
                $userData['password'] = Hash::make($request->new_password);
            }

            $user->update($userData);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Profil perusahaan berhasil diperbarui!',
                'data' => [
                    'company' => $company->fresh(), // ← Refresh data
                    'user' => $user->fresh()
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

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


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
