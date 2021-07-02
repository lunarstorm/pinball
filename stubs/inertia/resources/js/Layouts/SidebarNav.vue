<template>
	<div
	  ref="menu"
	  id="stacked-menu"
	  class="stacked-menu"
	>
		<nav class="menu">
			<div class="menu-item">
				<inertia-link
				  href="/settings"
				  class="menu-link"
				>
					<i class="menu-icon fa fa-building"></i>
					<span class="menu-text">Settings</span>
				</inertia-link>
			</div>
		</nav>
	</div>
</template>

<script>

export default {
	name: "SidebarNav",
	components: {
	},
	mounted() {
	}
}
</script>

<style scoped>

</style>