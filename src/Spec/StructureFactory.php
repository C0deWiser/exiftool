<?php

namespace Codewiser\Exiftool\Spec;

use Codewiser\Exiftool\Attributes\StructureAttribute;
use Codewiser\Exiftool\Structures;;

class StructureFactory
{
    protected function factory(string $name): StructureAttribute
    {
        return new StructureAttribute(
            Specification::make()->struct($name)
        );
    }

    public function artworkOrObject(): StructureAttribute|Structures\ArtworkOrObject
    {
        return $this->factory('ArtworkOrObject');
    }

    public function copyrightOwner(): StructureAttribute|Structures\CopyrightOwner
    {
        return $this->factory('CopyrightOwner');
    }

    public function creatorContactInfo(): StructureAttribute|Structures\CreatorContactInfo
    {
        return $this->factory('CreatorContactInfo');
    }

    public function cvTerm(): StructureAttribute|Structures\CvTerm
    {
        return $this->factory('CvTerm');
    }

    public function embeddedEncodedRightsExpression(): StructureAttribute|Structures\EmbdEncRightsExpr
    {
        return $this->factory('EmbdEncRightsExpr');
    }

    public function entity(): StructureAttribute|Structures\Entity
    {
        return $this->factory('Entity');
    }

    public function entityWithRole(): StructureAttribute|Structures\EntityWRole
    {
        return $this->factory('EntityWRole');
    }

    public function imageCreator(): StructureAttribute|Structures\ImageCreator
    {
        return $this->factory('ImageCreator');
    }

    public function imageRegion(): StructureAttribute|Structures\ImageRegion
    {
        return $this->factory('ImageRegion');
    }

    public function imageSupplier(): StructureAttribute|Structures\ImageSupplier
    {
        return $this->factory('ImageSupplier');
    }

    public function licensor(): StructureAttribute|Structures\Licensor
    {
        return $this->factory('Licensor');
    }

    public function linkedEncodedRightsExpression(): StructureAttribute|Structures\LinkedEncRightsExpr
    {
        return $this->factory('LinkedEncRightsExpr');
    }

    public function location(): StructureAttribute|Structures\Location
    {
        return $this->factory('Location');
    }

    public function personWithDetails(): StructureAttribute|Structures\PersonWDetails
    {
        return $this->factory('PersonWDetails');
    }

    public function productWithGtin(): StructureAttribute|Structures\ProductWGtin
    {
        return $this->factory('ProductWGtin');
    }

    public function regionBoundary(): StructureAttribute|Structures\RegionBoundary
    {
        return $this->factory('RegionBoundary');
    }

    public function regionBoundaryPoint(): StructureAttribute|Structures\RegionBoundaryPoint
    {
        return $this->factory('RegionBoundaryPoint');
    }

    public function registryEntry(): StructureAttribute|Structures\RegistryEntry
    {
        return $this->factory('RegistryEntry');
    }
}
