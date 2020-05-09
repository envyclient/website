<template>
    <div>
        <!-- Ban Confirmation Modal -->
        <div class="modal fade" id="banModal" tabindex="-1" role="dialog" aria-labelledby="banLabel" aria-hidden="true"
             v-if="selectedUser != null">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="banLabel">Warning - {{ selectedUser.name}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Are you sure that you want to ban this user?
                        <select class="form-control" v-model="modal.ban">
                            <option value="Chargeback">Chargeback</option>
                            <option value="Exploiting">Exploiting</option>
                            <option value="Account Sharing">Account Sharing</option>
                            <option value="Blacklisted">Blacklisted</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"
                                v-on:click="setSelectedUser(null)">Close
                        </button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal" v-on:click="banUser">
                            Ban
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Credits Modal -->
        <div class="modal fade" id="addCoinsModal" tabindex="-1" role="dialog" aria-labelledby="addCoinsLabel"
             aria-hidden="true" v-if="selectedUser != null">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addCoinsLabel">Add Coins - {{ selectedUser.name}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <select class="form-control" v-model="modal.credits">
                            <option value="5">$5</option>
                            <option value="10">$10</option>
                            <option value="15">$15</option>
                            <option value="20">$20</option>
                            <option value="30">$30</option>
                            <option value="40">$40</option>
                            <option value="50">$50</option>
                            <option value="60">$50</option>
                            <option value="70">$70</option>
                            <option value="80">$80</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"
                                v-on:click="setSelectedUser(null)">Close
                        </button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal"
                                v-on:click="addCreditsToUser">Add
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- search bar -->
        <input class="form-control" type="text" placeholder="Name" v-model="name">

        <br>

        <!-- users table -->
        <table class="table table-striped table-hover">
            <thead>
            <tr>
                <th>#</th>
                <th>name</th>
                <th>email</th>
                <th>credits</th>
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
                <td>${{ user.wallet.balance }}</td>
                <td>{{ calculateDifference(user.subscription.end_date) }}
                    Days Left
                </td>
                <td>
                    <a class="btn btn-outline-primary text-primary" data-toggle="modal"
                       data-target="#addCoinsModal" v-on:click="setSelectedUser(user)">
                        <i class="fas fa-coins"></i>
                    </a>
                    <a class="btn btn-outline-danger text-danger" data-toggle="modal" data-target="#banModal"
                       v-on:click="setSelectedUser(user)" v-if="user.ban_reason == null">
                        <i class="fas fa-ban"></i>
                    </a>
                    <a class="btn btn-outline-success text-success" v-on:click="unBanUser(user)" v-else>
                        <i class="fas fa-handshake"></i>
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
    import pagination from 'laravel-vue-pagination';
    import moment from "moment";

    export default {
        name: "UsersTable",
        components: {
            pagination
        },
        props: {
            url: {type: String, required: true},
            creditsUrl: {type: String, required: true},
            banUrl: {type: String, required: true},
            unBanUrl: {type: String, required: true},
            apiToken: {type: String, required: true}
        },
        data() {
            return {
                loading: true,
                data: {},
                name: null,
                selectedUser: null,
                modal: {
                    credits: 5,
                    ban: 'Chargeback'
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
                        name: this.name,
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
                axios.put(this.creditsUrl, {
                    user_id: this.selectedUser.id,
                    amount: this.modal.credits,
                    api_token: this.apiToken
                }).then(data => {
                    this.$notify({
                        type: "success",
                        title: "Success",
                        text: `Added ${this.modal.credits} credits to '${this.selectedUser.name}'.`
                    });
                    console.log(data);
                }).catch(error => {
                    console.log(error);
                    this.$notify({
                        type: "error",
                        title: "Error",
                        text: 'Error, please check console.'
                    });
                }).finally(() => {
                    this.modal.credits = 5;
                    this.closing();
                });
            },
            banUser() {
                axios.put(this.banUrl, {
                    user_id: this.selectedUser.id,
                    ban_reason: this.modal.ban,
                    api_token: this.apiToken
                }).then(data => {
                    console.log(data);
                    this.$notify({
                        type: "success",
                        title: "Success",
                        text: `'${this.selectedUser.name}' has been banned for '${this.modal.ban}'.`
                    });
                }).catch(error => {
                    console.log(error);
                    this.$notify({
                        type: "error",
                        title: "Error",
                        text: 'Error, please check console.'
                    });
                }).finally(() => {
                    this.modal.ban = 'Chargeback';
                    this.closing();
                });
            },
            unBanUser(user) {
                axios.put(this.unBanUrl, {
                    user_id: user.id,
                    api_token: this.apiToken
                }).then(data => {
                    console.log(data);
                    this.$notify({
                        type: "success",
                        title: "Success",
                        text: `'${user.name}' has been unbanned.`
                    });
                }).catch(error => {
                    console.log(error);
                    this.$notify({
                        type: "error",
                        title: "Error",
                        text: 'Error, please check console.'
                    });
                }).finally(this.closing);
            },
            closing() {
                this.setSelectedUser(null);
                this.fetchData();
            }
        },
        watch: {
            name: function () {
                this.fetchData();
            }
        }
    }
</script>
