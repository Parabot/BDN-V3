<?php
/**
 * @author JKetelaar
 */

namespace Parabot\BDN\BotBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class TranslationController extends Controller {
    /**
     * @ApiDoc(
     *  description="Returns a translation for a specific language",
     *  requirements={
     *      {
     *          "name"="lang",
     *          "dataType"="string",
     *          "description"="Language to return the translation file from"
     *      }
     *  },
     *  parameters={
     *  }
     * )
     *
     * @Route("/get/{lang}", name="language_download")
     * @Template()
     * @Method({"GET"})
     *
     * @param Request $request
     * @param string  $lang
     *
     * @return JsonResponse
     */
    public function downloadAction(Request $request, $lang) {
        return new JsonResponse($this->get('bot.translation_helper')->returnTranslation($lang));
    }


    /**
     * @ApiDoc(
     *  description="Returns all available translations",
     *  requirements={
     *  },
     *  parameters={
     *  }
     * )
     *
     * @Route("/list", name="language_list")
     * @Template()
     * @Method({"GET"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function listAction(Request $request) {
        return new JsonResponse([ 'languages' => [ 'en' => 'English', 'pt' => 'Portuguese', 'nl' => 'Dutch' ] ]);
    }
}