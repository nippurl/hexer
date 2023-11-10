<?php

namespace App\Controller;

use http\Env\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
		#[Route('/app', name: 'app_app')]
		public function index(Request $request, HexerApiService $hexerApiService): Response
		{
				$text = $request->get('q');
				if ($text) {
						$vehiculos = $hexerApiService->findQuery($text);
						
				} else {
						$vehiculos = $hexerApiService->findAll();
				}
				return $this->render('app/index.html.twig', [
					'vehiculos' => $vehiculos,
				]);
		}
		
		//todo nuevo auto
		//todo nueva moto
		//todo ver
		//todo editar
		//todo rematricular
		//todo vender
}
