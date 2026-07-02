<?php

namespace Database\Seeders;

use App\Models\AttendanceSummary;
use App\Models\ExamSummary;
use App\Models\FeeSummary;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DashboardSummarySeeder extends Seeder
{
    /**
     * Seed realistic dashboard summary data across all three tables.
     * Generates 6 months of data across 3 campuses and multiple classes.
     */
    public function run(): void
    {
        $campuses = ['Main Campus', 'North Campus', 'South Campus'];
        $classes = ['Grade 6', 'Grade 7', 'Grade 8', 'Grade 9', 'Grade 10'];
        $startMonth = Carbon::now()->subMonths(5)->startOfMonth();

        // --- Attendance Summaries ---
        foreach ($campuses as $campus) {
            foreach ($classes as $class) {
                // Base student count varies by campus and class
                $baseStudents = fake()->numberBetween(30, 55);

                for ($i = 0; $i < 6; $i++) {
                    $month = $startMonth->copy()->addMonths($i);
                    $totalStudents = $baseStudents + fake()->numberBetween(-3, 3);

                    // Working days in a month (~22), times total students
                    $totalPossibleAttendance = $totalStudents * fake()->numberBetween(20, 24);
                    $attendanceRate = fake()->randomFloat(2, 78, 97);
                    $totalPresent = (int) round($totalPossibleAttendance * ($attendanceRate / 100));
                    $totalAbsent = $totalPossibleAttendance - $totalPresent;

                    AttendanceSummary::create([
                        'campus' => $campus,
                        'class_name' => $class,
                        'month' => $month->format('Y-m-01'),
                        'total_students' => $totalStudents,
                        'total_present' => $totalPresent,
                        'total_absent' => $totalAbsent,
                        'attendance_percentage' => $attendanceRate,
                    ]);
                }
            }
        }

        // --- Fee Summaries ---
        foreach ($campuses as $campus) {
            // Per-campus fee structure
            $feePerStudent = fake()->randomFloat(2, 8000, 25000);
            $totalStudentsInCampus = fake()->numberBetween(200, 450);

            for ($i = 0; $i < 6; $i++) {
                $month = $startMonth->copy()->addMonths($i);
                $totalExpected = round($feePerStudent * $totalStudentsInCampus, 2);

                // Collection rate varies realistically (75-96%)
                $collectionRate = fake()->randomFloat(2, 75, 96);
                $totalCollected = round($totalExpected * ($collectionRate / 100), 2);
                $totalOutstanding = round($totalExpected - $totalCollected, 2);

                $studentsPaid = (int) round($totalStudentsInCampus * ($collectionRate / 100));
                $studentsUnpaid = $totalStudentsInCampus - $studentsPaid;

                FeeSummary::create([
                    'campus' => $campus,
                    'month' => $month->format('Y-m-01'),
                    'total_expected' => $totalExpected,
                    'total_collected' => $totalCollected,
                    'total_outstanding' => $totalOutstanding,
                    'collection_percentage' => $collectionRate,
                    'students_paid' => $studentsPaid,
                    'students_unpaid' => $studentsUnpaid,
                ]);
            }
        }

        // --- Exam Summaries ---
        $exams = [
            ['name' => 'Mid-Term Exam', 'monthOffset' => 1],
            ['name' => 'Monthly Test', 'monthOffset' => 3],
            ['name' => 'Final Exam', 'monthOffset' => 5],
        ];

        foreach ($campuses as $campus) {
            foreach ($classes as $class) {
                $totalStudents = fake()->numberBetween(30, 55);

                foreach ($exams as $exam) {
                    $examDate = $startMonth->copy()->addMonths($exam['monthOffset']);

                    $passRate = fake()->randomFloat(2, 60, 95);
                    $studentsPassed = (int) round($totalStudents * ($passRate / 100));
                    $studentsFailed = $totalStudents - $studentsPassed;

                    $avgScore = fake()->randomFloat(2, 55, 85);
                    $highestScore = fake()->randomFloat(2, max($avgScore + 5, 88), 100);
                    $lowestScore = fake()->randomFloat(2, 15, min($avgScore - 10, 45));

                    ExamSummary::create([
                        'campus' => $campus,
                        'class_name' => $class,
                        'exam_name' => $exam['name'],
                        'exam_date' => $examDate->format('Y-m-d'),
                        'total_students' => $totalStudents,
                        'students_passed' => $studentsPassed,
                        'students_failed' => $studentsFailed,
                        'pass_percentage' => $passRate,
                        'average_score' => $avgScore,
                        'highest_score' => $highestScore,
                        'lowest_score' => $lowestScore,
                    ]);
                }
            }
        }
    }
}
