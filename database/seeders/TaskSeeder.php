<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Task;


class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Task::create(['task' => 'Check Errors', 'completed' => false]);
        Task::create(['task' => 'Remove Bugs', 'completed' => false]);
        Task::create(['task' => 'Need Improvements', 'completed' => false]);
    }
}
