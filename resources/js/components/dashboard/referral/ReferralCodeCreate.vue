<template>
    <div>
        <div class="form-group">
            <label for="user">Username</label>
            <div class="input-group mb-2 mr-sm-2">
                <div class="input-group-prepend">
                    <div class="input-group-text">
                        <i class="fas fa-user"></i>
                    </div>
                </div>
                <input type="text" class="form-control" id="user" placeholder="username" v-model="username">
            </div>
        </div>

        <div class="form-group">
            <label for="code">Code</label>
            <div class="input-group mb-2 mr-sm-2">
                <div class="input-group-prepend">
                    <div class="input-group-text">
                        <i class="fas fa-tag"></i>
                    </div>
                </div>
                <input type="text" class="form-control" id="code" placeholder="code" v-model="code" maxlength="15">
            </div>
            <button type="submit" class="btn btn-primary" v-on:click="submit">Submit</button>
        </div>
    </div>
</template>

<script>
    import EventBus from "../../../eventbus"

    export default {
        name: "ReferralCodeCreate",
        props: {
            url: {type: String, required: true},
            apiToken: {type: String, required: true}
        },
        data() {
            return {
                username: null,
                code: null
            }
        },
        methods: {
            submit() {
                // validator form
                if (!this.username || !this.code) {
                    this.$notify({
                        type: "error",
                        title: "Error",
                        text: "Username or Code are invalid."
                    });
                }

                axios.post(this.url, {
                    username: this.username,
                    code: this.code,
                    api_token: this.apiToken,
                }).then(data => {
                    console.log(data);

                    this.$notify({
                        type: "success",
                        title: "Success",
                        text: "Referral Code created."
                    });

                    EventBus.$emit("UPDATE_REFERRALS_CODE");

                    this.username = null;
                    this.code = null;
                }).catch(error => {
                    console.log(error);
                    this.$notify({
                        type: "error",
                        title: "Error",
                        text: "Error, please check console."
                    });
                });
            }
        }
    }
</script>
