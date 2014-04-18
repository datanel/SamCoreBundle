<?php

namespace CanalTP\SamCoreBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * RoleRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RoleRepository extends EntityRepository
{
    /**
     * Return roles with parent
     * @return type
     */
    public function findAllWithParent()
    {
        return $this->createQueryBuilder('a')
                    ->join('a.applicationRoles', 'p')
                    ->getQuery()
                    ->getResult();
    }

    public function findByRoleJoinedToRequestMatcher(Array $roles)
    {
        $roles = $this->findByRole($roles);
        $result = array();
        foreach ($roles as $role) {
            foreach ($role->getRequestMatchers() as $requestMatcher) {
                if ($requestMatcher->isDomainComponent()) {
                    $result[] = $requestMatcher;
                }
            }
        }

        return $result;
    }

    public function findRolesByUserAndApplication($userId, $appName)
    {
        return $this->createQueryBuilder('r')
            ->join('r.userApplications', 'ua')
            ->join('ua.application', 'app')
            ->where('ua.user = :user_id')
            ->andWhere('app.canonicalName = :appName')
            ->setParameter('appName', $appName)
            ->setParameter('user_id', $userId)
            ->getQuery()
            ->getResult();
    }

    public function findAll()
    {
        return $this->createQueryBuilder('r')
            ->addSelect('a')
            ->join('r.application', 'a')
            ->getQuery()
            ->getResult();
    }
}
