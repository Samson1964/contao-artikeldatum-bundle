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

		// Inserttag {{article_update::d.m.Y}}
		if($arrSplit[0] == 'article_update')
		{
			$datum = self::ladeDatum();
			// Parameter 2 angegeben?
			if(isset($arrSplit[1]))
			{
				// Parameter 2 angegeben?
				return date($arrSplit[1], $datum);
				//return date('d.m.Y H:i', $datum);
			}
			else
			{
				return date('d.m.Y H:i', $datum);
			}
		}

		return false; // Tag nicht dabei
	}

	public function ladeDatum()
	{
		global $objPage;

//https://community.contao.org/de/showthread.php?61903-Last-Modified&p=404109&viewfull=1#post404109
		
		// Wurde ein Artikel aufgerufen? Dann Artikel-ID ermitteln
		$alias_article = \Contao\Input::get('articles');
		if($alias_article)
		{
			// Ein Artikel wurde ermittelt
			//$objArticleModel = \Contao\ArticleModel::findByIdOrAliasAndPid($alias_article, $objPage->id);
			$objArticle = \Contao\ArticleModel::findByIdOrAlias($alias_article);
			$id_article = 0;
			if($objArticle)
			{
				$id_article = $objArticle->id;
			}
		}
		else
		{
			$objArticle = \Contao\ArticleModel::findOneByPid($objPage->id);
			$id_article = $objArticle->id;
		}
		
		//echo 'Artikel-ID='.$id_article;
		
		// Inhaltselemente finden
		$aktzeit = time();
		$objContent = \Contao\Database::getInstance()->prepare("SELECT * FROM tl_content WHERE pid = ? AND ptable = ? AND (start = ? OR start < ?) AND (stop = ? OR stop > ?) AND invisible = ?") 
		                                             ->execute($id_article, 'tl_article', '', $aktzeit, '', $aktzeit, ''); 
		$artikelzeit = $objArticle->tstamp;
		//echo 'Artikel '.$objArticle->id.' / Zeit: '.date('d.m.Y H:i', $objArticle->tstamp).'<br>';

		while($objContent->next())
		{
			//echo 'Inhaltselement '.$objContent->id.' / Zeit: '.date('d.m.Y H:i', $objContent->tstamp).'<br>';
			if($objContent->tstamp > $artikelzeit) $artikelzeit = $objContent->tstamp;
		}
		
		return $artikelzeit;

	}
}
