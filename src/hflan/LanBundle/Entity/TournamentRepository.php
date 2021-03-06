<?php

namespace hflan\LanBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * TournamentRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TournamentRepository extends EntityRepository
{
    public function queryTournamentsOfEvent(Event $event = null)
    {
        $qb = $this->createQueryBuilder('t')
            ->where('t.event = :event')
            ->setParameter('event', $event);

        return $qb;
    }
}
