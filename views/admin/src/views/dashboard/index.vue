<template>
  <div class="dashboard-container">
    <div class="dashboard-text">当前管理员: {{ name }}</div>
    <div class="dashboard-text">状态:等待开发~</span></div>
  </div>
</template>

<script>
import { mapGetters } from "vuex";

export default {
  name: "dashboard",
  computed: {
    ...mapGetters(["name", "roles"]),
  },
};
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
