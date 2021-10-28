<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\UserSiteArticle;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class WordPressFetchController extends Controller
{
    //

    // public function index()
    // {

    // }

    public static function check($url)
    {
        try {
            $result = Http::get($url . '/wp-json');
            if (!isset($result['name'])) {
                return false;
            } else {
                return $result['name'];
            }
        } catch (Exception $e) {
            return false;
        }
    }

    public static function fetch($user_id, $url)
    {
        $url .= '/wp-json/wp/v2/posts?per_page=100&page=1';
        $result = Http::get($url)->json();

        foreach ($result as $array) {
            $title = $array['title']['rendered'];
            $date = str_replace("T", " ", $array['date']);
            $excerpt = $array['excerpt']['rendered'];
            $link = $array['link'];


            $userSiteArticle = new UserSiteArticle();
            if (!$userSiteArticle->where('link', $link)->exists()) {
                $userSiteArticle->title = $title;
                $userSiteArticle->description = $excerpt;
                $userSiteArticle->link = $link;
                $userSiteArticle->datetime = $date;
                $userSiteArticle->user_id = $user_id;
                $userSiteArticle->save();
            }
        }
    }
}
