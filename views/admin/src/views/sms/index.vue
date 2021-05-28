<template>
  <div>
    <el-row class="operation-scope">
      <el-button type="primary" plain @click="btnStatus = ''"
        >全部</el-button
      >
      <el-button type="info" plain @click="btnStatus = 'waiting'"
        >等待中</el-button
      >
      <el-button type="warning" plain @click="btnStatus = 'failed'"
        >已失败</el-button
      >
      <el-button type="success" plain @click="btnStatus = 'success'"
        >已成功</el-button
      >
    </el-row>
    <el-table
      v-loading="listLoading"
      :data="list"
      row-key="role_id"
      border
      style="width: 100%"
    >
      <el-table-column prop="type_name" label="短信类型名称"> </el-table-column>
      <el-table-column prop="type" label="短信类型"> </el-table-column>
      <el-table-column prop="mobile" label="手机号"> </el-table-column>
      <el-table-column prop="status" label="状态">
        <template slot-scope="{ row }">
          <el-tag type="warning" v-if="row.status == 'waiting'"
            >等待发送</el-tag
          >
          <el-tag type="success" v-if="row.status == 'success'"
            >发送成功</el-tag
          >
          <el-tag type="failed" v-if="row.status == 'failed'">发送失败</el-tag>
        </template>
      </el-table-column>
      <el-table-column prop="is_retry" label="重发状态" width="100">
        <template slot-scope="{ row }">
          <el-tag type="warning" v-if="row.is_retry == 1">已重发</el-tag>
          <el-tag type="danger" v-else>未重发</el-tag>
        </template>
      </el-table-column>
      <el-table-column prop="create_time" label="创建时间"> </el-table-column>
      <el-table-column prop="update_time" label="更新时间"> </el-table-column>
      <el-table-column prop="retry_time" label="重发时间"> </el-table-column>

      <el-table-column fixed="right" label="操作" width="100">
        <template slot-scope="scope">
          <el-button
            v-if="scope.row.status == 'failed' && scope.row.is_retry == 0"
            type="text"
            size="small"
            @click="retry(scope.row.sms_id)"
            >短信重发</el-button
          >
        </template>
      </el-table-column>
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
import { smsList,smsRetry } from "@/api/sms";
import "@/styles/sms.scss";
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
    retry(id) {
      this.$confirm("确定重发此短信?", "提示", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning",
      }).then(() => {
        smsRetry({ sms_id: id }).then((response) => {
          const { msg } = response;
          this.listQuery.page_no = 1;
          this.getList();
          this.$message({
            type: "success",
            message: msg,
          });
        });
      });
    },
    handleCurrentChange(val) {
      this.listQuery.page_no = val;
      this.getList();
    },
    getList() {
      this.listLoading = true;
      smsList(this.listQuery).then((response) => {
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
