<?php
/**
 * @author JKetelaar
 */

namespace Parabot\BDN\BotBundle\Controller;

use AppBundle\Service\SerializerManager;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;
use Parabot\BDN\BotBundle\Entity\Script;
use Parabot\BDN\BotBundle\Entity\Scripts\Git;
use Parabot\BDN\BotBundle\Entity\Scripts\Release;
use Parabot\BDN\UserBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ScriptController extends Controller {

    /**
     * @Route("/run/{scriptId}", name="run_script")
     * @Method({"GET"})
     *
     * @PreAuthorize("isNotBanned()")
     *
     * @param Request $request
     *
     * @param int     $scriptId
     *
     * @return JsonResponse
     */
    public function runAction(Request $request, $scriptId) {
        $script  = $this->getDoctrine()->getRepository('BDNBotBundle:Script')->findOneBy([ 'id' => $scriptId ]);
        $release = $this->getDoctrine()->getRepository('BDNBotBundle:Scripts\Release')->getLatestRelease($script);

        if($script != null) {
            if($script->hasUser($this->get('request_access_evaluator')->getUser())) {
                return $this->get('bot.download_manager')->provideScriptDownload($script, $release);
            } else {
                return new JsonResponse([ 'result' => 'You do not have access to this script' ], 403);
            }
        } else {
            return new JsonResponse([ 'result' => 'Unknown script requested' ], 404);
        }
    }

    /**
     * @Route("/get/{scriptId}", name="get_script")
     * @Method({"GET"})
     *
     * @PreAuthorize("isNotBanned()")
     *
     * @param Request $request
     *
     * @param int     $scriptId
     *
     * @return JsonResponse
     */
    public function getAction(Request $request, $scriptId) {
        $script = $this->getDoctrine()->getRepository('BDNBotBundle:Script')->findOneBy([ 'id' => $scriptId ]);
        if($script != null) {
            if($this->get('request_access_evaluator')->isScriptWriter() && $script->hasAuthor(
                    $this->get('request_access_evaluator')->getUser()
                )) {
                $groups = [ 'default', 'developer' ];
                if($request->get('include') == 'users') {
                    $groups[] = 'script_users';
                }

                $scriptResponse = SerializerManager::normalize($script, 'json', $groups);
                $version        = $this->getDoctrine()->getRepository(
                    'BDNBotBundle:Scripts\Release'
                )->getLatestRelease($script);

                if($version instanceof Release) {
                    $scriptResponse[ 'version' ] = $version->getVersion();
                } else {
                    $scriptResponse[ 'version' ] = 0;
                }

                return new JsonResponse(
                    [ 'result' => $scriptResponse ]
                );
            } else {
                return new JsonResponse([ 'result' => SerializerManager::normalize($script) ]);
            }
        } else {
            return new JsonResponse([ 'result' => 'Unknown script requested' ], 404);
        }
    }


    /**
     * @Route("/release/create", name="create_release")
     * @Method({"POST"})
     *
     * @PreAuthorize("isScriptWriter()")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function createReleaseAction(Request $request) {
        $scriptId = $request->request->get('id');
        $manager  = $this->getDoctrine()->getManager();

        if($scriptId != null) {
            $script = $this->getDoctrine()->getRepository('BDNBotBundle:Script')->findOneBy([ 'id' => $scriptId ]);
            if($script != null) {
                if($script->hasAuthor($this->get('request_access_evaluator')->getUser())) {
                    if(($version = $request->request->get('version')) != null) {
                        $release = $this->getDoctrine()->getRepository(
                            'BDNBotBundle:Scripts\Release'
                        )->getLatestRelease($script);
                        if($release != null && $release instanceof Release) {
                            $_fv = intval(trim(str_replace('.', '', $version)));
                            $_sv = intval(trim(str_replace('.', '', $release->getVersion())));

                            if(strlen($_fv) > strlen($_sv)) {
                                $_sv = str_pad($_sv, strlen($_fv), 0);
                            }

                            if(strlen($_fv) < strlen($_sv)) {
                                $_fv = str_pad($_fv, strlen($_sv), 0);
                            }

                            if(version_compare(( string ) $_fv, ( string ) $_sv, '>=') === false) {
                                return new JsonResponse(
                                    [ 'result' => 'You seem to have provided a version lower or equal to the current version' ],
                                    400
                                );
                            }
                        }

                        $newRelease = new Release();
                        $newRelease->setVersion($version);

                        if(($changelog = $request->request->get('changelog')) != null) {
                            $newRelease->setChangelog($changelog);
                        } else {
                            return new JsonResponse([ 'result' => 'Release changelog missing' ], 400);
                        }

                        $newRelease->setScript($script);
                        $manager->persist($newRelease);
                        $script->addRelease($newRelease);

                        $releaseDownloaded = $this->get('bot.teamcity.api')->getLatestArtifact($script, $newRelease);

                        if($releaseDownloaded === true) {
                            $manager->persist($script);
                            $manager->flush();

                            return new JsonResponse([ 'result' => 'Release created' ]);
                        } else {
                            return new JsonResponse(
                                [ 'result' => 'Server couldn\'t download an artifact, have you started a new build yet?' ],
                                500
                            );
                        }
                    } else {
                        return new JsonResponse([ 'result' => 'Release version missing' ], 400);
                    }
                } else {
                    return new JsonResponse([ 'result' => 'User does not have write access to script' ], 403);
                }
            } else {
                return new JsonResponse([ 'result' => 'Unknown Script requested' ], 404);
            }
        } else {
            return new JsonResponse([ 'result' => 'Script ID not given' ], 400);
        }
    }

    /**
     * @Route("/categories/list", name="list_categories")
     * @Method({"GET"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function listCategoriesAction(Request $request) {
        return new JsonResponse(
            [
                'categories' => SerializerManager::normalize(
                    $this->getDoctrine()->getRepository('BDNBotBundle:Scripts\Category')->findAll()
                ),
            ]
        );
    }

    /**
     * @Route("/create", name="create_script")
     * @Method({"POST"})
     *
     * @PreAuthorize("isScriptWriter()")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function createAction(Request $request) {
        $script  = new Script();
        $manager = $this->getDoctrine()->getManager();

        $scriptAttributes = [
            'name'        => null,
            'forum'       => 0,
            'description' => null,
            'git'         => null,
            'authors'     => [ $this->get('request_access_evaluator')->getUser()->getUsername() ],
            'groups'      => [],
            'active'      => true,
            'categories'  => null,
        ];

        foreach($scriptAttributes as $attribute => $value) {
            $temp = $request->request->get($attribute);
            if($temp !== null) {
                $scriptAttributes[ $attribute ] = $temp;
            } elseif($value !== null) {
                $scriptAttributes[ $attribute ] = $value;
            } else {
                return new JsonResponse([ 'result' => 'Missing attribute (' . $attribute . ')' ], 400);
            }
        }

        if($scriptAttributes[ 'name' ] !== null) {
            $script->setName($scriptAttributes[ 'name' ]);
        }

        if($scriptAttributes[ 'active' ] !== null) {
            $script->setActive($scriptAttributes[ 'active' ]);
        }

        if($scriptAttributes[ 'forum' ] !== null) {
            $script->setForum($scriptAttributes[ 'forum' ]);
        }

        if($scriptAttributes[ 'description' ] !== null) {
            $script->setDescription($scriptAttributes[ 'description' ]);
        }

        $script->setCreator($this->get('request_access_evaluator')->getUser());

        if(($authors = $scriptAttributes[ 'authors' ]) !== null && count($authors) > 0) {
            /**
             * @var User[] $scriptAuthors
             */
            $scriptAuthors  = [];
            $userRepository = $this->getDoctrine()->getRepository('BDNUserBundle:User');
            foreach($authors as $author) {
                $a = $userRepository->findOneBy([ 'username' => $author[ 'username' ] ]);
                if($a != null) {
                    if($this->get('request_access_evaluator')->isScriptWriter($a)) {
                        $scriptAuthors[] = $a;
                    } else {
                        return new JsonResponse(
                            [ 'result' => 'Given user (' . $author[ 'username' ] . ') is not a script writer' ], 400
                        );
                    }
                } else {
                    return new JsonResponse(
                        [ 'result' => 'Given user (' . $author[ 'username' ] . ') is unknown' ], 400
                    );
                }
            }

            $creatorIncluded = false;
            foreach($scriptAuthors as $author) {
                if($author->getId() === $script->getCreator()->getId()) {
                    $creatorIncluded = true;
                    break; // Speed up the process
                }
            }

            if($creatorIncluded !== true) {
                return new JsonResponse(
                    [ 'result' => 'Creator of the script is not included in the authors' ], 400
                );
            }

            $script->setAuthors($scriptAuthors);
        }

        if($scriptAttributes[ 'git' ] != null) {
            $gitURL = $scriptAttributes[ 'git' ][ 'url' ];
            if(preg_match('/((git)|(git@[\w\.]+))(:(\/\/)?)([\w\.@\:\/\-~]+)(\.git)(\/)?/', $gitURL)) {
                $git = new Git();
                $git->setUrl($gitURL);
                $script->setGit($git);

                $manager->persist($git);
            } else {
                return new JsonResponse(
                    [ 'result' => 'Git URL not a valid Git URL (like git@domain.com:username/project.git)' ], 400
                );
            }
        }

        if(($groups = $scriptAttributes[ 'groups' ]) !== null && count($groups) > 0) {
            $groupRepository = $this->getDoctrine()->getRepository('BDNUserBundle:Group');

            $scriptGroups = [];
            foreach($groups as $group) {
                $g = $groupRepository->findOneBy([ 'id' => $group[ 'id' ] ]);

                if($g != null) {
                    $scriptGroups[] = $g;
                } else {
                    return new JsonResponse(
                        [ 'result' => 'Unknown group ID given (' . $group[ 'id' ] . ')', 404 ]
                    );
                }
            }

            $script->setGroups($scriptGroups);
        }

        if(($categories = $scriptAttributes[ 'categories' ]) !== null && count($categories) > 0) {
            $categoryRepository = $this->getDoctrine()->getRepository('BDNBotBundle:Scripts\Category');

            $scriptCategories = [];
            foreach($categories as $category) {
                $c = $categoryRepository->findOneBy([ 'id' => $category[ 'id' ] ]);

                if($c != null) {
                    $scriptCategories[] = $c;
                } else {
                    return new JsonResponse(
                        [ 'result' => 'Unknown category ID given (' . $category[ 'id' ] . ')', 404 ]
                    );
                }
            }

            $script->setCategories($scriptCategories);
        }

        $manager->persist($script);
        $manager->flush();

        return new JsonResponse([ 'result' => 'Script added' ]);
    }

    /**
     * @Route("/update", name="update_script")
     * @Method({"POST"})
     *
     * @PreAuthorize("isScriptWriter()")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function updateAction(Request $request) {
        $scriptId = $request->request->get('id');

        $scriptAttributes = [
            'name'        => null,
            'forum'       => null,
            'description' => null,
            'git'         => null,
            'authors'     => null,
            'groups'      => null,
            'active'      => null,
            'categories'  => null,
        ];

        if($scriptId != null) {
            $script = $this->getDoctrine()->getRepository('BDNBotBundle:Script')->findOneBy([ 'id' => $scriptId ]);
            if($script != null) {
                if($script->hasAuthor($this->get('request_access_evaluator')->getUser())) {
                    foreach($scriptAttributes as $attribute => $value) {
                        $temp = $request->request->get($attribute);
                        if($temp !== null) {
                            $scriptAttributes[ $attribute ] = $temp;
                        }
                    }

                    if($scriptAttributes[ 'name' ] !== null) {
                        $script->setName($scriptAttributes[ 'name' ]);
                    }

                    if($scriptAttributes[ 'active' ] !== null) {
                        $script->setActive($scriptAttributes[ 'active' ]);
                    }

                    if($scriptAttributes[ 'forum' ] !== null) {
                        $script->setForum($scriptAttributes[ 'forum' ]);
                    }

                    if($scriptAttributes[ 'description' ] !== null) {
                        $script->setDescription($scriptAttributes[ 'description' ]);
                    }

                    if(($authors = $scriptAttributes[ 'authors' ]) !== null && count($authors) > 0) {
                        /**
                         * @var User[] $scriptAuthors
                         */
                        $scriptAuthors  = [];
                        $userRepository = $this->getDoctrine()->getRepository('BDNUserBundle:User');
                        foreach($authors as $author) {
                            $a = $userRepository->findOneBy([ 'username' => $author[ 'username' ] ]);
                            if($a != null) {
                                if($this->get('request_access_evaluator')->isScriptWriter($a)) {
                                    $scriptAuthors[] = $a;
                                } else {
                                    return new JsonResponse(
                                        [ 'result' => 'Given user (' . $author[ 'username' ] . ') is not a script writer' ],
                                        400
                                    );
                                }
                            } else {
                                return new JsonResponse(
                                    [ 'result' => 'Given user (' . $author[ 'username' ] . ') is unknown' ], 400
                                );
                            }
                        }

                        $creatorIncluded = false;
                        foreach($scriptAuthors as $author) {
                            if($author->getId() === $script->getCreator()->getId()) {
                                $creatorIncluded = true;
                                break; // Speed up the process
                            }
                        }

                        if($creatorIncluded !== true) {
                            return new JsonResponse(
                                [ 'result' => 'Creator of the script is not included in the authors' ], 400
                            );
                        }

                        $script->setAuthors($scriptAuthors);
                    }

                    if($scriptAttributes[ 'git' ] != null) {
                        $gitURL = $scriptAttributes[ 'git' ][ 'url' ];
                        if(preg_match('/((git)|(git@[\w\.]+))(:(\/\/)?)([\w\.@\:\/\-~]+)(\.git)(\/)?/', $gitURL)) {
                            $script->getGit()->setUrl($gitURL);
                            if ($this->get('bot.teamcity.api')->updateVSCGitURL($script) !== true){
                                return new JsonResponse(
                                    [ 'result' => 'Could not update Git URL at TeamCity, please contact a system administrator' ],
                                    500
                                );
                            }
                        } else {
                            return new JsonResponse(
                                [ 'result' => 'Git URL not a valid Git URL (like git@domain.com:username/project.git)' ],
                                400
                            );
                        }
                    }

                    if(($groups = $scriptAttributes[ 'groups' ]) !== null && count($groups) > 0) {
                        $groupRepository = $this->getDoctrine()->getRepository('BDNUserBundle:Group');

                        $scriptGroups = [];
                        foreach($groups as $group) {
                            $g = $groupRepository->findOneBy([ 'id' => $group[ 'id' ] ]);

                            if($g != null) {
                                $scriptGroups[] = $g;
                            } else {
                                return new JsonResponse(
                                    [ 'result' => 'Unknown group ID given (' . $group[ 'id' ] . ')', 404 ]
                                );
                            }
                        }

                        $script->setGroups($scriptGroups);
                    }

                    if(($categories = $scriptAttributes[ 'categories' ]) !== null && count($categories) > 0) {
                        $categoryRepository = $this->getDoctrine()->getRepository('BDNBotBundle:Scripts\Category');

                        $scriptCategories = [];
                        foreach($categories as $category) {
                            $c = $categoryRepository->findOneBy([ 'id' => $category[ 'id' ] ]);

                            if($c != null) {
                                $scriptCategories[] = $c;
                            } else {
                                return new JsonResponse(
                                    [ 'result' => 'Unknown category ID given (' . $category[ 'id' ] . ')', 404 ]
                                );
                            }
                        }

                        $script->setCategories($scriptCategories);
                    }

                    $manager = $this->getDoctrine()->getManager();
                    $manager->persist($script);
                    $manager->flush();

                    return new JsonResponse([ 'result' => 'Script updated' ]);
                } else {
                    return new JsonResponse([ 'result' => 'User does not have write access to script' ], 403);
                }
            } else {
                return new JsonResponse([ 'result' => 'Unknown Script requested' ], 404);
            }
        } else {
            return new JsonResponse([ 'result' => 'Script ID not given' ], 400);
        }
    }

    /**
     * @Route("/list/my", name="list_my_scripts")
     * @Method({"GET"})
     *
     * @PreAuthorize("isScriptWriter()")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function listMyAction(Request $request) {
        $sRepository = $this->getDoctrine()->getRepository('BDNBotBundle:Script');
        $user        = $this->get('request_access_evaluator')->getUser();

        $scripts = $sRepository->findByAuthor($user, false);

        return new JsonResponse([ 'scripts' => SerializerManager::normalize($scripts) ]);
    }

    /**
     * @Route("/list/author/{username}")
     *
     * @param Request $request
     * @param string  $username
     *
     * @return JsonResponse
     */
    public function listAuthorAction(Request $request, $username) {
        $sRepository = $this->getDoctrine()->getRepository('BDNBotBundle:Script');
        $uRepository = $this->getDoctrine()->getRepository('BDNUserBundle:User');

        if(($user = $uRepository->findOneBy([ 'username' => $username ])) != null) {
            $scripts = $sRepository->findByAuthor($user);

            $scriptsResult = [];
            foreach($scripts as $script) {
                $authors = [];
                foreach($script->getAuthors() as $author) {
                    $authors[] = [
                        'id'       => $author->getId(),
                        'username' => $author->getUsername(),
                    ];
                }
                $scriptsResult[] = [
                    'id'          => $script->getId(),
                    'name'        => $script->getName(),
                    'authors'     => $authors,
                    'description' => $script->getDescription(),
                ];
            }

            return new JsonResponse([ 'result' => $scriptsResult ]);
        } else {
            return new JsonResponse([ 'result' => 'No user found with that username' ], 404);
        }
    }
}