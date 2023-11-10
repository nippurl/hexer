<?php

namespace App\Form;

use App\Util\HexerApiService;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VehiculoType extends AbstractType
{
		private ?HexerApiService $hexerApiService;
		
		public function buildForm(FormBuilderInterface $builder, array $options): void
		{
				$this->hexerApiService = $options['hexerApiService'];
				$opcionesTipo          = HexerApiService::VehiculoTipo;
				$builder
					->add('tipo', ChoiceType::class, [
						'expanded' => true,
						'choices' => $opcionesTipo,
					])
					->add('marca', TextType::class)
					->add('modelo', TextType::class)
					->add('color', TextType::class)
					->add('matricula', TextType::class)
					->add('Guardar', SubmitType::class, [
						'attr' => [
							'class' => 'btn btn-success',
						],
					])
					->addEventListener(
						FormEvents::POST_SUBMIT,
						[$this, 'validarMatricula']
					);
		}
		
		public function configureOptions(OptionsResolver $resolver): void
		{
				$resolver->setRequired('hexerApiService');
				$resolver->setDefaults([
						// Configure your form options here
				]);
		}
		
		/**
		 * Valida en cada caso la matricula utilizado la funcion de validacion
		 * @return void
		 */
		public function validarMatricula(FormEvent $event): void
		{
				/** @var Vehiculo $data */
				$data = $event->getData();
				/** @var \App\Form\MovimientoType $form */
				$form = $event->getForm();
				$hexerApiService = $this->hexerApiService;
				$resultado = $hexerApiService->verificar($data);
				if ($resultado['resultado'] != 'ok') {
						$form->get('matricula')
						     ->addError(new FormError($resultado['error']));
				}
				return;
		}
}
