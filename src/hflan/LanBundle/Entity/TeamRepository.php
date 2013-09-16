<?php

namespace hflan\LanBundle\Entity;

use Doctrine\ORM\EntityRepository;
use hflan\LanBundle\Entity\Export\EventExport;

/**
 * TeamRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TeamRepository extends EntityRepository
{
    public function findTeamRegistrationData(Tournament $tournament)
    {
        $qb = $this->createQueryBuilder('t')
            ->select('COUNT(t), t.paid, t.infoLocked')
            ->where('t.tournament = :tournament')
            ->setParameter('tournament', $tournament)
            ->groupBy('t.paid, t.infoLocked');

        return $qb->getQuery()->getResult();
    }
    
    public function filter(EventExport $export)
    {
        $qb = $this->createQueryBuilder('t')
            ->where('t.tournament IN (:tournaments)')
            ->setParameter('tournaments', $export->getTournaments()->toArray());

        $lists = array(
            EventExport::LIST_PAID      => in_array(EventExport::LIST_PAID, $export->getLists()),
            EventExport::LIST_LOCKED    => in_array(EventExport::LIST_LOCKED, $export->getLists()),
            EventExport::LIST_BLANK     => in_array(EventExport::LIST_BLANK, $export->getLists()),
        );

        if(!$lists[EventExport::LIST_PAID] && !$lists[EventExport::LIST_LOCKED] && !$lists[EventExport::LIST_BLANK])
            $qb->andWhere('1 = 0');
        elseif(!$lists[EventExport::LIST_PAID] && !$lists[EventExport::LIST_LOCKED] && $lists[EventExport::LIST_BLANK])
            $qb->andWhere('t.infoLocked = false');
        elseif(!$lists[EventExport::LIST_PAID] && $lists[EventExport::LIST_LOCKED] && !$lists[EventExport::LIST_BLANK])
            $qb->andWhere('t.infoLocked = true AND t.paid = false');
        elseif(!$lists[EventExport::LIST_PAID] && $lists[EventExport::LIST_LOCKED] && $lists[EventExport::LIST_BLANK])
            $qb->andWhere('t.paid = false');
        elseif($lists[EventExport::LIST_PAID] && !$lists[EventExport::LIST_LOCKED] && !$lists[EventExport::LIST_BLANK])
            $qb->andWhere('t.paid = true');
        elseif($lists[EventExport::LIST_PAID] && !$lists[EventExport::LIST_LOCKED] && $lists[EventExport::LIST_BLANK])
            $qb->andWhere('t.infoLocked = false OR t.paid = true');
        elseif($lists[EventExport::LIST_PAID] && $lists[EventExport::LIST_LOCKED] && !$lists[EventExport::LIST_BLANK])
            $qb->andWhere('t.infoLocked = true');

        return $qb->getQuery()->getResult();
    }
}
