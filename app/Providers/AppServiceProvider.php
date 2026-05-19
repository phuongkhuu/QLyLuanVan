<?php

namespace App\Providers;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
// 1. Thêm dòng use này ở trên cùng
use Illuminate\Database\Eloquent\Relations\Relation; 

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
         Schema::defaultStringLength(191);
        // 2. Thêm khối lệnh này vào hàm boot()
        Relation::enforceMorphMap([
            'user' => \App\Models\User::class,
            
            // Khai báo thêm nếu bạn có dùng GiangVien để nhận thông báo trực tiếp
            'giangvien' => \App\Models\GiangVien::class, 
        ]);
    }
}