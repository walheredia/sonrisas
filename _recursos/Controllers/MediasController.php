<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use FFMpeg\FFMpeg;
use FFMpeg\FFProbe;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Medias;

class MediasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $medias = Medias::paginate();

        return view('medias/index', compact('medias'));
    }

    public function add() {
    	return view('medias/add');
    }

    public function upload() {
    	$response = array(
            'success' => true,
            'error' => array(),
            'status' => 'File uploaded correctly',
            'type' => 'ok',
            'path' => null,
        );

        $fd = fopen("php://input", "r");

        $file_name = $_GET['file_name'];
        $file_size = $_GET['file_size'];

        $file_type = $_GET['file_type'];
        $file_type = explode("/", $file_type);
        $file_type = $file_type[0];

        $path = 'uploads/';
        if ($file_type == 'video') {
            $path .= 'videos';
        } elseif ($file_type == 'audio') {
            $path .= 'audios';
        } elseif ($file_type == 'image') {
            $path .= 'images';
        } else {
            $path .= 'files';
        }

        $num = 0;

        $allowedExts = array("jpg", "jpeg", "png", "gif", "mp3", "webm", "ogg", "wav", "mp4");
        $temp = explode(".", $file_name);
        $extension = end($temp);

        $allowedTypes = array('image', 'video', 'audio');

        if (!in_array($file_type, $allowedTypes)) {
            $response['success'] = false;
            $response['error'][] = 'Type file not allowed';
            $response['status'] = 'An error was ocurred';
            $response['type'] = 'critical';
        }

        if (!in_array($extension, $allowedExts)) {
            $response['success'] = false;
            $response['error'][] = 'Extension file not allowed';
            $response['status'] = 'An error was ocurred';
            $response['type'] = 'critical';
        }

        if ((int)$file_size > 102400) {
            $response['success'] = false;
            $response['error'][] = 'File size should be less than 100 kB';
            $response['status'] = 'An error was ocurred';
            $response['type'] = 'critical';
        }

        if ($response['success']) {
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            if (isset($_GET['path']) && $_GET['path'] != '' && $_GET['path'] != 'null') {
                $filePath = $_GET['path'];
            } else {
                $filePath = $this->checkIfFileExist($path, $file_name, $num);
            }

            while ($data = fread($fd, 10000000)) {
                file_put_contents($path.'/'.$filePath, $data, FILE_APPEND);
            }

            $response['path'] = $filePath;
            $response['type'] = $file_type;
        }

        echo json_encode($response);
    }

    public function uploaded() {
        $data = request()->all();
        $form = array();

        $url = 'uploads/';
        if ($data['type'] == 'image') {
            $url .= 'images/';
        } elseif ($data['type'] == 'video') {
            $url .= 'videos/';
        } elseif ($data['type'] == 'audio') {
            $url .= 'audios/';
        }
        $url .= $data['file_name'];

        $data['url'] = $url;
        $thumbnailUrl = null;

        if ($data['type'] == 'video') {
            $thumbnailName = preg_replace('/\\.[^.\\s]{3,4}$/', '', $data['file_name']);
            $thumbnailUrl = 'uploads/thumbnails/'.$thumbnailName.'.jpg';

            $ffmpeg = FFMpeg::create();
            $video = $ffmpeg->open($url);
            $video
                ->frame(\FFMpeg\Coordinate\TimeCode::fromSeconds(1))
                ->save($thumbnailUrl);

            $ffprobe = FFProbe::create();
            $videoDuration = $ffprobe
                ->streams($url) // extracts streams informations
                ->videos()                      // filters video streams
                ->first()                       // returns the first video stream
                ->get('duration'); 
        }

        $data = array(
            'type' => $data['type'],
            'url' => $url,
            'duration' => ($data['type'] == 'video') ? $videoDuration : null, 
            'thumbnail' => $thumbnailUrl,
        );

        Medias::create($data);
    }

    public function delete($id) {
        $media = Medias::findOrFail($id);

        unlink($media->url);
        $media->delete();

        return redirect()->to('medias');
    }

    public function images() {
        $medias = Medias::where('type', '=', 'image')->get();

        return view('medias/images', compact('medias'));
    }

    public function all() {
        $medias = Medias::all();

        return view('medias/all', compact('medias'));
    }

    public function uploadedImage() {
        $data = request()->all();
        $form = array();

        $url = 'uploads/'.$data['type'].'s/'.$data['file_name'];
        $thumbnailUrl = null;

        if ($data['type'] == 'video') {
            $thumbnailName = preg_replace('/\\.[^.\\s]{3,4}$/', '', $data['file_name']);
            $thumbnailUrl = 'uploads/thumbnails/'.$thumbnailName.'.jpg';

            $ffmpeg = FFMpeg::create();
            $video = $ffmpeg->open($url);
            $video
                ->frame(\FFMpeg\Coordinate\TimeCode::fromSeconds(1))
                ->save($thumbnailUrl);

            $ffprobe = FFProbe::create();
            $videoDuration = $ffprobe
                ->streams($url) // extracts streams informations
                ->videos()                      // filters video streams
                ->first()                       // returns the first video stream
                ->get('duration'); 

            $data = array(
                'type' => $data['type'],
                'url' => $url,
                'duration' => ($data['type'] == 'video') ? $videoDuration : null, 
                'thumbnail' => $thumbnailUrl,
            );
        } else {
            $data = array(
                'type' => $data['type'],
                'url' => url($url),
            );
        }

        $image = Medias::create($data);
        $image['url'] = $image['url'];

        echo json_encode($image);
    }

    public function checkIfFileExist($path, $fileName, $num, $originalName = null) {
        if (file_exists($path.'/'.$fileName)) {
            $temp = explode(".", $fileName);
            $extension = end($temp);

            $name = $temp[0];
            for ($i = 1; $i < (count($temp) - 1); $i++) {
                $name .= '.'.$temp[$i];
            }

            $num++;
            $newFileName = $name.'('.$num.').'.$extension;
            if ($originalName != null) {
                $newFileName = $originalName.'('.$num.').'.$extension;
            } else {
                $originalName = $name;
            }

            $fileName = $this->checkIfFileExist($path, $newFileName, $num, $originalName);
        }
        
        return $fileName;
    }
}
