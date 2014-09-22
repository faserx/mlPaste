<?php
/*
 * senza nome.php
 * 
 * Copyright 2014 Piero <Piero@PIERO-PC>
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 * 
 * 
 */
include "config.php";

class mlPaste{
	
	public $DB;
	
	public function __construct()
	{
		global $db_name;
		global $db_user;
		global $db_pass;
		$this->DB = new PDO('mysql:host=localhost;dbname='.$db_name, $db_user, $db_pass);
	}
	
	private function controlID($ID)
	{
		global $db_table;
		$res = $this->DB->prepare("SELECT `id` FROM `$db_table` WHERE `id`=:id;");
		if(!$res->execute(Array(":id" => $ID)))
			return NULL;
		return $res->rowCount();
	}
		
	
	private function generateID()
	{
		$words = "QWERTYUIOPASDFGHJKLZXCVBNMqwertyuiopasdfghjklzxcvbnm0123456789";
		
		do{
			for($c = '', $i=0; $i<5; $i++)
				$c .= $words[rand(0,62)];
		}while($this->controlID($c));
		
		return $c;
	}
	
	public function showHlPaste($ID)
	{
		$source = $this->showRawPaste($ID);
		if( $source == NULL)
			return NULL;
		$template = file_get_contents("TEMPLATE.htm");
		$template = str_replace("<!-- %codice% -->", htmlspecialchars($source), $template);
		
		return $template;
	}
	
	public function showRawPaste($ID)
	{
		global $db_table;
		if(!$this->controlID($ID))
			return NULL;
		
		$res = $this->DB->prepare("SELECT * FROM `$db_table` WHERE `id`=:id;");
		$res->execute(Array(":id" => $ID));
		$row = $res->fetch(PDO::FETCH_ASSOC);
		
		return $row['source'];
		
	}
	
	public function addPaste($source, $ip)
	{
		global $db_table;
		if((empty($source)) || (empty($ip)))
			return NULL;
		
		$res = $this->DB->prepare("INSERT INTO `$db_table` (`id`, `ip`, `source`) VALUES (:id, :ip, :source);");
		$ID = $this->generateID();
		
		if(!$res->execute(Array(":id" => $ID, ":ip" => $ip, ":source" => $source)))
			return NULL;
		
		$url = "http://".$_SERVER['HTTP_HOST']."/".$ID;
		return $url;
	}
}

?>
