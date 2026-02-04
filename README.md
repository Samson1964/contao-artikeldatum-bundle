# Inserttag für Contao um das aktuelle Artikeldatum anzuzeigen

## Verwendbare Inserttags ##

{{article_update}}<br>
{{article_update::d.m.Y}}

Um ein Caching zu verhindern, muß das Flag uncached angegeben werden, z.B.:

{{article_update|uncached}}<br>
{{article_update::d.m.Y|uncached}}

## Entwickler ##

**Frank Binding**
