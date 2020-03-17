<template>
    <main class="p-5">
        <div class="container">
            <div class="row">
                <div class="col-md-8 offset-md-2 bg-white p-5">
                    <section class="mb-5" v-for="section in estimateData.sections" :key="section.id">
                        <p v-html="section.text"></p>
    
                        <table class="table mt-4" v-if="section.items.length">
                            <tr>
                                <th></th>
                                <th>Description</th>
                                <th>Duration</th>
                                <th>Price</th>
                            </tr>

                            <tr v-for="item in section.items" :key="item.id">
                                <td><input type="checkbox" name="" id=""></td>
                                <td>{{ item.description || '-' }}</td>
                                <td>{{ item.duration || '-' }}</td>
                                <td>{{ item.price || '-' }}</td>
                            </tr>
                        </table>
                    </section>
                </div>
            </div>
        </div>
    </main>
</template>

<script>
export default {

    props: ['estimate'],

    data() {
        return {
            estimateData: null
        }
    },

    mounted() {
        this.init();
    },

    methods: {

        init() {
            axios.get('/estimates/' + this.estimate + '/data').then(({data}) => {
                this.estimateData = data;
            });
        }

    }

}
</script>