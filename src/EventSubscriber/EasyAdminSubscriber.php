<?php

namespace App\EventSubscriber;

use App\Entity\Article;
use App\Entity\Project;
use App\Entity\ProjectCategory;
use App\Entity\NewsCategory;
use App\Service\Slugify;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class EasyAdminSubscriber implements EventSubscriberInterface
{
    private object $slugger;

    public function __construct(
        Slugify $slugify
    ) {
        $this->slugger = $slugify;
    }

    public static function getSubscribedEvents(): array
    {
        return array(
            BeforeEntityPersistedEvent::class => ['setEntitiesPrePersist'],
            BeforeEntityUpdatedEvent::class => ['setEntitiesPreUpdate'],
        );
    }

    public function setEntitiesPrePersist(BeforeEntityPersistedEvent $event): void
    {
        $entity = $event->getEntityInstance();

        if ($entity instanceof Article) {
            $entity->setSlug($this->slugger->generate($entity->getTitle()));
        }

        return;
    }

    public function setEntitiesPreUpdate(BeforeEntityUpdatedEvent $event): void
    {
        $entity = $event->getEntityInstance();

        if ($entity instanceof Article) {
            $entity->setSlug($this->slugger->generate($entity->getTitle()));
        }
        
        return;
    }
}