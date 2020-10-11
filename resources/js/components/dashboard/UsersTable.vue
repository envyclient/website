<template>
    <div>
        <!-- filters -->
        <div class="form-row">
            <div class="col">
                <input class="form-control" type="text" placeholder="Name" v-model="filter.name">
            </div>
            <div class="col">
                <select class="form-control" v-model="filter.type">
                    <option value="all">All</option>
                    <option value="banned">Only Banned</option>
                </select>
            </div>
        </div>

        <br>

        <!-- users table -->
        <table class="table table-striped table-hover">
            <thead>
            <tr>
                <th>#</th>
                <th>name</th>
                <th>email</th>
                <th>subscription</th>
                <th>actions</th>
            </tr>
            </thead>
            <tbody>
            <tr v-if="loading">
                Loading...
            </tr>
            <tr v-for="user in data.data" v-else>
                <th scope="row">{{ user.id }}</th>
                <td>{{ user.name }}</td>
                <td>{{ user.email }}</td>
                <td v-if="user.subscription == null">No Subscription</td>
                <td v-else>{{ calculateDifference(user.subscription.end_date) }}
                    Days Left
                </td>
                <td>
                    <a class="btn btn-outline-success text-success" v-on:click="banUser(user)" v-if="user.banned">
                        <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                            <path fill="currentColor" d="M11 6H14L17.29 2.7A1 1 0 0 1 18.71 2.7L21.29 5.29A1 1 0 0 1 21.29 6.7L19 9H11V11A1 1 0 0 1 10 12A1 1 0 0 1 9 11V8A2 2 0 0 1 11 6M5 11V15L2.71 17.29A1 1 0 0 0 2.71 18.7L5.29 21.29A1 1 0 0 0 6.71 21.29L11 17H15A1 1 0 0 0 16 16V15H17A1 1 0 0 0 18 14V13H19A1 1 0 0 0 20 12V11H13V12A2 2 0 0 1 11 14H9A2 2 0 0 1 7 12V9Z" />
                        </svg>
                    </a>
                    <a class="btn btn-outline-danger text-danger" v-on:click="banUser(user)" v-else>
                        <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                            <path fill="currentColor" d="M12 2C17.5 2 22 6.5 22 12S17.5 22 12 22 2 17.5 2 12 6.5 2 12 2M12 4C10.1 4 8.4 4.6 7.1 5.7L18.3 16.9C19.3 15.5 20 13.8 20 12C20 7.6 16.4 4 12 4M16.9 18.3L5.7 7.1C4.6 8.4 4 10.1 4 12C4 16.4 7.6 20 12 20C13.9 20 15.6 19.4 16.9 18.3Z" />
                        </svg>
                    </a>
                </td>
            </tr>
            </tbody>
        </table>

        <br>

        <pagination class="container d-flex justify-content-center"
                    v-if="!loading"
                    :data="data"
                    :limit="10"
                    @pagination-change-page="fetchData">
        </pagination>
    </div>
</template>

<script>
import EventBus from "../../eventbus"
import pagination from "laravel-vue-pagination"
import moment from "moment"

export default {
    name: "UsersTable",
    components: {
        pagination
    },
    props: {
        url: {type: String, required: true},
        apiToken: {type: String, required: true}
    },
    data() {
        return {
            loading: true,
            data: {},
            filter: {
                name: null,
                type: "all"
            },
            selectedUser: null,
            modal: {
                credits: 5
            }
        }
    },
    created() {
        this.fetchData();
    },
    methods: {
        fetchData(page = 1) {
            this.loading = true;
            axios.get(this.url, {
                params: {
                    name: this.filter.name,
                    type: this.filter.type,
                    page: page,
                    api_token: this.apiToken
                }
            }).then(data => {
                console.log(data);
                this.data = data.data;
                this.loading = false;
            }).catch(error => console.log(error));
        },
        calculateDifference(date) {
            const now = moment();
            const then = moment(date, "Y-MM-DD");
            return then.diff(now, "days");
        },
        setSelectedUser(user) {
            this.selectedUser = user;
        },
        addCreditsToUser() {
            axios.put(`${this.url}/credits/${this.selectedUser.id}`, {
                user_id: this.selectedUser.id,
                amount: this.modal.credits,
                api_token: this.apiToken
            }).then(data => {
                console.log(data);
                this.$notify({
                    type: "success",
                    title: "Success",
                    text: `Added ${this.modal.credits} credits to '${this.selectedUser.name}'.`
                });
                EventBus.$emit("UPDATE_TRANSACTIONS");
            }).catch(error => {
                console.log(error);
                this.$notify({
                    type: "error",
                    title: "Error",
                    text: "Error, please check console."
                });
            }).finally(() => {
                this.modal.credits = 5;
                this.closing();
            });
        },
        banUser(user) {
            axios.put(`${this.url}/ban/${user.id}`, {
                user_id: user.id,
                api_token: this.apiToken
            }).then(data => {
                console.log(data);
                this.$notify({
                    type: "success",
                    title: "Success",
                    text: `'${user.name}' has been banned or unbanned.`
                });
            }).catch(error => {
                console.log(error);
                this.$notify({
                    type: "error",
                    title: "Error",
                    text: "Error, please check console."
                });
            }).finally(this.closing);
        },
        closing() {
            this.setSelectedUser(null);
            this.fetchData();
        }
    },
    watch: {
        filter: {
            handler() {
                this.fetchData();
            },
            deep: true
        }
    }
}
</script>
