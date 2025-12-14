<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Score;
use Barryvdh\DomPDF\Facade\Pdf;

class TranscriptController extends Controller
{
    /**
     * Download transcript as PDF
     */
    public function download()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        // Check if user has student role
        if (!$user->hasRole('Student')) {
            abort(403, 'Unauthorized access.');
        }
        
        // Get student
        $student = $user->student;
        
        if (!$student) {
            abort(404, 'Student profile not found.');
        }
        
        // Get all scores for this student
        $scores = Score::where('student_id', $student->id)
            ->with(['subject', 'student.class.department'])
            ->orderBy('subject_id')
            ->get();
        
        // Load PDF view
        $pdf = Pdf::loadView('pdf.transcript', [
            'student' => $student,
            'scores' => $scores
        ]);
        
        // Set paper size and orientation
        $pdf->setPaper('A4', 'portrait');
        
        // Generate filename
        $filename = 'BangDiem_' . $student->student_code . '_' . date('YmdHis') . '.pdf';
        
        // Download PDF
        return $pdf->download($filename);
    }
    
    /**
     * View transcript in browser
     */
    public function view()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        // Check if user has student role
        if (!$user->hasRole('Student')) {
            abort(403, 'Unauthorized access.');
        }
        
        // Get student
        $student = $user->student;
        
        if (!$student) {
            abort(404, 'Student profile not found.');
        }
        
        // Get all scores for this student
        $scores = Score::where('student_id', $student->id)
            ->with(['subject', 'student.class.department'])
            ->orderBy('subject_id')
            ->get();
        
        // Load PDF view
        $pdf = Pdf::loadView('pdf.transcript', [
            'student' => $student,
            'scores' => $scores
        ]);
        
        // Set paper size and orientation
        $pdf->setPaper('A4', 'portrait');
        
        // Stream PDF (view in browser)
        return $pdf->stream('BangDiem_' . $student->student_code . '.pdf');
    }
}
