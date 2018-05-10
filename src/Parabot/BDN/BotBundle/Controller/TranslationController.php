<?php
/**
 * @author JKetelaar
 */

namespace Parabot\BDN\BotBundle\Controller;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/api/bot/translations")
 *
 * Class TranslationController
 * @package Parabot\BDN\BotBundle\Controller
 */
class TranslationController extends Controller
{
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
     * @Route("/get/{lang}", name="translation_get")
     * @Template()
     * @Method({"GET"})
     *
     * @param Request $request
     * @param string $lang
     *
     * @return JsonResponse
     */
    public function getAction(Request $request, $lang)
    {
        $manager = $this->getDoctrine()->getManager();
        $repository = $manager->getRepository('BDNBotBundle:Language');
        if (($language = $repository->findOneBy(['languageKey' => $lang]))) {
            return new JsonResponse(
                $this->get('bot.translation_helper')->returnTranslation($language->getLanguageKey())
            );
        } else {
            return new JsonResponse(['result' => 'Unknown language requested'], 404);
        }
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
     * @Route("/list", name="translations_list")
     * @Template()
     * @Method({"GET"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function listAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $repository = $manager->getRepository('BDNBotBundle:Language');
        $jsonLanguages = [];

        $languages = $repository->findBy(['active' => true]);
        if ($languages != null && count($languages) > 0) {
            foreach ($languages as $language) {
                $jsonLanguages['languages'][$language->getLanguageKey()] = $language->getLanguage();
            }
        }

        return new JsonResponse($jsonLanguages);
    }

    /**
     * @ApiDoc(
     *  description="Downloads and creates all translations",
     *  requirements={
     *  },
     *  parameters={
     *  }
     * )
     *
     * @Route("/create", name="translations_create")
     * @Template()
     * @Method({"GET"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function createAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $repository = $manager->getRepository('BDNBotBundle:Language');

        $translations = $this->get('bot.translation_helper')->listAPITranslations();
        foreach ($translations as $translation) {
            if ($repository->findOneBy(['languageKey' => $translation->getLanguageKey()]) == null) {
                $manager->persist($translation);
            }
        }
        $manager->flush();

        foreach ($repository->findAll() as $language) {
            if ($language->getActive()) {
                $this->get('bot.translation_helper')->getDownloadTranslation($language);
            }
        }

        return new JsonResponse(['result' => 'ok']);
    }
}