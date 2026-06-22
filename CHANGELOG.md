# Inserttag für Contao um das aktuelle Artikeldatum anzuzeigen

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
