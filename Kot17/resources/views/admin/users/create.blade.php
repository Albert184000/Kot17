<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <title>បន្ថែមអ្នកប្រើប្រាស់ថ្មី</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Google Font --}}
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@400;600;700&display=swap" rel="stylesheet">

    {{-- Bootstrap + Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Kantumruy Pro', sans-serif;
            background-color: #f8f9fa;
        }
        .bg-orange-gradient {
            background: linear-gradient(135deg, #ff6b00 0%, #e65100 100%);
        }
        .text-orange { color: #ff6b00; }
        .icon-box {
            width: 70px;
            height: 70px;
            background: #fff;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .btn-orange {
            background-color: #ff6b00;
            border: none;
            color: #fff;
        }
        .btn-orange:hover {
            background-color: #e65100;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255,107,0,.3);
        }
        .custom-input-group .form-control,
        .custom-input-group .form-select,
        .custom-input-group .input-group-text {
            padding-top: 14px;
            padding-bottom: 14px;
            border-color: #eee;
        }
        .input-group:focus-within .input-group-text {
            border-color: #ff6b00;
            color: #ff6b00;
            background-color: #fff;
        }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-10">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">

                {{-- Header --}}
                <div class="card-header bg-orange-gradient py-5 border-0 text-center">
                    <div class="icon-box mb-3 shadow-sm">
                        <i class="bi bi-person-plus-fill text-orange fs-1"></i>
                    </div>
                    <h3 class="fw-bold text-white mb-1">ចុះឈ្មោះអ្នកប្រើប្រាស់ថ្មី</h3>
                    <p class="text-white-50 mb-0">បំពេញព័ត៌មានខាងក្រោម</p>
                </div>

                {{-- Body --}}
                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row g-4">

                            {{-- Avatar --}}
                            <div class="col-md-12">
                                <label class="form-label fw-bold">រូប Profile (Avatar)</label>
                                <div class="input-group custom-input-group">
                                    <span class="input-group-text"><i class="bi bi-image"></i></span>
                                    <input type="file" name="avatar" class="form-control" accept="image/*">
                                </div>
                                <small class="text-muted">JPG / PNG / WebP • Max 2MB</small>
                            </div>

                            {{-- Name --}}
                            <div class="col-md-6">
                                <label class="form-label fw-bold">ឈ្មោះ *</label>
                                <div class="input-group custom-input-group">
                                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                                    <input type="text" name="name" class="form-control" required>
                                </div>
                            </div>

                            {{-- Phone --}}
                            <div class="col-md-6">
                                <label class="form-label fw-bold">លេខទូរស័ព្ទ *</label>
                                <div class="input-group custom-input-group">
                                    <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                    <input type="text" name="phone" class="form-control" required>
                                </div>
                            </div>

                            {{-- Email --}}
                            <div class="col-md-6">
                                <label class="form-label fw-bold">អ៊ីមែល *</label>
                                <div class="input-group custom-input-group">
                                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                    <input type="email" name="email" class="form-control" required>
                                </div>
                            </div>

                            {{-- Role --}}
                            <div class="col-md-6">
                                <label class="form-label fw-bold">តួនាទី</label>
                                <div class="input-group custom-input-group">
                                    <span class="input-group-text"><i class="bi bi-shield-lock"></i></span>
                                    <select name="role" class="form-select">
                                        <option value="admin">Admin</option>
                                        <option value="treasurer">Treasurer</option>
                                        <option value="collector">Collector</option>
                                        <option value="member" selected>Member</option>
                                    </select>
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

                            {{-- Confirm --}}
                            <div class="col-md-6">
                                <label class="form-label fw-bold">បញ្ជាក់លេខសម្ងាត់ *</label>
                                <div class="input-group custom-input-group">
                                    <span class="input-group-text"><i class="bi bi-key-fill"></i></span>
                                    <input type="password" name="password_confirmation" class="form-control" required>
                                </div>
                            </div>

                            {{-- Active --}}
                            <div class="col-md-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_active" value="1" checked>
                                    <label class="form-check-label fw-bold">សកម្ម (Active)</label>
                                </div>
                            </div>
                        </div>

                        <hr class="my-5">

                        <div class="text-center">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-light px-4">បោះបង់</a>
                            <button type="submit" class="btn btn-orange px-5 ms-2">
                                <i class="bi bi-check-circle-fill me-1"></i> រក្សាទុក
                            </button>
                        </div>
                    </form>
                </div>

            </div>

            <p class="text-center text-muted small mt-4">
                ប្រព័ន្ធគ្រប់គ្រង កុដិ១៧ © ២០២៦
            </p>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
