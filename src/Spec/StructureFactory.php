<?php

namespace Codewiser\Exiftool\Spec;

use Codewiser\Exiftool\Attributes\StructureAttribute;
use Codewiser\Exiftool\Structures;;

class StructureFactory
{
    public function make(string $name): StructureAttribute
    {
        return new StructureAttribute(
            Specification::make()->struct($name)
        );
    }

    public function artworkOrObject(): StructureAttribute|Structures\ArtworkOrObject
    {
        return $this->make('ArtworkOrObject');
    }

    public function copyrightOwner(): StructureAttribute|Structures\CopyrightOwner
    {
        return $this->make('CopyrightOwner');
    }

    public function creatorContactInfo(): StructureAttribute|Structures\CreatorContactInfo
    {
        return $this->make('CreatorContactInfo');
    }

    public function cvTerm(): StructureAttribute|Structures\CvTerm
    {
        return $this->make('CvTerm');
    }

    public function embeddedEncodedRightsExpression(): StructureAttribute|Structures\EmbdEncRightsExpr
    {
        return $this->make('EmbdEncRightsExpr');
    }

    public function entity(): StructureAttribute|Structures\Entity
    {
        return $this->make('Entity');
    }

    public function entityWithRole(): StructureAttribute|Structures\EntityWRole
    {
        return $this->make('EntityWRole');
    }

    public function imageCreator(): StructureAttribute|Structures\ImageCreator
    {
        return $this->make('ImageCreator');
    }

    public function imageRegion(): StructureAttribute|Structures\ImageRegion
    {
        return $this->make('ImageRegion');
    }

    public function imageSupplier(): StructureAttribute|Structures\ImageSupplier
    {
        return $this->make('ImageSupplier');
    }

    public function licensor(): StructureAttribute|Structures\Licensor
    {
        return $this->make('Licensor');
    }

    public function linkedEncodedRightsExpression(): StructureAttribute|Structures\LinkedEncRightsExpr
    {
        return $this->make('LinkedEncRightsExpr');
    }

    public function location(): StructureAttribute|Structures\Location
    {
        return $this->make('Location');
    }

    public function personWithDetails(): StructureAttribute|Structures\PersonWDetails
    {
        return $this->make('PersonWDetails');
    }

    public function productWithGtin(): StructureAttribute|Structures\ProductWGtin
    {
        return $this->make('ProductWGtin');
    }

    public function regionBoundary(): StructureAttribute|Structures\RegionBoundary
    {
        return $this->make('RegionBoundary');
    }

    public function regionBoundaryPoint(): StructureAttribute|Structures\RegionBoundaryPoint
    {
        return $this->make('RegionBoundaryPoint');
    }

    public function registryEntry(): StructureAttribute|Structures\RegistryEntry
    {
        return $this->make('RegistryEntry');
    }
}
