<?php

namespace App\Http\Controllers;

use App\Answers;
use App\Library\Library;
use App\Profile;
use App\QuestionComments;
use App\Questions;
use App\UpDownVoteRecords;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;

class QuestionController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        return view('pages.ask_question', [

        ]);
    }

    public function saveQuestion(Request $request){
        $userId = Auth::id();
        $request['user_id'] = $userId;
        $request['upvote'] = 0;
        $request['downvote'] = 0;

        $request['details'] = $request->input('details') ?: "null";

        $askedQuestion = Questions::create($request->all());
        $questionId = $askedQuestion->id;

        echo $questionId ?: $questionId;
    }

    public function showAllQuestions(){
        $allQuestions = Questions::all();

        return view('pages.show_all_questions', [
            'allQuestions' => $allQuestions
        ]);
    }

    public function showSingleQuestion($questionId){
        $userId = Auth::id();

        $question = DB::table('questions as q')
            ->select('q.*', 'u.name as username', 'profiles.pp_url as pp_url')
            ->join('users as u', 'u.id', '=', 'q.user_id')
            ->join('profiles', 'profiles.user_id', '=', 'q.user_id')
            ->where('q.id', $questionId)
            ->get();

        $user = User::where('id', $userId)->get();
        $profile = Profile::where('user_id', $userId)->get();

        $raw = Answers::where('user_id', $userId)
            ->where('question_id', $questionId)
            ->get();
        $alreadyAnswered = count($raw) == 1 ? true : false;

        //$allAnswers = Answers::where('question_id', $questionId)->get();
        $allAnswers = DB::table('answers as ans')
            ->select('ans.*', 'u.name as username', 'u.id as uid', 'profiles.pp_url as pp_url')
            ->join('users as u', 'ans.user_id', '=', 'u.id')
            ->join('profiles', 'ans.user_id', '=', 'profiles.user_id')
            ->where('ans.question_id', $questionId)
            ->orderBy('created_at', 'desc')
            ->get();
            //this raw sql excludes joining profile
            //SELECT ans.*, u.id as uid, u.name as username from answers as ans join users as u WHERE ans.user_id = u.id

        $qComments = QuestionComments::where('q_id', $questionId)->get();

        if( count($question) == 1 ){
            $question[0]->details = ! $question[0]->details ?: "";
            return view('pages.show_single_question', [
                'question'          => $question[0],
                'user'              => $user[0],
                'profile'           => $profile[0],
                'alreadyAnswered'   => $alreadyAnswered,
                'allAnswers'        => $allAnswers,
                'qComments'         => $qComments
            ]);
        }
        return abort(404);
    } // showSingleQuestion()


    public function saveAnswer(Request $request){
        $request['user_id'] = Auth::id();
        $request['upvote'] = $request['downvote'] = 0;

        try{
            $answer = Answers::create($request->all());
            echo $answer->id;
        }catch (Exception $e){}
    }

    public function deleteAnswer(Request $request){
        $ansId = $request->input('ansId');
        try{
            Answers::where('id', $ansId)->delete();
            echo 'deleted';
        }catch (Exception $e){}
    }

    public function updateAnswer(Request $request){
        $ansId = $request->input('ansId');
        $answer = $request->input('answer');
        try{
            Answers::where('id', $ansId)->update(['answer'=>$answer]);
            echo 'success';
        }catch (Exception $e){}
    }


    public function castVote(Request $request){
        $Lib = new Library();
        $userId = Auth::id();
        $voteType = $request->input('upvoteId') ? 'upvote' : 'downvote';
        $qAnsType = $request->input('type');
        $qAnsType = ( $qAnsType == 'ans' ) ? 'a' : 'q';

        //if its a answer, $id indicates the id column  of 'answers' table
        //if its a question, $id indicates the id column  of 'question' table
        //client side does the trick
        $id = ! is_null( $request->input('upvoteId') ) ? $request->input('upvoteId') : $request->input('downvoteId');

        $vType = ($voteType == 'upvote') ? 'up' : 'down';
        if( $Lib->alreadyVoted($qAnsType, $id, $vType) ) return ;

        if( $voteType == 'upvote' ){
            if( $qAnsType == 'q' ){
                //echo "upvote at $qAnsType id = $id";
                Questions::where('id', $id)->increment('upvote');

                //keep record for user that, <b>He</b> voted for this post
                UpDownVoteRecords::insert([
                    'q_ans_type'    => 'q',
                    'post_id'       => $id,
                    'user_id'       => $userId,
                    'vote_type'     => 'up'
                ]);
            }
            else{
                //ans
                //echo "upvote at $qAnsType id = $id";
                Answers::where('id', $id)->increment('upvote');

                //keep record for user that, <b>He</b> voted for this post
                UpDownVoteRecords::insert([
                    'q_ans_type'    => 'a',
                    'post_id'       => $id,
                    'user_id'       => $userId,
                    'vote_type'     => 'up'
                ]);
            }
            //after upvoting, delete row which contains downvote record
            UpDownVoteRecords::where('q_ans_type', $qAnsType)->where('post_id', $id)
                ->where('user_id', $userId)->where('vote_type', 'down')->delete();
            echo 'success';
        }//upvote done
        else{
            //downvote
            if( $qAnsType == 'q' ){
                //echo "downvote at $qAnsType id = $id";
                Questions::where('id', $id)->decrement('upvote');

                //keep record for user that, <b>He</b> voted for this post
                UpDownVoteRecords::insert([
                    'q_ans_type'    => 'q',
                    'post_id'       => $id,
                    'user_id'       => $userId,
                    'vote_type'     => 'down'
                ]);
            }
            else{
                //ans
                //echo "downvote at $qAnsType id = $id";
                Answers::where('id', $id)->decrement('upvote');

                //keep record for user that, <b>He</b> voted for this post
                UpDownVoteRecords::insert([
                    'q_ans_type'    => 'a',
                    'post_id'       => $id,
                    'user_id'       => $userId,
                    'vote_type'     => 'down'
                ]);
            }
            //after downvoting, delete row which contains upvote record
            UpDownVoteRecords::where('q_ans_type', $qAnsType)->where('post_id', $id)
                ->where('user_id', $userId)->where('vote_type', 'up')->delete();
            //echo $qAnsType.'--'.$id.'--donvoted';
            echo 'success';
        } //downvote done
    } // castVote()


    public function doCommentForQuestion(Request $request){
        $userId = Auth::id();
        $request['comment_by'] = $userId;

        try{
            QuestionComments::create($request->all());
            echo 'success';
        }catch (Exception $e){
            echo "Error";
        }
    } // doCommentForQuestion()


    public function editComment(Request $request){
        print_r($request->all());
    } // editComment()
}
