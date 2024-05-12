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

class CompanyFilter extends SQLFilter
{
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
    {
        if ($this->hasParameter('companyId')) {

            $companyId = $this->getParameter("companyId");
            if($targetEntity->hasAssociation("company")){
                switch($targetEntity->name){
                    case Order::class:
                        if($this->getParameter("isNapaj") == "'1'"){
                            /* un utilisateur Napaj voit toutes les commandes 
                            car actuellement les commandes ne peuvent être passé qu'auprès de Napaj*/
                            return "";
                        }
                        return sprintf('%s.company_id = %s',$targetTableAlias,$companyId);
                        break;
                    case Recipe::class:
                    case Product::class:
                        if($this->getParameter("isNapaj") == "'1'"){
                            /* un utilisateur Napaj ne voit que ses produits */
                            return sprintf('%s.company_id = %s',$targetTableAlias,$companyId);
                        }else{
                            /* Les autre peuvent voir leurs produits et les produits Napaj */
                            return sprintf('%s.company_id in (%s,1)',$targetTableAlias,$companyId);
                        }
                        break;
                    default:
                        return sprintf('%s.company_id = %s',$targetTableAlias,$companyId);
                }
            }elseif ($targetEntity->name == Company::class){
                return sprintf('%s.id = %s',$targetTableAlias,$companyId);
            }
        }

        return '';
        // TODO: Implement addFilterConstraint() method.
    }
}
?>