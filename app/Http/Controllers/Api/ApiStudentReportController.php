<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Models\t_classrooms;
use App\Models\t_students;
use App\Services\StudentReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ApiStudentReportController
{
    protected $studentReportService;
    protected $userData;

    public function __construct(StudentReportService $studentReportService, Request $request)
    {
        $this->studentReportService = $studentReportService;
        $this->userData = $request->{"USER_DATA"};
    }
    public function getStudentReport(Request $request)
    {
        $studentId = $request->query('studentId');
        $teacherId = $request->query('teacherId');
        $parentId = $request->query('parentId');
        $date = $request->query('date');

        try {
            return Helper::composeReply('SUCCESS', 'Success get report', $this->studentReportService->getAllReport($studentId, $teacherId, $parentId, $date));
        } catch (\Exception $exception) {
            return Helper::composeReply('ERROR', 'ERROR get report', $exception->getMessage(), 500);
        }
    }

    public function getStudentReportDetailById(Request $request, $id)
    {
        try {
            return Helper::composeReply('SUCCESS', 'Success get detail report', $this->studentReportService->getReportById($id));
        } catch (\Exception $exception) {
            return Helper::composeReply('ERROR', 'ERROR get detail report', $exception->getMessage(), 500);
        }
    }
    public function createStudentReport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'S_ID' => 'required|exists:t_students,S_ID',
            'U_ID' => 'required|exists:_users,U_ID',
            'SR_TITLE' => 'required|string',
            'SR_CONTENT' => 'required|string',
            'SR_DATE' => 'required|date',
            'ACTIVITIES' => 'nullable|array',
            'ACTIVITIES.*.ACTIVITY_NAME' => 'required_with:ACTIVITIES|string',
            'ACTIVITIES.*.REF_ACTIVITIES' => 'nullable|array',
            'ACTIVITIES.*.REF_ACTIVITIES.*.STATUS' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return Helper::composeReply('ERROR', 'Validation failed', $validator->errors()->all(), 422);
        }

        $data = [
            'S_ID' => $request->S_ID,
            'U_ID' => $request->U_ID,
            'SR_TITLE' => $request->SR_TITLE,
            'SR_CONTENT' => $request->SR_CONTENT,
            'SR_DATE' => $request->SR_DATE,
            'SYS_CREATE_USER' => $this->userData->{"U_ID"},
            'ACTIVITIES' => $request->ACTIVITIES,
        ];

        try {
            $newReport = $this->studentReportService->createReport($data);
            return Helper::composeReply('SUCCESS', 'Student report created successfully', $newReport, 201);
        } catch (\Exception $e) {
            return Helper::composeReply('ERROR', 'Failed to create report', $e->getMessage(), 500);
        }
    }
    public function updateStudentReport(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'SR_CONTENT' => 'sometimes|required|string',
            'SR_DATE' => 'sometimes|required|date',
            'ACTIVITIES' => 'nullable|array',
            'ACTIVITIES.*.ACTIVITY_TYPE' => 'required_with:ACTIVITIES|string',
            'ACTIVITIES.*.ACTIVITY_NAME' => 'required_with:ACTIVITIES|string',
            'ACTIVITIES.*.STATUS' => 'required_with:ACTIVITIES|string',
        ]);
        if ($validator->fails()) {
            return Helper::composeReply('ERROR', 'Validation failed', $validator->errors()->all(), 422);
        }
        $data = $request->only(['SR_CONTENT', 'SR_DATE', 'ACTIVITIES']);
        $data['SYS_UPDATE_USER'] = $this->userData->{"U_ID"};
        try {
            $updatedReport = $this->studentReportService->updateReport($data, $id);
            return Helper::composeReply('SUCCESS', 'Student report updated successfully', $updatedReport, 200);
        } catch (\Exception $e) {
            return Helper::composeReply('ERROR', 'Failed to update report', $e->getMessage(), 500);
        }
    }

    public function getMyReports(Request $request)
    {
        $userId = $this->userData->{"U_ID"};
        $date = $request->query('date');
        try {
            return Helper::composeReply('SUCCESS', 'Success get my reports', $this->studentReportService->getReportsByUserId($userId, $date));
        } catch (\Exception $exception) {
            return Helper::composeReply('ERROR', 'ERROR get my reports', $exception->getMessage(), 500);
        }
    }

    public function deleteStudentReport(Request $request, $id)
    {
        $userId = $this->userData->{"U_ID"};
        try {
            $this->studentReportService->deleteReport($id, $userId);
            return Helper::composeReply('SUCCESS', 'Student report deleted successfully', null, 200);
        } catch (\Exception $e) {
            $statusCode = $e->getCode() === 403 ? 403 : 500;
            $message = $e->getCode() === 403 ? 'Unauthorized' : 'Failed to delete report';
            return Helper::composeReply('ERROR', $message, $e->getMessage(), $statusCode);
        }
    }

    public function reviewStudentReport(Request $request, $id)
    {
        $report = $this->studentReportService->getReportById($id);
        if (!$report) {
            return Helper::composeReply('ERROR', 'Report not found', null, 404);
        }

        if (!$report['STUDENT'] || $report['STUDENT']['STUDENT_PARENT_U_ID'] != $this->userData->{"U_ID"}) {
            return Helper::composeReply('ERROR', 'Unauthorized - only the parent can review', null, 403);
        }

        $validator = Validator::make($request->all(), [
            'review_star' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return Helper::composeReply('ERROR', 'Validation failed', $validator->errors()->all(), 422);
        }

        $data = [
            'review_star' => $request->review_star,
            'review' => $request->review,
            'SYS_UPDATE_USER' => $this->userData->{"U_ID"},
        ];

        try {
            $updated = $this->studentReportService->updateReport($data, $id);
            return Helper::composeReply('SUCCESS', 'Review submitted successfully', $updated, 200);
        } catch (\Exception $e) {
            return Helper::composeReply('ERROR', 'Failed to submit review', $e->getMessage(), 500);
        }
    }


    public function getStudentsReports(Request $request, int $classroom){
        $classroom = t_classrooms::where('CLSRM_ID', $classroom)->firstOrFail();

        $year = $request->query('year', date('Y'));
        $month = $request->query('month', date('n'));

        $students = t_students::where('CLSRM_ID', $classroom->CLSRM_ID)
            ->with(['reports' => function ($query) use ($year, $month) {
                $query->whereYear('SR_DATE', $year)
                    ->whereMonth('SR_DATE', $month)
                    ->with(['activities' => function ($q) {
                        $q->with('refActivities');
                    }]);
            }])
            ->get();

        $grouped = [];
        foreach ($students as $student) {
            foreach ($student->reports as $report) {
                $dateKey = \Carbon\Carbon::parse($report->SR_DATE)->format('Y-m-d');
                $grouped[$dateKey][] = [
                    'student' => [
                        'S_ID' => $student->S_ID,
                        'STUDENT_NAME' => $student->STUDENT_NAME,
                        'STUDENT_ROLL_NUMBER' => $student->STUDENT_ROLL_NUMBER,
                    ],
                    'report' => [
                        'SR_ID' => $report->SR_ID,
                        'SR_TITLE' => $report->SR_TITLE,
                        'SR_CONTENT' => $report->SR_CONTENT,
                        'SR_DATE' => $report->SR_DATE,
                        'review_star' => $report->review_star,
                        'review' => $report->review,
                        'activities' => $report->activities->map(function ($activity) {
                            return [
                                'ACTIVITY_NAME' => $activity->ACTIVITY_NAME,
                                'REF_ACTIVITIES' => $activity->refActivities->map(function ($ref) {
                                    return [
                                        'ACTIVITY_TYPE' => $ref->ACTIVITY_TYPE,
                                        'ACTIVITY_NAME' => $ref->ACTIVITY_NAME,
                                        'STATUS' => $ref->STATUS,
                                    ];
                                }),
                            ];
                        }),
                    ],
                ];
            }
        }

        return Helper::composeReply('SUCCESS', 'Success get students reports', [
            'classroom' => [
                'CLSRM_ID' => $classroom->CLSRM_ID,
                'CLSRM_NAME' => $classroom->CLSRM_NAME,
                'CLSRM_GRADE' => $classroom->CLSRM_GRADE,
            ],
            'year' => $year,
            'month' => $month,
            'reports' => $grouped,
        ]);
    }
}
