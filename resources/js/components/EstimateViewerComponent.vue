<style>
    #estimateMainSection {
        background-color: #eee;
        text-align: justify;
    }

    #estimateMainSection h1 {
        text-align: left !important;
        font-size: 1.5rem;
    }

    #estimateMainSection tr.item:not(.selected) {
        color: #ccc;
        text-decoration: line-through;
    }

    #estimateMainSection input[type="checkbox"] {
        width: 1.5em;
        height: 1.5em;
    }
</style>

<template>
    <main id="estimateMainSection" class="p-5">
        <div class="fixed-top p-4 text-right" v-if="estimateData">
            <button class="btn btn-primary d-print-none" @click="print()"><i class="icon ion-md-print"></i> Print</button>
        </div>

        <div class="container" id="printContainer">

            <div class="row">

                <div id="estimateDocument" class="col-md-8 offset-md-2 bg-white p-5" v-if="estimateData">
                    <section class="mb-5" v-for="section in estimateData.sections" :key="section.id">
                        <p v-html="section.presentable_text"></p>
    
                        <table class="table mt-4" v-if="section.items.length">
                            <tr>
                                <th></th>
                                <th>Description</th>
                                <th>Duration</th>
                                <th class="text-right">Price</th>
                            </tr>

                            <tr v-for="item in section.items" :key="item.id" class="item" :class="{'selected': item.selected}">
                                <td><input type="checkbox" v-if="!item.obligatory" v-model="item.selected" @change="renderPrices()"></td>
                                <td>{{ item.description || '-' }}</td>
                                <td>{{ item.duration || '-' }}</td>
                                <td class="text-right">{{ item.price || '-' }}</td>
                            </tr>

                            <tr>
                                <td colspan="3" class="text-right"><b>Section Total:</b></td>
                                <td class="text-right">{{ formattedPrice(sectionTotal(section)) }}</td>
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
            estimateData: null,
            isPrinting: false,
        }
    },

    created() {
        window.addEventListener('beforeprint', this.preparePrintMode);
        window.addEventListener('afterprint', this.returnToViewMode);
    },

    mounted() {
        this.init();
    },

    computed: {

        estimateTotalPrice() {
            if(!this.estimateData.sections) return 0;

            let total = this.estimateData.sections.reduce((sum, section) => {
                return sum + this.sectionTotal(section, false); 
            }, 0);

            return total;
        },

        estimateTotalSelectedPrice() {
            if(!this.estimateData.sections) return 0;
            
            let total = this.estimateData.sections.reduce((sum, section) => {
                return sum + this.sectionTotal(section, true); 
            }, 0);

            return total;
        }

    },

    methods: {

        init() {
            axios.get('/estimates/' + this.estimate + '/data').then(({data}) => {
                this.estimateData = this.treatData(data);
                
                this.$nextTick(() => {
                    this.renderPrices();
                })
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

        sectionTotal(section, onlySelected = true) {
            let total = section.items.reduce((sum, item) => {
                let itemPrice = (parseFloat(item.price) || 0);

                if(onlySelected) {
                    itemPrice = !item.selected ? 0 : itemPrice;
                }

                return sum + itemPrice; 
            }, 0);

            return parseFloat(total);
        },

        formattedPrice(price) {
            return price.toFixed(2);
        },

        renderPrices() {
            let totalPriceElements = document.querySelectorAll('.total-price');
            let totalSelectedPriceElements = document.querySelectorAll('.total-selected-price');

            document.querySelectorAll('.total-price').forEach(priceElement => {
                priceElement.innerHTML = this.formattedPrice(this.estimateTotalPrice);
            });

            document.querySelectorAll('.total-selected-price').forEach(priceElement => {
                priceElement.innerHTML = this.formattedPrice(this.estimateTotalSelectedPrice);
            });
        },

        print() {
            window.print();
        },

        preparePrintMode() {
            /**
             * Wee need to manipulate the DOM here because VueJs can't
             * update the VirtualDOM on beforeprint event
             */
            let container = document.getElementById('printContainer'),
                estimate = document.getElementById('estimateDocument'),
                mainElement = document.getElementById('estimateMainSection');
            
            container.classList.remove('container');
            container.classList.add('container-fluid');
            estimate.classList.remove('col-md-8');
            estimate.classList.remove('offset-md-2');

            mainElement.classList.add('bg-white');
        },

        returnToViewMode() {
            let container = document.getElementById('printContainer'),
                estimate = document.getElementById('estimateDocument'),
                mainElement = document.getElementById('estimateMainSection');
            
            container.classList.add('container');
            container.classList.remove('container-fluid');
            estimate.classList.add('col-md-8');
            estimate.classList.add('offset-md-2');

            mainElement.classList.remove('bg-white');
        }

    }

}
</script>