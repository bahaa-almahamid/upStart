<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\ORM\EntityRepository;
use App\DTO\PostSearch;
use App\DTO\PostSearchFormType;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends EntityRepository
{


//    /**
//     * @return Post[] Returns an array of Post objects
//     */
    
 
    public function findByPostSearch(PostSearch $dto)
    {
        $queryBuilder = $this->createQueryBuilder('ta');
        if (!empty($dto->post))
        {
            $queryBuilder->andWhere('ta.post = :post');
            $queryBuilder->setParameter('post',$dto->post);
        }
        if (!empty($dto->search)) {

            $queryBuilder->andWhere('ta.title like :search');

            $queryBuilder->setParameter('search','%'.$dto->search.'%');
        }
        return $queryBuilder->getQuery()->execute();
        
    }
}
