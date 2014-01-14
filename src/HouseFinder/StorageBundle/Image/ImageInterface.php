<?php
/**
 * Created by PhpStorm.
 * User: nvvetal
 * Date: 13.01.14
 * Time: 21:30
 */

namespace HouseFinder\StorageBundle\Image;

interface ImageInterface {
    public function getFile($entity);
    public function saveFile($fileName, $entity);
    public function setContext();
    public function isFilled($entity);
}