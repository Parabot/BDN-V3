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
     * @Route("/create", name="create_server")
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
        $manager          = $this->getDoctrine()->getManager();

        $serverAttributes = [
            'name'        => null,
            'active'      => true,
            'groups'      => [],
            'authors'     => [ $this->get('request_access_evaluator')->getUser()->getUsername() ],
            'details'     => null,
            'version'     => 1.0,
            'description' => null,
        ];

        foreach($serverAttributes as $attribute => $value) {
            $temp = $request->request->get($attribute);
            if($temp !== null) {
                $serverAttributes[ $attribute ] = $temp;
            } elseif($value !== null) {
                $serverAttributes[ $attribute ] = $value;
            } else {
                return new JsonResponse([ 'result' => 'Missing attribute (' . $attribute . ')' ], 400);
            }
        }

        $server = new Server();

        if(($name = $serverAttributes[ 'name' ]) !== null) {
            if($serverRepository->findOneBy([ 'name' => $name ]) != null) {
                return $response->setData(
                    [ 'result' => 'There is already a server that is named like this' ]
                )->setStatusCode(400);
            } else {
                $server->setName($name);
            }
        }

        $matching = 0;
        foreach(ServerDetail::DEFAULT_DETAILS as $detail) {
            foreach($serverAttributes[ 'details' ] as $serverAttributeDetail) {
                if($serverAttributeDetail[ 'name' ] == $detail) {
                    if($serverAttributeDetail[ 'value' ] != null) {
                        $matching++;
                    } else {
                        $response->setData([ 'result' => 'Missing value for detail ' . $detail ])->setStatusCode(
                            400
                        );

                        return $response;
                    }
                }
            }
        }

        if($matching !== count(ServerDetail::DEFAULT_DETAILS)) {
            return new JsonResponse(
                [
                    'result' => 'There are missing required server details (' . implode(
                            ', ',
                            ServerDetail::DEFAULT_DETAILS
                        ) . ')',
                ], 400
            );
        }

        $details = [];
        foreach($serverAttributes[ 'details' ] as $serverAttributeDetail) {
            $name  = $serverAttributeDetail[ 'name' ];
            $value = $serverAttributeDetail[ 'value' ];

            $d = new ServerDetail();
            $d->setName($name);
            $d->setValue($value);

            $manager->persist($d);

            $details[] = $d;
        }

        $server->setDetails($details);

        if($serverAttributes[ 'active' ] !== null) {
            $server->setActive(boolval($serverAttributes[ 'active' ]));
        }

        if($serverAttributes[ 'description' ] !== null) {
            $server->setDescription($serverAttributes[ 'description' ]);
        }

        if($serverAttributes[ 'version' ] !== null) {
            $server->setVersion($serverAttributes[ 'version' ]);
        }

        if(($authors = $serverAttributes[ 'authors' ]) !== null && count($authors) > 0) {
            /**
             * @var User[] $serverAuthors
             */
            $serverAuthors = [];
            foreach($authors as $author) {
                $a = $userRepository->findOneBy([ 'username' => $author[ 'username' ] ]);
                if($a != null) {
                    if($this->get('request_access_evaluator')->isServerDeveloper($a)) {
                        $serverAuthors[] = $a;
                    } else {
                        return new JsonResponse(
                            [ 'result' => 'Given user (' . $author[ 'username' ] . ') is not a server developer' ], 400
                        );
                    }
                } else {
                    return new JsonResponse(
                        [ 'result' => 'Given user (' . $author[ 'username' ] . ') is unknown' ], 400
                    );
                }
            }

            $server->setAuthors($serverAuthors);
        }

        if(($groups = $serverAttributes[ 'groups' ]) !== null && count($groups) > 0) {
            $serverGroups = [];
            foreach($groups as $group) {
                $g = $groupRepository->findOneBy([ 'id' => $group[ 'id' ] ]);

                if($g != null) {
                    $serverGroups[] = $g;
                } else {
                    return new JsonResponse(
                        [ 'result' => 'Unknown group ID given (' . $group[ 'id' ] . ')', 404 ]
                    );
                }
            }

            $server->setGroups($serverGroups);
        }

        $this->getDoctrine()->getManager()->persist($server);
        $this->getDoctrine()->getManager()->flush();

        return $response->setData([ 'result' => 'Server inserted' ]);
    }

    /**
     * @ApiDoc(
     *  description="Inserts server file into server object",
     *  requirements={
     *      {
     *          "name"="name",
     *          "dataType"="string",
     *          "description"="Name of the server"
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
    public function insertServer(Request $request) {
        $server = $request->files->get('server');
        $id     = $request->get('id');
        if($id != null) {
            $serverObject = $this->getDoctrine()->getRepository('BDNBotBundle:Servers\Server')->findById($id);
            if($serverObject != null) {
                if($server != null) {
                    if($server->guessExtension() == 'zip') {
                        $serverObject->insertFile($server);

                        return new JsonResponse([ 'result' => 'Server added' ]);
                    } else {
                        return new JsonResponse([ 'result' => 'Upload may only be a jar' ], 400);
                    }
                } else {
                    return new JsonResponse([ 'result' => 'File not provided' ], 400);
                }
            } else {
                return new JsonResponse([ 'result' => 'Could not find server with ID' ], 404);
            }
        } else {
            return new JsonResponse([ 'result' => 'Missing server ID' ], 400);
        }
    }

    /**
     * @ApiDoc(
     *  description="Updates a server in the database",
     *  requirements={
     *     {
     *          "name"="id",
     *          "dataType"="int",
     *          "description"="ID of the server to be changed"
     *      }
     *  },
     *  parameters={
     *     {
     *          "name"="name",
     *          "dataType"="string",
     *          "description"="Name of the server",
     *          "required"=false
     *      },
     *      {
     *          "name"="active",
     *          "dataType"="boolean",
     *          "description"="Define if the server should be active",
     *          "required"=false
     *      },
     *      {
     *          "name"="groups",
     *          "dataType"="array",
     *          "description"="Array of the group ids that may access the server, delimited with a comma",
     *          "required"=false
     *      },
     *      {
     *          "name"="authors",
     *          "dataType"="array",
     *          "description"="Array of the usernames that have made this server possible, delimited with a comma",
     *          "required"=false
     *      },
     *      {
     *          "name"="details",
     *          "dataType"="string",
     *          "description"="JSON object of the server details",
     *          "required"=false
     *      },
     *      {
     *          "name"="version",
     *          "dataType"="float",
     *          "description"="Version of the server",
     *          "required"=false
     *      },
     *      {
     *          "name"="description",
     *          "dataType"="string",
     *          "description"="Description of the server",
     *          "required"=false
     *      }
     *  }
     * )
     *
     * @Route("/update", name="update_server")
     * @Method({"POST"})
     *
     * @PreAuthorize("isServerDeveloper()")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function updateServerAction(Request $request) {
        $response         = new JsonResponse();
        $groupRepository  = $this->getDoctrine()->getRepository('BDNUserBundle:Group');
        $userRepository   = $this->getDoctrine()->getRepository('BDNUserBundle:User');
        $serverRepository = $this->getDoctrine()->getRepository('BDNBotBundle:Servers\Server');
        $serverID         = $request->request->get('id');

        if(($serverObject = $serverRepository->findById($serverID)) != null) {
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
                if(($requestValue = $request->request->get($key)) != null && (is_array($requestValue) || strlen(
                                                                                                             $requestValue
                                                                                                         ) > 0)
                ) {
                    $value          = $requestValue;
                    $server[ $key ] = $value;
                }

                switch($key) {
                    case 'name':
                        if($requestValue != null && $serverRepository->notExistingNameWithoutID(
                                $serverID,
                                $value
                            ) !== true
                        ) {
                            return $response->setData(
                                [ 'result' => 'There is already a server that is named like this' ]
                            )->setStatusCode(400);
                        }
                        break;
                    case 'details':
                        if($requestValue != null) {
                            $details = $value;
                            $matches = 0;
                            foreach(ServerDetail::DEFAULT_DETAILS as $detail) {
                                foreach($details as $item) {
                                    if($item[ 'name' ] == $detail) {
                                        $matches++;
                                        break;
                                    }
                                }
                            }

                            if($matches != count(ServerDetail::DEFAULT_DETAILS)) {
                                $response->setData(
                                    [
                                        'result' => 'Missing values for default details: [' . implode(
                                                ', ',
                                                ServerDetail::DEFAULT_DETAILS
                                            ) . ']',
                                    ]
                                )->setStatusCode(
                                    400
                                );

                                return $response;
                            }
                        }
                        break;
                }
            }

            if($server[ 'name' ] != null) {
                $serverObject->setName($server[ 'name' ]);
            }

            if($server[ 'active' ] != null) {
                $serverObject->setActive(boolval($server[ 'active' ]));
            }

            if($server[ 'description' ] != null) {
                $serverObject->setDescription($server[ 'description' ]);
            }

            if($server[ 'version' ] != null) {
                $serverObject->setVersion(floatval($server[ 'version' ]));
            }

            if($server[ 'groups' ] != null) {
                $groups = [];
                if( ! is_array($server[ 'groups' ])) {
                    foreach(explode(',', $server[ 'groups' ]) as $item) {
                        $result = $groupRepository->findOneBy([ 'id' => $item ]);
                        if($result != null) {
                            $groups[] = $result;
                        }
                    }
                } else {
                    $groups = $server[ 'groups' ];
                }
                $serverObject->setGroups($groups);
            }

            if($server[ 'authors' ] != null) {
                $authors = [];
                foreach($server[ 'authors' ] as $item) {
                    $result = $userRepository->findOneBy([ 'username' => $item[ 'username' ] ]);
                    if($result != null) {
                        $authors[] = $result;
                    }
                }
                $serverObject->setAuthors($authors);
            }

            if($server[ 'details' ] != null) {
                $details = [];
                foreach($server[ 'details' ] as $value) {
                    $detail = new ServerDetail();

                    $detail->setName($value[ 'name' ]);
                    $detail->setValue($value[ 'value' ]);
                    $details[] = $detail;

                    $this->getDoctrine()->getManager()->persist($detail);
                }

                if(count($details) >= count(ServerDetail::DEFAULT_DETAILS)) {
                    foreach($serverObject->getDetails() as $d) {
                        $this->getDoctrine()->getManager()->remove($d);
                    }

                    $serverObject->setDetails($details);
                }
            }

            $this->getDoctrine()->getManager()->persist($serverObject);
            $this->getDoctrine()->getManager()->flush();

            $response->setData([ 'result' => 'Server saved!' ]);

        } else {
            $response->setData([ 'result' => 'Could not find server' ]);
            $response->setStatusCode(404);
        }

        return $response;
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
        $servers          = $serverRepository->findForUser(
            $this->getUser(),
            $this->get('request_access_evaluator')->isServerDeveloper()
        );

        return new JsonResponse(SerializerManager::normalize($servers));
    }

    /**
     * @Route("/hooks/{id}", name="get_hooks")
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
        $detailed         = ($d = $request->query->get('detailed')) != null && $d == 'true' ? true : false;

        /**
         * @var Server $server
         */
        $server = $serverRepository->findOneBy([ 'id' => $id ]);
        if($server != null) {
            if($serverRepository->hasAccess($this->getUser(), $server->getId())) {
                $hooks = $this->get('bot.servers.hook_manager')->createHookArray($server, $version, $detailed);
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
        } else {
            $response->setData([ 'result' => 'Given server ID does not exist' ]);
            $response->setStatusCode(404);
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
     *      {
     *          "name"="version",
     *          "dataType"="float",
     *          "description"="Version of the hooks",
     *          "required"=false
     *      }
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
     *  description="Sets an hook for a server, for a version",
     *  requirements={
     *      {
     *          "name"="id",
     *          "dataType"="int",
     *          "description"="ID of the hook"
     *      },
     *      {
     *          "name"="fields",
     *          "dataType"="json",
     *          "description"="JSON array of the hooks"
     *      }
     *
     *  },
     *  parameters={
     *  }
     * )
     *
     * @Route("/update/hook/{id}", name="update_server_hook")
     * @Method({"POST"})
     *
     * @PreAuthorize("isServerDeveloper()")
     *
     * @param Request $request
     * @param int     $id
     *
     * @return JsonResponse
     */
    public function setHookAction(Request $request, $id) {
        $response        = new JsonResponse();
        $hooksRepository = $this->getDoctrine()->getRepository('BDNBotBundle:Servers\Hook');
        $fields          = json_decode($request->request->get('fields'), true);

        if($fields != null) {
            $arguments = [ 'id' => $id ];
            $hook      = $hooksRepository->findOneBy($arguments);
            if($hook != null) {
                $hook->setFromFields($fields);

                $this->getDoctrine()->getManager()->persist($hook);
                $this->getDoctrine()->getManager()->flush();

                $response->setData([ 'result' => 'Hook fields adjusted for hook ' . $hook->getId() ]);
            } else {
                $response->setData([ 'result' => 'Hook ID given is not a valid hook ID' ]);
                $response->setStatusCode(404);
            }
        } else {
            $response->setData(
                [ 'result' => 'Fields parameter is not filled correctly' ]
            );
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

            if($allowed !== true && $this->get('request_access_evaluator')->isServerDeveloper() !== true) {
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
     * @Route("/download/{id}", name="download_server")
     * @Method({"GET"})
     *
     * @PreAuthorize("isNotBanned()")
     *
     * @param Request $request
     * @param int     $id
     *
     * @return JsonResponse
     */
    public function downloadAction(Request $request, $id) {
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

            if($allowed !== true && $this->get('request_access_evaluator')->isServerDeveloper() !== true) {
                $response->setData([ 'result' => 'User does not have enough permission to access this page' ]);
                $response->setStatusCode(403);
            } else {
                return $this->get('bot.download_manager')->provideServerDownload($server);
            }
        } else {
            $response->setData([ 'result' => 'Unknown server ID requested' ]);
            $response->setStatusCode(404);
        }

        return $response;
    }
}