<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->string('audio_path')->nullable()->after('duration_minutes');
            $table->string('audio_mime')->nullable()->after('audio_path');
        });

        $courseRows = DB::table('courses')
            ->select(['id', 'media_path', 'media_mime', 'pdf_path'])
            ->get();

        foreach ($courseRows as $courseRow) {
            $audioPath = null;
            $audioMime = null;

            if ($this->isAudioAsset($courseRow->media_path, $courseRow->media_mime)) {
                $audioPath = $courseRow->media_path;
                $audioMime = $courseRow->media_mime;
            } else {
                $this->deleteAsset($courseRow->media_path);
            }

            $this->deleteAsset($courseRow->pdf_path);

            DB::table('courses')
                ->where('id', $courseRow->id)
                ->update([
                    'audio_path' => $audioPath,
                    'audio_mime' => $audioMime,
                ]);
        }

        $unsupportedResources = DB::table('course_resources')
            ->select(['id', 'file_path'])
            ->whereIn('resource_type', ['video', 'pdf'])
            ->get();

        foreach ($unsupportedResources as $resourceRow) {
            $this->deleteAsset($resourceRow->file_path);
        }

        DB::table('course_resources')
            ->whereIn('resource_type', ['video', 'pdf'])
            ->delete();

        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn(['media_path', 'media_mime', 'pdf_path']);
        });
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->string('media_path')->nullable()->after('duration_minutes');
            $table->string('media_mime')->nullable()->after('media_path');
            $table->string('pdf_path')->nullable()->after('media_mime');
        });

        DB::table('courses')
            ->whereNotNull('audio_path')
            ->update([
                'media_path' => DB::raw('audio_path'),
                'media_mime' => DB::raw('audio_mime'),
            ]);

        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn(['audio_path', 'audio_mime']);
        });
    }

    private function isAudioAsset(?string $path, ?string $mime): bool
    {
        if (blank($path)) {
            return false;
        }

        if (filled($mime)) {
            return Str::startsWith((string) $mime, 'audio/');
        }

        return Str::endsWith(Str::lower($path), ['.mp3', '.wav', '.ogg', '.m4a', '.aac']);
    }

    private function deleteAsset(?string $path): void
    {
        if (blank($path)) {
            return;
        }

        if (Storage::disk('local')->exists($path)) {
            Storage::disk('local')->delete($path);

            return;
        }

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
};
