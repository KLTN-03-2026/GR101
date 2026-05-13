<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $table = 'banners';

    // Trả về URL hình an toàn để view không gọi phương thức thiếu
    public function getImage()
    {
        $file = $this->image ?? $this->img ?? $this->file ?? null;

        if (! $file) {
            return 'https://images.unsplash.com/photo-1540148426945-6cf22a6b2383?auto=format&fit=crop&q=80&w=1500';
        }

        if (preg_match('/^https?:\/\//', $file)) {
            return $file;
        }

        $file = ltrim($file, '/');

        // Check if file exists in public/ folder directly (e.g. banner_images/...)
        if (file_exists(public_path($file))) {
            return asset($file);
        }

        // Fallback to admin_images/ prefix
        return asset('admin_images/' . $file);
    }
}
