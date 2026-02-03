<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Companies;
use App\Models\Candidates;
use App\Models\Industries;
use App\Models\Days;
use App\Models\Cities;
use App\Models\Skills;
use App\Models\TypeJobs;
use App\Models\Portofolios;
use App\Models\HistoryPoint;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class RegisteredUserController extends Controller
{
    public function create()
    {
        $industries = Industries::all();
        $days = Days::all();
        $cities = Cities::all();
        $skills = Skills::all();
        $typeJobs = TypeJobs::all();

        return view('auth.register', compact('industries', 'days', 'cities', 'skills', 'typeJobs'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:company,user,super_admin'],

            // Candidate fields
            'gender' => ['required_if:role,user', 'in:male,female'],
            'birth_date' => ['required_if:role,user', 'date'],
            'description' => ['nullable', 'string'],

            'preferred_days' => ['nullable', 'array'],
            'preferred_days.*' => ['integer', 'exists:days,id'],
            'preferred_cities' => ['nullable', 'array'],
            'preferred_cities.*' => ['integer', 'exists:cities,id'],
            'preferred_skills' => ['nullable', 'array'],
            'preferred_skills.*' => ['integer', 'exists:skills,id'],
            'preferred_jobs' => ['nullable', 'array'],
            'preferred_jobs.*' => ['integer', 'exists:type_jobs,id'],
            'preferred_industries' => ['nullable', 'array'],
            'preferred_industries.*' => ['integer', 'exists:industries,id'],
            'portfolios.*' => ['nullable', 'file', 'mimes:pdf,doc,docx,jpg,jpeg,png', 'max:5120'],

            'level_mandarin' => ['nullable', 'in:beginner,intermediate,expert'],
            'level_english' => ['nullable', 'in:beginner,intermediate,expert'],
            'min_height' => ['nullable', 'integer', 'min:100', 'max:250'],
            'min_weight' => ['nullable', 'integer', 'min:30', 'max:200'],
            'min_salary' => ['nullable', 'integer', 'min:0'],

            // Company fields
            'company_name' => ['required_if:role,company', 'string', 'max:255'],
            'company_phone_number' => ['required_if:role,company', 'string', 'max:30'],
            'company_location' => ['required_if:role,company', 'string', 'max:255'],
            'industries_id' => ['required_if:role,company', 'integer', 'exists:industries,id'],
            'company_description' => ['required_if:role,company', 'string'],
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            return back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            $roleModel = Role::where('name', $request->role)->first();
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'id_roles' => $roleModel ? $roleModel->id : null,
            ]);

            if ($roleModel) {
                $user->assignRole($request->role);
            }

            if ($request->role === 'company') {
                Companies::create([
                    'name' => $request->company_name,
                    'phone_number' => $request->company_phone_number,
                    'location' => $request->company_location,
                    'description' => $request->company_description,
                    'industries_id' => $request->industries_id,
                    'user_id' => $user->id,
                ]);
            } elseif ($request->role === 'user') {
                // ✅ Create candidate dengan point default 100
                $candidate = Candidates::create([
                    'name' => $request->name,
                    'phone_number' => $request->phone_number,
                    'gender' => $request->gender,
                    'description' => $request->description,
                    'birth_date' => $request->birth_date,
                    'level_mandarin' => $request->level_mandarin ?: null,
                    'level_english' => $request->level_english ?: null,
                    'min_height' => $request->min_height ?: null,
                    'min_weight' => $request->min_weight ?: null,
                    'min_salary' => $request->min_salary ?: null,
                    'point' => 100,
                    'user_id' => $user->id,
                ]);

                // ✅ Create history point untuk registrasi (FIXED)
                HistoryPoint::recordRegistration($candidate->id, 100);

                Log::info('Candidate registered with initial points', [
                    'candidate_id' => $candidate->id,
                    'user_id' => $user->id,
                    'initial_point' => 100
                ]);

                // Sync optional relationships
                if ($request->filled('preferred_days')) {
                    $candidate->days()->sync($request->preferred_days);
                }
                if ($request->filled('preferred_cities')) {
                    $candidate->preferredCities()->sync($request->preferred_cities);
                }
                if ($request->filled('preferred_skills')) {
                    $candidate->skills()->sync($request->preferred_skills);
                }
                if ($request->filled('preferred_jobs')) {
                    $candidate->preferredTypeJobs()->sync($request->preferred_jobs);
                }
                if ($request->filled('preferred_industries')) {
                    $candidate->preferredIndustries()->sync($request->preferred_industries);
                }

                // Handle portfolio uploads
                if ($request->hasFile('portfolios')) {
                    foreach ($request->file('portfolios') as $file) {
                        $path = $file->store('portfolios', 'public');
                        Portofolios::create([
                            'file' => $path,
                            'caption' => null,
                            'candidates_id' => $candidate->id,
                        ]);
                    }
                }
            }

            DB::commit();

            Auth::login($user);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Registration successful! Welcome ' . $user->name . '! 🎉',
                    'redirect' => route('index.home')
                ], 200);
            }

            session()->flash('success', 'Registration successful! Welcome ' . $user->name . '! 🎉');

            return redirect()->route('index.home');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Registration error: ' . $e->getMessage(), [
                'request' => $request->except(['password', 'password_confirmation']),
                'exception' => $e,
            ]);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'There was an error during registration. Please try again.',
                    'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
                ], 500);
            }

            return back()->withErrors(['error' => 'There was an error during registration. Please try again.'])->withInput();
        }
    }

    public function checkEmail(Request $request)
    {
        $exists = User::where('email', $request->email)->exists();

        return response()->json([
            'available' => !$exists
        ]);
    }
}
