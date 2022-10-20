<template>
  <div class="app has-full-width">
    <HeaderBar />

    <aside class="app-aside app-aside-light app-aside-expand-md">
      <div class="aside-content">
        <div class="aside-menu overflow-hidden">
          <SidebarNav />
        </div>
      </div>
    </aside>

    <main class="app-main">
      <div class="wrapper">
        <div class="container p-3">
          <BreadCrumbs :crumbs="$page.props.crumbs" />
          <slot />
        </div>
      </div>
    </main>
  </div>
</template>

<script>
import HeaderBar from './Header';
import SidebarNav from '@/Layouts/SidebarNav';
import BreadCrumbs from 'vio/vendor/inertia/BreadCrumbs';
import _ from 'lodash';
import {watchEffect} from 'vue';

export default {
    name: 'LayoutDefault',
    components: {
        HeaderBar,
        SidebarNav,
        BreadCrumbs,
    },
    props: {
        app: Object,
    },
    mounted() {
        Looper.init();

        watchEffect(() => {
            let items = this.$page.props.app.messages || [];
            _.forEach(items, (item) => {
                this.$Messages.push(item);
            });
        });
    },
};
</script>

<style scoped>

</style>