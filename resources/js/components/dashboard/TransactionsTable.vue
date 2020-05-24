<template>
    <div>

        <div class="form-row">
            <div class="col">
                <select class="form-control" v-model="filter.date">
                    <option value="today">Today</option>
                    <option value="yesterday">Yesterday</option>
                    <option value="week">7 Days</option>
                    <option value="month">30 Days</option>
                    <option value="lifetime">Lifetime</option>
                </select>
            </div>
            <div class="col">
                <select class="form-control" v-model="filter.type">
                    <option value="all">All</option>
                    <option value="deposit">Only Deposits</option>
                    <option value="withdraw">Only Withdrawals</option>
                </select>
            </div>
        </div>

        <br>

        <!-- table -->
        <table class="table table-striped table-hover">
            <thead>
            <tr>
                <th>id</th>
                <th>user</th>
                <th>amount</th>
                <th>description</th>
                <th>date</th>
            </tr>
            </thead>
            <tbody>
            <tr v-if="loading">
                Loading...
            </tr>
            <tr v-else-if="data.length === 0">
                No transactions.
            </tr>
            <tr v-for="(transaction, index) in data" v-else>
                <th scope="row">{{ index + 1 }}</th>
                <td>{{ transaction.user.name }}</td>
                <td style="color: green">${{ transaction.amount }}</td>
                <td>{{ transaction.meta['description'] }}</td>
                <td>{{ transaction.date }}</td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    import EventBus from "../../eventbus"

    export default {
        name: "TransactionsTable",
        props: {
            url: {type: String, required: true},
            apiToken: {type: String, required: true}
        },
        data() {
            return {
                loading: true,
                data: [],
                filter: {
                    date: 'today',
                    type: 'all',
                }
            }
        },
        created() {
            EventBus.$on("UPDATE_TRANSACTIONS", this.fetchData);
            this.fetchData();
        },
        methods: {
            fetchData() {
                this.loading = true;
                axios.get(this.url, {
                    params: {
                        date: this.filter.date,
                        type: this.filter.type,
                        api_token: this.apiToken
                    }
                }).then(data => {
                    console.log(data);
                    this.data = data.data;
                    this.loading = false;
                }).catch(error => console.log(error));
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
