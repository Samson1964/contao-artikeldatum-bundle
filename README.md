# Inserttag für Contao um das aktuelle Artikeldatum anzuzeigen

Das Bundle stellt das Inserttag `{{article_update}}` bereit. Ausgegeben wird der
Zeitpunkt der letzten Änderung eines Artikels – also der jüngste Zeitstempel aus
dem Artikel selbst und seinen aktuell sichtbaren Inhaltselementen.

Maßgeblich ist der aufgerufene Artikel (URL-Parameter `articles`); wurde kein
Artikel aufgerufen, wird der erste Artikel der aktuellen Seite verwendet.

## Voraussetzungen ##

* PHP 8.0 oder neuer
* Contao 4.13 LTS oder Contao 5

## Installation ##

```
composer require schachbulle/contao-artikeldatum-bundle
```

## Verwendbare Inserttags ##

| Inserttag | Ausgabe |
| --- | --- |
| `{{article_update}}` | Datum und Uhrzeit im Format `d.m.Y H:i`, z. B. `29.07.2026 14:05` |
| `{{article_update::d.m.Y}}` | Datum im angegebenen Format |

Als Format ist jede von PHP unterstützte Formatangabe erlaubt. Die Ausgabe läuft
über `Contao\Date::parse()`, dadurch werden Monats- und Wochentagsnamen
(`F`, `M`, `l`, `D`) in der Sprache der Seite ausgegeben.

## Caching ##

Ab Contao 5 setzt das Inserttag automatisch die passenden Cache-Tags
(`contao.db.tl_article.*` und `contao.db.tl_content.*`). Der Seitencache wird
also verworfen, sobald der Artikel oder eines seiner Inhaltselemente gespeichert
wird – das Flag `uncached` ist dort nicht mehr nötig.

Unter Contao 4.13 gibt es diese Cache-Tags nicht. Damit dort immer das aktuelle
Datum erscheint, weiterhin das Flag `uncached` verwenden:

```
{{article_update|uncached}}
{{article_update::d.m.Y|uncached}}
```

## Technischer Hinweis ##

Ab Contao 5.2 wird das Inserttag über das Attribut `AsInsertTag` registriert.
Nur unter Contao 4.13 bis 5.1 greift der dort noch verfügbare Hook
`replaceInsertTags`. Dadurch entstehen unter Contao 5 keine Deprecation-Meldungen.

## Tests ##

```
vendor/bin/phpunit
```

## Entwickler ##

**Frank Binding**
