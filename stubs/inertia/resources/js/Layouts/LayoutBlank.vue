<template>
  <div>
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

export default {
    name: 'LayoutBlank',
    components: {
    },
};
</script>

<style scoped>

</style>