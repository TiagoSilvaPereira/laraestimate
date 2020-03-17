<style scoped>
    tr.item:not(.selected) {
        color: #ccc;
        text-decoration: line-through;
    }
</style>
<template>
    <main class="p-5">
        <div class="container">
            <div class="row">
                <div class="col-md-8 offset-md-2 bg-white p-5" v-if="estimateData">
                    <section class="mb-5" v-for="section in estimateData.sections" :key="section.id">
                        <p v-html="section.text"></p>
    
                        <table class="table mt-4" v-if="section.items.length">
                            <tr>
                                <th></th>
                                <th>Description</th>
                                <th>Duration</th>
                                <th class="text-right">Price</th>
                            </tr>

                            <tr v-for="item in section.items" :key="item.id" class="item" :class="{'selected': item.selected}">
                                <td><input type="checkbox" v-model="item.selected"></td>
                                <td>{{ item.description || '-' }}</td>
                                <td>{{ item.duration || '-' }}</td>
                                <td class="text-right">{{ item.price || '-' }}</td>
                            </tr>

                            <tr>
                                <td colspan="3" class="text-right"><b>Total:</b></td>
                                <td class="text-right" colspan="4">{{ formattedPrice(sectionTotal(section)) }}</td>
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
                this.estimateData = this.treatData(data);
            });
        },

        treatData(data) {
            data.sections = data.sections.map(section => {
                
                section.items = section.items.map(item => { 
                    item.selected = true ;
                    return item;
                });
                
                return section;
            });

            return data;
        },

        sectionTotal(section) {
            let total = section.items.reduce((sum, item) => {
                let itemPrice = !item.selected ? 0 : (parseFloat(item.price) || 0);
                return sum + itemPrice; 
            }, 0);

            return parseFloat(total);
        },

        formattedPrice(price) {
            return price.toFixed(2);
        }

    }

}
</script>