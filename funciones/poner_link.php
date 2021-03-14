<?php
class link{
	function poner_link($url,$texto){
		$link="<a href='$url'>$texto</a>";
		return($link);
	}
}
?>