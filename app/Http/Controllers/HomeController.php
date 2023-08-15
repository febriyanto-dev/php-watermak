<?php

namespace App\Http\Controllers;
use DB;
use Uploader;
use Watermark;
use File;

class HomeController extends BaseController
{
    protected $pages = 'pages.home';

    public function __construct(){

        parent::__construct();
    }

    public function index(){
        
        try {

            return view($this->pages . '.index')->with($this->data);
        }
        catch (Exception $e) {
            throw $e;
        }
    }

    public function ActionForm(){

        try{

            $transaction = DB::transaction(function () {

                $data = array();

                if(!$this->request->ajax()){
                    $data = [
                        'respon' => 'failed',
                        'flag' => 'invalid',
                        'message' => "You don't have access"
                    ];
                }
                else{

                    $module = $this->request->module;
                    $act  = $this->request->act;

                    if($module=="watermak" && $act == "add"){

                        $name = $this->request->name;

                        if($this->request->hasFile('images')) {

                            $list_image = $this->request->file('images');

                            if(count($list_image)*1>0){

                                // Allowed file types 
                                $allowTypes = array('jpg', 'png', 'jpeg'); 

                                $uploadStatus = true; 
                                foreach($list_image as $key => $upload){

                                    //get file extension
                                    $extension = $upload->getClientOriginalExtension();

                                    if(!in_array(strtolower($extension), $allowTypes)){ 
                                        $uploadStatus = false;
                                        break;
                                    }
                                }

                                if($uploadStatus){
                                    
                                    foreach($list_image as $key => $upload){
                                        //get filename with extension
                                        $filenamewithextension = $upload->getClientOriginalName();

                                        //get filename without extension
                                        $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);

                                        //get file extension
                                        $extension = $upload->getClientOriginalExtension();

                                        $name_rand = rand(000,999);
                                        $name_paksa = preg_replace("/[^A-Z0-9._-]/i", "_", $name).'_'.$name_rand;

                                        //filename to store
                                        $filenametostore = $name_paksa.'.'.$extension;

                                        $folderPath = 'tukukabe.id/'.date('Ymd');

                                        //Upload File
                                        $upload->storeAs('public/'.$folderPath, $filenametostore);

                                        $upl = new Uploader($folderPath,$filenametostore);
                                        $upl->save_width(400,'tukukabe-id_');
                                        $upl->delete_real();

                                        $marks = new Watermark();
                                        $marks->create($folderPath.'/','tukukabe-id_'.$filenametostore,'Tengah');
                                    }

                                    $data = [
                                        'respon'    => 'success',
                                        'flag'      => 'insert',
                                        'message'   => 'Images successfully uploaded!',
                                        'url'       => null,
                                    ];
                                }
                                else{
                                    $data = [
                                        'respon'    => 'failed',
                                        'message'   => 'Sorry, only <strong><i>'.implode('/', $allowTypes).'</i></strong> files are allowed to upload.',
                                        'url'       => null,
                                    ];
                                }
                            }
                            else{
                                $data = [
                                    'respon'    => 'failed',
                                    'message'   => 'Image not found.',
                                    'url'       => null,
                                ];
                            }
                        }
                        else{
                            $data = [
                                'respon'    => 'failed',
                                'message'   => 'Image not found',
                                'url'       => null,
                            ];
                        }

                    }

                }

                return response()->json($data);
                
            });

            return $transaction;
        }
        catch(Exception $e){
            throw $e;
        }
    }


}
