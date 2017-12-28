<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Remove download_pictures in craue_config_setting.
 */
class Version20170420134133 extends AbstractMigration implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql('DELETE FROM `' . $this->getTable('craue_config_setting') . "` WHERE name = 'download_pictures';");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $downloadPictures = $this->container
            ->get('doctrine.orm.default_entity_manager')
            ->getConnection()
            ->fetchArray('SELECT * FROM `' . $this->getTable('craue_config_setting') . "` WHERE name = 'download_pictures'");

        $this->skipIf(false !== $downloadPictures, 'It seems that you already played this migration.');

        $this->addSql('INSERT INTO `' . $this->getTable('craue_config_setting') . "` (name, value, section) VALUES ('download_pictures', '1', 'entry')");
    }

    private function getTable($tableName)
    {
        return $this->container->getParameter('database_table_prefix') . $tableName;
    }
}
