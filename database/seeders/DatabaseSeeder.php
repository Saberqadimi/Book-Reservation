<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\BookCopy;
use App\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $book = Book::create([
            'title' => 'صدسال تنهایی',
            'author' => 'گابریل گارسیا مارکز',
            'genre' => 'رئالیسم جادویی'
        ]);

        BookCopy::create([
            'book_id' => $book->id,
            'status' => 'available',
            'edition' => 'چاپ دوم',
            'published_year' => 2010,
            'location' => 'قفسه 1، ردیف 3',
            'repair_history' => json_encode([
                [
                    'date' => '2022-07-15',
                    'description' => 'ترمیم صفحات داخلی'
                ],
                [
                    'date' => '2023-02-20',
                    'description' => 'تعویض جلد'
                ]
            ])
        ]);

        $user = User::create([
            'name' => 'Saber',
            'email' => 'qadimi74@gmail.com',
            'membership_type' => 'vip',
            'password' => bcrypt('12345678'),
            'score' => 100
        ]);

        $role = Role::create(['name' => 'admin', 'guard_name' => 'api']);
        DB::table('model_has_roles')->insert([
            'role_id' => $role->id,
            'model_type' => User::class,
            'model_id' => $user->id
        ]);
    }

}
