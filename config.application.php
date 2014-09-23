<?php

/**
 * Modelo de Configuracao 
 * [Explicar o arquivo]
 * return array(
 *   "config"   => array (
 *      "email_administrador" => array(
 *          "dilsonrabelo.unasus@gmail.com", "aldrea.mno@gmail.com", "romulomf@gmail.com",
 *      ),
 *      "unit_disk_default"  => "/", //Unidade de disco padrão do sistema Windows C:, Unix Like /
 *      "exclude_old_backup" => "7", //Medida em dias
 *   )
 *   "adapters" => array (
 *       "[NAME_ADAPTER]" => array (
 *           "CONFIG" => array(
 *              "type" => "FTP",
 *              "config" => array(
 *                  "server"    => "[server]",
 *                  "username"  => "[usuario]",
 *                  "password"  => "[senha]",
 *                  "port"      => "[porta]",
 *                  "timeout"   => 360,
 *              ),
 *          ),
 *          "DIRETORIOS" => array (
 *              "[DIR_BACKUP_1]" => "[NOME_ARQ_ZIP]",
 *              "[DIR_BACKUP_2]" => "[NOME_ARQ_ZIP]",
 *          ),
 *         "PATH_SAVE_BACKUP_DIRECTORY" => "[PATH]",
 *        ),
 *       "[NAME_ADAPTER]" => array (
 *           "CONFIG" => array(
 *              "type" => "NFS",
 *              "config" => array(
 *                  "server"    => "[server]",
 *                  "dir_share"  => "[dir_share]",
 *                  "dir_point_mount"  => "[dir_point_mount]",
 *              ),
 *          ),
 *          "DIRETORIOS" => array (
 *              "[DIR_BACKUP_1]" => "[NOME_ARQ_ZIP]",
 *              "[DIR_BACKUP_2]" => "[NOME_ARQ_ZIP]",
 *          ),
 *         "PATH_SAVE_BACKUP_DIRECTORY" => "[PATH]",
 *        ),
 *       "[NAME_ADAPTER]" => array (
 *           "CONFIG" => array(
 *              "type" => "MYSQL",
 *              "config" => array(
 *                  "server"     => "[IP_SERVER]",
 *                  "user"       => "[USER_SERVER]",
 *                  "password"   => "[PASSWORD_SERVER]"
 *              ),
 *          ),
 *          "DIRETORIOS" => array (
 *              "[BANCO]" => "[BANCO].sql",
 *          ),
 *         "PATH_SAVE_BACKUP_DIRECTORY" => "[PATH]",
 *        ),
 *   )
 * );
 */
return array(
    "config" => array(
        "email_administrador" => array(
            "dilsonrabelo.unasus@gmail.com",
        ),
       "unit_disk_default"  => "/", //Unidade de disco padrão do sistema Windows C:, Unix Like /
       "exclude_old_backup" => "1", //Medida em dias
    ),
    "adapters" => array(
        "LOCAL" => array(
            "CONFIG" => array(
                "type" => "FTP",
                "config" => array(
                    "server" => "ftp.ftp.pudim.com",
                    "username" => "usuario",
                    "password" => "senha",
                    "port" => "21",
                    "timeout" => 360,
                ),
            ),
            "DIRETORIOS" => array(
                "configuration_.php" => "configuration.zip",
            ),
            "PATH_SAVE_BACKUP_DIRECTORY" => "/Users/NME/www/SigU/backup",
        ),
    )
);
