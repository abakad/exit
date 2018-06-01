<?php

function boesen_adressen_nicht_antworten()
{
	$boese_ips = array_fill_keys(array(

		// Hier sind IPs
		// Achtung, IPs Ausschliessen auf eigene Gefahr

		'81.209.177.136',
		'81.209.177.145',
		'81.209.177.16',
		'81.209.177.189',

		), 1);

	$boese_hostnames = array_fill_keys(array(


		// Hier sind vollständige Hostnamen
		// Bei ehrlichen Crawlern zuverlaessiger
		// Bei unehrlichen Crawlern Ausschluss von IPs erwaegen

		'bardolino1.netestate.de',
		'bardolino.netestate.de',
		'vinsanto.netestate.de',
		'bardolino2.netestate.de',

		), 1);

	$boese_strings = array(

		// Hier sind Strings, die man nicht in einem Hostnamen akzeptiert
		// Der Test erfolgt mit strpos auf Vorhandensein im gesamten Hostnamen

		'.netestate.de',

		);

	// --------------------------

	// IP-Adresse bekommen
	$remote_ip = $_SERVER['REMOTE_ADDR'];

	// boesen IPs nicht antworten
	if($boese_ips[$remote_ip])
	{
		exit;
	}

	// --------------------------

	// Host name nachschauen
	$remote_hostname = gethostbyaddr($remote_ip);

	// ...wenn das funktioniert hat
	if($remote_hostname != $remote_ip)
	{
		// Boesen Hostnamen nicht antworten
		if($boese_hostnames[$remote_hostname])
		{
			exit;
		}

		// Hostnames mit boesem String darin nicht antworten
		foreach($boese_strings as $boeser_string)
		{
			if(strpos(
				$remote_hostname,
				$boeser_string) !== FALSE)
			{
				exit;
			}
		}
	}
}

boesen_adressen_nicht_antworten();

?>