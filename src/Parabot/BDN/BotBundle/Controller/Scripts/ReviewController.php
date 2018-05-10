<?php

namespace Parabot\BDN\BotBundle\Controller\Scripts;

use AppBundle\Service\SerializerManager;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Parabot\BDN\BotBundle\Entity\Scripts\Review;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/api/scripts/reviews")
 *
 * Class ReviewController
 * @package Parabot\BDN\BotBundle\Controller\Scripts
 */
class ReviewController extends Controller
{
    /**
     * @ApiDoc(
     *  description="Accepts review",
     *  requirements={}
     * )
     *
     * @Route("/accepted")
     * @Method({"POST"})
     *
     * @PreAuthorize("isNotBanned()")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function acceptReviewAction(Request $request)
    {
        $review = $this->getDoctrine()->getRepository('BDNBotBundle:Scripts\Review')->findOneBy(
            ['id' => $request->get('id')]
        );

        if (!$this->get('request_access_evaluator')->isAdministrator()) {
            if (!$review->getScript()->hasAuthor($this->get('request_access_evaluator')->getUser())) {
                return new JsonResponse(['result' => 'You are not allowed to perform this action'], 403);
            }
        }

        if ($review != null) {
            $manager = $this->getDoctrine()->getManager();
            $accepted = boolval($request->get('accepted'));

            $review->setAccepted($accepted);

            $manager->persist($review);
            $manager->flush();

            return new JsonResponse(['result' => 'Review '.($accepted ? 'accepted' : 'declined')]);
        } else {
            return new JsonResponse(['result' => 'Unknown review requested'], 404);
        }
    }

    /**
     * @ApiDoc(
     *  description="List reviews of a script",
     *  requirements={
     *      {
     *          "name"="scriptId",
     *          "dataType"="integer",
     *          "description"="ID of the script"
     *      }
     *  }
     * )
     *
     * @Route("/list/{scriptId}")
     * @Method({"GET"})
     *
     * @param Request $request
     *
     * @param int $scriptId
     *
     * @return JsonResponse
     */
    public function listReviewsAction(Request $request, $scriptId)
    {
        $script = $this->getDoctrine()->getRepository('BDNBotBundle:Script')->findOneBy(['id' => $scriptId]);
        $reviews = $this->getDoctrine()->getRepository('BDNBotBundle:Scripts\Review')->findReviewsForScript(
            $script,
            !($request->get('accepted') == 'all')
        );
        if ($reviews != null) {

            return new JsonResponse(
                [
                    'result' => [
                        'reviews' => SerializerManager::normalize($reviews, 'json', ['review']),
                        'average_stars' => $script->getAverageReviewStars(),
                    ],
                ]
            );
        } else {
            return new JsonResponse(['result' => 'No reviews found for script'], 404);
        }
    }

    /**
     * @ApiDoc(
     *  description="Add review to a script",
     *  requirements={
     *      {
     *          "name"="scriptId",
     *          "dataType"="integer",
     *          "description"="ID of the script"
     *      }
     *  }
     * )
     *
     * @Route("/add/{scriptId}")
     * @Method({"POST"})
     *
     * @PreAuthorize("isNotBanned()")
     *
     * @param Request $request
     *
     * @param int $scriptId
     *
     * @return JsonResponse
     */
    public function addReviewAction(Request $request, $scriptId)
    {
        $script = $this->getDoctrine()->getRepository('BDNBotBundle:Script')->findOneBy(['id' => $scriptId]);
        if ($script != null) {
            if (($stars = $request->get('stars')) != null && ($review = $request->get('review')) != null) {
                if (is_numeric($stars)) {
                    if (strlen($review) <= 250) {
                        $user = $this->get('request_access_evaluator')->getUser();
                        if ($this->getDoctrine()->getRepository('BDNBotBundle:Scripts\Review')->getReview(
                                $script,
                                $user
                            ) == null) {
                            $reviewObject = new Review();
                            $reviewObject->setReview($review);
                            $reviewObject->setScript($script);
                            $reviewObject->setStars($stars);
                            $reviewObject->setUser($user);

                            $manager = $this->getDoctrine()->getManager();
                            $manager->persist($reviewObject);
                            $manager->flush();

                            return new JsonResponse(['result' => 'Review added']);
                        } else {
                            return new JsonResponse(['result' => 'User already has a review on this script'], 400);
                        }
                    } else {
                        return new JsonResponse(['result' => 'Review may only be 250 characters long'], 400);
                    }
                } else {
                    return new JsonResponse(['result' => 'Stars parameter has to be numeric'], 400);
                }
            } else {
                return new JsonResponse(
                    ['result' => 'Missing '.($stars == null ? 'stars' : 'review').' parameter'], 400
                );
            }
        } else {
            return new JsonResponse(['result' => 'Unknown script requested'], 404);
        }
    }

    /**
     * @ApiDoc(
     *  description="Add review to a script",
     *  requirements={
     *      {
     *          "name"="scriptId",
     *          "dataType"="integer",
     *          "description"="ID of the script"
     *      }
     *  }
     * )
     *
     * @Route("/update/{scriptId}")
     * @Method({"POST"})
     *
     * @PreAuthorize("isNotBanned()")
     *
     * @param Request $request
     *
     * @param int $scriptId
     *
     * @return JsonResponse
     */
    public function updateReviewAction(Request $request, $scriptId)
    {
        $script = $this->getDoctrine()->getRepository('BDNBotBundle:Script')->findOneBy(['id' => $scriptId]);
        if ($script != null) {
            $user = $this->get('request_access_evaluator')->getUser();

            $reviewObject = $this->getDoctrine()->getRepository('BDNBotBundle:Scripts\Review')->getReview(
                $script,
                $user
            );
            if ($reviewObject != null) {

                if ($reviewObject->getId() == $request->get('id')) {
                    $stars = $request->get('stars');
                    $review = $request->get('review');
                    if ($stars == null || is_numeric($stars)) {
                        if ($review == null || strlen($review) <= 250) {
                            $reviewObject->setReview($review);
                            $reviewObject->setScript($script);
                            $reviewObject->setStars($stars);
                            $reviewObject->setUser($user);

                            $manager = $this->getDoctrine()->getManager();
                            $manager->persist($reviewObject);
                            $manager->flush();

                            return new JsonResponse(['result' => 'Review updated']);
                        } else {
                            return new JsonResponse(['result' => 'Review may only be 250 characters long'], 400);
                        }
                    } else {
                        return new JsonResponse(['result' => 'Stars parameter has to be numeric'], 400);
                    }
                } else {
                    return new JsonResponse(['result' => 'Review author doesn\'t match the the user'], 404);
                }
            } else {
                return new JsonResponse(['result' => 'Unknown review requested'], 404);
            }
        } else {
            return new JsonResponse(['result' => 'Unknown script requested'], 404);
        }
    }
}
