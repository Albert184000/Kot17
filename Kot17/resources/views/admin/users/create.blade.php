@extends('layouts.admin')

@section('title', 'បន្ថែមអ្នកប្រើប្រាស់ថ្មី - កុដិ១៧')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@400;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
    body { font-family: 'Kantumruy Pro', sans-serif; background-color: #f8f9fa; }
    .bg-orange-gradient { background: linear-gradient(135deg, #ff6b00 0%, #e65100 100%); }
    .text-orange { color: #ff6b00; }
    .icon-box { width: 70px; height: 70px; background: #fff; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; }
    .btn-orange { background-color: #ff6b00; border: none; color: #fff; transition: 0.3s; }
    .btn-orange:hover { background-color: #e65100; transform: translateY(-2px); box-shadow: 0 8px 20px rgba(255,107,0,.3); }
    .custom-input-group .form-control, .custom-input-group .form-select, .custom-input-group .input-group-text { padding-top: 14px; padding-bottom: 14px; border-color: #eee; }
    .input-group:focus-within .input-group-text { border-color: #ff6b00; color: #ff6b00; background-color: #fff; }

    /* Avatar Styles */
    .avatar-wrap { display:flex; gap:16px; align-items:center; }
    .avatar-preview { width: 96px; height: 96px; border-radius: 50%; overflow: hidden; border: 2px solid #fff; box-shadow: 0 10px 25px rgba(0,0,0,.08); background: #fff; display:flex; align-items:center; justify-content:center; position: relative; }
    .avatar-preview img { width:100%; height:100%; object-fit: cover; display:none; }
    .avatar-fallback { color:#9aa0a6; font-size: 34px; }
    .avatar-badge { position:absolute; bottom: -6px; right: -6px; width: 36px; height: 36px; border-radius: 50%; border: 2px solid #fff; background: #ff6b00; color:#fff; display:flex; align-items:center; justify-content:center; }
    .dropzone { flex:1; border: 1.5px dashed #e7e7e7; border-radius: 16px; background: #fff; padding: 14px 16px; transition: .15s ease; }
    .dropzone.dragover { border-color:#ff6b00; background: rgba(255,107,0,0.05); }
    .file-hidden { position:absolute; left:-9999px; }
    .btn-soft { background: #fff; border: 1px solid #eee; border-radius: 12px; padding: 8px 12px; font-size: 14px; }
</style>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-10">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-header bg-orange-gradient py-5 border-0 text-center">
                    <div class="icon-box mb-3 shadow-sm">
                        <i class="bi bi-person-plus-fill text-orange fs-1"></i>
                    </div>
                    <h3 class="fw-bold text-white mb-1">ចុះឈ្មោះអ្នកប្រើប្រាស់ថ្មី</h3>
                    <p class="text-white-50 mb-0">បំពេញព័ត៌មានខាងក្រោម</p>
                </div>

                <div class="card-body p-4 p-md-5">
                    @if ($errors->any())
                        <div class="alert alert-danger rounded-4">
                            <div class="fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-1"></i> មានបញ្ហា</div>
                            <ul class="mb-0 ps-4">
                                @foreach ($errors->all() as $error)
                                    <li class="small">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-4">

                            {{-- Avatar --}}
                            <div class="col-md-12">
                                <label class="form-label fw-bold">រូប Profile (Avatar)</label>
                                <div class="avatar-wrap">
                                    <div class="avatar-preview" id="avatarPreview">
                                        <i class="bi bi-person avatar-fallback" id="avatarFallback"></i>
                                        <img id="avatarImg" alt="Preview">
                                        <div class="avatar-badge"><i class="bi bi-camera-fill"></i></div>
                                    </div>
                                    <div class="dropzone" id="dropzone">
                                        <div class="d-flex flex-wrap align-items-center gap-2">
                                            <button type="button" class="btn-soft" id="btnChoose"><i class="bi bi-upload"></i> ជ្រើសរើសរូប</button>
                                            <button type="button" class="btn-soft" id="btnRemove" disabled><i class="bi bi-trash3"></i> លុប</button>
                                            <span class="text-muted small">Max 2MB (JPG, PNG, WebP)</span>
                                        </div>
                                        <input id="avatarInput" class="file-hidden" type="file" name="avatar" accept="image/*">
                                    </div>
                                </div>
                            </div>

                            {{-- Name --}}
                            <div class="col-md-6">
                                <label class="form-label fw-bold">ឈ្មោះ *</label>
                                <div class="input-group custom-input-group">
                                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                                </div>
                            </div>

                            {{-- Phone (make optional if you want) --}}
                            <div class="col-md-6">
                                <label class="form-label fw-bold">លេខទូរស័ព្ទ</label>
                                <div class="input-group custom-input-group">
                                    <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                                </div>
                            </div>

                            {{-- Email --}}
                            <div class="col-md-6">
                                <label class="form-label fw-bold">អ៊ីមែល *</label>
                                <div class="input-group custom-input-group">
                                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                                </div>
                            </div>

                            {{-- Role --}}
                            <div class="col-md-6">
                                <label class="form-label fw-bold">តួនាទី (សិទ្ធិក្នុងប្រព័ន្ធ) *</label>
                                <div class="input-group custom-input-group">
                                    <span class="input-group-text"><i class="bi bi-shield-lock"></i></span>
                                    <select name="role" class="form-select" required>
                                        <option value="member" {{ old('role','member') === 'member' ? 'selected' : '' }}>Member</option>
                                        <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                                        <option value="treasurer" {{ old('role') === 'treasurer' ? 'selected' : '' }}>Treasurer</option>
                                        <option value="collector" {{ old('role') === 'collector' ? 'selected' : '' }}>Collector</option>
                                        <option value="utility" {{ old('role') === 'utility' ? 'selected' : '' }}>Utility (ទឹក / ភ្លើង)</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Person Type --}}
                            <div class="col-md-6">
                                <label class="form-label fw-bold">ប្រភេទបុគ្គល *</label>
                                <div class="input-group custom-input-group">
                                    <span class="input-group-text"><i class="bi bi-people"></i></span>
                                    <select name="person_type" class="form-select" id="personType" required>
                                        <option value="lay" {{ old('person_type','lay')==='lay'?'selected':'' }}>គ្រហស្ថ / កូនសិស្ស</option>
                                        <option value="monk" {{ old('person_type')==='monk'?'selected':'' }}>ព្រះសង្ឃ</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Monk Rank (FIXED VALUES) --}}
                            <div class="col-md-6 monk-only d-none">
                                <label class="form-label fw-bold">លំដាប់ព្រះសង្ឃ *</label>
                                <div class="input-group custom-input-group">
                                    <span class="input-group-text"><i class="bi bi-award"></i></span>
                                    <select name="monk_rank" class="form-select @error('monk_rank') is-invalid @enderror" id="monkRank">
                                        <option value="">-- ជ្រើសរើសលំដាប់ --</option>
                                        <option value="maha_thera" {{ old('monk_rank')==='maha_thera'?'selected':'' }}>ព្រះមហាថេរ</option>
                                        <option value="bhikkhu" {{ old('monk_rank')==='bhikkhu'?'selected':'' }}>ព្រះភិក្ខុ</option>
                                        <option value="samanera" {{ old('monk_rank')==='samanera'?'selected':'' }}>សាមណេរ</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Vassa --}}
                            <div class="col-md-6 monk-only d-none">
                                <label class="form-label fw-bold">ចំនួនវស្សា *</label>
                                <div class="input-group custom-input-group">
                                    <span class="input-group-text"><i class="bi bi-calendar-check"></i></span>
                                    <input type="number" name="vassa" class="form-control @error('vassa') is-invalid @enderror"
                                           placeholder="0" value="{{ old('vassa') }}" min="0">
                                </div>
                            </div>

                            {{-- Password --}}
                            <div class="col-md-6">
                                <label class="form-label fw-bold">លេខសម្ងាត់ *</label>
                                <div class="input-group custom-input-group">
                                    <span class="input-group-text"><i class="bi bi-key"></i></span>
                                    <input type="password" name="password" class="form-control" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">បញ្ជាក់លេខសម្ងាត់ *</label>
                                <div class="input-group custom-input-group">
                                    <span class="input-group-text"><i class="bi bi-key-fill"></i></span>
                                    <input type="password" name="password_confirmation" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <hr class="my-5">
                        <div class="text-center">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-light px-4">បោះបង់</a>
                            <button type="submit" class="btn btn-orange px-5 ms-2"><i class="bi bi-check-circle-fill"></i> រក្សាទុក</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
(() => {
    // Toggle monk fields
    const personType = document.getElementById('personType');
    const monkOnlyFields = document.querySelectorAll('.monk-only');

    function toggleMonkFields() {
        const isMonk = personType.value === 'monk';
        monkOnlyFields.forEach(el => {
            if (isMonk) el.classList.remove('d-none');
            else {
                el.classList.add('d-none');
                const input = el.querySelector('input, select');
                if (input) input.value = '';
            }
        });
    }
    personType.addEventListener('change', toggleMonkFields);
    window.addEventListener('DOMContentLoaded', toggleMonkFields);

    // Avatar preview
    const input = document.getElementById('avatarInput');
    const btnChoose = document.getElementById('btnChoose');
    const btnRemove = document.getElementById('btnRemove');
    const img = document.getElementById('avatarImg');
    const fallback = document.getElementById('avatarFallback');

    btnChoose.onclick = () => input.click();
    input.onchange = (e) => {
        const file = e.target.files[0];
        if (file) {
            img.src = URL.createObjectURL(file);
            img.style.display = 'block';
            fallback.style.display = 'none';
            btnRemove.disabled = false;
        }
    };
    btnRemove.onclick = () => {
        input.value = '';
        img.style.display = 'none';
        fallback.style.display = 'block';
        btnRemove.disabled = true;
    };
})();
</script>
@endsection
