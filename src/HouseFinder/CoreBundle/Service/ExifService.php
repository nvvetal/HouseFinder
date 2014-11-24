<?php

namespace HouseFinder\CoreBundle\Service;

class ExifService
{
    /**
     * @param string $filename
     * @return array|null
     */
    public function getExifData($filename)
    {
        $exifData = NULL;
        list($width, $height, $type) = getimagesize($filename);
        $allowedTypes = array(IMAGETYPE_JPEG, IMAGETYPE_TIFF_II, IMAGETYPE_TIFF_MM);
        $exifData = null;
        if (in_array($type, $allowedTypes)){
            $exifData = exif_read_data($filename);
        }
        return $exifData;
    }

    /**
     * @param $exifCoord
     * @param $hemi
     * @return int
     */
    public function getGPS($exifCoord, $hemi)
    {
        $degrees = count($exifCoord) > 0 ? $this->gps2Num($exifCoord[0]) : 0;
        $minutes = count($exifCoord) > 1 ? $this->gps2Num($exifCoord[1]) : 0;
        $seconds = count($exifCoord) > 2 ? $this->gps2Num($exifCoord[2]) : 0;
        $flip = ($hemi == 'W' or $hemi == 'S') ? -1 : 1;
        return $flip * ($degrees + $minutes / 60 + $seconds / 3600);
    }

    /**
     * @param $coordPart
     * @return float|int
     */
    private function gps2Num($coordPart)
    {
        $parts = explode('/', $coordPart);
        if (count($parts) <= 0)
            return 0;
        if (count($parts) == 1)
            return $parts[0];
        return floatval($parts[0]) / floatval($parts[1]);
    }

    /**
     * @param array $exifData
     * @return array|null
     */
    public function getCoords($exifData)
    {
        $coords = NULL;
        if (!isset($exifData['GPSLongitude']) || !isset($exifData['GPSLatitude'])) return $coords;
        $coords = array(
            'long'  => $this->getGps($exifData['GPSLongitude'], $exifData['GPSLongitudeRef']),
            'lat'   => $this->getGps($exifData['GPSLatitude'], $exifData['GPSLatitudeRef'])
        );
        return $coords;
    }

    /**
     * @param string $filename
     * @return array|null
     */
    public function getCoordsByFile($filename)
    {
        $exifData = $this->getExifData($filename);
        if(is_null($exifData)) return null;
        return $this->getCoords($exifData);
    }
}