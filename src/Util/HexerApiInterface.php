<?php

namespace App\Util;

interface HexerApiInterface
{
		public function  findAll();
		public function findQuery(string $text);
		
		public function newAuto(array $data );
		public function newMoto(array $data );
		
		public function show(int $id);
		public function edit(int $id, array $data);
		
}