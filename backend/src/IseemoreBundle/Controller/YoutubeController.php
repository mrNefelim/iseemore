<?php

namespace App\IseemoreBundle\Controller;

use App\IseemoreBundle\Entity\Video;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class YoutubeController extends AbstractController
{
    /**
     * @Route("/youtube", name="youtube")
     * @param int $lastId
     * @return Response
     */
    public function index(int $lastId): Response
    {
        try {
            $video = null;

            while ($video == null) {
                if (empty($lastId)) {
                    $lastId = rand(1, $this->getCount());
                }

                $video = $this->getNextVideo($lastId);
            }
        } catch (\Throwable $exception) {
            dd($exception);

        }
        return $this->json($video);
    }

    public function getCount(): int
    {
        try {
            return $this->getDoctrine()->getRepository(Video::class)
                ->createQueryBuilder('v')
                ->select('count(v.id)')
                ->getQuery()
                ->getSingleScalarResult();
        } catch (NoResultException | NonUniqueResultException $e) {
            return 0;
        }
    }

    public function getNextVideo($lastId)
    {
        try {
            return $this->getDoctrine()->getRepository(Video::class)
                ->createQueryBuilder('v')
                ->where('v.id > :lastId')
                ->setParameter('lastId', $lastId)
                ->setMaxResults(1)
                ->getQuery()
                ->getSingleResult();
        } catch (NoResultException | NonUniqueResultException $e) {
            return 0;
        }
    }

    public function count(): JsonResponse
    {
        return $this->json($this->getCount());
    }

    public function delete(int $id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $video = $entityManager->getRepository(Video::class)->find($id);
        $video->setStatus(false);
        $entityManager->persist($video);
        $entityManager->flush();
    }
}
