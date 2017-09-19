<template>
    <div class="login-form">
        <el-row>
            <el-col :span="24">
                <el-form :label-position="labelPosition" label-width="80px" :model="formLabelAlign">
                    <el-form-item label="名称">
                        <el-input v-model="formLabelAlign.name"></el-input>
                    </el-form-item>
                    <el-form-item label="活动区域">
                        <el-input v-model="formLabelAlign.region"></el-input>
                    </el-form-item>
                    <el-form-item label="活动形式">
                        <el-input v-model="formLabelAlign.type"></el-input>
                    </el-form-item>
                </el-form>
            </el-col>
        </el-row>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                labelPosition: 'right',
                formLabelAlign: {
                    name: '',
                    region: '',
                    type: ''
                }
            };
        }
    }
</script>

<style type="text/css">
    body {
        background: #324057;
        color: #FFF;
    }
    .login-form {
        width: 500px;
        padding: 50px;
        margin: 10% auto 0 auto;
        background-color: #ffffff;
        border-radius: 5px;
    }
</style>
