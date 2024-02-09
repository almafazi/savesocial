<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class InstagramController extends Controller
{
    public function printMediaInfo(\InstagramScraper\Model\Media $media, $padding = '') {
        return collect([
            "id" => $media->getId(),
            "shortcode" => $media->getShortCode(),
            "created_at" =>$media->getCreatedTime(),
            "caption" => $media->getCaption(),
            "comment_count" => $media->getCommentsCount(),
            "like_count" => $media->getLikesCount(),
            "link" => $media->getLink(),
            "hd_link" => $media->getImageHighResolutionUrl(),
            "type" => $media->getType()
        ]);
    }

    public function post($shortcode) {
        // If account is public you can query Instagram without auth
        $instagram = new \InstagramScraper\Instagram(new \GuzzleHttp\Client());

        $media = $instagram->getMediaByUrl('https://www.instagram.com/p/'.$shortcode);

        $medias = collect();
        
        $medias->push([
            "id" => $media->getId(),
            "shortcode" => $media->getShortCode(),
            "created_at" =>$media->getCreatedTime(),
            "caption" => $media->getCaption(),
            "comment_count" => $media->getCommentsCount(),
            "like_count" => $media->getLikesCount(),
            "link" => $media->getLink(),
            "hd_link" => $media->getImageHighResolutionUrl(),
            "type" => $media->getType()
        ]);

        foreach ($media->getSidecarMedias() as $sidecarMedia) {
            $medias->push([
                "id" => $sidecarMedia->getId(),
                "shortcode" => $sidecarMedia->getShortCode(),
                "created_at" => $sidecarMedia->getCreatedTime(),
                "caption" => $sidecarMedia->getCaption(),
                "comment_count" => $sidecarMedia->getCommentsCount(),
                "like_count" => $sidecarMedia->getLikesCount(),
                "link" => $sidecarMedia->getLink(),
                "hd_link" => $sidecarMedia->getImageHighResolutionUrl(),
                "type" => $sidecarMedia->getType()
            ]);
        }

        $account = $media->getOwner();
        $profile = collect();
        $profile["id"] = $account->getId();
        $profile["username"] = $account->getUsername();
        $profile["full_name"] = $account->getFullName();
        $profile["pp_url"] = $account->getProfilePicUrl();

        $results = collect([
            'profile' => $profile,
            'medias' => $medias
        ]);
        return $results;
    }

    public function fetch(Request $request) {
        $shortcode = preg_match('~(?:https?://)?(?:www\.)?instagram\.com(?:/[^/]+)*/\K\w+~', $request->url , $m) ? $m[0] : '';
        
        $response = Http::get('http://192.53.116.208:5000/fetch-post/'.$shortcode);

        $posts = $response->collect();

        return response()->json($posts);
    }

}
