<template>
  <div>
    <el-row class="operation-scope">
      <el-button type="primary" plain @click="btnStatus = ''"
        >全部任务</el-button
      >
      <el-button type="info" plain @click="btnStatus = 'waiting'"
        >等待中任务</el-button
      >
      <el-button type="warning" plain @click="btnStatus = 'failed'"
        >失败任务</el-button
      >
      <el-button type="warning" plain @click="btnStatus = 'retrying'"
        >重试中任务</el-button
      >
      <el-button type="success" plain @click="btnStatus = 'success'"
        >成功任务</el-button
      >
    </el-row>
    <el-table
      v-loading="listLoading"
      :data="list"
      row-key="role_id"
      border
      style="width: 100%"
    >
      <el-table-column prop="unique_code" label="标识码" width="300">
      </el-table-column>
      <el-table-column prop="queue_name" label="队列名"> </el-table-column>
      <el-table-column prop="task_name" label="任务名"> </el-table-column>
      <el-table-column prop="result" label="任务状态" width="100">
        <template slot-scope="{ row }">
          <el-tag v-if="row.result == 'waiting'">等待中</el-tag>
          <el-tag type="warning" v-if="row.result == 'retrying'">重试中</el-tag>
          <el-tag type="success" v-if="row.result == 'success'">成功</el-tag>
          <el-tag type="danger" v-if="row.result == 'failed'">失败</el-tag>
          <el-tag v-else></el-tag>
        </template>
      </el-table-column>
      <el-table-column prop="create_time" label="创建时间"> </el-table-column>
      <el-table-column prop="update_time" label="更新时间"> </el-table-column>
    </el-table>

    <el-pagination
      @current-change="handleCurrentChange"
      background
      layout="prev, pager, next"
      :total="total"
      :page-size="listQuery.page_size"
    >
    </el-pagination>
  </div>
</template>

<script>
import { taskList } from "@/api/task";
import "@/styles/queue.scss";
export default {
  name: "queueList",
  data() {
    return {
      btnStatus: "",
      labelWidth: "100px",
      list: [],
      total: null,
      listLoading: true,
      listQuery: {
        page_no: 1,
        page_size: 10,
        status: null,
      },
    };
  },
  created() {
    self = this;
    this.getList();
  },
  methods: {
    handleCurrentChange(val) {
      this.listQuery.page_no = val;
      this.getList();
    },
    getList() {
      this.listLoading = true;
      taskList(this.listQuery).then((response) => {
        const { data } = response;
        this.list = data.data;
        this.total = data.total;
        this.listLoading = false;
      });
    },
  },
  watch: {
    btnStatus(newVal, oldVal) {
      this.listQuery.page_no = 1;
      this.listQuery.status = newVal;
      this.getList();
    },
  },
};
</script>

<style scoped>
</style>
