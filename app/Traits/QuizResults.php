<?php
namespace App\Traits;

use App\Models\ModuleAnswer;
use App\Models\ModuleQuiz;
use App\Models\Module;
use Auth;

trait QuizResults {
    public function getQuizResults()
    {
        $user = Auth::user();

        $modules = Module::all();
        $questions = ModuleQuiz::all();
        $answers = ModuleAnswer::where('user_id', $user->id)->get();

        // Run through all answers to count the total correct for each module
        $a_count = array();
        $q_count = array();

        // Use the module ID's to verify each section of questions and answers
        foreach ($modules as $m) {
            $tot_correct = 0;
            $tot_qty = 0;
            // Use answers for loop so time is not spent looping through non answered modules
            foreach ($answers as $a) {
                if ($a['module_id'] == $m->id) {
                    // If an answer is from the current module, loop through all the questions to find the one that matches it
                    foreach ($questions as $q) {
                        if ($q['module_id'] == $m->id && $q['q_id'] == $a['q_id']) {
                            // count the number of questions for the module
                            $tot_qty++;
                            if ($q['answer_correct'] == $a['answer']) {
                                // count the number of correct answers
                                $tot_correct++;
                            }
                        }
                    }
                }
            }

            // pass the totals into the arrays created earlier
            $a_count[$m->id] = $tot_correct;
            $q_count[$m->id] = $tot_qty;
        }

        // put answer count and q count into a parent array for return
        $results = array();
        $results['a'] = $a_count;
        $results['q'] = $q_count;

        return $results;
    }
}
