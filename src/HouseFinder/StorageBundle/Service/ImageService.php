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
    protected $container;

    public function __construct(BaseImage $image, $container)
    {
        $this->image = $image;
        $this->container = $container;
    }

    public function getFileData($entity)
    {
        return $this->image->getFile($entity);
    }

    public function getURL($entity){
        $data = $this->getFileData($entity);
        if(empty($data['path'])) return '';
        $path = explode('/', $data['path']);
        $url = $this->container->get('router')->generate('storage_image_url', array(
            'id'        => $entity->getId(),
            'path1'     => $path[0],
            'path2'     => $path[1],
            'context'   => $data['context'],
            'ext'       => $data['ext'],
        ), true);
        return $url;
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