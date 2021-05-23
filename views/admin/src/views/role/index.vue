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
        placeholder="搜索角色账号"
      ></el-input>
      <el-button type="primary" icon="el-icon-search" @click="queryWord"
        >搜索</el-button
      >
    </el-row>

    <el-table
      v-loading="listLoading"
      :data="list"
      row-key="role_id"
      border
      style="width: 100%"
    >
      <el-table-column prop="role_name" label="角色名"> </el-table-column>

      <el-table-column prop="role.role_name" label="超级角色">
        <template slot-scope="{ row }">
          <el-tag type="danger" v-if="row.is_super_role">
            超级管理员角色
          </el-tag>
          <el-tag v-else>普通角色</el-tag>
        </template>
      </el-table-column>
      <el-table-column prop="status" label="状态">
        <template slot-scope="{ row }">
          <el-tag type="danger" v-if="row.status == 1">禁用</el-tag>
          <el-tag type="success" v-else>正常</el-tag>
        </template>
      </el-table-column>
      <el-table-column prop="sort" label="排序"> </el-table-column>
      <el-table-column prop="create_time" label="创建时间"> </el-table-column>
      <el-table-column prop="update_time" label="修改时间"> </el-table-column>
      <el-table-column fixed="right" label="操作" width="200">
        <template slot-scope="scope">
          <el-button
            v-if="scope.row.is_super_role != 1"
            type="text"
            size="small"
            @click="edit(scope.row.role_id)"
            >编辑</el-button
          >
          <el-button
            v-if="scope.row.is_super_role != 1"
            type="text"
            size="small"
            @click="disable(scope.row.role_id, scope.row.status)"
            >{{ scope.row.status ? "启用" : "禁用" }}</el-button
          >
          <el-button
            v-if="scope.row.is_super_role != 1"
            type="text"
            size="small"
            @click="del(scope.row.role_id)"
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
      :title="form.role_id ? '编辑' : '新增'"
      :visible.sync="editFormVisible"
      width="500px"
    >
      <el-form :model="form">
        <el-form-item label="角色名" :label-width="labelWidth">
          <el-input v-model="form.role_name" autocomplete="off"></el-input>
        </el-form-item>

        <el-form-item label="排序" :label-width="labelWidth">
          <el-input-number
            v-model="form.sort"
            label="越小越靠前"
          ></el-input-number>
        </el-form-item>

        <el-form-item label="菜单权限组" :label-width="labelWidth">
          <el-tree
            :data="menuList"
            show-checkbox
            node-key="id"
            :default-expanded-keys="form.menu_ids"
            :default-checked-keys="form.menu_ids"
            @check-change="menuCheckChange"
          >
          </el-tree>
        </el-form-item>

        <el-form-item label="功能权限组" :label-width="labelWidth">
          <el-tree
            :data="permissionList"
            show-checkbox
            node-key="id"
            :default-expanded-keys="form.permission_rules"
            :default-checked-keys="form.permission_rules"
            @check-change="permissionCheckChange"
          >
          </el-tree>
        </el-form-item>
      </el-form>

      <div slot="footer" class="dialog-footer">
        <el-button @click="editFormVisible = false">取 消</el-button>
        <el-button type="primary" @click="saveForm">确 定</el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import {
  roleList,
  roleInfo,
  permissionList,
  roleEdit,
  roleCreate,
  roleDisble,
  roleDel,
} from "@/api/role";
import { menuList } from "@/api/menu";

import "@/styles/role.scss";

let self = null;
export default {
  name: "roleList",
  computed: {},
  data() {
    return {
      labelWidth: "100px",
      list: [],
      total: null,
      listLoading: true,
      listQuery: {
        query_word: "",
        page_no: 1,
        page_size: 10,
        status: null,
        // hide_super: 1,
      },
      form: {
        role_id: null,
        role_name: null,
        sort: 100,
        menu_ids: [],
        permission_rules: [],
      },
      editFormVisible: false,
      permissionList: [],
      menuList: [],
    };
  },
  created() {
    self = this;
    this.getList();
  },
  methods: {
    menuCheckChange(data, checked, indeterminate) {
      //   console.log(data, checked, indeterminate);
      let id = data.id;
      if (checked && this.form.menu_ids.indexOf(id) == -1) {
        this.form.menu_ids.push(id);
      } else if (!checked && !indeterminate) {
        this.form.menu_ids.splice(this.form.menu_ids.indexOf(id), 1);
      }
    },
    permissionCheckChange(data, checked, indeterminate) {
      let id = data.id;
      if (checked && this.form.permission_rules.indexOf(id) == -1) {
        this.form.permission_rules.push(id);
      } else if (!checked && !indeterminate) {
        this.form.permission_rules.splice(
          this.form.permission_rules.indexOf(id),
          1
        );
      }
    },
    saveForm() {
      return new Promise((resolve, reject) => {
        if (self.form.role_id) {
          roleEdit(self.form)
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
          roleCreate(self.form)
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
    async create() {
      const loading = this.$loading({
        lock: true,
        text: "Loading",
        spinner: "el-icon-loading",
        background: "rgba(0, 0, 0, 0.7)",
      });
      await this.getmenuList();
      await this.getPermissionList();
      loading.close();

      this.editFormVisible = true;
    },
    async edit(roleId) {
      await this.getRoleInfo(roleId);
      this.editFormVisible = true;
    },
    clearForm() {
      this.form = {};
      this.form = {
        role_id: null,
        role_name: null,
        sort: 0,
        menu_ids: [],
        permission_rules: [],
      };
    },
    disable(roleId, curStatus) {
      const status = curStatus == 1 ? 0 : 1;
      const tipSt = "确定" + (curStatus == 1 ? "启用" : "禁用") + "此角色?";
      this.$confirm(tipSt, "提示", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning",
      }).then(() => {
        roleDisble({ role_id: roleId, status: status }).then((response) => {
          const { msg } = response;
          this.getList();
          this.$message({
            type: "success",
            message: msg,
          });
        });
      });
    },
    del(roleId) {
      this.$confirm("确定删除此角色?", "提示", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning",
      }).then(() => {
        roleDel({ role_id: roleId }).then((response) => {
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
    queryWord() {
      this.getList();
    },
    handleCurrentChange(val) {
      this.listQuery.page_no = val;
      this.getList();
    },
    getRoleInfo(roleId) {
      const loading = this.$loading({
        lock: true,
        text: "Loading",
        spinner: "el-icon-loading",
        background: "rgba(0, 0, 0, 0.7)",
      });
      return new Promise((resolve, reject) => {
        this.getmenuList();
        this.getPermissionList();
        roleInfo({ role_id: roleId })
          .then((response) => {
            const { data } = response;

            self.form.role_id = data.role_id;
            self.form.role_name = data.role_name;
            self.form.sort = data.sort;
            self.form.menu_ids = data.rel_child_menu.map((obj) => {
              return obj.menu_id;
            });
            self.form.permission_rules = data.rel_permission.map((obj) => {
              return obj.rule;
            });

            loading.close();
            resolve(data);
          })
          .catch((error) => {
            loading.close();
            reject(error);
          });
      });
    },
    getList() {
      this.listLoading = true;
      roleList(this.listQuery).then((response) => {
        const { data } = response;
        this.list = data.data;
        this.total = data.total;
        this.listLoading = false;
      });
    },
    async getPermissionList() {
      await permissionList().then((response) => {
        const { data } = response;
        this.$store
          .dispatch("recursion/handle", {
            map: { id: "rule", label: "name" },
            data: data,
            curChildName: "child_list",
            needChildName: "children",
          })
          .then((list) => {
            this.permissionList = list;
          });
      });
    },
    async getmenuList() {
      await menuList().then((response) => {
        const { data } = response;

        this.$store
          .dispatch("recursion/handle", {
            map: { id: "menu_id", label: "title" },
            data: data,
            curChildName: "child_list",
            needChildName: "children",
          })
          .then((list) => {
            this.menuList = list;
          });
      });
    },
  },
  watch: {
    editFormVisible(newVal, oldVal) {
      if (!newVal) {
        this.clearForm();
      }
    },
  },
};
</script>
