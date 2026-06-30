import Swal from 'sweetalert2';

const baseUrl = document.querySelector('meta[name="base-url"]').content;


/*
|--------------------------------------------------------------------------
| ROLE CREATE
|--------------------------------------------------------------------------
*/

window.openRoleCreateModal = function () {
    document.getElementById('roleCreateModal').classList.remove('hidden');
    document.getElementById('roleCreateModal').classList.add('flex');
};

window.closeRoleCreateModal = function () {
    document.getElementById('roleCreateModal').classList.add('hidden');
    document.getElementById('roleCreateModal').classList.remove('flex');
};

/*
|--------------------------------------------------------------------------
| ROLE EDIT
|--------------------------------------------------------------------------
*/

window.editRole = function (role) {

    document.getElementById('edit_role_name').value = role.name;

    document.querySelectorAll('.role-permission-checkbox').forEach(item => {
        item.checked = false;
    });

    if (role.permissions) {

        role.permissions.forEach(permission => {

            const checkbox = document.querySelector(
                `.role-permission-checkbox[value="${permission.id}"]`
            );

            if (checkbox) {
                checkbox.checked = true;
            }

        });

    }

    const form = document.getElementById('roleEditForm');
    form.action = baseUrl + `/central/acl/roles/${role.id}`;

    document.getElementById('roleEditModal').classList.remove('hidden');
    document.getElementById('roleEditModal').classList.add('flex');

};

window.closeRoleEditModal = function () {

    document.getElementById('roleEditModal').classList.add('hidden');
    document.getElementById('roleEditModal').classList.remove('flex');

};

/*
|--------------------------------------------------------------------------
| PERMISSION CREATE
|--------------------------------------------------------------------------
*/

window.openPermissionCreateModal = function () {

    document.getElementById('permissionCreateModal').classList.remove('hidden');
    document.getElementById('permissionCreateModal').classList.add('flex');

};

window.closePermissionCreateModal = function () {

    document.getElementById('permissionCreateModal').classList.add('hidden');
    document.getElementById('permissionCreateModal').classList.remove('flex');

};

/*
|--------------------------------------------------------------------------
| PERMISSION EDIT
|--------------------------------------------------------------------------
*/

window.editPermission = function (permission) {

    document.getElementById('edit_permission_name').value = permission.name;
    document.getElementById('edit_permission_alias').value = permission.alias;
    document.getElementById('edit_permission_group').value = permission.group;

    const form = document.getElementById('permissionEditForm');
    form.action = baseUrl + `/central/acl/permissions/${permission.id}`;

    document.getElementById('permissionEditModal').classList.remove('hidden');
    document.getElementById('permissionEditModal').classList.add('flex');

};

window.closePermissionEditModal = function () {

    document.getElementById('permissionEditModal').classList.add('hidden');
    document.getElementById('permissionEditModal').classList.remove('flex');

};

/*
|--------------------------------------------------------------------------
| DELETE ROLE
|--------------------------------------------------------------------------
*/

window.deleteRole = function (id) {

    Swal.fire({
        title: 'Excluir Regra?',
        text: 'Esta ação não poderá ser desfeita.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#FF0000',
        cancelButtonColor: '#6B7280',
        confirmButtonText: 'Excluir',
        cancelButtonText: 'Cancelar',
    }).then((result) => {

        if (!result.isConfirmed) {
            return;
        }

        const form = document.createElement('form');

        form.method = 'POST';
        form.action = baseUrl + `/central/acl/roles/${id}`;

        form.innerHTML = `
            <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
            <input type="hidden" name="_method" value="DELETE">
        `;

        document.body.appendChild(form);
        form.submit();

    });

};

/*
|--------------------------------------------------------------------------
| DELETE PERMISSION
|--------------------------------------------------------------------------
*/

window.deletePermission = function (id) {

    Swal.fire({
        title: 'Excluir permissão?',
        text: 'Esta ação não poderá ser desfeita.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#FF0000',
        cancelButtonColor: '#6B7280',
        confirmButtonText: 'Excluir',
        cancelButtonText: 'Cancelar',
    }).then((result) => {

        if (!result.isConfirmed) {
            return;
        }

        const form = document.createElement('form');

        form.method = 'POST';
        form.action = baseUrl + `/central/acl/permissions/${id}`;

        form.innerHTML = `
            <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
            <input type="hidden" name="_method" value="DELETE">
        `;

        document.body.appendChild(form);
        form.submit();

    });

};