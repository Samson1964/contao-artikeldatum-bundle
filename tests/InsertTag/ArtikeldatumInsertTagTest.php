<?php

declare(strict_types=1);

/*
 * Dieses Bundle stellt das Inserttag {{article_update}} bereit.
 *
 * @author    Frank Binding
 * @license   LGPL-3.0-or-later
 */

namespace Schachbulle\ContaoArtikeldatumBundle\Tests\InsertTag;

use PHPUnit\Framework\TestCase;
use Schachbulle\ContaoArtikeldatumBundle\InsertTag\ArtikeldatumInsertTag;

class ArtikeldatumInsertTagTest extends TestCase
{
    public function testFremdeInserttagsWerdenNichtBeansprucht(): void
    {
        $listener = new ArtikeldatumInsertTag();

        $this->assertFalse($listener->doReplace('env::request'));
        $this->assertFalse($listener->doReplace('article_updated'));
        $this->assertFalse($listener->doReplace(''));
    }

    public function testOhneInhaltselementeGiltDerArtikelZeitstempel(): void
    {
        [$timestamp, $cacheTags] = ArtikeldatumInsertTag::mergeContentTimestamps(5, 1000, []);

        $this->assertSame(1000, $timestamp);
        $this->assertSame(['contao.db.tl_article.5'], $cacheTags);
    }

    public function testJuengeresInhaltselementGewinnt(): void
    {
        [$timestamp, $cacheTags] = ArtikeldatumInsertTag::mergeContentTimestamps(5, 1000, [
            ['id' => 11, 'tstamp' => 900],
            ['id' => 12, 'tstamp' => 2000],
        ]);

        $this->assertSame(2000, $timestamp);
        $this->assertSame(
            ['contao.db.tl_article.5', 'contao.db.tl_content.11', 'contao.db.tl_content.12'],
            $cacheTags,
        );
    }

    public function testAeltereInhaltselementeAendernNichts(): void
    {
        [$timestamp] = ArtikeldatumInsertTag::mergeContentTimestamps(5, 3000, [
            ['id' => 11, 'tstamp' => 900],
        ]);

        $this->assertSame(3000, $timestamp);
    }

    public function testDatenbankwerteWerdenNachIntGewandelt(): void
    {
        // Die Datenbank liefert je nach Treiber Strings zurueck
        [$timestamp, $cacheTags] = ArtikeldatumInsertTag::mergeContentTimestamps(5, 1000, [
            ['id' => '11', 'tstamp' => '2000'],
        ]);

        $this->assertSame(2000, $timestamp);
        $this->assertSame(['contao.db.tl_article.5', 'contao.db.tl_content.11'], $cacheTags);
    }
}
