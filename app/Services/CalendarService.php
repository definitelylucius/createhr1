<?php

namespace App\Services;

use Spatie\GoogleCalendar\Event;
use Carbon\Carbon;

class CalendarService
{
    public function scheduleInterview($application, $data)
    {
        $event = new Event;
        
        $event->name = 'Interview with ' . $application->name . ' for ' . $application->job->title;
        $event->description = 'Interview for position: ' . $application->job->title . "\n\n";
        $event->description .= 'Candidate: ' . $application->name . "\n";
        $event->description .= 'Email: ' . $application->email . "\n";
        $event->description .= 'Resume: ' . route('application.downloadResume', $application->id);
        
        $event->startDateTime = Carbon::parse($data['date'] . ' ' . $data['time']);
        $event->endDateTime = Carbon::parse($data['date'] . ' ' . $data['time'])->addHour();
        
        if (isset($data['location'])) {
            $event->location = $data['location'];
        }
        
        if (isset($data['meeting_link'])) {
            $event->addMeetLink(); // For Google Meet
        }
        
        $event->addAttendee(['email' => $application->email]);
        
        try {
            $event->save();
            return $event->id; // Return event ID for future reference
        } catch (\Exception $e) {
            \Log::error("Calendar event creation failed: " . $e->getMessage());
            return false;
        }
    }
    
    public function cancelInterview($eventId)
    {
        try {
            $event = Event::find($eventId);
            if ($event) {
                $event->delete();
                return true;
            }
            return false;
        } catch (\Exception $e) {
            \Log::error("Calendar event deletion failed: " . $e->getMessage());
            return false;
        }
    }
}