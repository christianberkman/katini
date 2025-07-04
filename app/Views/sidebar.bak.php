<div class="sidebar-content">
	<!--dashboard-->
		<div class="sidebar-main <?= url_is('/dashboard*') ? 'active' : ''; ?>">
			<a href="<?= site_url('/dashboard'); ?>" class="stretched-link">
				<i class="bi bi-grid"></i> Dashboard
			</a>
		</div>

	<!--supporters-->
		<div class="sidebar-main <?= url_is('supporters') ? 'active' : ''; ?>">
			<a href="<?= site_url('supporters'); ?>" class="stretched-link">
				<i class="bi bi-people"></i> Supporters
			</a>
		</div>
		<ul class="sidebar-sub mb-0 collapse <?= url_is('supporters*') ? 'show' : ''; ?>" id="sidebar-supporters">
			<li class="<?= url_is('/supporters') ? 'active' : ''; ?>">
				<a href="<?= site_url('/supporters'); ?>" class="stretched-link">Overzicht</a>
			</li>
			<li class="<?= url_is('/supporters/find') ? 'active' : ''; ?>">
				<a href="<?= site_url('/supporters/find'); ?>" class="stretched-link">Supporter vinden</a>
			</li>
			<li class="<?= url_is('/supporters/new') ? 'active' : ''; ?>">
				<a href="<?= site_url('/supporters/new'); ?>" class="stretched-link">Supporter toevoegen</a>
			</li>
			<li class="<?= url_is('/supporters/all') ? 'active' : ''; ?>">
				<a href="<?= site_url('/supporters/all'); ?>" class="stretched-link">Alle supporters</a>
			</li>
		</ul>

	<!--circles-->
		<div class="sidebar-main <?= url_is('circles*') ? 'active' : ''; ?>">
			<a href="<?= site_url('circles'); ?>" class="stretched-link">
				<i class="bi bi-circle"></i> Kringen
			</a>
		</div>

	<!--donations-->
		<div class="sidebar-main <?= url_is('donations') ? 'active' : ''; ?>">
			<a href="<?= site_url('donations'); ?>" class="stretched-link">
				<i class="bi bi-cash-coin"></i> Donaties
			</a>
		</div>
		<ul class="sidebar-sub mb-0 collapse <?= url_is('donations*') ? 'show' : ''; ?>" id="sidebar-donations">
			<li class="<?= url_is('/donations') ? 'active' : ''; ?>">
				<a href="<?= site_url('/donations'); ?>" class="stretched-link">Overzicht</a>
			</li>
			<li class="<?= url_is('/donations/new') ? 'active' : ''; ?>">
				<a href="<?= site_url('/donations/new'); ?>" class="stretched-link">Donatie toevoegen</a>
			</li>
			<li class="<?= url_is('/donations/all') ? 'active' : ''; ?>">
				<a href="<?= site_url('/donations/all'); ?>" class="stretched-link">Alle donaties</a>
			</li>
		</ul>

	<!--settings-->
		<div class="sidebar-main <?= url_is('settings*') ? 'active' : ''; ?>">
			<a href="<?= site_url('settings'); ?>" class="stretched-link">
				<i class="bi bi-gear"></i> Instellingen
			</a>
		</div>

	<!--logout-->
		<div class="sidebar-main <?= url_is('logout*') ? 'active' : ''; ?>">
			<a href="<?= site_url('logout'); ?>" class="stretched-link">
				<i class="bi bi-box-arrow-right"></i> Uitloggen
			</a>
		</div>

</div><!--/sidebar-content-->
