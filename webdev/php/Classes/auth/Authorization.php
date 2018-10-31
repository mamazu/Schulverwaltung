<?php

/**
 * A class for managing authorisation
 * Class Authorization
 */
class Authorization
{

    private $id;
    /**
     * @var array
     */
    private $permissionList = [];

    /**
     * Creates a permission list based on the userId.
     *
     * @param int $id
     */
    public function __construct($id)
    {
        global $database;
        require_once 'Permission.php';
        $this->id = intval($id);
        $result = $database->query("
		SELECT
			permission__names.permNode AS 'node',
			permission__users.permId AS 'id'
		FROM permission__users
		JOIN permission__names
		ON ABS(permission__users.permId) = permission__names.id
		WHERE userId = $this->id
		ORDER BY LENGTH(permNode) DESC;");

        while ($row = $result->fetch_assoc()) {
            $nodeName = $row['node'];
            if ($row['id'] < 0) {
                $nodeName = '- ' . $nodeName;
            }
            $key = (string) abs($row['id']);
            $this->permissionList[$key] = new Permission($nodeName);
        }
    }

    /**
     * Returns if the user has a certain permission.
     *
     * @param int    $id   Id of the user
     * @param string $node Name of the permission node
     *
     * @return bool
     */
    public static function userHasPermission($id, $node)
    {
        global $database;
        require_once 'Permission.php';
        $result = $database->query("SELECT permNode FROM permission__users JOIN permission__names ON permId = permission__names.id WHERE userId = $id;");
        while ($row = $result->fetch_row()) {
            $perm = new Permission($result->fetch_row()[0]);
            if ($perm->match($node)) {
                return true;
            }

        }
        return false;
    }

    /**
     * Returns the id inited in the constructor
     * @return int
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Checks whether the user has the permission
     *
     * @param string $node
     *
     * @return boolean
     */
    public function hasPermission($node)
    {
        foreach ($this->permissionList as $permission) {
            if ($permission->hasPermission($node)) {
                return true;
            }

        }
        return false;
    }

    /**
     * Sets a new permission node for the user
     *
     * @param string  $node    Name of the node
     * @param boolean $enabled Should it be granted or not
     *
     * @return bool True if it was inserted, false if the permission already exists
     */
    public function addPermission($node, $enabled)
    {
        if ($this->permissionExists($node)) {
            return false;
        }

        $nodeId = $this->addNode($node);
        $this->permissionList[$nodeId] = new Permission($node);
        $this->permissionList[$nodeId]->setRevoked(!$enabled);
        return true;
    }

    /**
     * Checks if the user has already any permission.
     *
     * @param string $node Node to find
     *
     * @return bool True if there is already a permission for that, false otherwise
     */
    private function permissionExists($node)
    {
        foreach ($this->permissionList as $permission) {
            if ($permission->match($node)) {
                return true;
            }

        }
        return false;
    }

    /**
     * Adds a node to the database and returns the id.
     *
     * @param string $node
     *
     * @return int
     */
    private static function addNode($node)
    {
        global $database;
        $strNode = (string) $node;
        $database->query("INSERT IGNORE INTO permission__names(permNode) VALUES ('$strNode');");
        if ($database->affected_rows == 0) {
            $id = $database->query("SELECT id FROM permission__names WHERE permNode = '$strNode'");
            $row = $id->fetch_row();
            return $row[0];
        }
        return $database->insert_id;
    }

    /**
     * Revokes the permission node that is entered. Returns true on success.
     *
     * @param string $node
     *
     * @return boolean
     */
    public function revokePermission($node)
    {
        /**
         * @var Permission $permission
         */
        global $database;
        foreach ($this->permissionList as $permID => $permission) {
            if (!$permission->match($node)) {
                continue;
            }

            $permission->togglePermission();
            $database->query("UDPDATE permission__users SET permId = -($permID) WHERE userId = $this->id AND permId = ABS($permID);");
            return $database->errno == 0;
        }
        return false;
    }

    /**
     * Returns the list of permissions the user has
     * @return string
     */
    public function __toString()
    {
        $result = '';
        foreach ($this->permissionList as $key) {
            $result .= (string) $key . "<br />";
        }

        return $result;
    }

}
