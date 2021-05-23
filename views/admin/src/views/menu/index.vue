<template>
  <div>
    <el-row class="operation-scope">
      <el-button type="primary" @click="showCreate">创建</el-button>
    </el-row>

    <el-table
      v-loading="listLoading"
      :data="list"
      style="width: 100%; margin-bottom: 20px"
      row-key="menu_id"
      border
      default-expand-all
      :tree-props="{ children: 'child_list', hasChildren: 'hasChildren' }"
    >
      <el-table-column prop="title" label="菜单名称" width="180">
      </el-table-column>
      <el-table-column prop="mark_name" label="标识" width="180">
      </el-table-column>
      <el-table-column prop="sort" label="排序"> </el-table-column>
      <el-table-column prop="hidden" label="状态">
        <template slot-scope="{ row }">
          <el-tag type="danger" v-if="row.hidden == 1">隐藏中</el-tag>
          <el-tag type="success" v-else>显示中</el-tag>
        </template>
      </el-table-column>
      <el-table-column prop="create_time" label="创建时间"> </el-table-column>

      <el-table-column fixed="right" label="操作" width="200">
        <template slot-scope="scope">
          <el-button
            type="text"
            size="small"
            @click="showEdit(scope.row.menu_id)"
            >编辑</el-button
          >
          <el-button
            type="text"
            size="small"
            @click="disable(scope.row.menu_id, scope.row.hidden)"
            >{{ scope.row.hidden ? "显示" : "隐藏" }}</el-button
          >
          <el-button type="text" size="small" @click="del(scope.row.menu_id)"
            >删除</el-button
          >
        </template>
      </el-table-column>
    </el-table>

    <!-- 编辑 -->
    <el-dialog
      :title="form.menu_id ? '编辑' : '新增'"
      :visible.sync="editFormVisible"
      width="500px"
    >
      <el-form :model="form">
        <el-form-item label="上级菜单" :label-width="labelWidth">
          <el-select
            v-model="form.parent_id"
            :disabled="form.menu_id != null"
            placeholder="请选择"
          >
            <el-option
              v-for="item in selectMenuList"
              :key="item.menu_id"
              :label="item.title"
              :value="item.menu_id"
            >
            </el-option>
          </el-select>
        </el-form-item>

        <el-form-item label="排序" :label-width="labelWidth">
          <el-input-number
            v-model="form.sort"
            label="越小越靠前"
          ></el-input-number>
        </el-form-item>

        <el-form-item label="菜单名" :label-width="labelWidth">
          <el-input v-model="form.title" autocomplete="off"></el-input>
        </el-form-item>

        <el-form-item label="菜单标识" :label-width="labelWidth">
          <el-input v-model="form.mark_name" autocomplete="off"></el-input>
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
import "@/styles/menu.scss";

import {
  menuList,
  menuInfo,
  menuCreate,
  menuEdit,
  menuHidden,
  menuDelete,
} from "@/api/menu";

let self = null;
export default {
  name: "menuList",
  data() {
    return {
      labelWidth: "100px",
      list: [],
      total: null,
      listLoading: true,
      listQuery: {
        query_word: "",
        not_needed_key: 1,
        is_show_hidden: 1,
      },
      editFormVisible: false,
      form: {
        menu_id: null,
        parent_id: 0,
        title: null,
        sort: 100,
        mark_name: null,
      },
    };
  },
  computed: {
    selectMenuList: function () {
      let firstObj = [{ menu_id: 0, title: "无上级" }];

      const arr = firstObj.concat(this.list);

      return arr;
    },
  },
  created() {
    self = this;
    this.getList();
  },
  methods: {
    saveForm() {
      return new Promise((resolve, reject) => {
        if (self.form.menu_id) {
          menuEdit(self.form)
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
          menuCreate(self.form)
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
    async showEdit(menuId) {
      await this.getMenuInfo(menuId);
      this.editFormVisible = true;
    },
    showCreate() {
      this.editFormVisible = true;
    },
    disable(menuId, curStatus) {
      const status = curStatus == 1 ? 0 : 1;
      const tipSt = "确定" + (curStatus == 1 ? "展示" : "隐藏") + "此菜单?";
      this.$confirm(tipSt, "提示", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning",
      }).then(() => {
        menuHidden({ menu_id: menuId, hidden: status }).then((response) => {
          const { msg } = response;
          this.getList();
          this.$message({
            type: "success",
            message: msg,
          });
        });
      });
    },
    del(menuId) {
      this.$confirm("确定删除此菜单?", "提示", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning",
      }).then(() => {
        menuDelete({ menu_id: menuId }).then((response) => {
          const { msg } = response;
          this.getList();
          this.$message({
            type: "success",
            message: msg,
          });
        });
      });
    },
    async getMenuInfo(menuId) {
      const loading = this.$loading({
        lock: true,
        text: "Loading",
        spinner: "el-icon-loading",
        background: "rgba(0, 0, 0, 0.7)",
      });
      return new Promise((resolve, reject) => {
        menuInfo({ menu_id: menuId })
          .then((response) => {
            const { data } = response;

            self.form.menu_id = data.menu_id;
            self.form.parent_id = data.parent_id;
            self.form.sort = data.sort;
            self.form.title = data.title;
            self.form.mark_name = data.mark_name;

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
      menuList(this.listQuery).then((response) => {
        const { data } = response;

        this.list = data;
        this.listLoading = false;
      });
    },
    clearForm() {
      this.form = {
        menu_id: null,
        parent_id: 0,
        title: null,
        sort: 100,
        mark_name: null,
      };
    },
    queryWord() {
      this.getList();
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

<style scoped>
</style>

