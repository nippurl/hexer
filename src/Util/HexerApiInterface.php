<?php

namespace App\Util;

interface HexerApiInterface
{
		/**
		 * Busca todos los vehiculos disponibles
		 * @return array <Vehiculo>
		 */
		public function  findAll();
		
		/**
		 * Busca los vehiculos disponible con un filtro
		 * @param string $text
		 * @return array <Vehiculo>
		 */
		public function findQuery(string $text);
		
		/**
		 * Crea un nuevo auto
		 * @param array $data
		 * @return mixed
		 */
		public function newAuto(array $data );
		
		/**
		 * Crea una moto
		 * @param array $data
		 * @return mixed
		 */
		public function newMoto(array $data );
		
		/**
		 * trae los datos de un vehiculo
		 * @param int $id
		 * @return mixed
		 */
		public function show(int $id);
		
		/**
		 * Edita los datos de un vehiculo
		 * @param int   $id id del vehiculo
		 * @param array $data datos a cargar
		 * @return mixed respuesta de la peticion
		 */
		public function edit(int $id, array $data);
		
		/**
		 * Rematricular un vehiculo, generando la nueva matricula
		 * @param $id
		 * @return mixed
		 */
		public function rematricular ($id);
		
		/**
		 *  Vende un vehiculo, para que no este mas disponible
		 * @param $id
		 * @return mixed
		 */
		public function vender($id);
		
}