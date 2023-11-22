<?php
/**
 * Microsoft Access driver.
 *
 * Requires ODBC. Works only on Microsoft Windows.
 *
 * This file is part of ADOdb, a Database Abstraction Layer library for PHP.
 *
 * @package ADOdb
 * @link https://adodb.org Project's web site and documentation
 * @link https://github.com/ADOdb/ADOdb Source code and issue tracker
 *
 * The ADOdb Library is dual-licensed, released under both the BSD 3-Clause
 * and the GNU Lesser General Public Licence (LGPL) v2.1 or, at your option,
 * any later version. This means you can use it in proprietary products.
 * See the LICENSE.md file distributed with this source code for details.
 * @license BSD-3-Clause
 * @license LGPL-2.1-or-later
 *
 * @copyright 2000-2013 John Lim
 * @copyright 2014 Damien Regad, Mark Newnham and the ADOdb community
 */

if (!defined('_ADODB_ODBC_LAYER')) {
    if (!defined('ADODB_DIR')) {
        die();
    }

    include_once(ADODB_DIR."/drivers/adodb-odbc.inc.php");
}

if (!defined('_ADODB_ACCESS')) {
    define('_ADODB_ACCESS', 1);

    class ADODB_access extends ADODB_odbc
    {
        public $databaseType = 'access';
        public $hasTop = 'top';		// support mssql SELECT TOP 10 * FROM TABLE
        public $fmtDate = "#Y-m-d#";
        public $fmtTimeStamp = "#Y-m-d h:i:sA#"; // note not comma
        public $_bindInputArray = false; // strangely enough, setting to true does not work reliably
        public $sysDate = "FORMAT(NOW,'yyyy-mm-dd')";
        public $sysTimeStamp = 'NOW';
        public $hasTransactions = false;
        public $upperCase = 'ucase';

        public function Time()
        {
            return time();
        }

        public function BeginTrans()
        {
            return false;
        }

        public function IfNull($field, $ifNull)
        {
            return " IIF(IsNull($field), $ifNull, $field) "; // if Access
        }
        /*
            function MetaTables()
            {
            global $ADODB_FETCH_MODE;

                $savem = $ADODB_FETCH_MODE;
                $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
                $qid = odbc_tables($this->_connectionID);
                $rs = new ADORecordSet_odbc($qid);
                $ADODB_FETCH_MODE = $savem;
                if (!$rs) return false;

                $arr = $rs->GetArray();
                //print_pre($arr);
                $arr2 = array();
                for ($i=0; $i < sizeof($arr); $i++) {
                    if ($arr[$i][2] && $arr[$i][3] != 'SYSTEM TABLE')
                        $arr2[] = $arr[$i][2];
                }
                return $arr2;
            }*/
    }


    class ADORecordSet_access extends ADORecordSet_odbc
    {
        public $databaseType = "access";

    } // class

}
