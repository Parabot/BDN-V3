<?php
/**
 * @author JKetelaar
 */

namespace Parabot\BDN\BotBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Route;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ServerController extends Controller {

    /**
     * @ApiDoc(
     *  description="Returns the requested server information",
     *  requirements={
     *      {
     *          "name"="key",
     *          "dataType"="int",
     *          "description"="ID of the server"
     *      }
     *  },
     *  parameters={
     *  }
     * )
     *
     * @Route("/get/{key}", name="get_server_information")
     * @Method({"GET"})
     *
     * @param Request $request
     * @param string  $key
     *
     * @return JsonResponse
     */
    public function getInformationAction(Request $request, $key) {
        return new JSONResponse([ $key ]);
    }

}