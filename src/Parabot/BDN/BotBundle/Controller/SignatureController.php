<?php

namespace Parabot\BDN\BotBundle\Controller;

use AppBundle\Service\StringUtils;
use Parabot\BDN\BotBundle\Entity\Signatures\Types\ImageSignatureType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/signatures")
 *
 * Class SignatureController
 * @package Parabot\BDN\BotBundle\Controller
 */
class SignatureController extends Controller
{
    /**
     * @Route("create", name="create_signature")
     * @Method({"POST"})
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(Request $request)
    {
        /**
         * @var $file File
         */
        $file = $request->files->get('file');
        $filename = StringUtils::generateRandomString().$file->guessExtension();
        $name = $request->request->get('name');

        $image = new ImageSignatureType();
        $image->setName($name);
        $image->insertFile($file, $filename);

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($image);
        $manager->flush();

        return new JsonResponse();
    }
}
