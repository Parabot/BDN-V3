<?php
/**
 * @author JKetelaar
 */

namespace Parabot\BDN\BotBundle\Service;

use Parabot\BDN\BotBundle\Entity\Types\Type;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;

class DownloadProvider {

    /**
     * DownloadProvider constructor.
     */
    public function __construct() {

    }

    /**
     * @param Type $type
     *
     * @return bool
     */
    public function provideDownload($type) {
        $file = $type->getFile();
        if(file_exists($file) && is_file($file)) {
            if(ini_get('zlib.output_compression')) {
                ini_set('zlib.output_compression', 'Off');
            }
            $mime = mime_content_type($file);

            $response = new BinaryFileResponse($file);
            $response->headers->set('Pragma', 'public');
            $response->headers->set('Expires', '0');
            $response->headers->set('Cache-Control', 'must-revalidate, post-check=0, pre-check=0');

            $response->headers->set('Last-Modified', gmdate('D, d M Y H:i:s', filemtime($file)) . ' GMT');
            $response->headers->set('Cache-Control', '0');
            $response->headers->set('Expires', 'private');
            $response->headers->set('Content-Type', $mime);
            $response->headers->set('Content-Disposition', 'attachment; filename="' . basename($file) . '"');

            $response->headers->set('Content-Transfer-Encoding', 'binary');
            $response->headers->set('Content-Length', filesize($file));
            $response->headers->set('Connection', 'close');

            return $response;
        }

        return false;
    }
}