<?php

namespace App\Services;

use App\Models\ApplicationForm;
use App\Models\CandidateRegistration;
use App\Models\Result;
use App\Models\SmsLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CandidateSmsNotificationService
{
    public function __construct(private SparrowSmsService $sms)
    {
    }

    public function applicationSentBack(ApplicationForm $application, string $actor, ?string $notes = null): void
    {
        $position = $this->positionTitle($application);
        $reason = $this->reasonText($notes);

        $this->sendForApplication(
            $application,
            "NOC E-Recruitment: Your application for {$position} has been sent back for correction by {$actor}.{$reason} Please login and update your application."
        );
    }

    public function applicationRejected(ApplicationForm $application, string $actor, ?string $reason = null): void
    {
        $position = $this->positionTitle($application);
        $reasonText = $this->reasonText($reason);

        $this->sendForApplication(
            $application,
            "NOC E-Recruitment: Your application for {$position} has been rejected by {$actor}.{$reasonText} Please login for details."
        );
    }

    public function applicationApproved(ApplicationForm $application, string $actor): void
    {
        $position = $this->positionTitle($application);

        $this->sendForApplication(
            $application,
            "NOC E-Recruitment: Congratulations, your application for {$position} has been approved by {$actor}. Please login for details."
        );
    }

    public function admitCardAvailable(ApplicationForm $application): void
    {
        $position = $this->positionTitle($application);
        $rollNumber = $application->roll_number ? " Roll No: {$application->roll_number}." : '';
        $examSchedule = $application->exam_date_first
            ? " Exam: {$application->exam_date_first}" . ($application->exam_time_first ? " {$application->exam_time_first}" : '') . '.'
            : '';

        $this->sendForApplication(
            $application,
            "NOC E-Recruitment: Your admit card for {$position} is now available.{$rollNumber}{$examSchedule} Please login to view/download it."
        );
    }

    public function resultPublished(Result $result): void
    {
        $candidate = CandidateRegistration::find($result->candidate_id);
        $phone = $candidate?->phone;

        if (!$phone) {
            return;
        }

        $post = $result->post ?: 'your applied post';
        $message = "NOC E-Recruitment: Your exam result for {$post} has been published. Please login to view your result.";

        $this->sendAndLog($phone, $message, $candidate?->id, null);
    }

    private function sendForApplication(ApplicationForm $application, string $message): void
    {
        $candidate = $this->candidateForApplication($application);
        $phone = $candidate?->phone ?: $application->phone;

        if (!$phone) {
            return;
        }

        $this->sendAndLog($phone, $message, $candidate?->id, $application->job_posting_id);
    }

    private function sendAndLog(string $phone, string $message, ?int $candidateId, ?int $jobPostingId): void
    {
        $message = Str::limit($message, 500, '');
        $result = $this->sms->send($phone, $message);

        SmsLog::create([
            'admin_id'         => Auth::guard('admin')->id() ?? 0,
            'candidate_id'     => $candidateId,
            'job_posting_id'   => $jobPostingId,
            'phone'            => $phone,
            'message'          => $message,
            'response_code'    => $result['response_code'] ?? null,
            'response_message' => $result['response'] ?? null,
        ]);
    }

    private function candidateForApplication(ApplicationForm $application): ?CandidateRegistration
    {
        if ($application->relationLoaded('candidateRegistration')) {
            return $application->candidateRegistration;
        }

        if (!$application->citizenship_number) {
            return null;
        }

        return CandidateRegistration::where('citizenship_number', $application->citizenship_number)->first();
    }

    private function positionTitle(ApplicationForm $application): string
    {
        $jobTitle = $application->relationLoaded('jobPosting')
            ? $application->jobPosting?->title
            : $application->jobPosting()->value('title');

        return $jobTitle
            ?: $application->applying_position
            ?: $application->advertisement_no
            ?: 'your applied post';
    }

    private function reasonText(?string $reason): string
    {
        $reason = trim((string) $reason);

        if ($reason === '') {
            return '';
        }

        return ' Reason: ' . Str::limit($reason, 180, '');
    }
}
