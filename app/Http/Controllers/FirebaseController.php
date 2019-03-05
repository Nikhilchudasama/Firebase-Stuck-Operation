<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Kreait\Firebase;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

class FirebaseController extends Controller
{
    protected $file = __DIR__.'/learningfirebase-ca0df-firebase-adminsdk-e1dda-c5f825c396.json';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $database = $this->Firebase();

        $reference  = $database->getReference('posts');

        return response()->json($reference->getValue());
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $database = $this->Firebase();

        $newPost = $database
            ->getReference('posts')
            ->push([
                'title' => request()->input('title'),
                'body' => request()->input('body')
            ]);
        return response()->json($newPost->getValue());

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $database = $this->Firebase();
        $reference  = $database->getReference('posts/'.$id);
        return response()->json($reference->getValue());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $database = $this->Firebase();
        $reference  = $database->getReference('posts/'.$id);
        return response()->json($reference->getValue());
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
        $database = $this->Firebase();
        $newPostKey = $id;
        $updates = [
            'posts/'.$newPostKey => [
                    'title' => request()->input('title'),
                    'body' => request()->input('body')
                ],
        ];
        $database->getReference()
           ->update($updates);
        $postData = request()->all();
        unset($postData['_method']);
        return response()->json(request()->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $database = $this->Firebase();
        $update = ['/is_read'  => "true"];
        $database->getReference('posts/'. $id)->update($update);
        return response()->json('deleted');
    }

    protected function Firebase(){
        $serviceAccount = ServiceAccount::fromJsonFile($this->file);
        $firebase = (new Factory)
        ->withServiceAccount($serviceAccount)
        ->withDatabaseUri('https://learningfirebase-ca0df.firebaseio.com')
        ->create();
        $database = $firebase->getDatabase();
        return $database;
    }
}
