<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExamRequest;
use App\Http\Requests\UpdateExamRequest;
use App\Jobs\CalculateExamScoreJob;
use App\Models\Exam;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->hasRole('admin')) {
            $exams = Exam::all()->where('ended_at', '!=', null);
            return view('admin.exams.index', compact('exams'));
        }
        $exams = Auth::user()->exams()->get()->sortByDesc('created_at');

        $history = $exams->filter(fn ($exam) => $exam->ended_at != null);
        $progressing = $exams->filter(fn ($exam) => $exam->ended_at == null);

        $available = Quiz::all()->where("status",'=',true)->whereNotIn('id', $exams->pluck('quiz_id'));
        // filter zero questions
        $available = $available->filter(function ($quiz) {
            return $quiz->questions()->count() > 0;
        });
        return view('exams.index', compact('history', 'progressing','available'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return redirect()->route('exams.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreExamRequest $request)
    {
        // CHECK IF USER HAS ALREADY TAKEN THE QUIZ
        if (Auth::user()->exams()->where('quiz_id', $request->quiz_id)->exists()) {
            return redirect()->route('exams.index')->with('error', 'You have already taken this quiz.');
        }

        // CHECK IF USER IS TAKING ANY QUIZ WITHIN THE TIME LIMIT
        if (Auth::user()->exams()->where('ended_at', '=', null)->exists()) {
            return redirect()->route('exams.index')->with('error', 'You can only take one quiz at a time.');
        }

        $exam = Exam::create([
            'user_id' => Auth::user()->id,
            'quiz_id' => $request->quiz_id,
            'ended_at' => null,
        ]);

        dispatch(new CalculateExamScoreJob($exam))->delay(now()->addMinutes($exam->quiz->duration??5));

        return redirect()->route('exams.show', $exam);
    }

    /**
     * Display the specified resource.
     */
    public function show(Exam $exam)
    {

        if (Auth::user()->hasRole('user') && Auth::user()->id != $exam->user_id)
            return redirect()->back()->with("error","You Can't Access this Exams.");

        $quiz = $exam->quiz;
        $questions = $exam->quiz->questions()->with('choices')->get();
        $answers = $exam->answers()->get();

        return view('exams.show', compact('exam', 'questions', 'quiz', 'answers'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Exam $exam)
    {
        return redirect()->route('exams.show', $exam);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateExamRequest $request, Exam $exam)
    {

        if (Auth::user()->hasRole("user") && $request->has('finished')) {
            $exam->update([
                'ended_at' => now(),
            ]);
            dispatch(new CalculateExamScoreJob($exam));
            return redirect()->route('exams.index')->with("success","You successfully finish the exam, Please wait for your results.");

        }

        return redirect()->route('exams.index');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Exam $exam)
    {
        return redirect()->route('exams.index');
    }

}
