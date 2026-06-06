<?php

namespace App\Traits;

/**
 * Reusable trait for permission checking in Dashboard Controllers.
 * Eliminates the duplicate `checkPermission()` method defined in each controller.
 */
trait ChecksPermissions
{
    /**
     * Abort with 403 if the current admin doesn't have the given permission
     * or isn't a Super Admin.
     */
    protected function checkPermission(string $permission): void
    {
        /** @var \App\Models\Admin $admin */
        $admin = auth('admin')->user();

        abort_if(
            !$admin->hasRole('Super Admin') && !$admin->can($permission),
            403,
            'غير مصرح لك بتنفيذ هذه العملية.'
        );
    }

    /**
     * Abort with 403 if the admin can't perform any of the given permissions.
     */
    protected function checkAnyPermission(array $permissions): void
    {
        /** @var \App\Models\Admin $admin */
        $admin = auth('admin')->user();

        if ($admin->hasRole('Super Admin')) {
            return;
        }

        $hasAny = collect($permissions)->some(fn ($perm) => $admin->can($perm));
        abort_if(!$hasAny, 403, 'غير مصرح لك بتنفيذ هذه العملية.');
    }
}
