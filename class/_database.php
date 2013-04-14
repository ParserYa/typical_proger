<?php //if (!defined('ACCESS')) exit('Deny access');
/*
** Файл: database.php
** Описание: Связь с базой данных и доступ к сайту
** Версия: 2.5
** Создано: 26.02.2012
** Автор: Soroka Andrew
** Э/почта: unick@live.com
**
** Copyright by AndreiSoroka.com
** All rights reserved.
*/

/* <comment>
**	Класс MySql для связи с БД
**	 Функция GetConenction осуществляет связь БД MySQL
** 
*/
class MySql
{
    static protected $SqlConnection = null;
    static function GetConnection()
    {
        if (self::$SqlConnection === null)
        {
            @require_once 'config.php';
            if (!@$link = mysql_connect(HOST, USER_DB, PASS_DB))
            {
                exit('not connect db');
            }
            if (!@mysql_select_db(DB,$link))
            {
                exit('not select db');
            }
            mysql_set_charset("utf8");
        }
        return self::$SqlConnection=true;
    }
	static function CloseConnection()
	{
		if (self::$SqlConnection === true)
        {
			@mysql_close();
			return self::$SqlConnection=null;
		}
	}
}
/*
** Окончание файла database.php
** Расположение файла: ./core/database.php
*/
?>
