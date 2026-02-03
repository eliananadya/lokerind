@extends('layouts.main')

@section('title', 'Rekomendasi Kandidat')

@section('content')
	<style>
		:root {
			--primary-blue: #14489b;
			--secondary-blue: #244770;
			--dark-blue: #1e3992;
			--light-blue: #dbeafe;
			--bg-blue: #eff6ff;
		}

		.filter-section {
			background: white;
			border-radius: 8px;
			padding: 1.5rem;
			margin-bottom: 1.5rem;
			box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
		}

		.candidate-card {
			background: white;
			border-radius: 12px;
			padding: 1.5rem;
			margin-bottom: 1.5rem;
			box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
			transition: all 0.3s;
			cursor: pointer;
			border: 2px solid transparent;
		}

		.candidate-card:hover {
			transform: translateY(-5px);
			box-shadow: 0 8px 16px rgba(20, 72, 155, 0.15);
			border-color: var(--primary-blue);
		}

		.avatar-large {
			width: 80px;
			height: 80px;
			border-radius: 50%;
			background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
			color: white;
			display: flex;
			align-items: center;
			justify-content: center;
			font-size: 2rem;
			font-weight: 600;
			flex-shrink: 0;
		}

		.skill-badge {
			display: inline-block;
			padding: 0.35rem 0.75rem;
			margin: 0.25rem;
			background: var(--light-blue);
			color: var(--primary-blue);
			border-radius: 20px;
			font-size: 0.875rem;
			font-weight: 500;
		}

		.match-percentage {
			width: 80px;
			height: 80px;
			border-radius: 50%;
			background: linear-gradient(135deg, #10b981, #059669);
			color: white;
			display: flex;
			flex-direction: column;
			align-items: center;
			justify-content: center;
			font-weight: 600;
			box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
		}

		.match-percentage-value {
			font-size: 1.5rem;
			line-height: 1;
		}

		.match-percentage-label {
			font-size: 0.65rem;
			opacity: 0.9;
		}

		.btn-primary-custom {
			background-color: var(--primary-blue);
			border-color: var(--primary-blue);
			color: white;
			transition: all 0.3s;
		}

		.btn-primary-custom:hover {
			background-color: var(--dark-blue);
			border-color: var(--dark-blue);
			transform: translateY(-2px);
			box-shadow: 0 4px 8px rgba(20, 72, 155, 0.3);
		}

		.modal-xl {
			max-width: 1200px;
		}

		.info-item {
			padding: 0.75rem 0;
			border-bottom: 1px solid #f3f4f6;
		}

		.info-item:last-child {
			border-bottom: none;
		}

		.info-label {
			font-weight: 600;
			color: #6b7280;
			min-width: 150px;
			display: inline-block;
		}

		.portfolio-item {
			border: 1px solid #e5e7eb;
			border-radius: 8px;
			padding: 1rem;
			margin-bottom: 1rem;
		}

		.skeleton {
			animation: skeleton-loading 1s linear infinite alternate;
		}

		@keyframes skeleton-loading {
			0% {
				background-color: hsl(200, 20%, 80%);
			}

			100% {
				background-color: hsl(200, 20%, 95%);
			}
		}

		.empty-state {
			text-align: center;
			padding: 4rem 2rem;
		}

		.empty-state i {
			font-size: 5rem;
			color: #d1d5db;
		}

		#candidatesList {
			min-height: 400px;
		}

		.filter-chip {
			display: inline-block;
			padding: 0.4rem 1rem;
			margin: 0.25rem;
			background: var(--primary-blue);
			color: white;
			border-radius: 20px;
			font-size: 0.875rem;
		}

		.experience-badge {
			background: #fef3c7;
			color: #92400e;
			padding: 0.25rem 0.75rem;
			border-radius: 12px;
			font-size: 0.875rem;
			font-weight: 500;
		}
	</style>

	<div class="container-fluid py-4">
		<!-- Header -->
		<div class="row mb-4">
			<div class="col-12">
				<div class="d-flex justify-content-between align-items-center flex-wrap">
					<div class="mb-md-0 mb-3">
						<h3 class="fw-bold mb-1">Rekomendasi Kandidat</h3>
						<p class="text-muted mb-0">Temukan kandidat terbaik untuk lowongan Anda</p>
					</div>
					<a href="{{ route('company.dashboard') }}" class="btn btn-outline-secondary">
						<i class="bi bi-arrow-left me-2"></i>Kembali ke Dashboard
					</a>
				</div>
			</div>
		</div>

		<!-- Filter Section -->
		<div class="filter-section">
			<h5 class="mb-3"><i class="bi bi-funnel me-2"></i>Filter Pencarian</h5>
			<div class="row">
				<div class="col-md-4 mb-3">
					<label for="jobPostingFilter" class="form-label fw-semibold">Lowongan <span class="text-danger">*</span></label>
					<select class="form-select" id="jobPostingFilter">
						<option value="">Pilih Lowongan</option>
						@foreach ($jobPostings as $job)
							<option value="{{ $job->id }}">{{ $job->title }}</option>
						@endforeach
					</select>
					<small class="text-muted">Pilih lowongan untuk melihat kandidat yang cocok</small>
				</div>

				<div class="col-md-4 mb-3">
					<label for="searchInput" class="form-label fw-semibold">Cari Kandidat</label>
					<input type="text" class="form-control" id="searchInput" placeholder="Nama atau email kandidat...">
				</div>

				<div class="col-md-4 mb-3">
					<label for="cityFilter" class="form-label fw-semibold">Lokasi</label>
					<select class="form-select" id="cityFilter">
						<option value="">Semua Lokasi</option>
						@foreach ($cities as $city)
							<option value="{{ $city->id }}">{{ $city->name }}</option>
						@endforeach
					</select>
				</div>

				<div class="col-md-4 mb-3">
					<label for="genderFilter" class="form-label fw-semibold">Jenis Kelamin</label>
					<select class="form-select" id="genderFilter">
						<option value="">Semua</option>
						<option value="Male">Laki-laki</option>
						<option value="Female">Perempuan</option>
					</select>
				</div>

				<div class="col-md-8 mb-3">
					<label class="form-label fw-semibold">Filter Keterampilan</label>
					<div class="rounded border p-3" style="max-height: 200px; overflow-y: auto;">
						<div class="row">
							@foreach ($skills as $skill)
								<div class="col-md-4 col-sm-6">
									<div class="form-check">
										<input class="form-check-input skill-filter" type="checkbox" value="{{ $skill->id }}" id="skill_{{ $skill->id }}">
										<label class="form-check-label" for="skill_{{ $skill->id }}">
											{{ $skill->name }}
										</label>
									</div>
								</div>
							@endforeach
						</div>
					</div>
				</div>
			</div>

			<div class="d-flex justify-content-end mt-3 gap-2">
				<button type="button" class="btn btn-outline-secondary" id="resetFiltersBtn">
					<i class="bi bi-arrow-counterclockwise me-2"></i>Reset Filter
				</button>
				<button type="button" class="btn btn-primary-custom" id="applyFiltersBtn">
					<i class="bi bi-search me-2"></i>Cari Kandidat
				</button>
			</div>
		</div>

		<!-- Results Count -->
		<div class="mb-3">
			<h5 id="resultsCount" class="text-muted">Pilih lowongan untuk melihat rekomendasi kandidat</h5>
		</div>

		<!-- Candidates List -->
		<div class="row" id="candidatesList">
			<div class="col-12">
				<div class="empty-state">
					<i class="bi bi-people"></i>
					<h5 class="text-muted mt-3">Belum ada filter yang diterapkan</h5>
					<p class="text-muted">Pilih lowongan dan klik "Cari Kandidat" untuk melihat rekomendasi</p>
				</div>
			</div>
		</div>

		<!-- Pagination -->
		<div id="paginationContainer"></div>
	</div>

	<!-- Candidate Detail Modal -->
	<div class="modal fade" id="candidateDetailModal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-xl modal-dialog-scrollable">
			<div class="modal-content">
				<div class="modal-header" style="background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue)); color: white;">
					<h5 class="modal-title"><i class="bi bi-person-badge me-2"></i>Detail Kandidat</h5>
					<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
				</div>
				<div class="modal-body" id="candidateDetailContent">
					<div class="py-5 text-center">
						<div class="spinner-border text-primary" role="status">
							<span class="visually-hidden">Loading...</span>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
						<i class="bi bi-x-lg me-2"></i>Tutup
					</button>
					<button type="button" class="btn btn-primary-custom" id="sendInvitationBtn">
						<i class="bi bi-send me-2"></i>Kirim Undangan
					</button>
				</div>
			</div>
		</div>
	</div>

	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

	<script>
		$(document).ready(function() {
			let currentCandidateId = null;
			let currentJobPostingId = null;

			// Job Posting Change - Auto load and check skills
			$('#jobPostingFilter').on('change', function() {
				const jobPostingId = $(this).val();

				if (jobPostingId) {
					// Load job skills and auto-check them
					loadJobSkills(jobPostingId);
				} else {
					// Uncheck all skills if no job selected
					$('.skill-filter').prop('checked', false);
				}
			});

			// Load Job Skills and Auto-check
			function loadJobSkills(jobId) {
				$.ajax({
					url: `{{ url('dashboard/candidates/job-skills') }}/${jobId}`,
					method: 'GET',
					success: function(response) {
						if (response.success) {
							// Uncheck all first
							$('.skill-filter').prop('checked', false);

							// Check skills that match the job
							response.data.skills.forEach(skillId => {
								$(`#skill_${skillId}`).prop('checked', true);
							});

							// Show notification
							const skillCount = response.data.skills.length;
							if (skillCount > 0) {
								Swal.fire({
									icon: 'info',
									title: 'Filter Diperbarui',
									html: `<strong>${skillCount} keterampilan</strong> dari lowongan <strong>"${response.data.job_title}"</strong> telah dipilih secara otomatis.`,
									timer: 3000,
									showConfirmButton: false,
									toast: true,
									position: 'top-end'
								});
							}
						}
					},
					error: function(xhr) {
						console.error('Error loading job skills:', xhr);
					}
				});
			}

			// Apply Filters
			$('#applyFiltersBtn').on('click', function() {
				const jobPostingId = $('#jobPostingFilter').val();

				if (!jobPostingId) {
					Swal.fire({
						icon: 'warning',
						title: 'Pilih Lowongan',
						text: 'Silakan pilih lowongan terlebih dahulu',
						confirmButtonColor: '#14489b'
					});
					return;
				}

				currentJobPostingId = jobPostingId;
				loadCandidates();
			});

			// Reset Filters
			$('#resetFiltersBtn').on('click', function() {
				$('#jobPostingFilter').val('');
				$('#searchInput').val('');
				$('#cityFilter').val('');
				$('#genderFilter').val('');
				$('.skill-filter').prop('checked', false);
				$('#candidatesList').html(`
                    <div class="col-12">
                        <div class="empty-state">
                            <i class="bi bi-people"></i>
                            <h5 class="mt-3 text-muted">Belum ada filter yang diterapkan</h5>
                            <p class="text-muted">Pilih lowongan dan klik "Cari Kandidat" untuk melihat rekomendasi</p>
                        </div>
                    </div>
                `);
				$('#resultsCount').text('Pilih lowongan untuk melihat rekomendasi kandidat');
			});

			// Load Candidates
			function loadCandidates(page = 1) {
				const selectedSkills = $('.skill-filter:checked').map(function() {
					return $(this).val();
				}).get();

				const filters = {
					job_posting_id: currentJobPostingId,
					search: $('#searchInput').val(),
					city_id: $('#cityFilter').val(),
					gender: $('#genderFilter').val(),
					skills: selectedSkills,
					page: page
				};

				// Show loading
				$('#candidatesList').html(`
                    <div class="col-12 text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-3 text-muted">Mencari kandidat yang cocok...</p>
                    </div>
                `);

				$.ajax({
					url: '{{ route('company.candidates.get') }}',
					method: 'GET',
					data: filters,
					success: function(response) {
						if (response.success) {
							renderCandidates(response.data);
						}
					},
					error: function(xhr) {
						Swal.fire({
							icon: 'error',
							title: 'Error',
							text: 'Gagal memuat kandidat',
							confirmButtonColor: '#14489b'
						});
					}
				});
			}

			// Render Candidates
			function renderCandidates(data) {
				let html = '';

				if (data.data.length === 0) {
					html = `
                        <div class="col-12">
                            <div class="empty-state">
                                <i class="bi bi-search"></i>
                                <h5 class="mt-3 text-muted">Tidak ada kandidat ditemukan</h5>
                                <p class="text-muted">Coba ubah kriteria pencarian Anda</p>
                            </div>
                        </div>
                    `;
				} else {
					data.data.forEach(candidate => {
						const user = candidate.user;
						const initials = user ? getInitials(user.name) : '??';
						const skills = candidate.skills.slice(0, 5);
						const matchPercentage = candidate.skill_match_percentage || 0;
						const matchingSkillsCount = candidate.matching_skills_count || 0;

						html += `
                            <div class="col-lg-6 col-xl-4">
                                <div class="candidate-card" data-id="${candidate.id}">
                                    <div class="d-flex align-items-start mb-3">
                                        <div class="avatar-large me-3">${initials}</div>
                                        <div class="flex-grow-1">
                                            <h5 class="mb-1">${user ? user.name : 'Unknown'}</h5>
                                            <p class="text-muted mb-1"><i class="bi bi-envelope me-1"></i>${user ? user.email : '-'}</p>
                                            <p class="text-muted mb-0"><i class="bi bi-telephone me-1"></i>${candidate.phone_number || '-'}</p>
                                        </div>
                                        ${matchPercentage > 0 ? `
		                                            <div class="match-percentage">
		                                                <div class="match-percentage-value">${matchPercentage}%</div>
		                                                <div class="match-percentage-label">Match</div>
		                                            </div>
		                                        ` : ''}
                                    </div>

                                    ${candidate.description ? `
		                                        <p class="text-muted small mb-3">${candidate.description.substring(0, 100)}${candidate.description.length > 100 ? '...' : ''}</p>
		                                    ` : ''}

                                    <div class="mb-3">
                                        <small class="text-muted fw-semibold d-block mb-2">
                                            <i class="bi bi-tools me-1"></i>Keterampilan ${matchingSkillsCount > 0 ? `(${matchingSkillsCount} cocok)` : ''}:
                                        </small>
                                        <div>
                                            ${skills.map(skill => `<span class="skill-badge">${skill.name}</span>`).join('')}
                                            ${candidate.skills.length > 5 ? `<span class="skill-badge">+${candidate.skills.length - 5} lainnya</span>` : ''}
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            ${candidate.point ? `<span class="experience-badge"><i class="bi bi-star-fill me-1"></i>${candidate.point} Poin</span>` : ''}
                                        </div>
                                        <button class="btn btn-sm btn-primary-custom view-detail-btn" data-id="${candidate.id}">
                                            <i class="bi bi-eye me-1"></i>Lihat Detail
                                        </button>
                                    </div>
                                </div>
                            </div>
                        `;
					});
				}

				$('#candidatesList').html(html);
				$('#resultsCount').html(`Ditemukan <strong>${data.total}</strong> kandidat yang sesuai`);

				// Render pagination
				renderPagination(data);
			}

			// Render Pagination
			function renderPagination(data) {
				if (data.last_page <= 1) {
					$('#paginationContainer').empty();
					return;
				}

				let html = '<nav class="mt-4"><ul class="pagination justify-content-center">';

				// Previous
				if (data.current_page > 1) {
					html +=
						`<li class="page-item"><a class="page-link" href="#" data-page="${data.current_page - 1}">&laquo;</a></li>`;
				} else {
					html += '<li class="page-item disabled"><span class="page-link">&laquo;</span></li>';
				}

				// Pages
				for (let i = 1; i <= data.last_page; i++) {
					if (i === data.current_page) {
						html += `<li class="page-item active"><span class="page-link">${i}</span></li>`;
					} else if (i === 1 || i === data.last_page || (i >= data.current_page - 2 && i <= data
							.current_page + 2)) {
						html +=
							`<li class="page-item"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
					} else if (i === data.current_page - 3 || i === data.current_page + 3) {
						html += '<li class="page-item disabled"><span class="page-link">...</span></li>';
					}
				}

				// Next
				if (data.current_page < data.last_page) {
					html +=
						`<li class="page-item"><a class="page-link" href="#" data-page="${data.current_page + 1}">&raquo;</a></li>`;
				} else {
					html += '<li class="page-item disabled"><span class="page-link">&raquo;</span></li>';
				}

				html += '</ul></nav>';
				$('#paginationContainer').html(html);

				// Pagination click
				$('#paginationContainer').find('a.page-link').on('click', function(e) {
					e.preventDefault();
					const page = $(this).data('page');
					loadCandidates(page);
					$('html, body').animate({
						scrollTop: 0
					}, 'smooth');
				});
			}

			// View Candidate Detail
			$(document).on('click', '.candidate-card, .view-detail-btn', function(e) {
				e.stopPropagation();
				const candidateId = $(this).data('id') || $(this).closest('.candidate-card').data('id');
				showCandidateDetail(candidateId);
			});

			// Show Candidate Detail
			function showCandidateDetail(candidateId) {
				currentCandidateId = candidateId;
				$('#candidateDetailModal').modal('show');

				$('#candidateDetailContent').html(`
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                `);

				$.ajax({
					url: `{{ url('dashboard/candidates') }}/${candidateId}/detail`,
					method: 'GET',
					success: function(response) {
						if (response.success) {
							renderCandidateDetail(response.data);
						}
					},
					error: function(xhr) {
						$('#candidateDetailContent').html(`
                            <div class="text-center py-5">
                                <i class="bi bi-exclamation-triangle" style="font-size: 3rem; color: #ef4444;"></i>
                                <h5 class="mt-3 text-danger">Gagal memuat detail kandidat</h5>
                            </div>
                        `);
					}
				});
			}

			// Render Candidate Detail
			function renderCandidateDetail(candidate) {
				const user = candidate.user;
				const initials = user ? getInitials(user.name) : '??';

				let html = `
                    <div class="row">
                        <div class="col-md-4">
                            <div class="text-center mb-4">
                                <div class="avatar-large mx-auto mb-3" style="width: 120px; height: 120px; font-size: 3rem;">
                                    ${initials}
                                </div>
                                <h4>${user ? user.name : 'Unknown'}</h4>
                                <p class="text-muted">${user ? user.email : '-'}</p>
                            </div>

                            <div class="card">
                                <div class="card-body">
                                    <h6 class="fw-bold mb-3">Informasi Kontak</h6>
                                    <div class="info-item">
                                        <i class="bi bi-telephone me-2"></i>${candidate.phone_number || '-'}
                                    </div>
                                    <div class="info-item">
                                        <i class="bi bi-gender-ambiguous me-2"></i>${candidate.gender || '-'}
                                    </div>
                                    <div class="info-item">
                                        <i class="bi bi-calendar me-2"></i>${candidate.birth_date ? formatDate(candidate.birth_date) : '-'}
                                    </div>
                                    <div class="info-item">
                                        <i class="bi bi-star-fill me-2"></i>${candidate.point || 0} Poin
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-8">
                            ${candidate.description ? `
		                                <div class="mb-4">
		                                    <h5 class="fw-bold mb-3"><i class="bi bi-file-text me-2"></i>Deskripsi</h5>
		                                    <p class="text-muted">${candidate.description}</p>
		                                </div>
		                            ` : ''}

                            <div class="mb-4">
                                <h5 class="fw-bold mb-3"><i class="bi bi-tools me-2"></i>Keterampilan</h5>
                                <div>
                                    ${candidate.skills.map(skill => `<span class="skill-badge">${skill.name}</span>`).join('') || '<p class="text-muted">Belum ada keterampilan</p>'}
                                </div>
                            </div>

                            <div class="mb-4">
                                <h5 class="fw-bold mb-3"><i class="bi bi-briefcase me-2"></i>Preferensi Pekerjaan</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <span class="info-label">Tipe Pekerjaan:</span>
                                            <div class="mt-2">
                                                ${candidate.preferred_type_jobs && candidate.preferred_type_jobs.length > 0 ? 
                                                    candidate.preferred_type_jobs.map(type => `<span class="badge bg-secondary me-1">${type.name}</span>`).join('') : 
                                                    '<span class="text-muted">-</span>'}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <span class="info-label">Industri:</span>
                                            <div class="mt-2">
                                                ${candidate.preferred_industries && candidate.preferred_industries.length > 0 ? 
                                                    candidate.preferred_industries.map(ind => `<span class="badge bg-secondary me-1">${ind.name}</span>`).join('') : 
                                                    '<span class="text-muted">-</span>'}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <span class="info-label">Lokasi Preferensi:</span>
                                            <div class="mt-2">
                                                ${candidate.preffered_cities && candidate.preffered_cities.length > 0 ? 
                                                    candidate.preffered_cities.map(city => `<span class="badge bg-secondary me-1">${city.name}</span>`).join('') : 
                                                    '<span class="text-muted">-</span>'}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <span class="info-label">Gaji Minimum:</span>
                                            <span>${candidate.min_salary ? 'Rp ' + formatNumber(candidate.min_salary) : '-'}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <h5 class="fw-bold mb-3"><i class="bi bi-translate me-2"></i>Kemampuan Bahasa</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <span class="info-label">Bahasa Inggris:</span>
                                            <span class="badge bg-info">${candidate.level_english || 'None'}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <span class="info-label">Bahasa Mandarin:</span>
                                            <span class="badge bg-info">${candidate.level_mandarin || 'None'}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            ${candidate.portofolios && candidate.portofolios.length > 0 ? `
		                                <div class="mb-4">
		                                    <h5 class="fw-bold mb-3"><i class="bi bi-folder me-2"></i>Portofolio</h5>
		                                    ${candidate.portofolios.map(portfolio => `
                                        <div class="portfolio-item">
                                            <h6 class="fw-semibold">${portfolio.title || 'Portofolio'}</h6>
                                            <p class="text-muted small mb-0">${portfolio.description || '-'}</p>
                                        </div>
                                    `).join('')}
		                                </div>
		                            ` : ''}

                            ${candidate.applications && candidate.applications.length > 0 ? `
		                                <div class="mb-4">
		                                    <h5 class="fw-bold mb-3"><i class="bi bi-check-circle me-2"></i>Pengalaman Kerja</h5>
		                                    ${candidate.applications.map(app => `
                                        <div class="portfolio-item">
                                            <h6 class="fw-semibold">${app.job_posting ? app.job_posting.title : '-'}</h6>
                                            <p class="text-muted small mb-0">
                                                <i class="bi bi-building me-1"></i>${app.job_posting && app.job_posting.company ? app.job_posting.company.name : '-'}
                                            </p>
                                        </div>
                                    `).join('')}
		                                </div>
		                            ` : ''}
                        </div>
                    </div>
                `;

				$('#candidateDetailContent').html(html);
			}

			// Send Invitation
			$('#sendInvitationBtn').on('click', function() {
				if (!currentCandidateId || !currentJobPostingId) {
					Swal.fire({
						icon: 'error',
						title: 'Error',
						text: 'Data tidak lengkap',
						confirmButtonColor: '#14489b'
					});
					return;
				}

				Swal.fire({
					title: 'Kirim Undangan?',
					text: 'Kandidat ini akan menerima undangan untuk melamar lowongan yang dipilih',
					icon: 'question',
					input: 'textarea',
					inputLabel: 'Pesan untuk kandidat (opsional)',
					inputPlaceholder: 'Tulis pesan Anda di sini...',
					showCancelButton: true,
					confirmButtonText: '<i class="bi bi-send me-1"></i> Ya, Kirim Undangan',
					cancelButtonText: 'Batal',
					confirmButtonColor: '#14489b',
					cancelButtonColor: '#6c757d',
					reverseButtons: true
				}).then((result) => {
					if (result.isConfirmed) {
						$.ajax({
							url: '{{ route('company.candidates.invite') }}',
							method: 'POST',
							data: {
								_token: '{{ csrf_token() }}',
								candidate_id: currentCandidateId,
								job_posting_id: currentJobPostingId,
								message: result.value
							},
							success: function(response) {
								if (response.success) {
									Swal.fire({
										icon: 'success',
										title: 'Berhasil!',
										text: response.message,
										timer: 2000,
										showConfirmButton: false
									}).then(() => {
										$('#candidateDetailModal').modal('hide');
										loadCandidates();
									});
								}
							},
							error: function(xhr) {
								const response = xhr.responseJSON;
								Swal.fire({
									icon: 'error',
									title: 'Gagal',
									text: response.message || 'Gagal mengirim undangan',
									confirmButtonColor: '#14489b'
								});
							}
						});
					}
				});
			});

			// Helper Functions
			function getInitials(name) {
				if (!name) return '??';
				const parts = name.split(' ');
				if (parts.length >= 2) {
					return (parts[0][0] + parts[1][0]).toUpperCase();
				}
				return name.substring(0, 2).toUpperCase();
			}

			function formatDate(dateString) {
				const date = new Date(dateString);
				return date.toLocaleDateString('id-ID', {
					day: '2-digit',
					month: 'long',
					year: 'numeric'
				});
			}

			function formatNumber(num) {
				return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
			}
		});
	</script>
@endsection
