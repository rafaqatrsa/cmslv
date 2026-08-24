<?php

namespace App\Services\User;

class UserModuleRegistry
{
    /**
     * @return array<string, array<string, mixed>>
     */
    public function all(): array
    {
        return [
            'dashboard' => $this->module('Dashboard', 'student_session', 'user.dashboard', ['student_id'], ['student_id', 'class_id', 'section_id', 'session_id']),
            'leave-requests' => $this->module('Leave Requests', 'student_applyleave', 'user.leave-requests.index', ['reason'], ['from_date', 'to_date', 'apply_date', 'status', 'reason']),
            'attendance' => $this->module('Attendance', 'student_attendences', 'user.attendance.index', ['remark'], ['date', 'attendence_type_id', 'punch_in', 'punch_out', 'remark']),
            'books' => $this->module('Books', 'books', 'user.books.index', ['book_title', 'author', 'isbn_no', 'subject'], ['book_title', 'book_no', 'isbn_no', 'author', 'available']),
            'conferences' => $this->module('Conferences', 'conferences', 'user.conferences.index', ['title', 'subject', 'description'], ['title', 'date', 'duration', 'subject', 'status']),
            'content' => $this->module('Content', 'share_contents', 'user.content.index', ['title', 'description'], ['title', 'share_date', 'valid_upto', 'created_by']),
            'google-meet' => $this->module('Google Meet', 'gmeet', 'user.google-meet.index', ['title', 'subject', 'url', 'description'], ['title', 'date', 'duration', 'subject', 'status']),
            'video-tutorials' => $this->module('Video Tutorials', 'video_tutorial', 'user.video-tutorials.index', ['title', 'vid_title', 'description'], ['title', 'vid_title', 'video_link', 'created_at']),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function get(string $key): array
    {
        return $this->all()[$key];
    }

    /**
     * @param  array<int, string>  $search
     * @param  array<int, string>  $columns
     * @return array<string, mixed>
     */
    private function module(string $label, string $table, string $route, array $search, array $columns): array
    {
        return compact('label', 'table', 'route', 'search', 'columns');
    }
}
