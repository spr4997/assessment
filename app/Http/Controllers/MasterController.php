<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

use App\Http\Requests;
use App\questions;
use App\choices;
use App\answers;
class MasterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $question=new questions();
        $f=$question::with('choices')->get();
        return response($f);
    }


  
    public function qvalidate(Request $request)
    {
        try{
            $result=array();
            $question=new questions();
            if(array_key_exists("questions",$request->all())){
                $f=$question::with('quest')->whereIn('question',$request["questions"])->get();    
            }
            elseif(array_key_exists("id",$request->all())){
                $f=$question::with('quest')->find($request["id"]);
            }
            $i=0;
            foreach($f as $g){
                
                if($g->quest[0]["choice"]==$request["selected"][$i++]){
                    $result[$g->qid]="correct";   
                }
                else{
                    $result[$g->qid]="incorrect";
                }
            }
            return response($result);
        }
        catch(Exception $e){
            return response("Failed to process request:",$e);   
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function filterLevel($level)
    {
        $question=new questions();
        $f=$question::with('choices')->where('level',$level)->get();
        return response($f);
    }
    public function filterLang($lang)
    {
        $question=new questions();
        $f=$question::with('choices')->where('language',$lang)->get();
        return response($f);
    }

    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        try{
            foreach($request["data"] as $data){
                $question = new questions();
                $question->question=$data["question"];
                $question->level=$data["level"];
                $question->language=$data["language"];
                $question->save();
                
                $qid=$question->qid;
                $c=array();
                $choice = new choices();
                foreach($data["choices"] as $ch){
                    array_push($c,["qid"=>$qid,"choice"=>$ch]);
                    
                }
    
                $choice::insert($c);
    
                $c=$choice::where("choice",$data["answer"])->where("qid",$qid)->get();
                $question->quest()->attach($c);
            }    
            return response(["status"=>"success","msg"=>"questions inserted successfully"]);
        }
        catch(Exception $e){
            return response(["status"=>"failed","msg"=>"failed to insert questions"]);
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        print_r($id);
        $question=new questions();
        $choices = new choices();
        DB::connection()->enableQueryLog();
        $f=$question::with('choices')->find($id);
        return response($f);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try{
            $question=new questions();
            $q=$question::find($id);
            $q->question=$request["question"];
            $q->save();
    
            if(array_key_exists("choices",$request->all())){
                $c=array();
                $choice = new choices();
                $i=0;
                foreach($choice::where('qid', $id)->get() as $c){
                    $c->choice=$request["choices"][$i++];
                    $c->save();
                }
            }
            if(array_key_exists("answer",$request->all())){
                $choice = new choices();
                $c=$choice::where("choice",$request["answer"])->where("qid",$id)->first();
                if(!empty($c)){
                    $ans=new answers();
                    $a=$ans::where("qid",$id)->first();
                    $a->cid=$c->cid;
                    $a->save();    
                }
                else{
                    return response(["status"=>"failed","msg"=>"answer not found in choices"]);
                }
            }                
            return response(["status"=>"success","msg"=>"updated successfully"]);
        }
        catch(Exception $e){
            return response(["status"=>"failed","msg"=>"failed to update"]);
        }
    }

        /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $ans=new answers();
            $ans::where("qid",$id)->delete();
            
            $choice=new choices();
            $choice::where("qid",$id)->delete();
            $q=new questions();
            $q::where("qid",$id)->delete();
            return response(["status"=>"success"]);
        }
        catch(Exception $e){
            return response(["status"=>"failed"]);
        }
    }

}
