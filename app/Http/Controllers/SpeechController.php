<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SpeechController extends Controller
{

    public function test(Request $request)
    {
        if ($request->hasFile('wavfile')) {
            $file = $request->file('wavfile');
            $filename = $file->getClientOriginalName();
            $file->move(base_path().'/public/wav/',$filename);

            $output = exec('python '.base_path().'/public/python/client.py -u ws://localhost:8080/client/ws/speech -r 32000 '.base_path().'/public/wav/'.$filename);
            $output = json_decode($output);
            return $output;
            // return response()->json($output);
            // return $filename;
        }
        return 'no';
        

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
