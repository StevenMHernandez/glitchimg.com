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
                $printLink = 'http://www.zazzle.com/api/create/at-238499648125991919?rf=238499648125991919&ax=Linkover&pd=' . '190612048809777765' . '&fwd=productpage&tc=pop&ic=' . $filename . '&t_image0_iid=' . $directLink;
                if ($photo->orientation == 'horizontal') {
                    $printLink .= '&size=[' . $d . '%2C' . $d * $photo->ratio . ']';
                } else if ($photo->orientation == 'vertical') {
                    $printLink .= '&size=[' . $d * $photo->ratio . '%2C' . $d . ']';
                }
                return $printLink;
                break;
            case 'zazzle_wrapping_paper' :
                $directLink = config('filesystems.disks.s3.direct_url') . 'full/' . $filename . '.png';
                $printLink = 'http://www.zazzle.com/api/create/at-238499648125991919?rf=238499648125991919&ax=Linkover&pd=' . '256754757427346084' . '&fwd=ProductPage&ed=true&tc=&ic=' . $filename . '&t_image0_iid=' . $directLink;
                return $printLink;
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
                } else {
                    $directLink = config('filesystems.disks.s3.url') . 'preview/' . $filename . '.jpg';
                }
                return 'http://www.tumblr.com/share/photo?source=' . urlencode($directLink) . '&click_thru=' . $route . '&tags=glitch%2Cglitch%20art%2Cglitchimg.com&caption=%3C%2Fbr%3E%0Aimage%20created%20at%20%3Ca%20href%3D%22glitchimg.com%22%3Eglitchimg.com%3C%2Fa%3E.';
                break;
            default:
                return 'Site "' . $site . '" unknown."';
                break;
        }
    }
}