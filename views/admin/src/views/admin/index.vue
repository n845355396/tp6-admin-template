<template>
  <div>
    <el-row class="operation-scope">
      <el-button type="primary" @click="create">创建</el-button>
    </el-row>

    <el-row class="search-scope">
      <el-input
        class="query-word"
        clearable
        v-model="listQuery.query_word"
        placeholder="搜索管理员账号"
      ></el-input>
      <el-button type="primary" icon="el-icon-search" @click="queryWord"
        >搜索</el-button
      >
    </el-row>

    <el-table
      v-loading="listLoading"
      :data="list"
      row-key="admin_id"
      border
      style="width: 100%"
    >
      <el-table-column prop="login_name" label="账号"> </el-table-column>
      <el-table-column prop="avatar" label="头像">
        <template slot-scope="{ row }">
          <img :src="row.avatar" width="40" height="40" class="head_pic" />
        </template>
      </el-table-column>
      <el-table-column prop="nickname" label="昵称"> </el-table-column>
      <el-table-column prop="mobile" label="手机号"> </el-table-column>
      <el-table-column prop="role.role_name" label="角色">
        <template slot-scope="{ row }">
          <el-tag type="danger" v-if="row.is_super">{{
            row.role.role_name
          }}</el-tag>
          <el-tag v-else>{{ row.role.role_name }}</el-tag>
        </template>
      </el-table-column>
      <el-table-column prop="status" label="状态">
        <template slot-scope="{ row }">
          <el-tag type="danger" v-if="row.status == 1">禁用</el-tag>
          <el-tag type="success" v-else>正常</el-tag>
        </template>
      </el-table-column>
      <el-table-column prop="create_time" label="创建时间"> </el-table-column>
      <el-table-column prop="update_time" label="修改时间"> </el-table-column>
      <el-table-column fixed="right" label="操作" width="200">
        <template slot-scope="scope">
          <el-button type="text" size="small" @click="edit(scope.row.admin_id)"
            >编辑</el-button
          >
          <el-button
            type="text"
            size="small"
            @click="showUpPassword(scope.row.admin_id)"
            >修改密码</el-button
          >
          <el-button
            type="text"
            size="small"
            @click="disable(scope.row.admin_id, scope.row.status)"
            >{{ scope.row.status ? "启用" : "禁用" }}</el-button
          >
          <el-button type="text" size="small" @click="del(scope.row.admin_id)"
            >删除</el-button
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

    <!-- 编辑 -->
    <el-dialog
      :title="form.admin_id ? '编辑' : '新增'"
      :visible.sync="editFormVisible"
    >
      <el-form :model="form">
        <el-form-item
          v-if="form.is_super == 0"
          label="角色"
          :label-width="labelWidth"
        >
          <el-select v-model="form.role_id" placeholder="请选择角色">
            <el-option
              v-for="role in roleData"
              :key="role.role_id"
              :label="role.role_name"
              :value="role.role_id"
            ></el-option>
          </el-select>
        </el-form-item>

        <el-form-item label="管理员头像" :label-width="labelWidth">
          <single-upload v-model="form.avatar"></single-upload>
        </el-form-item>

        <el-form-item label="管理员账号" :label-width="labelWidth">
          <el-input v-model="form.login_name" autocomplete="off"></el-input>
        </el-form-item>

        <el-form-item
          label="管理员密码"
          :label-width="labelWidth"
          v-if="!form.admin_id"
        >
          <el-input
            placeholder="管理员密码"
            v-model="form.password"
            show-password
          ></el-input>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="editFormVisible = false">取 消</el-button>
        <el-button type="primary" @click="saveForm">确 定</el-button>
      </div>
    </el-dialog>

    <!-- 修改密码 -->
    <el-dialog title="修改密码" width="500px" :visible.sync="upPasswordVisible">
      <el-form :model="upPasswordData">
        <el-form-item label="管理员密码" :label-width="labelWidth">
          <el-input
            placeholder="管理员密码"
            v-model="upPasswordData.password"
            show-password
          ></el-input>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="upPasswordVisible = false">取 消</el-button>
        <el-button type="primary" @click="upPassword">确 定</el-button>
      </div>
    </el-dialog>
  </div>
</template>


<script>
import SingleUpload from "@/components/Upload/singleUpload";
import MultiUpload from "@/components/Upload/multiUpload";
import {
  adminList,
  adminCreate,
  adminEdit,
  adminInfo,
  adminDel,
  adminDisble,
  adminUpPassword,
} from "@/api/admin";
import { roleList } from "@/api/role";

import "@/styles/admin.scss";
import { mapGetters } from "vuex";

let self = null;
export default {
  name: "adminList",
  computed: {
    ...mapGetters(["name", "roles"]),
  },
  components: { SingleUpload, MultiUpload },
  data() {
    return {
      roleData: [],
      labelWidth: "100px",
      list: [],
      total: null,
      listLoading: true,
      listQuery: {
        query_word: "",
        page_no: 1,
        page_size: 10,
      },
      editFormVisible: false,
      form: {
        admin_id: null,
        login_name: null,
        password: null,
        role_id: null,
        avatar: "",
        is_super: 0,
      },
      upPasswordVisible: false,
      upPasswordData: {
        admin_id: null,
        password: null,
      },
    };
  },
  created() {
    self = this;
    this.getList();
  },
  methods: {
    clearForm() {
      this.form = {
        admin_id: null,
        login_name: null,
        password: null,
        role_id: null,
        avatar: null,
        is_super: 0,
      };
    },
    saveForm() {
      return new Promise((resolve, reject) => {
        if (self.form.admin_id) {
          adminEdit(self.form)
            .then((response) => {
              const { data, msg } = response;
              this.getList();
              self.$message({
                message: msg,
                type: "success",
              });
              this.editFormVisible = false;
              resolve(data);
            })
            .catch((error) => {
              reject(error);
            });
        } else {
          adminCreate(self.form)
            .then((response) => {
              const { data, msg } = response;
              this.getList();
              self.$message({
                message: msg,
                type: "success",
              });
              this.editFormVisible = false;

              resolve(data);
            })
            .catch((error) => {
              reject(error);
            });
        }
      });
    },
    create() {
      this.clearForm();
      this.roleList();
      this.editFormVisible = true;
    },
    async edit(adminId) {
      this.clearForm();
      this.roleList();
      await this.getAdminInfo(adminId);
      this.editFormVisible = true;
    },
    showUpPassword(adminId) {
      this.upPasswordData.admin_id = adminId;
      this.upPasswordVisible = true;
    },
    upPassword() {
      adminUpPassword(this.upPasswordData).then((response) => {
        self.upPasswordVisible = false;
        const { msg } = response;
        this.getList();
        this.$message({
          type: "success",
          message: msg,
        });
      });
    },
    del(adminId) {
      this.$confirm("确定删除此管理员?", "提示", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning",
      }).then(() => {
        adminDel({ admin_id: adminId }).then((response) => {
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
    disable(adminId, curStatus) {
      const status = curStatus == 1 ? 0 : 1;
      const tipSt = "确定" + (curStatus == 1 ? "启用" : "禁用") + "此管理员?";
      this.$confirm(tipSt, "提示", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning",
      }).then(() => {
        adminDisble({ admin_id: adminId, status: status }).then((response) => {
          const { msg } = response;
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
    async getList() {
      this.listLoading = true;
      const { data } = await adminList(this.listQuery);
      this.list = data.data;
      this.total = data.total;
      this.listLoading = false;
    },
    roleList() {
      return new Promise((resolve, reject) => {
        roleList({ hide_super: 1, page_size: 99999999 })
          .then((response) => {
            const { data } = response;
            this.roleData = data.data;
            resolve(data);
          })
          .catch((error) => {
            reject(error);
          });
      });
    },
    async getAdminInfo(adminId) {
      const loading = this.$loading({
        lock: true,
        text: "Loading",
        spinner: "el-icon-loading",
        background: "rgba(0, 0, 0, 0.7)",
      });
      await adminInfo({ admin_id: adminId }).then((response) => {
        const { data } = response;

        self.form.admin_id = data.admin_id;
        self.form.login_name = data.login_name;
        self.form.avatar = data.avatar;
        self.form.role_id = data.role != null ? data.role.role_id : null;
        self.form.is_super = data.is_super;
      });
      loading.close();
    },
    queryWord() {
      this.getList();
    },
  },
  watch: {
    upPasswordVisible(newVal, oldVal) {
      if (!newVal) {
        this.upPasswordData.admin_id = null;
        this.upPasswordData.password = null;
      }
    },
    editFormVisible(newVal, oldVal) {
      if (!newVal) {
        this.clearForm();
      }
    },
  },
};
</script>

<style lang="scss" scoped>
</style>
