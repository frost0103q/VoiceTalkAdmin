<?php
/**
 * Created by PhpStorm.
 * User: paulo
 * Date: 5/17/2017
 * Time: 12:26 AM
 */

namespace App\Models;
use Config;
use Illuminate\Database\Eloquent\Model;

define("TYPE_IMAGE",     0);
define("TYPE_VOICE",     1);

class ServerFile extends Model {
    protected $table = 't_file';

    protected $primaryKey = 'no';


    public function uploadFile($file, $type=TYPE_IMAGE) {
        $imageName = time() . '.' . $file->getClientOriginalExtension();
        $uploadDirectory =  Config::get('config.uploadDirectory');
        $publicDirectory =  Config::get('config.publicDirectory');
        $uploadURL = url('/')."/".$uploadDirectory."/".$imageName;
        $move = $file->move(base_path() ."/".$publicDirectory."/". $uploadDirectory, $imageName);

        if($move != null) {
            $this->path = $uploadURL;
            $this->type = $type;
            $this->save();
            return $this->no;
        }

        return null;
    }
}