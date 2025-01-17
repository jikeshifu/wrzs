<?php


namespace app\common\file;


class FileYs
{
    /**
     * 生成图片
     * @param string $im 源图片路径
     * @param string $dest 目标图片路径
     * @param int $maxwidth 生成图片宽
     * @param int $maxheight 生成图片高
     */
    static function resizeImage($im, $dest, $maxwidth, $maxheight)
    {
        $img = getimagesize($im);
        switch ($img[2]) {
            case 1:
                $im = @imagecreatefromgif($im);
                break;
            case 2:
                $im = @imagecreatefromjpeg($im);
                break;
            case 3:
                $im = @imagecreatefrompng($im);
                break;
        }

        $pic_width = imagesx($im);
        $pic_height = imagesy($im);
        $resizewidth_tag = false;
        $resizeheight_tag = false;
        if (($maxwidth && $pic_width > $maxwidth) || ($maxheight && $pic_height > $maxheight)) {
            if ($maxwidth && $pic_width > $maxwidth) {
                $widthratio = $maxwidth / $pic_width;
                $resizewidth_tag = true;
            }

            if ($maxheight && $pic_height > $maxheight) {
                $heightratio = $maxheight / $pic_height;
                $resizeheight_tag = true;
            }

            if ($resizewidth_tag && $resizeheight_tag) {
                if ($widthratio < $heightratio)
                    $ratio = $widthratio;
                else
                    $ratio = $heightratio;
            }


            if ($resizewidth_tag && !$resizeheight_tag)
                $ratio = $widthratio;
            if ($resizeheight_tag && !$resizewidth_tag)
                $ratio = $heightratio;
            $newwidth = $pic_width * $ratio;
            $newheight = $pic_height * $ratio;

            if (function_exists("imagecopyresampled")) {
                $newim = imagecreatetruecolor($newwidth, $newheight);
                imagecopyresampled($newim, $im, 0, 0, 0, 0, $newwidth, $newheight, $pic_width, $pic_height);
            } else {
                $newim = imagecreate($newwidth, $newheight);
                imagecopyresized($newim, $im, 0, 0, 0, 0, $newwidth, $newheight, $pic_width, $pic_height);
            }

            imagejpeg($newim, $dest);
            imagedestroy($newim);
        } else {
            imagejpeg($im, $dest);
        }
    }

    /**
     * 图片压缩处理
     * @param string $sFile 源图片路径
     * @param int $iWidth 自定义图片宽度
     * @param int $iHeight 自定义图片高度
     * @return string 压缩后的图片路径
     */
    static function getThumb($sFile, $iWidth, $iHeight)
    {
        //图片公共路径
        $public_path = '/www/wwwroot/was.weishequ.com/wrzs_apiserver/public';
        //判断该图片是否存在
        if (!file_exists($public_path . $sFile)) return $sFile;


        //判断图片格式(图片文件后缀)
        $extend = explode(".", $sFile);
        $attach_fileext = strtolower($extend[count($extend) - 1]);
        if (!in_array($attach_fileext, array('jpg','JPG', 'png', 'PNG', 'jpeg'))) {
            return '';
        }
        //压缩图片文件名称
        $sFileNameS = str_replace("." . $attach_fileext, "_" . $iWidth . '_' . $iHeight . '.' . $attach_fileext, $sFile);
        //判断是否已压缩图片，若是则返回压缩图片路径
        if (file_exists($public_path . $sFileNameS)) {
            return $sFileNameS;
        }

        //生成压缩图片，并存储到原图同路径下resizeImage

        self::resizeImage($public_path . $sFile, $public_path . $sFileNameS, $iWidth, $iHeight);
        if (!file_exists($public_path . $sFileNameS)) {
            return $sFile;
        }

        return  $sFileNameS;
    }
}
