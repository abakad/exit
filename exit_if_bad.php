<?php

// PUBLIC DOMAIN

function exit_if_bad_host()
{
	// Hier stellt man ein, welche Methoden
	// man verwenden möchte (1) oder nicht (0)
	
	$use_ips       = 1;
	$use_blocks    = 1;
	$use_hostnames = 1;
	$use_strings   = 1;

	$bad_ips = array_fill_keys(array(

		// Hier sind die einzelnen IPs
		// Achtung, IPs Ausschliessen auf eigene Gefahr

		'81.209.177.136',
		'81.209.177.145',
		'81.209.177.16',
		'81.209.177.189',
		'81.209.177.95',
		'138.201.30.176',

		), 1);

	$bad_blocks = array(

		// Hier sind IP-Bloecke
		// Achtung, IP-Bloecke Ausschliessen auf eigene Gefahr
		
		[ '81.209.177.0', '81.209.178.127' ],

		);

	$bad_hostnames = array_fill_keys(array(

		// Hier sind vollstaendige Hostnamen
		// Bei ehrlichen Crawlern zuverlaessiger
		// Bei unehrlichen Crawlern Ausschluss von IPs erwaegen

		'netestate.de',
		'bardolino1.netestate.de',
		'bardolino.netestate.de',
		'vinsanto.netestate.de',
		'bardolino2.netestate.de',
		'website-datenbank.de',

		), 1);

	$bad_strings = array(

		// Hier sind Strings, die man nicht in einem Hostnamen akzeptiert
		// Der Test erfolgt mit strpos auf Vorhandensein im gesamten Hostnamen

		'.netestate.de',

		);

	// IP-Adresse bekommen
	$remote_ip = $_SERVER['REMOTE_ADDR'];

	// ------------
	// Einzelne IPs

	if($use_ips)
	{
		// Bad IP nicht antworten
		if($bad_ips[$remote_ip])
		{
			exit;
		}
	}

	// -------
	// Bloecke

	if($use_blocks)
	{
		$remote_ip_long = ip2long($remote_ip);

		if($remote_ip_long !== FALSE)
		{
			foreach($bad_blocks as $block)
			{

				$from = ip2long($block[0]);
				$to   = ip2long($block[1]);

				// Bad block nicht antworten
				if($remote_ip_long >= $from
				&& $remote_ip_long <= $to)
				{
					exit;
				}
			}
		}
	}

	// ----------------------------------------
	// Ganze Hostnamen und Strings in Hostnamen

	// Hostname nachschauen
	$remote_hostname = gethostbyaddr($remote_ip);

	// ... wenn das funktioniert hat
	if($remote_hostname != $remote_ip)
	{
		// ---------------
		// Ganze Hostnamen

		if($use_hostnames)
		{
			// bad Hostname nicht antworten
			if($bad_hostnames[$remote_hostname])
			{
				exit;
			}
		}

		// --------------------
		// Strings in Hostnamen

		if($use_strings)
		{
			// Hostname mit bad String darin nicht antworten
			foreach($bad_strings as $badr_string)
			{
				if(strpos(
					$remote_hostname,
					$badr_string) !== FALSE)
				{
					exit;
				}
			}
		}
	}
}

exit_if_bad_host();

?>
