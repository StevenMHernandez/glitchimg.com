<?php namespace App\Helpers;

use App\Gifs;
use App\Images;

class UrlGenerator
{
    public static function buildAll($image_id)
    {
        return [
            'glitchimg' => UrlGenerator::build('glitchimg', $image_id),
            'zazzle' => UrlGenerator::build('zazzle', $image_id),
            'zazzle_wrapping_paper' => UrlGenerator::build('zazzle_wrapping_paper', $image_id),
            'twitter' => UrlGenerator::build('twitter', $image_id),
            'tumblr' => UrlGenerator::build('tumblr', $image_id),
            'facebook' => UrlGenerator::build('facebook', $image_id),
        ];
    }

    public static function build($site, $image_id, $type = null)
    {
        if ($type == 'gif') {
            $photo = Gifs::where('id', $image_id)->first();
            $route = route('gifs.show', $photo->filename);
        } else {
            $photo = Images::where('id', $image_id)->first();
            $route = route('photos.show', $photo->filename);
        }

        switch ($site) {
            case 'glitchimg':
                return $route;
                break;
            case 'preview_image':
                return config('filesystems.disks.s3.url') . 'preview/' . $photo->filename . '.jpg';
            case 'full_image':
                return config('filesystems.disks.s3.url') . 'full/' . $photo->filename . '.png';
            case 'gif':
                return config('filesystems.disks.s3.url') . 'gif/' . $photo->filename . '.gif';
            case 'zazzle':
                $d = 10;
                $directLink = config('filesystems.disks.s3.direct_url') . 'full/' . $photo->filename . '.png';
                $printLink = 'http://www.zazzle.com/api/create/at-238499648125991919?rf=238499648125991919&ax=Linkover&pd=' . '190612048809777765' . '&fwd=productpage&tc=pop&ic=' . $photo->filename . '&t_image0_iid=' . $directLink;
                if ($photo->orientation == 'horizontal') {
                    $printLink .= '&size=[' . $d . '%2C' . $d * $photo->ratio . ']';
                } else if ($photo->orientation == 'vertical') {
                    $printLink .= '&size=[' . $d * $photo->ratio . '%2C' . $d . ']';
                }
                return $printLink;
                break;
            case 'zazzle_wrapping_paper' :
                $directLink = config('filesystems.disks.s3.direct_url') . 'full/' . $photo->filename . '.png';
                $printLink = 'http://www.zazzle.com/api/create/at-238499648125991919?rf=238499648125991919&ax=Linkover&pd=' . '256754757427346084' . '&fwd=ProductPage&ed=true&tc=&ic=' . $photo->filename . '&t_image0_iid=' . $directLink;
                return $printLink;
                break;
            case 'facebook':
                return 'https://www.facebook.com/sharer/sharer.php?u=' . $route;
                break;
            case 'twitter':
                return 'https://twitter.com/intent/tweet?url=' . $route . '&hashtags=glitchart,glitch,glitchimg.com&via=glitch_img';
                break;
            case 'tumblr':
                $directLink = config('filesystems.disks.s3.url') . 'preview/' . $photo->filename . '.jpg';
                return 'http://www.tumblr.com/share/photo?source=' . urlencode($directLink) . '&click_thru=' . $route . '&tags=glitch%2Cglitch%20art%2Cglitchimg.com&caption=%3C%2Fbr%3E%0Aimage%20created%20at%20%3Ca%20href%3D%22glitchimg.com%22%3Eglitchimg.com%3C%2Fa%3E.';
                break;
            default:
                return 'Site "' . $site . '" unknown."';
                break;
        }
    }
}