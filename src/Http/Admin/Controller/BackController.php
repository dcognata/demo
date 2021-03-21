<?php

namespace App\Http\Admin\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class BackController extends AbstractController
{
    /**
     * @Route("/", name="admin_home")
     */
    public function home(): JsonResponse
    {
        return $this->json(['all is fine.']);
    }
}
