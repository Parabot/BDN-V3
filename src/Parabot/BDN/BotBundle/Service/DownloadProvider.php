<?php
/**
 * @author JKetelaar
 */

namespace Parabot\BDN\BotBundle\Service;

use Parabot\BDN\BotBundle\Entity\Library;
use Parabot\BDN\BotBundle\Entity\Script;
use Parabot\BDN\BotBundle\Entity\Scripts\Release;
use Parabot\BDN\BotBundle\Entity\Servers\Server;
use Parabot\BDN\BotBundle\Entity\Types\Type;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DownloadProvider {

    /**
     * DownloadProvider constructor.
     */
    public function __construct() {

    }

    /**
     * @param Type $type
     *
     * @return bool|BinaryFileResponse
     */
    public function provideDownload($type) {
        $file = $type->getFile();

        return $this->provideFileDownload($file, $type->getType());
    }

    /**
     * @param string $file
     * @param string $name
     *
     * @return bool|BinaryFileResponse
     */
    public function provideFileDownload($file, $name) {
        if(file_exists($file) && is_file($file)) {
            if(ini_get('zlib.output_compression')) {
                ini_set('zlib.output_compression', 'Off');
            }

            $response = new BinaryFileResponse($file);
            $response->headers->set('Pragma', 'public');
            $response->headers->set('Expires', '0');
            $response->headers->set('Cache-Control', 'must-revalidate, post-check=0, pre-check=0');

            $response->headers->set('Last-Modified', gmdate('D, d M Y H:i:s', filemtime($file)) . ' GMT');
            $response->headers->set('Cache-Control', '0');
            $response->headers->set('Expires', 'private');
            $response->headers->set('Content-Type', 'application/java-archive');
            $response->headers->set(
                'Content-Disposition',
                'attachment; filename="' . $name . '-' . basename($file) . '"'
            );

            $response->headers->set('Content-Transfer-Encoding', 'binary');
            $response->headers->set('Content-Length', filesize($file));
            $response->headers->set('Connection', 'close');

            return $response;
        }

        return false;
    }

    public function provideLibraryDownload(Library $library) {
        $file = $library->getAbsolutePath();

        return $this->provideFileDownload($file, $library->getName());
    }

    public function provideServerDownload(Server $server){
        $file = $server->getAbsolutePath();

        return $this->provideFileDownload($file, $server->getName());
    }

    public function provideScriptDownload(Script $script, Release $version) {
        $file = $script->getPath() . $version->getVersion() . '.jar';

        return $this->provideFileDownload($file, $script->getName());
    }
}