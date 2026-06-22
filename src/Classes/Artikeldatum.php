<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2014 Leo Feyer
 *
 * @package   ContaoArtikeldatumBundle
 * @author    Frank Binding
 * @license   GNU/LGPL
 * @copyright Frank Hoppe 2014
 */

namespace Schachbulle\ContaoArtikeldatumBundle\Classes;

class Artikeldatum
{

	public function doReplace($tag, $blnCache, $strTag, $flags, $tags, $arrCache, $_rit, $_cnt)
	{
		$arrSplit = explode('::', $tag);

		// Inserttag {{article_update}} bzw. {{article_update::d.m.Y}}
		if($arrSplit[0] == 'article_update')
		{
			$datum = $this->ladeDatum();

			// Wurde ein Datumsformat als Parameter angegeben?
			if(isset($arrSplit[1]) && $arrSplit[1] !== '')
			{
				return date($arrSplit[1], (int) $datum);
			}

			return date('d.m.Y H:i', (int) $datum);
		}

		return false; // Tag gehört nicht zu diesem Bundle
	}

	public function ladeDatum()
	{
		global $objPage;

//https://community.contao.org/de/showthread.php?61903-Last-Modified&p=404109&viewfull=1#post404109

		$objArticle = null;

		// Wurde ein Artikel aufgerufen? Dann diesen Artikel verwenden ...
		$alias_article = \Contao\Input::get('articles');
		if($alias_article)
		{
			$objArticle = \Contao\ArticleModel::findByIdOrAlias($alias_article);
		}
		// ... ansonsten den ersten Artikel der aktuellen Seite
		elseif($objPage !== null)
		{
			$objArticle = \Contao\ArticleModel::findOneByPid($objPage->id);
		}

		// Kein Artikel gefunden: auf Seiten- bzw. aktuelle Zeit zurueckfallen (PHP-8-sicher)
		if($objArticle === null)
		{
			return ($objPage !== null) ? (int) $objPage->tstamp : time();
		}

		$id_article  = (int) $objArticle->id;
		$artikelzeit = (int) $objArticle->tstamp;

		// Sichtbare Inhaltselemente des Artikels finden und juengsten Aenderungszeitpunkt ermitteln
		$aktzeit = time();
		$objContent = \Contao\Database::getInstance()->prepare("SELECT tstamp FROM tl_content WHERE pid = ? AND ptable = ? AND (start = ? OR start < ?) AND (stop = ? OR stop > ?) AND invisible = ?")
		                                             ->execute($id_article, 'tl_article', '', $aktzeit, '', $aktzeit, '');

		while($objContent->next())
		{
			if((int) $objContent->tstamp > $artikelzeit)
			{
				$artikelzeit = (int) $objContent->tstamp;
			}
		}

		return $artikelzeit;

	}
}
