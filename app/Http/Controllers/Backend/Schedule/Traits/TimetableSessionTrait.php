<?php

namespace App\Http\Controllers\Backend\Schedule\Traits;

use App\Models\Schedule\Timetable\TimetableGroupSessionLecturer;
use Illuminate\Support\Facades\DB;

trait TimetableSessionTrait
{
    public function assignLecturersToTimetableGroupSession(Request $request)
    {
          DB::beginTransaction();
          try {
              // @TODO implement your code here...
              $timetableGroupSessions = $request->timetableGroupSessions;
              foreach ($timetableGroupSessions as $timetableGroupSession) {
                  $timetableGroupSessionId = $timetableGroupSession['timetableGroupSessionId'];
                  $timetableGroupSessionLecturerIds = $timetableGroupSession['timetableGroupSessionLecturerIds'];
                  foreach ($timetableGroupSessionLecturerIds as $lecturerId) {
                      (new TimetableGroupSessionLecturer())->create([
                          'timetable_group_session_id' => $timetableGroupSessionId,
                          'lecturer_id' => $lecturerId
                      ]);
                  }
              }
              DB::commit();
              return message_success([]);
          } catch (\Exception $exception) {
              DB::rollback();
              return message_error($exception->getMessage());
          }
    }
}