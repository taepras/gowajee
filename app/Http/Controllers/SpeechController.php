<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SpeechController extends Controller
{

    public function chooseFunction(Request $request)
    {
        if ($request->hasFile('wavfile')) {
            $file = $request->file('wavfile');
            $filename = $file->getClientOriginalName();
            $file->move(base_path().'/public/wav/',$filename);

            $output = exec('python ./python/client.py -u ws://192.168.99.100:8080/client/ws/speech -r 32000 ./wav/'.$filename);
            // $output = exec('python ./python/client.py -u ws://localhost:8080/client/ws/speech -r 32000 ./wav/'.$filename);
            $output = json_decode($output);
            $out_json = [];
            $out_json['sentence'] = $output[0];
            $out_json['functionName'] = $output[1];
            if($output[1] == 1){ // 1 = confirm
                $out_json['functionName'] = 'confirm';
                $out_json['needsConfirm'] = false;
            } else if($output[1] == 2){ // 2 = cancel
                $out_json['functionName'] = 'cancel';
                $out_json['needsConfirm'] = false;
            } else if($output[1] == 3){ // 3 = find subject from time
                $out_json['functionName'] = 'get_courses_by_time';
                $out_json['params'] = [];
                $out_json['params']['day'] = $output[2]; // day = mon,tue,wed,thu,fri
                $out_json['params']['time'] = $output[3]; // time = am,pm
                $out_json['needsConfirm'] = false;
            } else if($output[1] == 4){ // 4 = what time of subject
                $out_json['functionName'] = 'get_course_by_id';
                $out_json['params'] = [];
                $out_json['params']['course'] = $output[2]; // subject = digital_photo, food_sci_art, paragraph_writing, weight_control, personal_finance, intro_pack
                $out_json['needsConfirm'] = false;
            } else if($output[1] == 5){ // 5 = register
                $out_json['functionName'] = 'register_course';
                $out_json['params'] = [];
                $out_json['params']['day'] = $output[2];
                $out_json['params']['section'] = $output[3]; // section = 1,2,...,10
                $out_json['needsConfirm'] = true;
            } else if($output[1] == 6){ // 6 = list all registered subject
                $out_json['functionName'] = 'get_enrolled_courses';
                $out_json['needsConfirm'] = false;
            } else if($output[1] == 7){ // 7 = withdraw
                $out_json['functionName'] = 'withdraw_course';
                $out_json['params'] = [];
                $out_json['params']['course'] = $output[2];
                $out_json['needsConfirm'] = true;
            } else if($output[1] == 9){ // 9 = unknow
                $out_json['needsConfirm'] = false;
            } else{
                $out_json['needsConfirm'] = false;
            }
            return $out_json;
        }
        return 'Not have file.';

        // // way1 to run python
        // // $command = escapeshellcmd('python ./python/kaldi.py');
        // // $output = shell_exec($command);

        // // way2 to run python
        // // $output = exec('python ./python/kaldi.py');

        // // get array from python have 2 way to return
        // // $output = exec('python ./python/kaldi.py');
        // $output = exec('python client.py -u ws://localhost:8080/client/ws/speech -r 32000 000.wav');
        // $output = json_decode($output);
        // return $output;
        // return response()->json($output);

        // // get object json from python
        // $output = exec('python ./python/parser.py');
        // $output = json_decode($output);
        // return response()->json($output);
    }

    public function uploadWav()
    {
        return view('uploadWav');

    }

    public function confirm(Request $request)
    {
        if ($request->hasFile('wavfile')) {
            $file = $request->file('wavfile');
            $filename = $file->getClientOriginalName();
            $file->move(base_path().'/public/wav/',$filename);

            // $output = exec('python ./python/client.py -u ws://192.168.99.100:8080/client/ws/speech -r 32000 ./wav/'.$filename);
            $output = exec('python ./python/client.py -u ws://localhost:8080/client/ws/speech -r 32000 ./wav/'.$filename);
            $output = json_decode($output);
            $out_json = [];
            // $out_json['sentence'] = $output[0];
            if($output[1] == 1){
                $out_json['result'] = "confirm";
            } else if($output[1] == 2){
                $out_json['result'] = "cancel";
            } else{
                $out_json['result'] = "unknown";
            }
            return $out_json;
        }
        return 'Not have file.';
    }

    public function checkResultWav(){
        $data = [];
        for($i=1;$i<=10;$i++){
			if(60<=$i && $i<=99)
				continue;
            if($i<10)
                $filename = "00".$i.".wav";
            else if($i<100)
                $filename = "0".$i.".wav";
            else
                $filename = $i.".wav";
            $data[$i]['name'] = $filename;

            $output = exec('python ./python/client.py -u ws://localhost:8080/client/ws/speech -r 32000 ./testWav/'.$filename);
            $output = json_decode($output);
            $data[$i]['data'] = $output[0];
        }
        return $data;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
