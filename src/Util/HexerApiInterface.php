<?php

namespace App\Util;

interface HexerApiInterface
{
		/**
		 * Busca todos los vehiculos disponibles
		 * @return array <Vehiculo>
		 */
		public function  findAll(): array;
		
		/**
		 * Busca los vehiculos disponible con un filtro
		 * @param string $text
		 * @return array <Vehiculo>
		 */
		public function findQuery(string $text):array;
		
		/**
		 * Crea un nuevo auto
		 * @param array $data
		 * @return mixed
		 */
		public function newAuto(array $data ): mixed;
		
		/**
		 * Crea una moto
		 * @param array $data
		 * @return mixed
		 */
		public function newMoto(array $data ): mixed;
		
		/**
		 * trae los datos de un vehiculo
		 * @param int $id
		 * @return mixed
		 */
		public function show(int $id): mixed;
		
		/**
		 * Edita los datos de un vehiculo
		 * @param int   $id id del vehiculo
		 * @param array $data datos a cargar
		 * @return mixed respuesta de la peticion
		 */
		public function edit(int $id, array $data):array;
		
		/**
		 * Rematricular un vehiculo, generando la nueva matricula
		 * @param $id
		 * @return mixed
		 */
		public function rematricular ($id):array;
		
		/**
		 *  Vende un vehiculo, para que no este mas disponible
		 * @param $id
		 * @return mixed
		 */
		public function vender($id):array;
		
}