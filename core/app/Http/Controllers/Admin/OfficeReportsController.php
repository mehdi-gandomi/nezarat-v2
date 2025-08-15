<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OfficeFile;
use App\Models\InspectionLog;
use Illuminate\Http\Request;

class OfficeReportsController extends Controller
{
    public function index()
    {
        // Get statistics for the cards
        $stats = $this->getViolationStats();

        return view('admin.office-reports', compact('stats'));
    }

    private function getViolationStats()
    {
        // Get total offices
        $totalOffices = OfficeFile::count();

        // Get offices with violations (adapt = 0)
        $officesWithViolations = InspectionLog::where('adapt', 0)
            ->distinct('office_code')
            ->count('office_code');

        // Get offices without violations (adapt = 1)
        $officesWithoutViolations = InspectionLog::where('adapt', 1)
            ->distinct('office_code')
            ->count('office_code');

        // Get offices requiring defect removal (you may need to adjust this based on your business logic)
        $officesRequiringDefectRemoval = InspectionLog::where('adapt', 0)
            ->where('requires_second_inspection', 1)
            ->distinct('office_code')
            ->count('office_code');

        // Get offices sent to primary board (you may need to adjust this based on your business logic)
        $officesSentToBoard = 0; // This would need to be implemented based on your specific logic

        return [
            'total_offices' => $totalOffices,
            'offices_with_violations' => $officesWithViolations,
            'offices_without_violations' => $officesWithoutViolations,
            'offices_requiring_defect_removal' => $officesRequiringDefectRemoval,
            'offices_sent_to_board' => $officesSentToBoard,
        ];
    }
}
