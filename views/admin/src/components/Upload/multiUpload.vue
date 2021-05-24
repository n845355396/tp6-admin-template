<template>
  <div>
    <el-button type="primary" size="small" @click="submitUpload"
      >上传服务器<i class="el-icon-upload el-icon--right"></i
    ></el-button>
    <el-upload
      ref="mupload"
      :action="minioUploadUrl"
      list-type="picture-card"
      :auto-upload="false"
      :http-request="uploadFile"
      :multiple="true"
      :file-list="fileList"
      :before-upload="beforeUpload"
      :on-success="handleAvatarSuccess"
      :on-error="handleAvatarError"
      :on-remove="handleRemove"
      :on-preview="handlePreview"
      :on-exceed="handleExceed"
      :limit="maxLimit"
    >
      <i class="el-icon-plus"></i>
    </el-upload>
    <el-dialog :visible.sync="dialogVisible">
      <img width="100%" :src="dialogImageUrl" alt="" />
    </el-dialog>
  </div>
</template>
<script>
import { mapGetters } from "vuex";
import { Message } from "element-ui";
import { policy } from "@/api/oss";
export default {
  name: "multiUpload",
  props: {
    value: Array,
  },
  computed: {
    ...mapGetters(["token"]),
    fileList() {
      // if(this.isFirst){this.isFirst = false;
      let fileList = [];
      for (let i = 0; i < this.value.length; i++) {
        fileList.push({ url: this.value[i] });
      }
      return fileList;
      // }
    },
  },
  data() {
    return {
      fileData: "",
      minioUploadUrl: process.env.VUE_APP_BASE_API + "/upload/image",//因为自定义了上传，此处不起作用了
      maxLimit: 30,
      dialogImageUrl: "",
      dialogVisible: false,
      disabled: false,
    };
  },
  methods: {
    uploadFile(file) {
      this.fileData.append(file.file.uid, file.file);
    },
    submitUpload() {
      this.loading = this.$loading({
        lock: true,
        text: "上传中",
        spinner: "el-icon-loading",
        background: "rgba(0, 0, 0, 0.7)",
      });

      this.fileData = new FormData();
      this.$refs.mupload.submit();
      return new Promise((resolve, reject) => {
        policy(this.fileData)
          .then((response) => {
            const { data } = response;

            for (const key in data) {
              this.fileList.push({ name: key, url: data[key] });
              this.emitInput(this.fileList);
            }

            this.loading.close();
            resolve(true);
          })
          .catch((err) => {
            Message({
              message: err || "上传失败！",
              type: "error",
              duration: 3 * 1000,
            });
            this.loading.close();
            reject(false);
          });
      });
    },

    emitInput(fileList) {
      let value = [];
      for (let i = 0; i < fileList.length; i++) {
        value.push(fileList[i].url);
      }
      this.$emit("input", value);
    },
    beforeUpload(file) {
      // const isJPG = file.type === "image/jpeg";
      const isLt10M = file.size / 1024 / 1024 < 10;

      // if (!isJPG) {
      //   this.$message.error("上传头像图片只能是 JPG 格式!");
      // }
      if (!isLt10M) {
        Message({
          message: "上传头像图片大小不能超过 10MB!",
          type: "warning",
          duration: 3 * 1000,
        });
        return false;
      }
      return true;
    },
    handleAvatarSuccess(res, file) {},
    handleAvatarError(res, file) {
      this.loading.close();
    },
    handleRemove(file, fileList) {
      this.emitInput(fileList);
    },
    handlePreview(file) {
      this.dialogVisible = true;
      this.dialogImageUrl = file.url;
    },
    handleExceed(files, fileList) {
      Message({
        message: "最多只能上传" + this.maxLimit + "张图片",
        type: "warning",
        duration: 3000,
      });
    },
  },
};
</script>
<style lang='scss'>
.avatar-uploader .el-upload {
  border: 1px dashed #d9d9d9;
  border-radius: 6px;
  cursor: pointer;
  position: relative;
  overflow: hidden;
}
.avatar-uploader .el-upload:hover {
  border-color: #409eff;
}
.avatar-uploader-icon {
  font-size: 28px;
  color: #8c939d;
  width: 148px;
  height: 148px;
  line-height: 50px;
  text-align: center;
}
.avatar {
  width: 148px;
  height: 148px;
  display: block;
}
</style>
