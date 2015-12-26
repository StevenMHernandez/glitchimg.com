<?php namespace App\Helpers;

use App\Images;

class UrlGenerator
{
    public static function buildAll($filename)
    {
        return [
            'glitchimg' => UrlGenerator::build('glitchimg', $filename),
            'zazzle' => UrlGenerator::build('zazzle', $filename),
            'zazzle_wrapping_paper' => UrlGenerator::build('zazzle_wrapping_paper', $filename),
            'twitter' => UrlGenerator::build('twitter', $filename),
            'tumblr' => UrlGenerator::build('tumblr', $filename),
            'facebook' => UrlGenerator::build('facebook', $filename),
        ];
    }

    public static function build($site, $filename, $type = null)
    {
        if ($type == 'gif') {
            $route = route('gifs.show', $filename);
        } else {
            $route = route('photos.show', $filename);
        }

        switch ($site) {
            case 'glitchimg':
                return $route;
                break;
            case 'preview_image':
                return config('filesystems.disks.s3.url') . 'preview/' . $filename . '.jpg';
            case 'full_image':
                return config('filesystems.disks.s3.url') . 'full/' . $filename . '.png';
            case 'gif':
                return config('filesystems.disks.s3.url') . 'gif/' . $filename . '.gif';
            case 'zazzle':
                $photo = Images::where('filename', $filename)->first();
                $d = 10;
                $directLink = config('filesystems.disks.s3.direct_url') . 'full/' . $filename . '.png';
                $query_params = [
                    'rf' => '238499648125991919', //affiliate
                    'ax' => 'Linkover',
                    'pd' => '190612048809777765', //product id
                    'fwd' => 'productpage',
                    'tc' => 'pop',
                    'ic' => $filename,
                    't_image0_iid' => $directLink
                ];
                if ($photo->orientation == 'horizontal') {
                    $printLink['size'] = '[' . $d . '%2C' . $d * $photo->ratio . ']';
                } else if ($photo->orientation == 'vertical') {
                    $printLink['size'] = '[' . $d * $photo->ratio . '%2C' . $d . ']';
                }
                $printLink = http_build_query($query_params);
                $printLink = 'http://www.zazzle.com/api/create/at-238499648125991919?' . $printLink;
                return $printLink;
                break;
            case 'zazzle_wrapping_paper' :
                $directLink = config('filesystems.disks.s3.direct_url') . 'full/' . $filename . '.png';
                $printLink = http_build_query([
                    'rf' => '238499648125991919', //affiliate
                    'ax' => 'Linkover',
                    'pd' => '256754757427346084', //product id
                    'ed' => 'true',
                    'fwd' => 'productpage',
                    'tc' => '',
                    'ic' => $filename,
                    't_image0_iid' => $directLink
                ]);
                return 'http://www.zazzle.com/api/create/at-238499648125991919?' . $printLink;
                break;
            case 'facebook':
                return 'https://www.facebook.com/sharer/sharer.php?u=' . $route;
                break;
            case 'twitter':
                return 'https://twitter.com/intent/tweet?url=' . $route . '&hashtags=glitchart,glitch,glitchimg.com&via=glitch_img';
                break;
            case 'tumblr':
                if ($type == 'gif') {
                    $directLink = config('filesystems.disks.s3.url') . 'gif/' . $filename . '.gif';
                    $caption = 'gif created at <a href="http://glitchimg.com">glitchimg</a>';
                    $tags = 'glitch, glitch art, glitch gif, gif, glitchimg.com';
                } else {
                    $directLink = config('filesystems.disks.s3.url') . 'preview/' . $filename . '.jpg';
                    $caption = 'image created at <a href="http://glitchimg.com">glitchimg</a>';
                    $tags = 'glitch, glitch art, glitchimg.com';
                }
                $url = http_build_query([
                    'source' => $directLink,
                    'click_thru' => $route,
                    'tags' => $tags,
                    'caption' => $caption
                ]);
                $url = 'http://www.tumblr.com/share/photo?' . $url;
                return $url;
                break;
            default:
                return 'Site "' . $site . '" unknown."';
                break;
        }
    }
}