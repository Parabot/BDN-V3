<?php

namespace Parabot\BDN\BotBundle\Controller\Bot;

use JMS\SecurityExtraBundle\Annotation\PreAuthorize;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Parabot\BDN\BotBundle\Entity\Library;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class LibraryController extends Controller {
    /**
     * @ApiDoc(
     *  description="Returns the requested download file",
     *  requirements={
     *      {
     *          "name"="library",
     *          "dataType"="string",
     *          "description"="library to be downloaded"
     *      }
     *  }
     * )
     *
     * @Route("/download/{library}", name="library_download")
     * @Method({"GET"})
     *
     * @param Request $request
     * @param string  $library
     *
     * @return JsonResponse
     */
    public function downloadLibraryAction(Request $request, $library) {
        $manager    = $this->getDoctrine()->getManager();
        $repository = $manager->getRepository('BDNBotBundle:Library');

        if(($libraryObject = $repository->findOneBy([ 'name' => $library ])) != null) {
            $result = $this->get('bot.download_manager')->provideLibraryDownload($libraryObject);
            if($result === false) {
                return new JsonResponse(
                    [
                        'result'     => 'Could not find requested library',
                        'help'       => 'Please ask an administrator to solve this issue',
                        'error_code' => '29GOAFJEAH1',
                    ], 500
                );
            } else {
                return $result;
            }
        } else {
            return new JsonResponse([ 'result' => 'No version of library found' ], 404);
        }
    }

    /**
     * @Route("/add", name="library_add")
     * @Method({"POST"})
     *
     * @PreAuthorize("isAdministrator()")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function addLibraryAction(Request $request) {
        $manager    = $this->getDoctrine()->getManager();
        $repository = $manager->getRepository('BDNBotBundle:Library');

        $libraryAttributes = [
            'version' => 1.0,
            'name'    => null,
        ];

        foreach($libraryAttributes as $key => $libraryAttribute) {
            if(($attribute = $request->request->get($key)) != null) {
                $libraryAttributes[ $key ] = $attribute;
            } else {
                if($libraryAttribute == null) {
                    return new JsonResponse([ 'result' => 'Missing value for parameter (' . $key . ')' ], 400);
                }
            }
        }

        if($repository->findOneBy([ 'name' => $libraryAttributes[ 'name' ] ]) == null) {
            /**
             * @var $libraryFile File
             */
            if(($libraryFile = $request->files->get('library')) != null) {
                if($libraryFile->guessExtension() == 'zip') {
                    $library = new Library();
                    $library->setName($libraryAttributes[ 'name' ]);
                    $library->setVersion($libraryAttributes[ 'version' ]);

                    $manager->persist($library);
                    $manager->flush();

                    $library->insertFile($libraryFile);

                    return new JsonResponse([ 'result' => 'Library added' ]);
                } else {
                    return new JsonResponse([ 'result' => 'Upload may only be a jar' ], 400);
                }
            } else {
                return new JsonResponse([ 'result' => 'Missing library file' ], 400);
            }
        } else {
            return new JsonResponse([ 'result' => 'A library already exists with the same name' ], 400);
        }
    }
}
