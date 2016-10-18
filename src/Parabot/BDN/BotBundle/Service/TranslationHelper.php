<?php
/**
 * @author JKetelaar
 */

namespace Parabot\BDN\BotBundle\Service;

use Buzz\Client\Curl;
use Buzz\Client\FileGetContents;
use Buzz\Listener\BasicAuthListener;
use Buzz\Message\Request;
use Buzz\Message\Response;
use Doctrine\ORM\EntityManager;
use Parabot\BDN\BotBundle\Entity\Language;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\HttpKernel\KernelInterface;

class TranslationHelper {

    const PATH = '/data/Translations/';
    const HOST = 'http://www.transifex.com';

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var Kernel
     */
    private $kernel;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * TranslationHelper constructor.
     *
     * @param EntityManager   $entityManager
     * @param KernelInterface $kernel
     * @param                 $username
     * @param                 $password
     */
    public function __construct(EntityManager $entityManager, KernelInterface $kernel, $username, $password) {
        $this->entityManager = $entityManager;
        $this->kernel        = $kernel;
        $this->username      = $username;
        $this->password      = $password;
    }

    public function getDownloadTranslation(Language $language) {
        $request = new Request(
            Request::METHOD_GET,
            '/api/2/project/parabot/resource/strings/translation/' . $language->getLanguageKey(
            ) . '/?mode=default&file',
            self::HOST
        );
        $auth    = new BasicAuthListener($this->username, $this->password);
        $auth->preSend($request);

        $response = new Response();

        $client = new FileGetContents();
        $client->send($request, $response);

        $json = json_decode($response->getContent(), true);

        file_put_contents($this->getPath() . $language->getLanguageKey() . '.json', json_encode($json));
    }

    private function getPath() {
        return $this->kernel->getRootDir() . self::PATH;
    }

    /**
     * @return Language[]
     */
    public function listAPITranslations() {
        $request = new Request(Request::METHOD_GET, '/api/2/project/parabot/languages/', self::HOST);
        $auth    = new BasicAuthListener($this->username, $this->password);
        $auth->preSend($request);

        $response = new Response();

        $client = new FileGetContents();
        $client->send($request, $response);

        $json = json_decode($response->getContent(), true);

        $languages = [];
        foreach($json as $lang) {
            $language = new Language();
            $language->setLanguageKey($lang[ 'language_code' ]);
            $language->setLanguage(\Locale::getDisplayName($language->getLanguageKey()));

            $languages[] = $language;
        }

        return $languages;
    }

    public function returnTranslation($key) {
        if(file_exists(($file = $this->getPath() . $key . '.json'))) {
            $content = file_get_contents($file);
            if($content != null && $content != false) {
                return json_decode($content, true);
            }
        }

        return [];
    }
}