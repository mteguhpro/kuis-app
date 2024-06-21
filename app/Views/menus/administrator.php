<li class="nav-item menu-open">
    <a href="#" class="nav-link">
        <i class="nav-icon fas fa-chart-pie"></i>
        <p>
            Dashboard
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="<?= site_url('dashboard/general') ?>" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>General</p>
            </a>
        </li>
    </ul>

    <a href="#" class="nav-link">
        <i class="nav-icon fas fa-database"></i>
        <p>
            Master
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="<?= site_url('administrator/master-user') ?>" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>User</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="<?= site_url('administrator/master-group') ?>" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Group</p>
            </a>
        </li>
    </ul>
</li>
