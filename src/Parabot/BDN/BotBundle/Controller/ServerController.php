<?php
/**
 * @author JKetelaar
 */

namespace Parabot\BDN\BotBundle\Controller;

use AppBundle\Service\SerializerManager;
use FOS\RestBundle\Controller\Annotations\Route;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Parabot\BDN\BotBundle\Entity\Servers\Server;
use Parabot\BDN\BotBundle\Entity\Servers\ServerDetail;
use Parabot\BDN\UserBundle\Entity\Group;
use Parabot\BDN\UserBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ServerController extends Controller {

    /**
     * @ApiDoc(
     *  description="Inserts a server into the database",
     *  requirements={
     *      {
     *          "name"="name",
     *          "dataType"="string",
     *          "description"="Name of the server"
     *      },
     *      {
     *          "name"="active",
     *          "dataType"="boolean",
     *          "description"="Define if the server should be active"
     *      },
     *      {
     *          "name"="groups",
     *          "dataType"="array",
     *          "description"="Array of the group ids that may access the server, delimited with a comma"
     *      },
     *      {
     *          "name"="authors",
     *          "dataType"="array",
     *          "description"="Array of the usernames that have made this server possible, delimited with a comma"
     *      },
     *      {
     *          "name"="details",
     *          "dataType"="string",
     *          "description"="JSON object of the server details"
     *      },
     *      {
     *          "name"="version",
     *          "dataType"="float",
     *          "description"="Version of the server"
     *      },
     *      {
     *          "name"="description",
     *          "dataType"="string",
     *          "description"="Description of the server"
     *      }
     *  },
     *  parameters={
     *  }
     * )
     *
     * @Route("/insert", name="insert_server")
     * @Method({"POST"})
     *
     * @PreAuthorize("isServerDeveloper()")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function insertServerAction(Request $request) {
        $response         = new JsonResponse();
        $groupRepository  = $this->getDoctrine()->getRepository('BDNUserBundle:Group');
        $userRepository   = $this->getDoctrine()->getRepository('BDNUserBundle:User');
        $serverRepository = $this->getDoctrine()->getRepository('BDNBotBundle:Servers\Server');

        $server = [
            'name'        => '',
            'active'      => '',
            'groups'      => '',
            'authors'     => '',
            'details'     => '',
            'version'     => '',
            'description' => '',
        ];
        foreach($server as $key => $value) {
            if(($value = $request->request->get($key)) != null && strlen($value) > 0) {
                $server[ $key ] = $value;
            } else {
                $response->setData([ 'result' => 'Missing value for ' . $key ])->setStatusCode(400);

                return $response;
            }

            switch($key) {
                case 'name':
                    if($serverRepository->findOneBy([ 'name' => $value ]) != null) {
                        return $response->setData(
                            [ 'result' => 'There is already a server that is named like this' ]
                        )->setStatusCode(400);
                    }
                    break;
                case 'details':
                    $details = json_decode($value, true);
                    foreach(ServerDetail::DEFAULT_DETAILS as $detail) {
                        if( ! isset($details[ $detail ]) || $details[ $detail ] == null) {
                            $response->setData([ 'result' => 'Missing value for detail ' . $detail ])->setStatusCode(
                                400
                            );

                            return $response;
                        }
                    }
                    break;
            }
        }

        $serverObject = new Server();
        $serverObject->setName($server[ 'name' ]);
        $serverObject->setActive(boolval($server[ 'active' ]));
        $serverObject->setDescription($server[ 'description' ]);
        $serverObject->setVersion(floatval($server[ 'version' ]));

        $groups = [];
        foreach(explode(',', $server[ 'groups' ]) as $item) {
            $result = $groupRepository->findOneBy([ 'id' => $item ]);
            if($result != null) {
                $groups[] = $result;
            }
        }
        $serverObject->setGroups($groups);

        $authors = [];
        foreach(explode(',', $server[ 'authors' ]) as $item) {
            $result = $userRepository->findOneBy([ 'username' => $item ]);
            if($result != null) {
                $authors[] = $result;
            }
        }
        $serverObject->setAuthors($authors);

        $details = [];
        $json    = json_decode($server[ 'details' ], true);
        foreach($json as $key => $value) {
            $detail = new ServerDetail();

            $detail->setName($key);
            $detail->setValue($value);
            $details[] = $detail;

            $this->getDoctrine()->getManager()->persist($detail);
        }
        $serverObject->setDetails($details);

        $this->getDoctrine()->getManager()->persist($serverObject);
        $this->getDoctrine()->getManager()->flush();

        return $response->setData([ 'result' => 'Server inserted' ]);
    }

    /**
     * @ApiDoc(
     *  description="Lists all possible servers for the logged in user",
     *  requirements={
     *  },
     *  parameters={
     *  }
     * )
     *
     * @Route("/list", name="list_servers")
     * @Method({"GET"})
     *
     * @PreAuthorize("isNotBanned()")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function listServersAction(Request $request) {
        $serverRepository = $this->getDoctrine()->getRepository('BDNBotBundle:Servers\Server');
        $servers          = $serverRepository->findForUser($this->getUser());

        return new JsonResponse(SerializerManager::normalize($servers));
    }

    /**
     * @Route("/hooks/{id}", name="list_servers")
     * @Method({"GET"})
     *
     * @PreAuthorize("isNotBanned()")
     *
     * @param Request $request
     * @param int     $id
     *
     * @return JsonResponse
     */
    public function getHooksAction(Request $request, $id) {
        $response         = new JsonResponse();
        $serverRepository = $this->getDoctrine()->getRepository('BDNBotBundle:Servers\Server');
        $xmlFormat        = (($format = $request->query->get('format')) != null && $format == 'xml') ? true : false;
        $version          = ($version = $request->query->get('version')) != null ? floatval($version) : null;

        /**
         * @var Server $server
         */
        $server = $serverRepository->findOneBy([ 'id' => $id ]);
        if($server != null) {
            if($serverRepository->hasAccess($this->getUser(), $server->getId())) {
                $hooks = $this->get('bot.servers.hook_manager')->createHookArray($server, $version);
                if($xmlFormat !== true) {
                    $response->setData([ 'hooks' => $hooks ]);
                } else {
                    $response = new Response();
                    $response->headers->set('Content-Type', 'xml');
                    $response->setContent($this->get('bot.servers.hook_manager')->hookArrayToXML($hooks));
                }
            } else {
                $response->setData([ 'result' => 'User does not have access to this server', 403 ]);
            }
        }

        return $response;
    }

    /**
     * @ApiDoc(
     *  description="Inserts the hooks file content into the database",
     *  requirements={
     *      {
     *          "name"="xml",
     *          "dataType"="string",
     *          "description"="XML content of the hooks"
     *      },
     *      {
     *          "name"="id",
     *          "dataType"="int",
     *          "description"="ID of the server"
     *      }
     *  },
     *  parameters={
     *  }
     * )
     *
     * @Route("/process/hooks", name="process_server_hooks")
     * @Method({"POST"})
     *
     * @PreAuthorize("isServerDeveloper()")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function hooksFileToDatabaseAction(Request $request) {
        /**
         * @var Server $server
         */
        $server          = $this->getDoctrine()->getRepository('BDNBotBundle:Servers\Server')->findOneBy(
            [ 'id' => $request->request->get('id') ]
        );
        $response        = new JsonResponse();
        $hooksRepository = $this->getDoctrine()->getRepository('BDNBotBundle:Servers\Hook');

        if($server != null) {
            if(count($hooksRepository->findHooksByServer($server)) < 15) {
                if($request->request->get('xml') != null) {
                    if(substr($request->request->get('xml'), 0, 5) === '<?xml') {
                        $xml   = str_replace([ "\t", "\n" ], '', $request->request->get('xml'));
                        $xml   = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
                        $array = json_decode(json_encode($xml), true);

                        $hooks = $this->get('bot.servers.hook_manager')->toHookType($array);

                        foreach($hooks as $hook) {
                            $hook->setVersion($server->getVersion());
                            $hook->setServer($server);

                            $this->getDoctrine()->getManager()->persist($hook);
                        }

                        $this->getDoctrine()->getManager()->flush();

                        $response->setData([ 'result' => 'Hooks inserted' ]);
                    } else {
                        $response->setData(
                            [ 'result' => 'Incorrect XML data given, does not seem to be an XML data type' ]
                        );
                        $response->setStatusCode(500);
                    }
                } else {
                    $response->setData([ 'result' => 'XML parameter not filled' ]);
                    $response->setStatusCode(500);
                }
            } else {
                $response->setData([ 'result' => 'Latest server version already has more than 15 hooks' ]);
                $response->setStatusCode(401);
            }
        } else {
            $response->setData([ 'result' => 'Server ID given is not a valid server ID' ]);
            $response->setStatusCode(404);
        }

        return $response;
    }

    /**
     * @ApiDoc(
     *  description="Returns the requested server information",
     *  requirements={
     *      {
     *          "name"="id",
     *          "dataType"="int",
     *          "description"="ID of the server"
     *      }
     *  },
     *  parameters={
     *  }
     * )
     *
     * @Route("/get/{id}", name="get_server_information")
     * @Method({"GET"})
     *
     * @PreAuthorize("isNotBanned()")
     *
     * @param Request $request
     * @param int     $id
     *
     * @return JsonResponse
     */
    public function getInformationAction(Request $request, $id) {
        $response = new JsonResponse();

        /**
         * @var User $user
         */
        $user = $this->getUser();
        $id   = intval($id);

        $repository = $this->getDoctrine()->getRepository('BDNBotBundle:Servers\Server');
        $server     = $repository->findById($id);

        if($server != null) {
            $allowed = false;
            if($server->getGroups() == null || count($server->getGroups()) <= 0) {
                $allowed = true;
            } else {
                foreach($server->getGroups() as $group) {
                    if($user->hasGroupId($group->getId())) {
                        $allowed = true;
                    }
                }
            }

            if($allowed !== true) {
                $response->setData([ 'result' => 'User does not have enough permission to access this page' ]);
                $response->setStatusCode(403);
            } else {
                $response->setData([ 'result' => SerializerManager::normalize($server) ]);
            }
        } else {
            $response->setData([ 'result' => 'Unknown server ID requested' ]);
            $response->setStatusCode(404);
        }

        return $response;
    }

}