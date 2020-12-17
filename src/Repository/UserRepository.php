<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    private LoggerInterface $logger;
    private UserPasswordEncoderInterface $encoder;

    public function __construct(ManagerRegistry $registry, LoggerInterface $logger, UserPasswordEncoderInterface  $encoder)
    {
        parent::__construct($registry, User::class);
        $this->logger = $logger;
        $this->encoder = $encoder;
    }

    public function saveUser($firstName, $lastName, $username, $email, $password)
    {
        $user = new User();

        $encodedPwd = $this->encoder->encodePassword($user, $password);

        $user
            ->setFirstName($firstName)
            ->setLastName($lastName)
            ->setUsername($username)
            ->setEmail($email)
            ->setPassword($encodedPwd);

        try {
            $this->getEntityManager()->persist($user);
            $this->getEntityManager()->flush();
        } catch (ORMException $e) {
            $this->logger->error($e->getMessage());
        }
    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
