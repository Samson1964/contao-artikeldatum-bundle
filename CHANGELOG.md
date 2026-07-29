# Artikeldatum Changelog

## Version 1.1.0 (2026-07-29) - mit Claude Code

* Add: Das Bundle läuft jetzt unter **Contao 4.13 LTS und Contao 5** (getestet
  gegen 4.13.58 und 5.7 mit PHP 8.3). Bisher war nur Contao 5 möglich.
* Add: Ab Contao 5.2 wird das Inserttag über das Attribut `AsInsertTag`
  registriert. Damit entfällt unter Contao 5 die Deprecation-Meldung
  „Using the replaceInsertTags hook is deprecated". Der Hook wird nur noch dort
  registriert, wo das Attribut fehlt (Contao 4.13 bis 5.1).
* Add: Ab Contao 5 setzt das Inserttag die Cache-Tags des Artikels und seiner
  Inhaltselemente. Der Seitencache wird dadurch automatisch verworfen, wenn sich
  etwas ändert – das Flag `uncached` wird dort nicht mehr benötigt.
* Fix: Die Inhaltselemente werden über `ContentModel::findPublishedByPidAndTable()`
  ermittelt statt über eine eigene SQL-Abfrage. Die Spalte `tl_content.invisible`
  ist in Contao 4.13 ein `char(1)` und ab Contao 5 ein `tinyint` – die alte
  Abfrage mit `invisible = ''` erzeugte unter Contao 5 MySQL-Warnungen
  („Truncated incorrect DOUBLE value"). Zusätzlich werden jetzt auch noch nicht
  gestartete bzw. bereits abgelaufene Elemente sowie ungespeicherte Entwürfe
  korrekt übergangen und der Vorschaumodus berücksichtigt.
* Fix: `Input::get('articles')` wird auf den Typ `string` geprüft. Ein
  Array-Parameter (`?articles[]=x`) führte unter PHP 8 zu einem TypeError.
* Fix: Der erste Artikel einer Seite wird jetzt nach `sorting` ermittelt und ist
  damit reproduzierbar; bisher entschied die Datenbank über die Reihenfolge.
* Add: Ausgabe über `Contao\Date::parse()` statt `date()` – Monats- und
  Wochentagsnamen erscheinen jetzt in der Sprache der Seite.
* Add: Unit-Tests unter `tests/` samt `phpunit.xml.dist`.
* Aufgeräumt: Klasse `Classes\Artikeldatum` nach `InsertTag\ArtikeldatumInsertTag`
  verschoben, `declare(strict_types=1)` in allen Dateien, wirkungslose
  `_instanceof`-Definition aus der Service-Konfiguration entfernt
  (`services.yml` → `services.yaml`), veraltete Entwicklungs-Abhängigkeit
  `doctrine/doctrine-cache-bundle` entfernt und die tatsächlich benötigten
  Symfony-Komponenten in der `composer.json` deklariert.

## Version 1.0.2 (2026-06-21) - mit Claude Code

* Kompatibilität mit PHP 8 (getestet mit 8.3) und Contao 5.7 geprüft und sichergestellt
* Fix: Statischen Aufruf `self::ladeDatum()` durch `$this->ladeDatum()` ersetzt (nicht-statische Methode, PHP-8-sauber)
* Fix: Null-Prüfungen für `$objPage` und `$objArticle` ergänzt – verhindert „Attempt to read property on null"-Warnungen unter PHP 8, wenn keine Seite bzw. kein Artikel vorhanden ist; Rückfall auf Seiten- bzw. aktuelle Zeit
* Zeitstempel durchgängig nach `int` gecastet (saubere Übergabe an `date()` unter PHP 8.1+)
* Datenbankabfrage liest nur noch die benötigte Spalte `tstamp` statt `SELECT *`
* Korrektur: Falscher Header-Eintrag `@package fh-counter` in `config.php` auf `ContaoArtikeldatumBundle` geändert
* Entfernt: Obsoletes `_instanceof` für `Symfony\Component\DependencyInjection\ContainerAwareInterface` in `services.yml` – dieses Interface gibt es in Symfony 7 (Contao 5.7) nicht mehr
* Hinweis: Der genutzte `replaceInsertTags`-Hook ist seit Contao 5.2 deprecated und entfällt erst in Contao 6 – unter Contao 5 weiterhin voll funktionsfähig

## Version 1.0.1 (2026-02-04)

* Fix: Immer gleiches Datum wurde ausgegeben, weil aus Artikel bundles geladen wurde (ein Test)

## Version 1.0.0 (2026-02-04)

* Ausbau und Anpassung an Contao 5

## Version 0.0.1 (2026-02-03)

* Initialversion als Contao-5-Bundle
