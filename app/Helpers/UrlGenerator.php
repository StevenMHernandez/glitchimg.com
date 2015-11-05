<?php namespace App\Helpers;

use App\Images;

class UrlGenerator {
    public static function buildAll($image_id) {
        return [
            'glitchimg' => UrlGenerator::build('glitchimg', $image_id),
            'zazzle' => UrlGenerator::build('zazzle', $image_id),
            'twitter' => UrlGenerator::build('twitter', $image_id),
            'tumblr' => UrlGenerator::build('tumblr', $image_id),
            'facebook' => UrlGenerator::build('facebook', $image_id),
        ];
    }

    public static function build($site, $image_id) {
        $photo = Images::where('id', $image_id)->first();
        switch ($site) {
            case 'glitchimg':
                return route('photos.show', $photo->filename);
                break;
            case 'zazzle':
                $d = 10;
                $directLink = config('filesystems.disks.s3.direct_url') . 'full/' . $photo->filename . '.png';
                $printLink = 'http://www.zazzle.com/api/create/at-238499648125991919?rf=238499648125991919&ax=Linkover&pd=' . '190612048809777765' . '&fwd=productpage&tc=pop&ic=imagePage&t_image0_iid=' . $directLink;
                if ($photo->orientation == 'horizontal') {
                    $printLink .= '&size=[' . $d . '%2C' . $d * $photo->ratio . ']';
                } else if ($photo->orientation == 'vertical') {
                    $printLink .= '&size=[' . $d * $photo->ratio . '%2C' . $d . ']';
                }
                return $printLink;
                break;
            case 'facebook':
                return 'https://www.facebook.com/sharer/sharer.php?u=' . route('photos.show', $photo->filename);
                break;
            case 'twitter':
                return 'https://twitter.com/intent/tweet?url=' . route('photos.show', $photo->filename) . '&hashtags=glitchart,glitch,glitchimg.com&via=glitch_img';
                break;
            case 'tumblr':
                $directLink = config('filesystems.disks.s3.url') . 'preview/' . $photo->filename . '.jpg';
                return 'http://www.tumblr.com/share/photo?source=' . urlencode($directLink) . '&click_thru=' . route('photos.show', $photo->filename) . '&tags=glitch%2Cglitch%20art%2Cglitchimg.com&caption=%3C%2Fbr%3E%0Aimage%20created%20at%20%3Ca%20href%3D%22glitchimg.com%22%3Eglitchimg.com%3C%2Fa%3E.';
                break;
            default:
                return 'Site "' . $site . '" unknown."';
                break;
        }
    }
}