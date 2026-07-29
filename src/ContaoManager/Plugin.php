<?php

declare(strict_types=1);

/*
 * Dieses Bundle stellt das Inserttag {{article_update}} bereit.
 *
 * @author    Frank Binding
 * @license   LGPL-3.0-or-later
 */

namespace Schachbulle\ContaoArtikeldatumBundle\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Schachbulle\ContaoArtikeldatumBundle\ContaoArtikeldatumBundle;

class Plugin implements BundlePluginInterface
{
    /**
     * {@inheritdoc}
     */
    public function getBundles(ParserInterface $parser): array
    {
        return [
            BundleConfig::create(ContaoArtikeldatumBundle::class)
                ->setLoadAfter([ContaoCoreBundle::class]),
        ];
    }
}
