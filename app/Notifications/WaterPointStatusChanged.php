<?php

namespace App\Notifications;

use App\Models\WaterPointReport;
use Illuminate\Notifications\Notification;

class WaterPointStatusChanged extends Notification
{
    public function __construct(public WaterPointReport $report) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'water_source_id' => $this->report->water_source_id,
            'message' => 'A critical incident was reported at a subscribed water point.',
            'report_id' => $this->report->id,
        ];
    }
}
