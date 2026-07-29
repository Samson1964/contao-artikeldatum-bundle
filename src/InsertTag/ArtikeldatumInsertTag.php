<?php

declare(strict_types=1);

/*
 * Dieses Bundle stellt das Inserttag {{article_update}} bereit.
 *
 * @author    Frank Binding
 * @license   LGPL-3.0-or-later
 */

namespace Schachbulle\ContaoArtikeldatumBundle\InsertTag;

use Contao\ArticleModel;
use Contao\ContentModel;
use Contao\CoreBundle\DependencyInjection\Attribute\AsInsertTag;
use Contao\CoreBundle\InsertTag\InsertTagResult;
use Contao\CoreBundle\InsertTag\OutputType;
use Contao\CoreBundle\InsertTag\ResolvedInsertTag;
use Contao\Date;
use Contao\Input;
use Contao\PageModel;

/**
 * Liefert den Zeitpunkt der letzten Änderung eines Artikels.
 *
 * Die Klasse bedient zwei Schnittstellen:
 *
 *  - ab Contao 5.2 das Attribut AsInsertTag (siehe __invoke()),
 *  - bis Contao 5.1 (und damit auch Contao 4.13) den Hook "replaceInsertTags"
 *    (siehe doReplace()), der in der config.php nur dann registriert wird,
 *    wenn das Attribut nicht zur Verfügung steht.
 */
final class ArtikeldatumInsertTag
{
    /**
     * Name des Inserttags.
     */
    public const TAG = 'article_update';

    /**
     * Datumsformat, wenn im Inserttag keines angegeben wurde.
     */
    public const DEFAULT_FORMAT = 'd.m.Y H:i';

    /**
     * Inserttag {{article_update}} bzw. {{article_update::d.m.Y}} (ab Contao 5.2).
     */
    #[AsInsertTag(self::TAG)]
    public function __invoke(ResolvedInsertTag $insertTag): InsertTagResult
    {
        [$timestamp, $cacheTags] = $this->resolveTimestamp();

        return new InsertTagResult(
            $this->formatTimestamp($timestamp, $insertTag->getParameters()->get(0)),
            OutputType::text,
            cacheTags: $cacheTags,
        );
    }

    /**
     * Hook "replaceInsertTags" für Contao 4.13 bis 5.1.
     *
     * Die Signatur ist durch den Hook vorgegeben; alle Parameter außer dem Tag
     * selbst werden hier nicht benötigt.
     *
     * @param string $strTag Das Inserttag inklusive Parameter, aber ohne Flags
     *
     * @return string|false Der Ersatztext oder false, wenn das Tag nicht zu
     *                      diesem Bundle gehört
     */
    public function doReplace($strTag, $blnCache = false, $strCache = '', $flags = [], $tags = [], $arrCache = [], $_rit = 0, $_cnt = 0)
    {
        $chunks = explode('::', (string) $strTag);

        if (self::TAG !== strtolower($chunks[0])) {
            return false;
        }

        [$timestamp] = $this->resolveTimestamp();

        return $this->formatTimestamp($timestamp, $chunks[1] ?? null);
    }

    /**
     * Ermittelt aus dem Artikel und seinen Inhaltselementen den jüngsten
     * Änderungszeitpunkt sowie die passenden Cache-Tags.
     *
     * @param array<array<string, mixed>> $contentRows Zeilen aus tl_content mit den Spalten "id" und "tstamp"
     *
     * @return array{0: int, 1: list<string>}
     */
    public static function mergeContentTimestamps(int $articleId, int $articleTstamp, array $contentRows): array
    {
        $timestamp = $articleTstamp;
        $cacheTags = ['contao.db.tl_article.'.$articleId];

        foreach ($contentRows as $row) {
            $timestamp = max($timestamp, (int) ($row['tstamp'] ?? 0));
            $cacheTags[] = 'contao.db.tl_content.'.(int) ($row['id'] ?? 0);
        }

        return [$timestamp, $cacheTags];
    }

    /**
     * Formatiert den Zeitstempel; ohne Formatangabe gilt DEFAULT_FORMAT.
     */
    private function formatTimestamp(int $timestamp, ?string $format): string
    {
        if (null === $format || '' === $format) {
            $format = self::DEFAULT_FORMAT;
        }

        return Date::parse($format, $timestamp);
    }

    /**
     * @return array{0: int, 1: list<string>}
     */
    private function resolveTimestamp(): array
    {
        $article = $this->findArticle();

        // Kein Artikel gefunden: auf die Seiten- bzw. die aktuelle Zeit zurückfallen
        if (null === $article) {
            $page = $this->getPage();

            return [null !== $page ? (int) $page->tstamp : time(), []];
        }

        return self::mergeContentTimestamps(
            (int) $article->id,
            (int) $article->tstamp,
            $this->findContentRows((int) $article->id),
        );
    }

    /**
     * Ermittelt den maßgeblichen Artikel: entweder den aufgerufenen Artikel
     * oder – falls keiner aufgerufen wurde – den ersten Artikel der Seite.
     */
    private function findArticle(): ?ArticleModel
    {
        $alias = Input::get('articles');

        if (\is_string($alias) && '' !== $alias) {
            return ArticleModel::findByIdOrAlias($alias);
        }

        $page = $this->getPage();

        if (null === $page) {
            return null;
        }

        // Über die Sortierung gehen, damit immer derselbe Artikel gefunden wird
        return ArticleModel::findOneBy(
            ['tl_article.pid = ?'],
            [(int) $page->id],
            ['order' => 'tl_article.sorting'],
        );
    }

    /**
     * Liest die aktuell sichtbaren Inhaltselemente eines Artikels.
     *
     * Bewusst über das Core-Model und nicht über eine eigene Abfrage: Die
     * Spalte "invisible" ist in Contao 4.13 ein char(1) und ab Contao 5 ein
     * tinyint. Das Model kennt die jeweils richtige Bedingung und beachtet
     * zusätzlich den Vorschaumodus.
     *
     * @return array<array<string, mixed>>
     */
    private function findContentRows(int $articleId): array
    {
        $elements = ContentModel::findPublishedByPidAndTable($articleId, 'tl_article');

        return null !== $elements ? $elements->fetchAll() : [];
    }

    private function getPage(): ?PageModel
    {
        $page = $GLOBALS['objPage'] ?? null;

        return $page instanceof PageModel ? $page : null;
    }
}
