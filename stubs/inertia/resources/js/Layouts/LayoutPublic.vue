<template>
  <div>
    <LayoutHeader />

    <main class="app-main">
      <div class="wrapper">
        <div class="page">
          <div class="page-inner">
            <div class="container">
              <slot />
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script>
import LayoutHeader from './Header';

export default {
    name: 'LayoutDefault',
    components: {
        LayoutHeader,
    },
};
</script>

<style scoped>

</style>