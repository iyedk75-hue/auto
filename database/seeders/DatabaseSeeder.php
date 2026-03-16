<?php

namespace Database\Seeders;

use App\Models\AutoSchool;
use App\Models\ExamSchedule;
use App\Models\PaymentRecord;
use App\Models\Course;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Database\Seeders\CandidateSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $school = AutoSchool::updateOrCreate([
            'name' => 'Auto-École Massar Centre',
        ], [
            'city' => 'Tunis',
            'address' => 'Avenue Habib Bourguiba',
            'whatsapp_phone' => '+216 99 000 000',
            'is_active' => true,
        ]);

        User::updateOrCreate([
            'email' => 'admin@massar.test',
        ], [
            'name' => 'Responsable Massar',
            'role' => User::ROLE_ADMIN,
            'auto_school_id' => $school->id,
            'password' => 'password',
        ]);

        $candidate = User::updateOrCreate([
            'email' => 'candidate@massar.test',
        ], [
            'name' => 'Candidat Démo',
            'role' => User::ROLE_CANDIDATE,
            'auto_school_id' => $school->id,
            'phone' => '+216 22 000 000',
            'status' => 'active',
            'balance_due' => 120.00,
            'registered_at' => now(),
            'password' => 'password',
        ]);

        $questions = [
            [
                'category' => 'priorite',
                'question_text' => 'في مفترق، شكون يتعدى الأول؟',
                'image_url' => null,
                'correct_answer' => 'أ',
                'explanation' => 'قاعدة الأولوية لليمين تطبّق إذا ما فماش إشارات.',
                'difficulty' => 'easy',
                'options' => [
                    ['option_id' => 'أ', 'text' => 'السيارة الزرقاء'],
                    ['option_id' => 'ب', 'text' => 'السيارة الحمراء'],
                ],
            ],
            [
                'category' => 'signalisation',
                'question_text' => 'شنوة معناها لافتة الوقوف؟',
                'image_url' => null,
                'correct_answer' => 'ب',
                'explanation' => 'اللافتة تعني وجوب التوقف التام قبل المتابعة.',
                'difficulty' => 'easy',
                'options' => [
                    ['option_id' => 'أ', 'text' => 'تخفيض السرعة فقط'],
                    ['option_id' => 'ب', 'text' => 'توقف تام ثم متابعة'],
                    ['option_id' => 'ج', 'text' => 'الأولوية للسيارات القادمة من الخلف'],
                ],
            ],
        ];

        $courses = [
            [
                'title' => 'Priority rules',
                'category' => 'priority_rules',
                'description' => 'Understand right-of-way and intersection priority.',
                'content' => 'Key concepts, priority scenarios, and right-of-way rules.',
                'duration_minutes' => 45,
                'sort_order' => 1,
            ],
            [
                'title' => 'Traffic signs',
                'category' => 'traffic_signs',
                'description' => 'Master the key road signs and their meanings.',
                'content' => 'Common signs, warnings, and regulatory signals.',
                'duration_minutes' => 50,
                'sort_order' => 2,
            ],
            [
                'title' => 'Driving safety',
                'category' => 'driving_safety',
                'description' => 'Safety basics, defensive driving, and hazard awareness.',
                'content' => 'Safe following distance, speed control, and hazard response.',
                'duration_minutes' => 40,
                'sort_order' => 3,
            ],
            [
                'title' => 'Vehicle basics',
                'category' => 'vehicle_basics',
                'description' => 'Core vehicle knowledge and essential checks.',
                'content' => 'Controls, dashboard indicators, and routine checks.',
                'duration_minutes' => 35,
                'sort_order' => 4,
            ],
        ];

        foreach ($questions as $payload) {
            $question = Question::updateOrCreate([
                'question_text' => $payload['question_text'],
            ], [
                'id' => (string) Str::uuid(),
                'category' => $payload['category'],
                'image_url' => $payload['image_url'],
                'correct_answer' => $payload['correct_answer'],
                'explanation' => $payload['explanation'],
                'difficulty' => $payload['difficulty'],
                'is_active' => true,
            ]);

            QuestionOption::query()->where('question_id', $question->id)->delete();
            $question->options()->createMany($payload['options']);
        }

        foreach ($courses as $payload) {
            Course::updateOrCreate([
                'title' => $payload['title'],
            ], [
                'id' => (string) Str::uuid(),
                'category' => $payload['category'],
                'description' => $payload['description'],
                'content' => $payload['content'],
                'duration_minutes' => $payload['duration_minutes'],
                'sort_order' => $payload['sort_order'],
                'is_active' => true,
            ]);
        }

        PaymentRecord::updateOrCreate([
            'user_id' => $candidate->id,
            'status' => PaymentRecord::STATUS_PENDING,
        ], [
            'amount' => 120.00,
            'note' => 'Reste à payer pour l’inscription au code.',
        ]);

        ExamSchedule::updateOrCreate([
            'user_id' => $candidate->id,
            'exam_date' => now()->addDays(14)->toDateString(),
        ], [
            'auto_school_id' => $school->id,
            'status' => ExamSchedule::STATUS_PLANNED,
            'note' => 'Session matinée.',
        ]);

        $this->call(CandidateSeeder::class);
    }
}
