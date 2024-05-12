<?php
namespace App\Doctrine;

use App\Entity\Company;
use App\Entity\Order;
use App\Entity\Product;
use App\Entity\Recipe;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class DeletedFilter extends SQLFilter
{
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
    {
        if($targetEntity->hasField("deleted_at")){
            return sprintf('%s.deleted_at IS NULL',$targetTableAlias);
        }

        return '';
    }
}
?>