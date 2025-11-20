@extends('layouts.main')

@section('content')
    <div class="auth-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card shadow">
                        <div class="card-body p-5">
                            <h3 class="card-title text-center mb-4">
                                <i class="bi bi-person-plus"></i> Registrasi
                            </h3>

                            {{-- Alert Error --}}
                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show">
                                    <strong>Terjadi kesalahan:</strong>
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <form action="{{ route('register') }}" method="POST" id="registerForm">
                                @csrf

                                {{-- Pilih Role --}}
                                <div class="mb-4">
                                    <label class="form-label fw-bold">Daftar Sebagai:</label>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="role"
                                                    id="roleCandidate" value="candidate"
                                                    {{ old('role') == 'candidate' ? 'checked' : '' }} required>
                                                <label class="form-check-label" for="roleCandidate">
                                                    <i class="bi bi-person"></i> Kandidat (Pencari Kerja)
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="role"
                                                    id="roleCompany" value="company"
                                                    {{ old('role') == 'company' ? 'checked' : '' }} required>
                                                <label class="form-check-label" for="roleCompany">
                                                    <i class="bi bi-building"></i> Perusahaan
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <hr class="my-4">

                                {{-- Data Akun (Semua Role) --}}
                                <h5 class="mb-3">Data Akun</h5>
                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            id="email" name="email" value="{{ old('email') }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6"></div>
                                    <div class="col-md-6">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                                            id="password" name="password" required>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                                        <input type="password" class="form-control" id="password_confirmation"
                                            name="password_confirmation" required>
                                    </div>
                                </div>

                                {{-- Form Candidate --}}
                                <div id="candidateFields" style="display: none;">
                                    <h5 class="mb-3">Data Kandidat</h5>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="name" class="form-label">Nama Lengkap</label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                id="name" name="name" value="{{ old('name') }}">
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="gender" class="form-label">Jenis Kelamin</label>
                                            <select class="form-select @error('gender') is-invalid @enderror" id="gender"
                                                name="gender">
                                                <option value="">Pilih...</option>
                                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>
                                                    Laki-laki</option>
                                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>
                                                    Perempuan</option>
                                            </select>
                                            @error('gender')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="phone_number" class="form-label">No. Telepon</label>
                                            <input type="text"
                                                class="form-control @error('phone_number') is-invalid @enderror"
                                                id="phone_number" name="phone_number" value="{{ old('phone_number') }}">
                                            @error('phone_number')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="birth_date" class="form-label">Tanggal Lahir</label>
                                            <input type="date"
                                                class="form-control @error('birth_date') is-invalid @enderror"
                                                id="birth_date" name="birth_date" value="{{ old('birth_date') }}">
                                            @error('birth_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                {{-- Form Company --}}
                                <div id="companyFields" style="display: none;">
                                    <h5 class="mb-3">Data Perusahaan</h5>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="company_name" class="form-label">Nama Perusahaan</label>
                                            <input type="text"
                                                class="form-control @error('company_name') is-invalid @enderror"
                                                id="company_name" name="company_name" value="{{ old('company_name') }}">
                                            @error('company_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="company_phone" class="form-label">No. Telepon Perusahaan</label>
                                            <input type="text"
                                                class="form-control @error('company_phone') is-invalid @enderror"
                                                id="company_phone" name="company_phone"
                                                value="{{ old('company_phone') }}">
                                            @error('company_phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="industries_id" class="form-label">Industri</label>
                                            <select class="form-select @error('industries_id') is-invalid @enderror"
                                                id="industries_id" name="industries_id">
                                                <option value="">Pilih Industri...</option>
                                                @foreach ($industries as $industry)
                                                    <option value="{{ $industry->id }}"
                                                        {{ old('industries_id') == $industry->id ? 'selected' : '' }}>
                                                        {{ $industry->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('industries_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="location" class="form-label">Lokasi (Opsional)</label>
                                            <input type="text" class="form-control" id="location" name="location"
                                                value="{{ old('location') }}">
                                        </div>
                                        <div class="col-12">
                                            <label for="description" class="form-label">Deskripsi Perusahaan
                                                (Opsional)</label>
                                            <textarea class="form-control" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                {{-- Submit Button --}}
                                <div class="d-grid mt-4">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="bi bi-person-plus"></i> Daftar
                                    </button>
                                </div>
                            </form>

                            <hr class="my-4">

                            <p class="text-center mb-0">
                                Sudah punya akun?
                                <a href="{{ route('login') }}" class="text-decoration-none">Login di sini</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- JavaScript untuk toggle form --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const roleCandidate = document.getElementById('roleCandidate');
            const roleCompany = document.getElementById('roleCompany');
            const candidateFields = document.getElementById('candidateFields');
            const companyFields = document.getElementById('companyFields');

            function toggleFields() {
                if (roleCandidate.checked) {
                    candidateFields.style.display = 'block';
                    companyFields.style.display = 'none';

                    // Set required untuk candidate fields
                    document.getElementById('name').required = true;
                    document.getElementById('gender').required = true;
                    document.getElementById('phone_number').required = true;
                    document.getElementById('birth_date').required = true;

                    // Remove required dari company fields
                    document.getElementById('company_name').required = false;
                    document.getElementById('company_phone').required = false;
                    document.getElementById('industries_id').required = false;
                } else if (roleCompany.checked) {
                    candidateFields.style.display = 'none';
                    companyFields.style.display = 'block';

                    // Remove required dari candidate fields
                    document.getElementById('name').required = false;
                    document.getElementById('gender').required = false;
                    document.getElementById('phone_number').required = false;
                    document.getElementById('birth_date').required = false;

                    // Set required untuk company fields
                    document.getElementById('company_name').required = true;
                    document.getElementById('company_phone').required = true;
                    document.getElementById('industries_id').required = true;
                }
            }

            roleCandidate.addEventListener('change', toggleFields);
            roleCompany.addEventListener('change', toggleFields);

            // Check pada page load (untuk old input)
            if (roleCandidate.checked || roleCompany.checked) {
                toggleFields();
            }
        });
    </script>
@endsection
