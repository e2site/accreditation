<?php
/**
 * Created by PhpStorm.
 * User: prosto
 * Date: 12.12.2017
 * Time: 13:16
 */

class m000000_000000_group_base extends yupe\components\DbMigration{
    /**
     * Функция настройки и создания таблицы:
     *
     * @return null
     **/
    public function safeUp()
    {
        // blog
        $this->createTable(
            '{{accreditation_group}}',
            [
                'id'             => "pk",
                'name'           => "varchar(250) NOT NULL",
                'description'    => "text",
                'template'           => "varchar(250) NOT NULL DEFAULT ''",
                'is_barcode'     => "integer NOT NULL DEFAULT '0'",
                'status'         => "integer NOT NULL DEFAULT '1'",
            ],
            $this->getOptions()
        );


        $this->createIndex("ix_{{accreditation_group}}_status", '{{accreditation_group}}', "status", false);



        // post
        $this->createTable(
            '{{accreditation_user}}',
            [
                'id'             => "pk",
                'firstname'         => "varchar(250) NOT NULL",
                'lastname'          => "varchar(250) NOT NULL",
                'surname'           => "varchar(250) NOT NULL",
                'barcode'           => "varchar(64) NOT NULL",

                'group_id'    => "integer DEFAULT NULL",

                'photo'           => "varchar(250) NOT NULL DEFAULT ''",

                'create_user_id' => "integer NOT NULL",
                'update_user_id' => "integer NOT NULL",
                'create_date'    => "integer NOT NULL",
                'update_date'    => "integer NOT NULL",
                'comment'        => "text",
                'is_print'       => "integer NOT NULL DEFAULT '0'",
                'issued'         => "integer NOT NULL DEFAULT '0'",

                'status'         => "integer NOT NULL DEFAULT '1'",

            ],
            $this->getOptions()
        );

        //ix
        $this->createIndex("ix_{{accreditation_user}}_firstname", '{{accreditation_user}}', "firstname", false);
        $this->createIndex("ix_{{accreditation_user}}_lastname", '{{accreditation_user}}', "lastname", false);
        $this->createIndex("ix_{{accreditation_user}}_surname", '{{accreditation_user}}', "surname", false);

        $this->createIndex("fx_{{accreditation_user}}_barcode", '{{accreditation_user}}', "barcode", true);

        $this->createIndex("ix_{{accreditation_user}}_group_id", '{{accreditation_user}}', "group_id", false);

        $this->createIndex("ix_{{accreditation_user}}_is_print", '{{accreditation_user}}', "is_print", false);
        $this->createIndex("ix_{{accreditation_user}}_issued", '{{accreditation_user}}', "issued", false);
        $this->createIndex("ix_{{accreditation_user}}_status", '{{accreditation_user}}', "status", false);


        //fks
        $this->addForeignKey(
            "fk_{{blog_post}}_group_id",
            '{{accreditation_user}}',
            'group_id',
            '{{accreditation_group}}',
            'id',
            'CASCADE',
            'NO ACTION'
        );

    }

    /**
     * Удаляем талицы
     *
     * @return null
     **/
    public function safeDown()
    {
        $this->dropTableWithForeignKeys('{{accreditation_user}}');
        $this->dropTableWithForeignKeys('{{accreditation_group}}');
    }
}