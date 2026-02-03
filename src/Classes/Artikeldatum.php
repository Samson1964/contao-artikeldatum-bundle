<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2014 Leo Feyer
 *
 * @package   fh-counter
 * @author    Frank Hoppe
 * @license   GNU/LGPL
 * @copyright Frank Hoppe 2014
 */

namespace Schachbulle\ContaoArtikeldatumBundle\Classes;

class Artikeldatum extends \Frontend
{

	public function Tag($strTag)
	{
		$arrSplit = explode('::', $strTag);

		// Inserttag {{fide::ID::Name}}
		// Liefert zu einer FIDE-ID den Link
		// Parameter 1 (ID) = FIDE-ID
		// Parameter 2 (Elo) = Name des Links, Standard ist "FIDE-Karteikarte"
		if($arrSplit[0] == 'fide' || $arrSplit[0] == 'cache_fide')
		{
			// Parameter 1 angegeben?
			if(isset($arrSplit[1]))
			{
				if(isset($arrSplit[2]))
				{
					// Parameter 2 angegeben?
					return '<a href="https://ratings.fide.com/profile/'.$arrSplit[1].'" target="_blank">'.$arrSplit[2].'</a>';
				}
				else
				{
					return '<a href="https://ratings.fide.com/profile/'.$arrSplit[1].'" target="_blank">FIDE-Karteikarte</a>';
				}
			}
		}
		
		// Inserttag {{wp-de::Name}}
		// Liefert zu den Link zu einem deutschen Wikipediaartikel. Es wird eine Grafik von schachbund.de angezeigt.
		// Parameter 1 (Name) = Artikelname
		if($arrSplit[0] == 'wp-de' || $arrSplit[0] == 'cache_wp-de')
		{
			// Parameter 1 angegeben?
			if(isset($arrSplit[1]))
			{
				return '<a href="https://de.wikipedia.org/wiki/'.$arrSplit[1].'" target="_blank" title="Wikipedia"><img src="files/dsb/icons/wikipedia_32.png"></a>';
			}
		}
		
		// Inserttag {{wp-en::Name}}
		// Liefert zu den Link zu einem englischen Wikipediaartikel. Es wird eine Grafik von schachbund.de angezeigt.
		// Parameter 1 (Name) = Artikelname
		if($arrSplit[0] == 'wp-en' || $arrSplit[0] == 'cache_wp-en')
		{
			// Parameter 1 angegeben?
			if(isset($arrSplit[1]))
			{
				return '<a href="https://en.wikipedia.org/wiki/'.$arrSplit[1].'" target="_blank" title="Wikipedia"><img src="files/dsb/icons/wikipedia_32.png"></a>';
			}
		}
		
		// Inserttag {{homepage::URL}}
		// Liefert zu den Link zu einer Homepage. Es wird eine Grafik von schachbund.de angezeigt.
		// Parameter 1 (URL) = URL der Homepage
		if($arrSplit[0] == 'homepage' || $arrSplit[0] == 'cache_homepage')
		{
			// Parameter 1 angegeben?
			if(isset($arrSplit[1]))
			{
				return '<a href="'.$arrSplit[1].'" target="_blank" title="Homepage"><img src="files/dsb/theme/icons/homepage_32.png"></a>';
			}
		}

		// Inserttag {{twitch::Name}}
		// Liefert zu den Link zu Twitch. Es wird eine Grafik von schachbund.de angezeigt.
		// Parameter 1 (Name) = Benutzername bei Twitch
		if($arrSplit[0] == 'twitch' || $arrSplit[0] == 'cache_twitch')
		{
			// Parameter 1 angegeben?
			if(isset($arrSplit[1]))
			{
				return '<a href="https://www.twitch.tv/'.$arrSplit[1].'" target="_blank" title="Twitch"><img src="files/dsb/theme/icons/twitch_32.png"></a>';
			}
		}

		// Inserttag {{youtube::Name}}
		// Liefert zu den Link zu Youtube. Es wird eine Grafik von schachbund.de angezeigt.
		// Parameter 1 (Name) = Benutzername bei Youtube ggfs. mit channel/ID davor
		if($arrSplit[0] == 'youtube' || $arrSplit[0] == 'cache_youtube')
		{
			// Parameter 1 angegeben?
			if(isset($arrSplit[1]))
			{
				return '<a href="https://www.youtube.com/'.$arrSplit[1].'" target="_blank" title="YouTube"><img src="files/dsb/theme/icons/youtube_32.png"></a>';
			}
		}

		return false; // Tag nicht dabei
	}

}
