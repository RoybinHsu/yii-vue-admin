<template>
  <div class="dashboard-container">
    <div class="dashboard-text">name: {{ name }}</div>
    <div class="dashboard-text">name: {{ phone }}</div>
    <div class="dashboard-text">name: {{ email }}</div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
  name: 'HomeIndex',
  computed: {
    ...mapGetters([
      'name',
      'phone',
      'email',
      'menus'
    ])
  }
}
</script>

<style lang="scss" scoped>
.dashboard {
  &-container {
    margin: 30px;
  }

  &-text {
    font-size: 30px;
    line-height: 46px;
  }
}
</style>
