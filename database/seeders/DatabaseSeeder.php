<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\BookCopy;
use App\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $book = Book::create([
            'title' => 'صدسال تنهایی ',
            'author' => ' گابریل گارسیا مارکز',
            'genre' => 'رئالیسم جادویی'
        ]);

        BookCopy::create([
            'book_id' => $book->id,
            'status' => 'available',
            'repair_history' => []
        ]);

        User::create([
            'name' => 'Saber',
            'email' => 'user@example.com',
            'membership_type' => 'regular',
            'password' => bcrypt('12345678'),
            'score' => 100
        ]);
    }
}
