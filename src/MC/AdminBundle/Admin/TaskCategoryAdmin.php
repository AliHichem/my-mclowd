<?php

namespace MC\AdminBundle\Admin;

use Sonata\AdminBundle\Admin\Admin as BaseAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;

use Knp\Menu\ItemInterface as MenuItemInterface;

use App\Entity\Task;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

class TaskCategoryAdmin extends BaseAdmin implements ContainerAwareInterface
{
    protected $baseRouteName = 'admin_task_category';
    protected $baseRoutePattern = 'categories';

    protected $container;

    /**
     * @param \Sonata\AdminBundle\Show\ShowMapper $showMapper
     *
     * @return void
     */
    protected function configureShowField(ShowMapper $showMapper)
    {
        $showMapper
            ->add('name')            
            ->add('materializedPath')                        
        ;
    }
    
    /**
     * @param \Sonata\AdminBundle\Form\FormMapper $formMapper
     *
     * @return void
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name')             
        ;
    }

    /**
     * @param \Sonata\AdminBundle\Datagrid\ListMapper $listMapper
     *
     * @return void
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->addIdentifier('name')
            ->add('materializedPath')                        
        ;
    }

    /**
     * @param \Sonata\AdminBundle\Datagrid\DatagridMapper $datagridMapper
     *
     * @return void
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name')
        ;
    }

    /**
     * Creates new category. We lock table and ensure parent root and correct id is set
     *
     **/
    public function create($object)
    {
        $em = $this->container->get('doctrine')->getEntityManager();
        $em->getConnection()->beginTransaction();
        $table = $em->getClassMetadata('App:TaskCategory')->getTableName();
        try {
            $root = $em->getRepository('App:TaskCategory')->getRootNodes()[0];
            
            $em->getConnection()->exec('LOCK TABLES '.$table.' WRITE;');

            $stmt = 'SELECT MAX(id) FROM '. $table;
            $id = $em->getConnection()->fetchColumn($stmt);
            $object->setId(++$id);
            $object->setChildOf($root);

            $em->persist($object);
            $em->flush();
            $em->getConnection()->commit();
            
        } catch (Exception $e) {
            $em->getConnection()->rollback();
            $em->close();
            throw $e;
        }
        $em->getConnection()->exec('UNLOCK TABLES;');

        $this->postPersist($object);
        $this->createObjectSecurity($object);
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
    
    
}