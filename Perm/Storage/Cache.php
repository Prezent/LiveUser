<?php
// LiveUser: A framework for authentication and authorization in PHP applications
// Copyright (C) 2002-2003 Markus Wolff
//
// This library is free software; you can redistribute it and/or
// modify it under the terms of the GNU Lesser General Public
// License as published by the Free Software Foundation; either
// version 2.1 of the License, or (at your option) any later version.
//
// This library is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
// Lesser General Public License for more details.
//
// You should have received a copy of the GNU Lesser General Public
// License along with this library; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

/**
 * MDB2_Complex container for permission handling
 *
 * @package  LiveUser
 * @category authentication
 */

/**
 * Require parent class definition.
 */
require_once 'LiveUser/Perm/Storage.php';

/**
 * This is a PEAR::MDB2 backend driver for the LiveUser class.
 * A PEAR::MDB2 connection object can be passed to the constructor to reuse an
 * existing connection. Alternatively, a DSN can be passed to open a new one.
 *
 * Requirements:
 * - File "Liveuser.php" (contains the parent class "LiveUser")
 * - Array of connection options or a PEAR::MDB2 connection object must be
 *   passed to the constructor.
 *   Example: array('dsn' => 'mysql://user:pass@host/db_name')
 *              OR
 *            &$conn (PEAR::MDB2 connection object)
 *
 * @author  Lukas Smith <smith@backendmedia.com>
 * @author  Bjoern Kraus <krausbn@php.net>
 * @version $Id$
 * @package LiveUser
 * @category authentication
 */
class LiveUser_Perm_Storage_Cache extends LiveUser_Perm_Storage
{
    /**
     * Constructor
     *
     * @access protected
     * @param  mixed      configuration array
     * @return void
     */
    function LiveUser_Perm_Storage_Cache(&$confArray, &$storageConf)
    {
        $this->_storage = LiveUser::storageFactory($confArray, $storageConf);
    }

    function mapUser($uid, $containerName)
    {
        if (in_cache) {
            return cache;
        }
        $result = $this->_storage->mapUser($uid, $containerName);
        if ($result === false) {
            return false;
        }
        write_into_cache
        return $result;
    }

    /**
     * Reads all rights of current user into a
     * two-dimensional associative array, having the
     * area names as the key of the 1st dimension.
     * Group rights and invididual rights are being merged
     * in the process.
     */
    function readUserRights($permUserId)
    {
        if (in_cache) {
            return cache;
        }
        $result = $this->_storage->readUserRights($permUserId);
        if ($result === false) {
            return false;
        }
        write_into_cache
        return $result;
    }

    function readAreaAdminAreas($permUserId)
    {
        if (in_cache) {
            return cache;
        }
        $result = $this->_storage->readAreaAdminAreas($permUserId);
        if ($result === false) {
            return false;
        }
        write_into_cache
        return $result;
    }

    /**
     * Reads all the group ids in that the user is also a member of
     * (all groups that are subgroups of these are also added recursively)
     *
     * @access private
     * @see    readRights()
     * @return void
     */
    function readGroups($permUserId)
    {
        if (in_cache) {
            return cache;
        }
        $result = $this->_storage->readGroups($permUserId);
        if ($result === false) {
            return false;
        }
        write_into_cache
        return $result;
    } // end func readGroups

    /**
     * Reads the group rights
     * and put them in the array
     *
     * right => 1
     *
     * @access  public
     * @return  mixed   MDB2_Error on failure or nothing
     */
    function readGroupRights($groupIds)
    {
        if (in_cache) {
            return cache;
        }
        $result = $this->_storage->readGroupRights($groupIds);
        if ($result === false) {
            return false;
        }
        write_into_cache
        return $result;
    } // end func readGroupRights

    function readSubGroups($groupIds, $newGroupIds)
    {
        if (in_cache) {
            return cache;
        }
        $result = $this->_storage->readSubGroups($groupIds, $newGroupIds);
        if ($result === false) {
            return false;
        }
        write_into_cache
        return $result;
    }

    function readImplyingRights($rightIds, $table)
    {
        if (in_cache) {
            return cache;
        }
        $result = $this->_storage->readImplyingRights($rightIds, $table);
        if ($result === false) {
            return false;
        }
        write_into_cache
        return $result;
    }

    function readImpliedRights($currentRights, $currentLevel)
    {
        if (in_cache) {
            return cache;
        }
        $result = $this->_storage->readImpliedRights($currentRights, $currentLevel);
        if ($result === false) {
            return false;
        }
        write_into_cache
        return $result;
    }

    /**
     * properly disconnect from resources
     *
     * @access  public
     */
    function disconnect()
    {
        $this->_storage->disconnect();
    }
}
?>