<?php

namespace Tests\ShopBundle\Database\EntityExtension;

use Shopsys\ShopBundle\Component\EntityExtension\LoadORMMetadataSubscriber;

class OverwritableLoadORMMetadataSubscriber extends LoadORMMetadataSubscriber
{
    /**
     * @param string[] $entityExtensionMap
     */
    public function overwriteEntityExtensionMap(array $entityExtensionMap): void
    {
        $this->overriddenEntities = $entityExtensionMap;
        $this->parentClassesByClass = [];
        foreach ($entityExtensionMap as $entityExtensionParentName => $extendedEntityName) {
            $extendedEntityName = $this->getClass($extendedEntityName);
            $this->parentClassesByClass[$extendedEntityName] = array_values(class_parents($extendedEntityName));
        }
    }
}
