<?php

if (!function_exists('can')) {
    /**
     * Check if user has permission for given module & action
     *
     * @param string $module  (e.g. 'pengguna', 'exchangerate')
     * @param string $action  (e.g. 'canView', 'canInsert', 'canUpdate', 'canDelete')
     * @return bool
     */
    function can(string $module, string $action): bool
    {
        $session = session();

        // if role is admin => always true
        if ($session->get('role') === 'admin' || 'natha01') {
            return true;
        }

        $permissions = $session->get('permissions');

        if (!empty($permissions->$module->$action) && $permissions->$module->$action == 1) {
            return true;
        }

        return false;
    }
}
