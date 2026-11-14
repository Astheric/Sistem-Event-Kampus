<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('events')->where('status', 'APPROVED')->update(['status' => 'PUBLISHED']);
        DB::table('events')->where('status', 'UNDER REVIEW')->update(['status' => 'SUBMITTED']);
        DB::table('events')->where('status', 'ONGOING')->update(['status' => 'PUBLISHED']);
    }

    public function down(): void
    {
        //
    }
};
