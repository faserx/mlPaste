<?php
/*
 * core.php
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
 
include 'geshi/geshi.php'; 

 function initialize(){
	 $db = simplexml_load_file("mlDB.xml");
	 $method = $_SERVER['REQUEST_METHOD'];
	 
	 switch($method){
		 case 'GET':
			index();
			break;
		case 'POST':
			paste();
			break;
		default:
			index();
			break;
	}
}

function genID(){
	$db = simplexml_load_file("mlDB.xml");
	$words = "Q,W,E,R,T,Y,U,I,O,P,A,S,D,F,G,H,J,K,L,Z,X,C,V,B,N,M,q,w,e,r,t,y,u,i,o,p,a,s,d,f,g,h,j,k,l,z,x,c,v,b,n,m,1,2,3,4,5,6,7,8,9,0";
	$len = 4;
	$str = ""; 
	
	while(1){
		for($i=0;$i<$len;$i++){
			$iu = explode(',',$words);
			$str .= $iu[rand(0, count($iu))];
		}
		$iiu = explode(',',$db->id_used);
		if(!(in_array($str, $iiu))){
			return $str;
		}else $str = "";
	}
}

function paste(){
	$db = simplexml_load_file("mlDB.xml");
	$iu = explode(',', $db->id_used);
	
	if(isset($_POST['mlPaste']))
		$code = base64_encode(htmlspecialchars($_POST['mlPaste']));
	else
		index();
	
	if(strlen($code) > 1){	
		$id = genID();
		array_push($iu, $id);
		$iu = implode(',', $iu);
		$db->id_used = $iu;
		$db->sources->addChild("srcID_$id", $code);
		$db->asXML("mlDB.xml");
	}else index();
	
	$url = 'http://'.$_SERVER['HTTP_HOST'].'index.php?id='.$id;
	echo $url;
}

function view_paste($id){
	$db = simplexml_load_file("mlDB.xml");
	$iu = explode(',', $db->id_used);
	
	if(!in_array($id, $iu))
		echo 'ID INESISTENTE';
	else{
		header("Content-type: text/plain");
		$id = "srcID_$id";
		$source = base64_decode(htmlspecialchars_decode($db->sources->$id));
		echo $source;
	}
}

function syntax($id, $lang){
	$db = simplexml_load_file("mlDB.xml");
	$iu = explode(',', $db->id_used);
	if(!in_array($id, $iu))
		echo 'ID INESISTENTE';
	else{
		$id = "srcID_$id";
		$source = base64_decode($db->sources->$id);
		
		$geshi = new GeSHi($source, $lang);
		$geshi->enable_keyword_links(false);
		$geshi->enable_line_numbers(GESHI_NORMAL_LINE_NUMBERS);
	
		header("Content-type: text/html ; charset=utf-8");
		echo $geshi->parse_code();
	}
}
	
	
function index(){
}
		
	
?>
