<?php
/**
 * This file is part of the Tmdb PHP API created by Michael Roterman.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package Tmdb
 * @author Michael Roterman <michael@wtfz.net>
 * @copyright (c) 2013, Michael Roterman
 * @version 0.0.1
 */
namespace Tmdb\Factory;

use Tmdb\Factory\People\CastFactory;
use Tmdb\Factory\People\CrewFactory;
use Tmdb\Model\Common\GenericCollection;
use Tmdb\Model\Tv\ExternalIds;
use Tmdb\Model\Tv\Person\CastMember;
use Tmdb\Model\Tv\Person\CrewMember;
use Tmdb\Model\Tv\Episode;

class TvEpisodeFactory extends AbstractFactory {
    /**
     * {@inheritdoc}
     */
    public static function create(array $data = array())
    {
        if (!$data) {
            return null;
        }

        $tvEpisode = new Episode();

        if (array_key_exists('credits', $data)) {
            if (array_key_exists('cast', $data['credits'])) {
                $tvEpisode->getCredits()->setCast(CastFactory::createCollection($data['credits']['cast'], new CastMember()));
            }

            if (array_key_exists('crew', $data['credits'])) {
                $tvEpisode->getCredits()->setCrew(CrewFactory::createCollection($data['credits']['crew'], new CrewMember()));
            }
        }

        /** External ids */
        if (array_key_exists('external_ids', $data)) {
            $tvEpisode->setExternalIds(
                parent::hydrate(new ExternalIds(), $data['external_ids'])
            );
        }

        /** Images */
        if (array_key_exists('images', $data)) {
            $tvEpisode->setImages(ImageFactory::createCollectionFromTv($data['images']));
        }

        return parent::hydrate($tvEpisode, $data);
    }

    /**
     * {@inheritdoc}
     */
    public static function createCollection(array $data = array())
    {
        $collection = new GenericCollection();

        foreach($data as $item) {
            $collection->add(null, self::create($item));
        }

        return $collection;
    }
}