<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LogController extends Controller
{

    public function login (Request $req) {

        // Validate request
        $validator = Validator::make($req->all(),[
            'username'  => 'required',
            'password'  => 'required'
        ]);

        // return error massege if validator falis
        if ($validator->fails())
            return response()->json([
                "status" => false,
                "error"  => $validator->errors()
            ], 401);

        // Check account credentials
        if ( $req->username !== 'admin' || $req->password !== 'admin' )
            return response()->json([
                "status" => false,
                "errors"  => [
                    "credentials" => "Wrong credentials"
                ]
            ], 401);

        // Return response
        return response()->json([
            "status" => true,
            "token"  => env('APP_TOKEN')
        ]);
    }

     public function formatBytes($bytes, $precision = 2) {
         $units = ["b", "kb", "mb", "gb", "tb"];

         $bytes = max($bytes, 0);
         $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
         $pow = min($pow, count($units) - 1);

         $bytes /= (1 << (10 * $pow));

         return round($bytes, $precision) . " " . $units[$pow];
     }

    public function string_gets(string $source, int $res_page = 1) {
        // Get file
        $file = new \SplFileObject($source);

        // Get Total lines number
        $file->seek($file->getSize());
        $linesTotal = $file->key();

        $limit = 10;

        // How many pages will there be
        $pages = round($linesTotal / $limit);

        // What page are we currently on?
        $page = $res_page;
        $data['current_page'] = $page;

        // Calculate the offset
        $offset = ($page - 1)  * $limit;
        $data['offset'] = $offset;

        // The "first" page
        $data['first_page_url'] = '?page=1';

        // The "prev" page
        $data['prev_page_url'] = ($page > 1) ? '?page=1' : '?page=' . ($page - 1);

        // The "next" page
        $data['next_page_url'] = ($page < $pages) ? '?page=' . ($page + 1) : null;

        // The "last" page
        $data['last_page_url'] = '?page=' . $pages;

        for ($i = 0; $i < $limit; $i ++ ) {
            $file->seek($offset + $i);
            $file->next();
            if ($file->current());
                $data['data'][] = $file->current();
        }
        return $data;
    }

    public function getFile ( Request $req ) {

        $path = $req->path;

        // Check if file exist
        if ( !file_exists($path) )
            return response()->json([
                "status" => false,
                "errors" => [
                    "exist" => "File Not Found."
                ]
            ], 404);

        // Check File Permission
        if ( ! is_readable($path) )
            return response()->json([
                "status" => false,
                "errors" => [
                    "readable" => "This file is not readable."
                ]
            ], 403);

        // Check for paginating return first page
        $page = $req->page ? $req->page : 1;

        return response()->json([
            "status" => true,
            "file"   => $this->string_gets($path, $page)
        ]);
    }
}
