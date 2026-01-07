<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfileUpdateRequest extends Model
{
   public function rules(): array
{
    return [
        'name'  => ['required', 'string', 'max:255'],
        'phone' => ['required', 'string', 'max:20'],
        'email' => ['required', 'email'],

        // ✅ FIX ROLE HERE
        'role' => ['required', 'in:member,admin,treasurer,collector,utility'],

        'avatar' => ['nullable', 'image', 'max:2048'],

        // password (optional update)
        'password' => ['nullable', 'confirmed', 'min:6'],
    ];
}


}
