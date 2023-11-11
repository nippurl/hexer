<?php

namespace App\Controller;

use App\Form\VehiculoType;
use App\Util\HexerApiInterface;
use Exception;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
		#[Route('/', name: 'app_index')]
		public function index(Request $request, HexerApiInterface $hexerApiService): Response
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
    
    /**
     * @throws \Exception
     */
    #[Route(path: '/nuevo', name: 'app_nuevo', methods: ['GET', 'POST'])]
		public function nuevo(Request $request, HexerApiInterface $hexerApiService): Response
		{
				$form = $this->createForm(VehiculoType::class, null, [
					'hexerApiService' => $hexerApiService,
				]);
				
				$form->handleRequest($request);
				if ($form->isSubmitted() && $form->isValid()) {
						switch ($form->get('tipo')
						             ->getData()) {
								case 'a':
										$hexerApiService->newAuto($form->getData());
										break;
								case 'm':
										$hexerApiService->newMoto($form->getData());
										break;
								default:
										throw new RuntimeException('No se ha podido registrar el vehiculo');
						}
						return $this->redirectToRoute('app_index');
				}
				return $this->render('app/nuevo.html.twig',
					[
						'form' => $form->createView(),
					]
				);
		}
    
    /**
     * @throws \Exception
     */
    #[Route(path: '/ver/{id}', name: 'app_ver', methods: ['GET'])]
		public function ver($id, HexerApiInterface $hexerApiService): Response
		{
				$vehiculo = $hexerApiService->show($id);
				if (!$vehiculo) {
						throw new RuntimeException('No se ha podido encontrar el vehiculo con ID '.$id);
				}
				return $this->render('app/ver.html.twig',
					[
						'vehiculo' => $vehiculo,
					]
				);
		}
    
    /**
     * @throws \Exception
     */
    #[Route(path: '/edit/{id}', name: 'app_edit', methods: ['GET', 'POST'])]
		public function edit($id, Request $request, HexerApiInterface $hexerApiService): Response
		{
				$vehiculo = $hexerApiService->show($id);
				if (!$vehiculo) {
						throw new RuntimeException('No se ha podido encontrar el vehiculo con ID '.$id);
				}
				$form = $this->createForm(VehiculoType::class, $vehiculo, [
					'hexerApiService' => $hexerApiService,
				]);
				$form->handleRequest($request);
				if ($form->isSubmitted() && $form->isValid()) {
						$hexerApiService->edit($id, $form->getData());
						return $this->redirectToRoute('app_index');
				}
				return $this->render('app/edit.html.twig', [
						'form' => $form->createView(),
						'vehiculo' => $vehiculo,
					]
				);
		}
		
		#[Route(path: '/{id}/rematricular/', name: 'app_rematricular', methods: ['GET'])]
		public function rematricular($id, HexerApiInterface $hexerApiService):Response
		{
				$hexerApiService->rematricular($id);
				return $this->redirectToRoute('app_ver', [
					'id' => $id,
				]);
		}
		
		
		#[Route(path: '/{id}/vender/', name: 'app_vender', methods: ['GET'])]
		public function vender($id, HexerApiInterface $hexerApiService):Response
		{
				$hexerApiService->vender($id);
				return $this->redirectToRoute('app_index');
		}
}
