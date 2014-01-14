<?php
/**
 * Created by PhpStorm.
 * User: nvvetal
 * Date: 13.01.14
 * Time: 21:40
 */

namespace HouseFinder\StorageBundle\Image;


use Doctrine\ORM\EntityManager;
use Goutte\Client;
use Guzzle\Plugin\Cookie\CookiePlugin;
use Guzzle\Plugin\Cookie\CookieJar\ArrayCookieJar;

abstract class BaseImage implements ImageInterface
{
    protected $client;
    protected $context = NULL;
    protected $container;
    protected $em;

    public function init()
    {
        $this->setContext();
        $this->client = new Client();
        $guzzle = $this->client->getClient();
        $guzzle->setDefaultOption('headers', array(
            'Accept'            => '*/*',
            'Accept-Encoding'   => 'gzip,deflate,sdch',
            'Accept-Language'   => 'ru-RU,ru;q=0.8,en-US;q=0.6,en;q=0.4,de;q=0.2,es;q=0.2,fr;q=0.2,pt;q=0.2,uk;q=0.2,pl;q=0.2',
            'Cache-Control'     => 'max-age=0',
            'Connection'        => 'keep-alive',
        ));
        $guzzle->setUserAgent('Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/32.0.1700.41 Safari/537.38');
        $cookiePlugin = new CookiePlugin(new ArrayCookieJar());
        $guzzle->addSubscriber($cookiePlugin);
    }

    public function fetchTmpFileByURL($url)
    {
        $fileName = NULL;
        try {
            $guzzle = $this->client->getClient();
            $request = $guzzle->get($url);
            $response = $request->send();
            $fileName = tempnam(sys_get_temp_dir(), 'hfImg_');
            file_put_contents($fileName, $response->getBody(true));
        }catch(\Exception $e){

        }
        return $fileName;
    }

    public function getFileExt($fileName)
    {
        if ( ( list($width, $height, $type, $attr) = getimagesize( $fileName ) ) !== false ) {
            switch($type){
                case IMAGETYPE_PNG:
                    return 'png';
                case IMAGETYPE_GIF:
                    return 'gif';
                case IMAGETYPE_JPEG:
                case IMAGETYPE_JPEG2000:
                    return 'jpg';
            }
        }
        return false;
    }

    public function getContext()
    {
        $context = $this->context;
        if(is_null($context)) throw new \Exception('Please set context in child class method setContext');
        return $context;
    }

    /**
     * @param $fileName
     * @param $entity
     * @return bool
     */
    public function saveFile($fileName, $entity)
    {
        try{
            $path = $this->container->getParameter('housefinder.storage.path');
            $imgPath = substr(md5(microtime(true)),0,3).'/'.substr(md5(microtime(true).mt_rand(1000,10000)),0,5);
            $dir = $path.'/'.$this->getContext().'/'.$imgPath;
            if(!is_dir($dir)) mkdir($dir, 0777, true);
            $ext = $this->getFileExt($fileName);
            if(!in_array($ext, array('jpg', 'gif', 'png'))) throw new \Exception('Wrong ext'.$ext);
            $fileNameEntity = $dir.'/'.$entity->getId().'.'.$ext;
            file_put_contents($fileNameEntity, file_get_contents($fileName));
            chmod($fileNameEntity, 0644);
            $entity->setPath($imgPath);
            $entity->setExt($ext);
            $this->em->flush($entity);
        }catch(\Exception $e){
            return false;
        }
        return true;
    }

    /**
     * @param $entity
     * @return array
     */
    public function getFile($entity)
    {
        // TODO: Implement getFile() method.
        return array(
            'path'      => $entity->getPath(),
            'ext'       => $entity->getExt(),
            'id'        => $entity->getId(),
            'context'   => $this->getContext(),
        );
    }


    public function isFilled($entity)
    {
        $path = $entity->getPath();
        $ext = $entity->getExt();
        return !empty($path) && !empty($ext) ? true : false;
    }
}