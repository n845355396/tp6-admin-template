<template>
  <div>
    <el-upload
      :action="minioUploadUrl"
      list-type="picture-card"
      :show-file-list="false"
      :auto-upload="true"
      :multiple="false"
      :headers="headers"
      :before-upload="beforeUpload"
      :on-success="handleAvatarSuccess"
      :on-error="handleAvatarError"
      :name="fileName"
    >
      <img v-if="value" :src="value" class="avatar" />
      <i v-else class="el-icon-plus avatar-uploader-icon"></i>
    </el-upload>
  </div>
</template>
<script>
import { mapGetters } from "vuex";
export default {
  name: "singleUpload",
  props: {
    value: String,
  },
  computed: {
    ...mapGetters(["token"]),
  },
  data() {
    return {
      minioUploadUrl: "http://tp6-admin-template.local/admin/upload/image",
      headers: {
        accessToken: null,
      },
      fileName: "file",
    };
  },
  methods: {
    emitInput(val) {
      this.$emit("input", val);
    },
    beforeUpload(file) {
      this.headers.accessToken = this.token;
      // const isJPG = file.type === "image/jpeg";
      const isLt10M = file.size / 1024 / 1024 < 10;

      // if (!isJPG) {
      //   this.$message.error("上传头像图片只能是 JPG 格式!");
      // }
      if (!isLt10M) {
        this.$message.error("上传头像图片大小不能超过 10MB!");
        return false;
      }
      // return isJPG && isLt2M;

      if (isLt10M) {
        this.loading = this.$loading({
          lock: true,
          text: "Loading",
          spinner: "el-icon-loading",
          background: "rgba(0, 0, 0, 0.7)",
        });
      }

      return true;
    },
    handleAvatarSuccess(res, file) {
      this.loading.close();
      let url = res.data[this.fileName];
      // this.value = url;
      this.emitInput(url);
    },
    handleAvatarError(res, file) {
      this.loading.close();
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
