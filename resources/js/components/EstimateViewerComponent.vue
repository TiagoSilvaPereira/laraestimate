<style>
    #estimateMainSection {
        min-height: 100vh;
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

        <div class="modal fade" id="shareEstimateModal" v-if="estimateData">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Share Estimate</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <label for="link">Copy this link</label>
                        <input ref="shareableUrl" type="text" class="form-control" :value="estimateData.share_url"  @click="copyToClipboard">

                        <template v-if="canShareEmail">
                            <label for="link" class="mt-4">Or send an e-mail:</label>
                            <input type="email" class="form-control" placeholder="Type e-mail address here" v-model="shareEmail">

                            <span class="mt-2 text-primary" v-show="sendingEmail">Sending email...</span>

                            <button class="btn btn-primary mt-4 float-right" :disabled="sendingEmail" @click="sendEmail()"><i class="icon ion-md-mail"></i> Send</button>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <div class="fixed-top p-4 text-right" v-if="estimateData">
            <button class="btn btn-primary d-print-none" @click="print()"><i class="icon ion-md-print"></i> Print</button>
            <button class="btn btn-success d-print-none" @click="openShareModal()"><i class="icon ion-md-share"></i> Share</button>
        </div>

        <div class="container" id="printContainer">

            <div class="row">

                <div id="estimateDocument" class="col-md-8 offset-md-2 bg-white p-5" v-if="estimateData">

                    <section class="mb-4 text-center" v-if="estimateData.logo_image">
                        <img :src="estimateData.logo_image" alt="Estimate Image" width="150px">
                    </section>

                    <section class="mb-5" v-if="estimateData.use_name_as_title">
                        <h1><b>{{ estimateData.name }}</b></h1>
                    </section>

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
                                <td class="text-right">{{ formattedPrice(item.price) || '-' }}</td>
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

    props: ['estimate', 'canShareEmail'],

    data() {
        return {
            shareEmail: '',
            sendingEmail: false,
            estimateData: null,
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
            let currencySettings = this.estimateData.currency_settings;

            return currencySettings.symbol + ' ' + formatMoney(
                price, 2, 
                currencySettings.decimal_separator,
                currencySettings.thousands_separator,
            ).toString();
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

        openShareModal() {
            $('#shareEstimateModal').modal('show');
        },

        copyToClipboard() {
            var copyText = this.$refs.shareableUrl;

            copyText.select();
            copyText.setSelectionRange(0, 99999);

            document.execCommand('copy');

            toast.success('Link copied successfully');
        },

        sendEmail() {
            this.sendingEmail = true;

            axios.post('/estimates/' + this.estimate + '/share', {
                'email': this.shareEmail
            }).then(() => {
                this.sendingEmail = false;
                toast.success('E-mail sent successfully');
            }).catch(error => {
                this.sendingEmail = false;
                treatAxiosError(error);
            })
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