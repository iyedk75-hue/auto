<?php

namespace App\Notifications;

use App\Models\ExamSchedule;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ExamScheduleUpdatedNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly ExamSchedule $exam,
        private readonly string $action,
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'kind' => 'exam_schedule',
            'action' => $this->action,
            'exam_id' => $this->exam->id,
            'exam_date' => optional($this->exam->exam_date)->format('Y-m-d'),
            'status' => $this->exam->status,
            'note' => $this->exam->note,
            'auto_school' => $this->exam->autoSchool?->name,
            'title' => $this->action === 'updated'
                ? 'Votre date d’examen a été mise à jour'
                : 'Une nouvelle date d’examen a été planifiée',
            'body' => sprintf(
                'Examen prévu le %s%s.',
                optional($this->exam->exam_date)->format('d/m/Y') ?? 'bientôt',
                $this->exam->autoSchool?->name ? ' par '.$this->exam->autoSchool->name : ''
            ),
        ];
    }
}