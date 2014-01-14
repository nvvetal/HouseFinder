<?php
/**
 * Created by PhpStorm.
 * User: nvvetal
 * Date: 13.01.14
 * Time: 21:42
 */

namespace HouseFinder\StorageBundle\Service;

use HouseFinder\StorageBundle\Image\BaseImage;

class ImageService
{
    protected $image;

    public function __construct(BaseImage $image)
    {
        $this->image = $image;
    }

    public function getFileData($entity)
    {
        return $this->image->getFile($entity);
    }

    public function saveFileByURL($url, $entity)
    {
        try {
            $fileName = $this->image->fetchTmpFileByURL($url);
            $this->image->saveFile($fileName, $entity);
        }catch(\Exception $e){
            return false;
        }
        return true;
    }

    public function isFilled($entity)
    {
        return $this->image->isFilled($entity);
    }
} 