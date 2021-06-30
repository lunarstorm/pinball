<template>
	<header class="app-header app-header-dark">
		<div class="top-bar">
			<div class="top-bar-brand">
				<a href="/" title="{{ $page.props.app.name }}">
					{{ $page.props.app.name }}
				</a>
			</div>

			<div class="top-bar-list">
				<div class="top-bar-item px-2 d-md-none d-lg-none d-xl-none">
					<button
					  class="hamburger hamburger-squeeze"
					  type="button"
					  data-toggle="aside"
					  aria-label="toggle menu"
					>
						<span class="hamburger-box"><span class="hamburger-inner"></span></span>
					</button>
				</div>

				<div class="top-bar-item top-bar-item-right px-0 d-none d-sm-flex">
					<div class="dropdown">
						<button
						  class="btn-account d-none d-md-flex"
						  type="button"
						  data-toggle="dropdown"
						  aria-haspopup="true"
						  aria-expanded="false"
						>
							<span class="account-summary pr-lg-4 d-none d-lg-block">
								<span class="account-name">{{ $page.props.app.user.name }}</span>
								<span class="account-description">{{ $page.props.app.user.email }}</span>
							</span>
						</button>

						<div class="dropdown-menu">
							<div class="dropdown-arrow"></div>
							<h6 class="dropdown-header d-none d-md-block d-lg-none">{{ $page.props.app.user.name }}</h6>

							<inertia-link
							  class="dropdown-item"
							  href="/logout"
							  method="post"
							  as="button"
							>
								<span class="dropdown-icon fa fa-sign-out"></span>
								Logout
							</inertia-link>
						</div>
					</div>
				</div>
			</div>
		</div>
	</header>
</template>

<script>
export default {
	name: "Header"
}
</script>

<style scoped>
</style>