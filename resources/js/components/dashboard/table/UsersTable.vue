<template>
    <div>
        <table class="table table-striped table-hover">
            <thead>
            <tr>
                <th>id</th>
                <th>name</th>
                <th>email</th>
                <th>subscription</th>
                <th>actions</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="user in data.data" v-show="data != null">
                <td>{{ user.id }}</td>
                <td>{{ user.name }}</td>
                <td>{{ user.email }}</td>
                <td>{{ calculateDifference(user.subscription.end_date) }} Days Left
                </td>
                <td>
                    <a class="btn btn-outline-primary color-blue" data-toggle="modal"
                       data-target="#addCoinsModal"><i
                        class="fas fa-coins"></i></a>
                    <a class="btn btn-outline-danger color-red" data-toggle="modal" data-target="#banModal"><i
                        class="fas fa-ban"></i></a>
                    <a class="btn btn-outline-danger color-red" data-toggle="modal"
                       data-target="#deleteModal"><i
                        class="fas fa-trash"></i></a>
                </td>
            </tr>
            </tbody>
        </table>
        <br>
        <pagination class="container d-flex justify-content-center"
                    v-show="data != null"
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
            apiToken: {type: String, required: true}
        },
        data() {
            return {
                data: null
            }
        },
        created() {
            this.fetchData();
        },
        methods: {
            fetchData(page = 1) {
                axios.get(this.url, {
                    params: {
                        page: page,
                        api_token: this.apiToken
                    }
                }).then(data => {
                    this.data = data.data;
                }).catch(error => console.log(error));
            },
            calculateDifference(date) {
                const now = moment();
                const then = moment(date, "Y-MM-DD");
                return then.diff(now, "days");
            }
        }
    }
</script>

<style scoped>

</style>
